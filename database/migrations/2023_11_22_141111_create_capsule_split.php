<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapsuleSplit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_split', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->length(20)->nullable();
            $table->integer('to_machines_id')->length(11)->unsigned()->nullable();
            $table->integer('from_machines_id')->length(11)->unsigned()->nullable();
            $table->integer('locations_id')->length(11)->unsigned()->nullable();
            $table->integer('created_by')->length(11)->unsigned()->nullable();
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
        Schema::dropIfExists('capsule_split');
    }
}