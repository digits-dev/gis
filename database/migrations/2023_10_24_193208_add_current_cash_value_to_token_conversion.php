<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentCashValueToTokenConversion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('token_conversions', function (Blueprint $table) {
            $table->decimal('current_cash_value', 18, 2)->nullable()->unsigned()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('token_conversions', function (Blueprint $table) {
            $table->dropColumn('current_cash_value');
        });
    }
}
