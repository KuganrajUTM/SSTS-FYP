<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\StripeClient;

class CreateStripeWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Stripe webhook endpoin';

    /**
     * Execute the console command.
     */

     public function __construct()
     {
         parent::__construct();
     }


    public function handle()
    {
        \Stripe\Stripe::setApiKey(config('stripe.stripe_sk'));

        $stripe = new StripeClient(config('stripe.stripe_sk'));

        try {
            $response = $stripe->webhookEndpoints->create([
                'url' => 'http://localhost:8000/stripe/webhook', // Your webhook URL
                'enabled_events' => ['checkout.session.completed', 'payment_intent.succeeded'],
            ]);

            $this->info("Webhook created successfully: " . $response->id);
        } catch (\Exception $e) {
            $this->error('Failed to create webhook: ' . $e->getMessage());
        }
    }
}
