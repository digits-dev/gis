<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPosLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_pos_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('item_pos_id')->nullable();
            $table->string('digits_code')->nullable();
            $table->string('jan_number')->nullable();
            $table->string('item_description')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('current_srp', 8, 2)->nullable();
            $table->decimal('total_price', 8, 2)->nullable();
            $table->integer('locations_id')->nullable();
            $table->string('status', 50)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('item_pos_lines');
    }
}
