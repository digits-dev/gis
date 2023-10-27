<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConversionRateInCashFloatHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_float_histories', function (Blueprint $table) {
            $table->decimal('conversion_rate')->length(18, 2)->unsigned()->nullable()->after('entry_date');
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
            $table->dropColumn('conversion_rate');
        });
    }
}
