<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustCollectTokenHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_tokens', function (Blueprint $table) {
            $table->integer('bay_id')->nullable()->after('location_id');
            $table->integer('confirmed_by')->nullable()->after('created_by');
            $table->integer('approved_by')->nullable()->after('confirmed_by');
            $table->integer('rejected_by')->nullable()->after('approved_by');
            $table->integer('confirmed_at')->nullable()->after('created_at');
            $table->integer('approved_at')->nullable()->after('confirmed_at');
            $table->integer('rejected_at')->nullable()->after('approved_at');
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
            $table->dropColumn('bay_id');
            $table->dropColumn('confirmed_by');
            $table->dropColumn('approved_by');
            $table->dropColumn('rejected_by');
            $table->dropColumn('approved_at');
            $table->dropColumn('rejected_at');
        });
    }
}
