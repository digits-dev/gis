<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectTokenHistoryLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collect_token_history_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('collect_token_id')->nullable();
            $table->integer('gasha_machines_id')->nullable();
            $table->string('jan_number')->nullable();
            $table->integer('no_of_token')->nullable();
            $table->integer('qty')->nullable();
            $table->string('variance')->nullable();
            $table->string('variance_type')->nullable();
            $table->integer('projected_capsule_sales')->nullable();
            $table->integer('actual_capsule_sales')->nullable();
            $table->integer('current_capsule_inventory')->nullable();
            $table->integer('actual_capsule_inventory')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collect_token_history_lines');
    }
}
