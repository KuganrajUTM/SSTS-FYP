<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('to_driver_id')->nullable();
            $table->foreign('to_driver_id')->references('id')->on('driver')->onDelete('cascade');
            $table->unsignedBigInteger('to_child_id')->nullable();
            $table->foreign('to_child_id')->references('id')->on('child')->onDelete('cascade');
            $table->enum('type', ['rating', 'complaint']);
            $table->tinyInteger('rating')->nullable();
            $table->text('comment');
            $table->enum('status', ['pending', 'reviewed'])->default('pending');
            $table->text('manager_remark')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
