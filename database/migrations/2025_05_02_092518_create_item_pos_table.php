<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_pos', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 255)->nullable();
            $table->decimal('total_value', 8, 2)->nullable();
            $table->decimal('amount_value', 8, 2)->nullable();
            $table->decimal('change_value', 8, 2)->nullable();
            $table->unsignedInteger('mode_of_payments_id')->nullable();
            $table->integer('locations_id')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('payment_reference', 255)->nullable();
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
        Schema::dropIfExists('item_pos');
    }
}
