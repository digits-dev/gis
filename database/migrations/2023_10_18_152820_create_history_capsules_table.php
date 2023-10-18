<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryCapsulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_capsules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->length(20)->nullable();
            $table->string('item_code')->length(10)->nullable();
            $table->integer('capsule_action_types_id')->length(10)->unsigned()->nullable();
            $table->integer('locations_id')->length(10)->unsigned()->nullable();
            $table->integer('gasha_machines_id')->length(10)->unsigned()->nullable();
            $table->integer('created_by')->length(10)->nullable()->unsigned();
            $table->integer('updated_by')->length(10)->nullable()->unsigned();
            $table->integer('qty')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('history_capsules');
    }
}
