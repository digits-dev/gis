<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRackToGashaMachines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gasha_machines', function (Blueprint $table) {
            $table->string('bay')->nullable()->after('no_of_token');
            $table->string('layer')->nullable()->after('bay');
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
            $table->dropColumn('bay');
            $table->dropColumn('layer');
        });
    }
}
