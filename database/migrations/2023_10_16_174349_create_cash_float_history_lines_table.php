<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFloatHistoryLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_float_history_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_float_histories_id')->length(10)->nullable();
            $table->integer('mode_of_payments_id')->length(10)->nullable();
            $table->integer('float_entries_id')->length(10)->nullable();
            $table->integer('qty')->length(10)->nullable();
            $table->decimal('value', 18, 2)->nullable();
            $table->integer('created_by')->unsigned()->length(10)->nullable();
            $table->integer('updated_by')->unsigned()->length(10)->nullable();
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
        Schema::dropIfExists('cash_float_history_lines');
    }
}
