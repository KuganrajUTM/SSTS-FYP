<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Receipt;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $billId  = $request->input('id');
        $paid    = $request->input('paid') === 'true';
        $paidAt  = $request->input('paid_at');
        $xSig    = $request->input('x_signature');

        Log::info('Billplz callback received:', ['bill_id' => $billId, 'paid' => $paid]);

        // Verify X-Signature if key is configured
        $xSigKey = env('BILLPLZ_X_SIGNATURE_KEY');
        if ($xSigKey) {
            $data     = "id={$billId}&paid=" . ($paid ? 'true' : 'false') . "&paid_at={$paidAt}";
            $expected = hash_hmac('sha256', $data, $xSigKey);
            if (!hash_equals($expected, $xSig ?? '')) {
                Log::warning('Billplz callback: invalid X-Signature', ['bill_id' => $billId]);
                return response('Invalid signature', 400);
            }
        }

        if (!$paid) {
            return response('OK', 200);
        }

        $payment = Payment::where('stripe_payment_id', $billId)->first();

        if ($payment && $payment->pay_status !== 'Paid') {
            $payment->pay_status = 'Paid';
            $payment->pay_date   = now()->format('Y-m-d');
            $payment->is_paid    = 1;
            $payment->save();

            $this->createReceipt($payment, 'FPX');
            Log::info('Billplz callback: payment marked Paid', ['payment_id' => $payment->id]);
        }

        return response('OK', 200);
    }

    private function createReceipt($payment, $paymentMethod)
    {
        if (Receipt::where('pay_id', $payment->id)->exists()) {
            return;
        }

        try {
            Receipt::create([
                'pay_id'         => $payment->id,
                'rec_date'       => now()->format('Y-m-d'),
                'rec_status'     => 'Paid',
                'rec_amount'     => $payment->pay_amount,
                'rec_num'        => 'REC-' . strtoupper(uniqid()),
                'child_id'       => $payment->child_id,
                'parent_id'      => $payment->parent_id,
                'payment_method' => $paymentMethod,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create receipt:', ['error' => $e->getMessage()]);
        }
    }
}
