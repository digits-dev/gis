<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapsuleSwapLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_swap_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('capsule_swap_id')->nullable();
            $table->integer('jan_no')->nullable();
            $table->string('from_machine')->nullable();
            $table->string('to_machine')->nullable();
            $table->integer('qty')->nullable();
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
        Schema::dropIfExists('capsule_swap_lines');
    }
}
