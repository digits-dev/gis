<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInCollectRrTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_tokens', function (Blueprint $table) {
            $table->integer('voided_by')->nullable()->after('rejected_by');
            $table->timestamp('voided_at')->nullable()->after('rejected_at');
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
            $table->dropColumn('voided_by');
            $table->dropColumn('voided_at');
        });
    }
}
