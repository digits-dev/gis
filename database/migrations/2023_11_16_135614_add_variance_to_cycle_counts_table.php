<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVarianceToCycleCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_counts', function (Blueprint $table) {
            $table->integer('total_qty')->length(10)->nullable()->after('sub_locations_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cycle_counts', function (Blueprint $table) {
            $table->dropColumn('total_qty');
        });
    }
}
