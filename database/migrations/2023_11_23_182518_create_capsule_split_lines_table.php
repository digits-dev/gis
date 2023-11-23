<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapsuleSplitLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_split_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('capsule_split_id')->length(10)->unsigned()->nullable();
            $table->string('item_code')->length(20)->nullable();
            $table->integer('actual_qty')->length(10)->unsigned()->nullable();
            $table->integer('remaining_qty')->length(10)->unsigned()->nullable();
            $table->integer('transfer_qty')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('capsule_split_lines');
    }
}