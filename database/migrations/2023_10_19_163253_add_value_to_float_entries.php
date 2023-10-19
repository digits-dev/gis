<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValueToFloatEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('float_entries', function (Blueprint $table) {
            $table->integer('value')->length(11)->nullable()->after('description');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('float_entries', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }
}
