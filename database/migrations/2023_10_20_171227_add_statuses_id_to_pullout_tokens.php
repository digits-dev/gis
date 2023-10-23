<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusesIdToPulloutTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pullout_tokens', function (Blueprint $table) {
            $table->integer('statuses_id')->nullable()->after('reference_number');
            $table->integer('received_qty')->nullable()->after('qty');
            $table->integer('to_locations_id')->nullable()->after('received_qty');
            $table->datetime('received_at')->nullable()->after('to_locations_id');
            $table->integer('received_by')->nullable()->after('received_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pullout_tokens', function (Blueprint $table) {
            $table->dropColumn('statuses_id');
            $table->dropColumn('received_qty');
            $table->dropColumn('to_locations_id');
            $table->dropColumn('received_at');
            $table->dropColumn('received_by');
        });
    }
}
