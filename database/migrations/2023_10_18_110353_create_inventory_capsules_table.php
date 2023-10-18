<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryCapsulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_capsules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_code')->length(10)->nullable();
            $table->integer('onhand_qty')->length(18)->nullable()->unsigned();
            $table->integer('locations_id')->length(10)->nullable()->unsigned();
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
        Schema::dropIfExists('inventory_capsules');
    }
}
