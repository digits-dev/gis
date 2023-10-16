<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokenConversionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_conversion_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_conversions_id')->length(10)->unsigned()->nullable();
            $table->decimal('old_cash_value')->length(18, 2)->unsigned()->nullable();
            $table->decimal('new_cash_value')->length(18, 2)->unsigned()->nullable();
            $table->integer('old_token_qty')->length(10)->unsigned()->nullable();
            $table->integer('new_token_qty')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('token_conversion_histories');
    }
}
