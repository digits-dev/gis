<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryCapsuleLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_capsule_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_capsules_id')->length(10)->nullable()->unsigned();
            $table->integer('gasha_machines_id')->length(10)->nullable()->unsigned();
            $table->integer('sub_locations_id')->length(10)->nullable()->unsigned();
            $table->integer('qty')->length(18)->nullable()->unsigned();
            $table->integer('created_by')->length(10)->nullable()->unsigned();
            $table->integer('updated_by')->length(10)->nullable()->unsigned();
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
        Schema::dropIfExists('inventory_capsule_lines');
    }
}
