<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->string('model_name');
            $table->string('legal_document')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->timestamps();
            $table->foreign('driver_id')->references('id')->on('driver')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
