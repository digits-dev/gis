<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentCashValueToCollectRrTokenLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {
            $table->decimal('current_cash_value')->length(18,2)->nullable()->after('location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {
            //
        });
    }
}
