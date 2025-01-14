<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJanNumberColumnInCollectTokenLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {
            $table->string('jan_number', 200)->nullable()->after('gasha_machines_id');
            $table->string('item_description', 250)->nullable()->after('jan_number');
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
            $table->dropColumn('jan_number');
            $table->dropColumn('item_description');
        });
    }
}
