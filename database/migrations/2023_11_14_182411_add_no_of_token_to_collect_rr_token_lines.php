<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoOfTokenToCollectRrTokenLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {
            $table->integer('no_of_token')->length(11)->nullable()->after('gasha_machines_id');
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
            $table->dropColumn('no_of_token');
        });
    }
}
