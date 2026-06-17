<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->date('paid_at')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('driver')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_salaries');
    }
};
