<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            if (!Schema::hasColumn('payment', 'stripe_payment_id')) {
                $table->string('stripe_payment_id')->nullable()->after('pay_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropColumn('stripe_payment_id');
        });
    }
};
