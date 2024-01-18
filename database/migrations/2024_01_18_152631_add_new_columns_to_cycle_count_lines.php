<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToCycleCountLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_count_lines', function (Blueprint $table) {
            $table->integer('status')->length(10)->unsigned()->nullable()->after('id');
            $table->string('cycle_count_type')->nullable()->after('variance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cycle_count_lines', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('cycle_count_type');
        });
    }
}
