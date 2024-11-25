<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaySelectStatusInGashaMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gasha_machines', function (Blueprint $table) {
            $table->integer('bay_select_status')->nullable()->default(0)->after('bay');
            $table->bigInteger('bay_selected_by')->nullable()->after('bay_select_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gasha_machines', function (Blueprint $table) {
            $table->dropColumn('bay_select_status');
            $table->dropColumn('bay_selected_by');
        });
    }
}
