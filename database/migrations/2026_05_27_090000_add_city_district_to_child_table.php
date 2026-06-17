<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('child', function (Blueprint $table) {
            $table->string('city')->nullable()->after('school_name');
            $table->string('district')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('child', function (Blueprint $table) {
            $table->dropColumn(['city', 'district']);
        });
    }
};
