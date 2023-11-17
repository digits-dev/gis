<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapsuleSwapHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_swap_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->nullable();
            $table->string('machine_no_one')->nullable();
            $table->integer('capsule_qty_one')->nullable();
            $table->integer('no_of_token_one')->nullable();
            $table->string('machine_no_two')->nullable();
            $table->integer('capsule_qty_two')->nullable();
            $table->integer('no_of_token_two')->nullable();
            $table->integer('location')->nullable();
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
        Schema::dropIfExists('capsule_swap_headers');
    }
}
