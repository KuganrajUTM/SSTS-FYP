<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use App\Models\Payment; 
use App\Models\Receipt; 

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info('Webhook payload received:', ['payload' => $request->all()]); //Log webhook payload sent by stripe
        $payload = $request->getContent(); //aw body of the webhook request, which contains the event data from Stripe
        $sigHeader = $request->header('Stripe-Signature'); //signature header sent by Stripe, used to verify the webhook's authenticity
        $webhookSecret = config('stripe.webhook_secret'); //secret key configured in config/stripe.php
        try {
            $event = \Stripe\Webhook::constructEvent( //validates the webhook's authenticity using Stripe's library
                $payload, $sigHeader, $webhookSecret
            );

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object; // PaymentIntent object
                    $this->handlePaymentIntentSucceeded($paymentIntent);
                    break;
                case 'payment_intent.requires_action':
                    $paymentIntent = $event->data->object; // PaymentIntent object
                    $this->handlePaymentIntentRequiresAction($paymentIntent);
                    break;
                case 'checkout.session.completed':
                    $checkoutSession = $event->data->object; // Checkout session object
                    $this->handleCheckoutSessionCompleted($checkoutSession);
                    break;
                case 'charge.succeeded':
                    $charge = $event->data->object; // Charge object
                    $this->handleChargeSucceeded($charge);
                    break;
                case 'charge.updated':
                    $charge = $event->data->object; // Charge object
                    $this->handleChargeUpdated($charge);
                    break;
                default:
                    // Other event types
                    Log::info('Unhandled event type:', ['type' => $event->type]);
                    break;
            }

            return response('Webhook handled', 200);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Invalid payload:', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Invalid signature:', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }
    }

    private function handleCheckoutSessionCompleted($checkoutSession)
    {
        Stripe::setApiKey(config('stripe.stripe_sk'));

        // Extract payment intent ID
        $sessionId = $checkoutSession->id;
        $paymentIntentId = $checkoutSession->payment_intent;

        if (!$sessionId || !$paymentIntentId) {
            Log::warning('Invalid Checkout Session payload:', ['payload' => $checkoutSession]);
            return;
        }

        // Find the payment record in your database
        $payment = Payment::where('stripe_payment_id' , $sessionId)->first();

        if ($payment && $paymentIntentId) {
            // Update the payment status to 'Paid'
            $payment->pay_status = 'Paid';
            $payment->payment_intent_id = $paymentIntentId;
            $payment->pay_date = now()->format('Y-m-d');
            $payment->is_paid = 1;
            $payment->save();

            Log::info('Checkout : Payment updated to Paid:', ['payment_id' => $payment->id]);
        } else {
            Log::warning('Payment record not found for PaymentIntent ID:', ['payment_intent_id' => $paymentIntentId]);
        }
    }



    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        $paymentIntentId = $paymentIntent->id;

        // Find the payment record in your database
        $payment = Payment::where('payment_intent_id' , $paymentIntentId)->first();

        if ($payment) {
            // Update the payment status to 'Paid'
            $payment->pay_status = 'Paid';
            $payment->payment_intent_id = $paymentIntentId; // Optionally, add a timestamp for the payment
            $payment->pay_date = now()->format('Y-m-d');
            $payment->is_paid = 1;

            $payment->save();

            Log::info('Intent_Succeed : Payment updated to Paid:', ['payment_id' => $payment->id]);
        }
    }

    private function handlePaymentIntentRequiresAction($paymentIntent)
    {
        // Logic for handling required actions, if needed
        Log::info('Payment requires additional action:', ['payment_intent_id' => $paymentIntent->id]);
    }

    private function handleChargeSucceeded($charge)
    {
        $paymentIntentId = $charge->payment_intent;
        $paymentStatus = $charge->status; // Retrieve the charge status

        if ($paymentStatus !== 'succeeded') {
            Log::warning('Charge status is not succeeded. Skipping receipt generation.', ['charge_id' => $charge->id]);
            return;
        }

        $payment = Payment::where('payment_intent_id', $paymentIntentId)->first();
        Log::info('Payment lookup result:', ['payment_intent_id' => $paymentIntentId, 'payment_found' => $payment ? true : false]);

        if ($payment) {
            // Update the payment status to 'Paid'
            $payment->pay_status = 'Paid';
            $payment->payment_intent_id = $paymentIntentId;
            $payment->pay_date = now()->format('Y-m-d');
            $payment->is_paid = 1;
            $payment->save();

            // Now create the receipt
            try {
                $paymentMethod = $charge->payment_method ?? 'unknown';
                $this->createReceipt($payment, $paymentMethod);
                Log::info('Charge_Succeed: Payment updated to Paid and receipt created:', ['payment_id' => $payment->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create receipt for payment ID ' . $payment->id, ['error' => $e->getMessage()]);
            }
        }
    }


    private function handleChargeUpdated($charge)
    {
        // Log the charge update for reference
        Log::info('Charge updated:', ['charge_id' => $charge->id]);

        // Retrieve payment data using the payment intent ID
        $paymentIntentId = $charge->payment_intent;
        $paymentStatus = $charge->status; // Retrieve the charge status

        // Log the charge status for debugging
        Log::info('Charge status:', ['status' => $paymentStatus]);

        // Ensure the charge status is 'succeeded' before processing further
        if ($paymentStatus !== 'succeeded') {
            Log::warning('Charge status is not succeeded. Skipping receipt generation.', ['charge_id' => $charge->id]);
            return;
        }

        $payment = Payment::where('payment_intent_id', $paymentIntentId)->first();

        if ($payment) {
            // If the payment status is 'Paid', create the receipt
            if ($payment->pay_status === 'Paid') {
                $paymentMethodType = $charge->payment_method_details->type ?? 'unknown';
                $this->createReceipt($payment, $paymentMethodType);
            }
        } else {
            Log::warning('Payment record not found for PaymentIntent ID:', ['payment_intent_id' => $paymentIntentId]);
        }
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

            Log::info('Receipt created successfully for Payment ID:', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Failed to create receipt:', ['error' => $e->getMessage()]);
        }
    }

}
