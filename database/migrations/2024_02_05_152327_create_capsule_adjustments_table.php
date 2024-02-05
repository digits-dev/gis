<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapsuleAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->length(20)->nullable();
            $table->integer('locations_id')->length(10)->unsigned()->nullable();
            $table->integer('adjustment_qty')->length(10)->nullable();
            $table->longText('reason')->nullable();
            $table->integer('before_qty')->length(10)->unsigned()->nullable();
            $table->integer('after_qty')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('capsule_adjustments');
    }
}
