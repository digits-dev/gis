<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLedgerTypeColumnsInHistoryCapsulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_capsules', function (Blueprint $table) {
            $table->integer('qty')->change();
            $table->integer('to_sub_locations_id')->length(10)->unsigned()->nullable()->after('qty');
            $table->integer('from_machines_id')->length(10)->unsigned()->nullable()->after('qty');
            $table->integer('to_machines_id')->length(10)->unsigned()->nullable()->after('qty');
            $table->integer('from_sub_locations_id')->length(10)->unsigned()->nullable()->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_capsules', function (Blueprint $table) {
            $table->integer('qty')->length(10)->nullable()->change();
            $table->dropColumn('to_sub_locations_id');
            $table->dropColumn('from_machines_id');
            $table->dropColumn('to_machines_id');
            $table->dropColumn('from_sub_locations_id');
        });
    }
}
