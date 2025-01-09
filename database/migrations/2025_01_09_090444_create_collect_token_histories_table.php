<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectTokenHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collect_token_histories', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->nullable();
            $table->integer('statuses_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('bay_id')->nullable();
            $table->integer('collected_qty')->nullable();
            $table->integer('received_qty')->nullable();
            $table->string('variance')->nullable();
            $table->integer('received_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collect_token_histories');
    }
}
