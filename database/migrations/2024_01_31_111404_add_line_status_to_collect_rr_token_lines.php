<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLineStatusToCollectRrTokenLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {
            $table->integer('line_status')->length(10)->unsigned()->nullable()->after('id');
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
            $table->dropColumn('line_status');
        });
    }
}
