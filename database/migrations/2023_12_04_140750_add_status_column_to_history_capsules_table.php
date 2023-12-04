<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToHistoryCapsulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_capsules', function (Blueprint $table) {
            $table->string('status')->length(20)->default('ACTIVE')->nullable()->after('to_sub_locations_id');
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
            $table->dropColumn('status');
        });
    }
}
