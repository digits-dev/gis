<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusesIdToCollectRrTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_tokens', function (Blueprint $table) {
            $table->integer('statuses_id')->nullable()->after('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collect_rr_tokens', function (Blueprint $table) {
            $table->dropColumn('statuses_id');
        });
    }
}
