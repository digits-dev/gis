<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInCashFloatHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_float_histories', function (Blueprint $table) {
            $table->string('token_drawer',255)->after('token_bal')->nullable();
            $table->string('token_sealed', 255)->after('token_drawer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_float_histories', function (Blueprint $table) {
            $table->dropColumn('token_drawer');
            $table->dropColumn('token_sealed');
        });
    }
}
