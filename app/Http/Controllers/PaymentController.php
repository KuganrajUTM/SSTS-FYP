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
use Stripe\Stripe;
use Stripe\StripeClient;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    //Parents view the all the specific details for the chosen row from table
    public function view_pay($id){
        $payment = Payment::with(['child', 'parent.user', 'driver.user'])
                           ->where('id', $id)
                           ->first();

        return view('payment.view_pay', ['payment' => $payment]);
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

        elseif($userRole === 'D'){
            $driver = Driver::where('user_id' , $userId)->first();

            $paymentQuery = Payment::with(['child', 'parent.user'])
                                ->where('driver_id' , $driver->id);


            $paymentCounts = Payment::selectRaw('
                                COUNT(CASE WHEN pay_status = "Pending" THEN 1 END) AS pending_count,
                                COUNT(CASE WHEN pay_status = "Overdue" THEN 1 END) AS overdue_count,
                                COUNT(CASE WHEN pay_status = "Paid" THEN 1 END) AS paid_count
                            ')
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

    //Update payment status from pending to overdue if the issue_date month exceed one month from current month
    private function updatePaymentStatus(){

        $oneMonthAgo = Carbon::now()->subMonth();
        $overdue_payments = Payment::where('pay_status' , 'Pending')
                                    ->where('issue_date' , '<' , $oneMonthAgo)
                                    ->get();

        
        foreach($overdue_payments as $over)
        {
            $over->update(['pay_status' => 'Overdue']);
        }
                
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

    //Process the payment vai Stripe checkout
    public function processPayment($id, Request $request)
    {
        $payment = Payment::with(['parent.user', 'driver.user'])
                            ->where('id', $id)
                            ->first();

        if (!$payment) {
            return redirect()->route('cancel')->with('error', 'Payment not found.');
        }
        $amount = $payment->pay_amount;
        $userEmail = $payment->parent->user->email;
        $service = $payment->driver->user->name . "'s Transportation Service";

        session(['payment_id' => $payment->id]);

        // Set your Stripe secret key
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));

        try {
            // Create a checkout session
            $response = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card', 'fpx'], // Allow both card and FPX payments
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'myr', // FPX requires MYR currency
                            'product_data' => [
                                'name' => $service,
                            ],
                            'unit_amount' => $amount * 100, // Convert to cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
                'customer_email' => $userEmail,
            ]);
        
            // Fetch the session details to get the payment_intent_id
            $sessionId = $response->id;
            $payment->update(['stripe_payment_id' => $sessionId]);

            // Redirect to the Stripe-hosted checkout page
            if (isset($response->url) && $response->url != '') {
                
                return redirect($response->url);
            } else {
                return redirect()->route('cancel');
            }
        } catch (\Exception $e) {
            return redirect()->route('cancel')->with('error', 'Unable to process payment. Please try again.');
        }
    }

    //Success page
    public function success(Request $request)
    {
        if ($request->has('session_id')) { // Check i session_id exists
            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));

            // Retrieve session from Stripe
            $response = $stripe->checkout->sessions->retrieve($request->get('session_id'));

            // Optionally, verify the payment status
            if ($response->payment_status === 'paid') {
                return view('payment.success');
            } else {
                return "Payment failed or is incomplete";
            }
        } else {
            return redirect()->route('cancel');
        }
    }

    //Cancel page
    public function cancel()
    {
        return view('payment.cancel');
    }

    //Driver select parent to issue payment
    public function select_pay(){
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Driver not logged in']);
        }

        $customer = Child::with(['parent.user' , 'driver.user'])
                            ->whereHas('driver', function($query) use ($userId)
                              {
                                 $query->where('user_id' , $userId);
                              })
                            ->get();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $payment = Payment::whereYear('issue_date' , $currentYear)
                            ->whereMonth('issue_date' , $currentMonth)
                            ->whereHas('driver', function($query) use ($userId)
                            {
                               $query->where('user_id' , $userId);
                            })
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

        $driver = Driver::where('user_id', $userId)->first();
        $driverId = $driver->id;

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
