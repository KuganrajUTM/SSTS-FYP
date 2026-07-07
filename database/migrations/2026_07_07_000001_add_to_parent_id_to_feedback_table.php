<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->unsignedBigInteger('to_parent_id')->nullable()->after('to_child_id');
            $table->foreign('to_parent_id')->references('id')->on('parent')->onDelete('cascade');
        });

        // Extend type enum to include 'feedback'
        DB::statement("ALTER TABLE feedback MODIFY COLUMN type ENUM('rating', 'complaint', 'feedback') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropForeign(['to_parent_id']);
            $table->dropColumn('to_parent_id');
        });

        DB::statement("ALTER TABLE feedback MODIFY COLUMN type ENUM('rating', 'complaint') NOT NULL");
    }
};
