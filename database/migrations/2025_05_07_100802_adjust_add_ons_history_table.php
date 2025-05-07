<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustAddOnsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addons_history', function (Blueprint $table) {
            $table->integer('item_pos_id')->nullable()->after('token_swap_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addons_history', function (Blueprint $table) {
            $table->dropColumn('item_pos_id');
        });
    }
}
