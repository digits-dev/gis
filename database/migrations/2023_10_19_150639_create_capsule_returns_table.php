<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapsuleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_returns', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('sub_locations_id')->nullable();
            $table->integer('gasha_machines_id')->nullable();
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
        Schema::dropIfExists('capsule_returns');
    }
}
