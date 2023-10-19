<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToGashaMachines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gasha_machines', function (Blueprint $table) {
            $table->string('description')->nullable()->after('serial_number');
            $table->string('location_name')->nullable()->after('description');
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
            $table->dropColumn('description');
            $table->dropColumn('location_name');
        });
    }
}
