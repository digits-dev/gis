<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCycleCountLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cycle_count_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('cycle_counts_id')->unsigned()->nullable();
            $table->integer('gasha_machines_id')->unsigned()->nullable();
            $table->string('digits_code',20)->nullable();
            $table->integer('qty')->nullable();
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
        Schema::dropIfExists('cycle_count_lines');
    }
}
