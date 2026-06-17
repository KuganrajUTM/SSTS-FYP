<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Parents; 
use App\Models\Child; 
use App\Models\Driver; 
use App\Models\Receipt; 
use App\Models\User; 
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    //Parents view the all the specific details for the chosen row from table
    public function view_pay($id){
        $payment = Payment::with(['child', 'parent.user', 'driver.user'])
                           ->where('id', $id)
                           ->first();

        $receipt = Receipt::where('pay_id', $id)->first();

        return view('payment.view_pay', ['payment' => $payment, 'receipt' => $receipt]);
    }

    //Parents or parents view all their children's payments (Pending, Overdue and Paid payments)
    public function parent_pay(Request $request){

        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Driver not logged in']);
        }

        $user = User::where('id' , $userId)->first();
        $userRole = $user->role;

        $this->updatePaymentStatus();

        if($userRole === 'P'){
            $parent = Parents::where('user_id' , $userId)->first();

            $paymentQuery = Payment::with(['child', 'parent.user', 'driver.user'])
                                ->where('parent_id' , $parent->id);


            $paymentCounts = Payment::selectRaw('
                                COUNT(CASE WHEN pay_status = "Pending" THEN 1 END) AS pending_count,
                                COUNT(CASE WHEN pay_status = "Overdue" THEN 1 END) AS overdue_count,
                                COUNT(CASE WHEN pay_status = "Paid" THEN 1 END) AS paid_count
                            ')
                            ->where('parent_id' , $parent->id)
                            ->first() ?? (object) ['pending_count' => 0, 'overdue_count' => 0, 'paid_count' => 0];

            if($request->has('status') && $request->status != '')
            {
                $paymentQuery = $paymentQuery->where('pay_status' , $request->status);
            }

            if($request->has('month') && $request->month != '')
            {
                $year = $request->has('year') && $request->year != '' ? $request->year : now()->year;

                $paymentQuery = $paymentQuery->whereMonth('issue_date', $request->month)
                                ->whereYear('issue_date', $year);
            }

            $payment = $paymentQuery->get();
            


            return view('payment.pay', [
                'payment' => $payment,
                'pay_count' => $paymentCounts,
                'userRole' => $userRole, // Pass user role to the view
            ]);
        }


    }

    //Update payment status from pending to overdue if 30 days have passed, then apply 2% penalty after 10 more days
    private function updatePaymentStatus(){

        // Mark as Overdue after 30 days
        Payment::where('pay_status', 'Pending')
            ->where('issue_date', '<', Carbon::now()->subDays(30))
            ->each(fn($p) => $p->update(['pay_status' => 'Overdue']));

        // Apply 2% penalty on payments overdue for 10+ days (40 days from issue)
        Payment::where('pay_status', 'Overdue')
            ->where('penalty_applied', false)
            ->where('issue_date', '<', Carbon::now()->subDays(40))
            ->each(function ($p) {
                $p->update([
                    'pay_amount'      => round($p->pay_amount * 1.02, 2),
                    'penalty_applied' => true,
                ]);
            });
    }

    //Parents view and print invoice
    public function inv($id)
    {
        $payment = Payment::with(['child', 'parent.user', 'driver.user'])
                            ->where('id', $id)
                            ->firstOrFail();

        $pdf = Pdf::loadView('payment.invoice', ['payment' => $payment]);

        return $pdf->stream('invoice.pdf'); // Stream the PDF to the browser
    }

    //Display payment checkout page
    public function checkout($pay_id){
        $payment = Payment::with(['parent.user'])
                           ->where('id', $pay_id)
                           ->first();
        
        return view('payment.parent_pay', ['payment' => $payment]); 
    }

    //Process payment via Stripe card checkout
    public function processPayment($id, Request $request)
    {
        $payment = Payment::with(['parent.user'])->where('id', $id)->first();

        if (!$payment) {
            return redirect()->route('cancel')->with('error', 'Payment not found.');
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'myr',
                        'product_data' => ['name' => 'SSTS Transportation Fee'],
                        'unit_amount'  => (int)($payment->pay_amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode'           => 'payment',
                'customer_email' => $payment->parent->user->email,
                'success_url'    => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'     => route('cancel'),
            ]);

            $payment->update(['stripe_payment_id' => $session->id]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return redirect()->route('cancel')->with('error', 'Unable to process payment. Please try again.');
        }
    }

    //Success page - handles Stripe redirect after payment
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $payment = Payment::where('stripe_payment_id', $session->id)->first();

                    if ($payment && $payment->pay_status !== 'Paid') {
                        $payment->pay_status = 'Paid';
                        $payment->pay_date   = now()->format('Y-m-d');
                        $payment->is_paid    = 1;
                        $payment->save();

                        $this->createReceipt($payment, 'Card');
                    }
                }
            } catch (\Exception $e) {
                Log::error('Stripe session retrieval error: ' . $e->getMessage());
            }

            return view('payment.success');
        }

        return view('payment.success');
    }

    //Cancel page
    public function cancel()
    {
        return view('payment.cancel');
    }

    //Admin select parent to issue payment
    public function select_pay(){
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Not logged in']);
        }

        $customer = Child::with(['parent.user', 'driver.user'])
                            ->whereNotNull('driver_id')
                            ->get();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $payment = Payment::whereYear('issue_date', $currentYear)
                            ->whereMonth('issue_date', $currentMonth)
                            ->get();

        return view('payment.driver_pay', ['customers' => $customer, 'payments' => $payment]);
    }

    //Driver enter amount and issue payment
    public function issue_pay($id){

        $customer = Child::with(['parent.user'])
                            ->where('id' , $id)
                            ->first();

        return view('payment.driver_app', ['customer' => $customer]);
        
    }

    //Driver edit the issued payment
    public function issue_edit($id){

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $payment = Payment::whereYear('issue_date' , $currentYear)
                            ->whereMonth('issue_date' , $currentMonth)
                            ->whereHas('child', function($query) use ($id)
                            {
                               $query->where('id' , $id);
                            })
                            ->first();

        return view('payment.driver_edit_pay', ['payment' => $payment]);
        
    }

    //Create payment after driver issued the payment
    public function create_pay(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $userId = session('user_id');   
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $child = Child::find($request->input('child_id'));
        $driverId = $child->driver_id;

        Payment::create([
            'child_id' => $request->input('child_id'),
            'parent_id' => $request->input('parent_id'),
            'driver_id' => $driverId,
            'pay_status' => 'Pending',
            'pay_amount' => $validated['amount'],
            'is_paid' => false,
            'issue_date' => Carbon::now()->format('Y-m-d')
        ]);

        return redirect()->route('driver-pay')->with('success', 'Payment issued successfully.');
    }

    //Update the payment after driver edit the issued payment
    public function edit_pay(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $payment = Payment::where('child_id', $request->input('child_id'));

        $payment->update([
            'pay_amount' => $validated['amount'],
            'issue_date' => Carbon::now()->format('Y-m-d')
        ]);

        return redirect()->route('driver-pay')->with('success', 'Payment updated successfully.');
    }

    //Display receipt
    public function getReceipt(Request $request){
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Driver not logged in']);
        }

        $parent = Parents::where('user_id' , $userId)->first();

        $receiptQuery = Receipt::with(['child', 'parent.user'])
                           ->where('parent_id', $parent->id);

        if($request->has('month') && $request->month != '')
        {
            $year = $request->has('year') && $request->year != '' ? $request->year : now()->year;

            $receiptQuery = $receiptQuery->whereMonth('rec_date', $request->month)
                                         ->whereYear('rec_date', $year);
        }

        $receipt = $receiptQuery->get();

        return view('payment.receipt', ['receipt' => $receipt]);
    }

    //View specific receipt
    public function viewReceipt($id)
    {
        $receipt = Receipt::with('parent.user' , 'child')
                            ->where('id' , $id)
                            ->first();


        $pdf = Pdf::loadView('payment.view-receipt', ['receipt' => $receipt]);

        return $pdf->stream('receipt.pdf'); // Stream the PDF to the browser
    }

    //Driver update payment status for parents who pay via cash
    public function cashPayment(Request $request , $pay_id)
    {
        $request->validate([
            'pay_status' => 'required|string|in:Pending,Paid,Overdue',
        ]);

        $payment = Payment::findOrFail($pay_id);

        if($payment)
        {
            $payment->pay_status = $request->pay_status;
            $payment->pay_date = now()->format('Y-m-d');
            $payment->save();
            $this->createReceipt($payment, "Cash");
        }

        return redirect()->back()->with('sucess', 'Payment status updated successfully');
    }

    public function showFPX($id)
    {
        $payment = Payment::with(['parent.user', 'child'])->findOrFail($id);
        return view('payment.fpx', ['payment' => $payment]);
    }

    public function processFPX(Request $request, $id)
    {
        $payment = Payment::with(['parent.user'])->findOrFail($id);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'myr',
                        'product_data' => ['name' => 'SSTS Transportation Fee'],
                        'unit_amount'  => (int)($payment->pay_amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode'           => 'payment',
                'customer_email' => $payment->parent->user->email,
                'success_url'    => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'     => route('cancel'),
            ]);

            $payment->update(['stripe_payment_id' => $session->id]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return redirect()->route('cancel')->with('error', 'Unable to process payment. Please try again.');
        }
    }

    public function showQRPay($id)
    {
        $payment = Payment::with(['parent.user', 'driver.user', 'child'])
                           ->where('id', $id)
                           ->firstOrFail();

        return view('payment.qr_pay', ['payment' => $payment]);
    }

    public function uploadQRProof(Request $request, $id)
    {
        $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $payment = Payment::with(['child', 'parent'])->findOrFail($id);

        $path = $request->file('proof')->store('proofs', 'public');

        $payment->pay_status = 'Paid';
        $payment->pay_date = now()->format('Y-m-d');
        $payment->is_paid = 1;
        $payment->save();

        Receipt::create([
            'pay_id'         => $payment->id,
            'rec_date'       => now()->format('Y-m-d'),
            'rec_status'     => 'Paid',
            'rec_amount'     => $payment->pay_amount,
            'rec_num'        => 'REC-' . strtoupper(uniqid()),
            'child_id'       => $payment->child_id,
            'parent_id'      => $payment->parent_id,
            'payment_method' => 'QR Pay',
            'proof_path'     => $path,
        ]);

        return redirect()->route('parent_pay')->with('success', 'Payment proof uploaded successfully.');
    }

    private function createReceipt($payment, $paymentMethod)
    {
        try {
            Receipt::create([
                'pay_id' => $payment->id,
                'rec_date' => now()->format('Y-m-d'),
                'rec_status' => 'Paid',
                'rec_amount' => $payment->pay_amount,
                'rec_num' => 'REC-' . strtoupper(uniqid()),
                'child_id' => $payment->child_id,
                'parent_id' => $payment->parent_id,
                'payment_method' => $paymentMethod,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create receipt:', ['error' => $e->getMessage()]);
        }
    }



}
