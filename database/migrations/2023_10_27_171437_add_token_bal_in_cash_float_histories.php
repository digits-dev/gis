<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenBalInCashFloatHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_float_histories', function (Blueprint $table) {
            $table->integer('token_bal')->length(11)->unsigned()->nullable()->after('conversion_rate');
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
            $table->dropColumn('token_bal');
        });
    }
}
