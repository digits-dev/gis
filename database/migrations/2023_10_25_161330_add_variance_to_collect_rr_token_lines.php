<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVarianceToCollectRrTokenLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {
            $table->string('variance')->nullable()->after('qty');
            $table->integer('location_id')->nullable()->after('variance');
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
            //
        });
    }
}
