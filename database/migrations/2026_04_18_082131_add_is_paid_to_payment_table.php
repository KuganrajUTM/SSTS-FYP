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
            if (!Schema::hasColumn('payment', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('pay_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });
    }
};
