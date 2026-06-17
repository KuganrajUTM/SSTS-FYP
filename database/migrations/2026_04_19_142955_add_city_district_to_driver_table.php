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
        Schema::table('driver', function (Blueprint $table) {
            if (!Schema::hasColumn('driver', 'city')) {
                $table->string('city')->nullable()->after('VRN');
            }
            if (!Schema::hasColumn('driver', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('driver', function (Blueprint $table) {
            $table->dropColumn(['city', 'district']);
        });
    }
};
