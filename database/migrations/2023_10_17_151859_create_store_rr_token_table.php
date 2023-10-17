<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreRrTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_rr_token', function (Blueprint $table) {
            $table->increments('id');
            $table->string('disburse_number')->nullable();
            $table->integer('released_qty')->nullable();
            $table->integer('received_qty')->nullable();
            $table->integer('from_locations_id')->nullable();
            $table->integer('to_locations_id')->nullable();
            $table->integer('statuses_id')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->integer('received_by')->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
            $table->integer('updated_by')->length(10)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_rr_token');
    }
}
