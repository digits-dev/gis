<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddOnMovementHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_on_movement_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->length(20)->nullable();
            $table->string('digits_code')->length(20)->nullable();
            $table->integer('add_on_action_types_id')->nullable();
            $table->integer('locations_id')->nullable();
            $table->integer('qty')->length(10)->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('add_on_movement_histories');
    }
}
