<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('driver_locations')) {
            return;
        }

        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->unique();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->timestamp('timestamp')->nullable();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};
