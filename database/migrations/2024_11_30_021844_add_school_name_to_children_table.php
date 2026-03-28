<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 
    public function up()
{
    Schema::table('child', function (Blueprint $table) {
        $table->string('school_name')->after('name');
    });
}
    /**
     * Reverse the migrations.
     */

public function down()
{
    Schema::table('child', function (Blueprint $table) {
        $table->dropColumn('school_name');
    });
}
};
