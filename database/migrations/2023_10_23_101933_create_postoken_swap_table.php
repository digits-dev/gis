<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostokenSwapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postoken_swap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->nullable();
            $table->integer('cash_value')->nullable();
            $table->integer('token_value')->nulable();
            $table->integer('total_value')->nullable();
            $table->integer('change_value')->nullable();
            $table->string('mode_of_payments')->nulable();
            $table->integer('locations_id')->nullable();
            $table->string('payment_reference')->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('postoken_swap');
    }
}