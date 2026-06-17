<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sos_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('audio_path');
            $table->text('transcript')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('driver')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sos_messages');
    }
};
