<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGashaMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gasha_machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('no_of_token')->nullable();
            $table->integer('machine_statuses_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gasha_machine_lists');
    }
}
