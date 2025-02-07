<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokenOnHandVsBeginningBalReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_on_hand_vs_beginning_bal_report', function (Blueprint $table) {
            $table->id();
            $table->integer('locations_id')->nullable();
            $table->string('location_name', 255)->nullable();
            $table->integer('total_beginning_bal')->nullable();
            $table->integer('total_token_on_hand')->nullable();
            $table->integer('variance')->nullable();
            $table->date('generated_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('token_on_hand_vs_beginning_bal_report');
    }
}
