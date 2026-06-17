<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sos_messages', function (Blueprint $table) {
            $table->boolean('deleted_by_admin')->default(false)->after('transcript');
            $table->boolean('deleted_by_parent')->default(false)->after('deleted_by_admin');
        });
    }

    public function down(): void
    {
        Schema::table('sos_messages', function (Blueprint $table) {
            $table->dropColumn(['deleted_by_admin', 'deleted_by_parent']);
        });
    }
};
