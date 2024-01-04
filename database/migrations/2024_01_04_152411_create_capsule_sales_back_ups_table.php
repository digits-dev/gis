<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapsuleSalesBackUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_sales_backup', function (Blueprint $table) {
            $table->increments('id');
            $table->date('backup_date')->nullable();
            $table->json('sales_data')->nullable();
            $table->string('status')->length(20)->default('ACTIVE')->nullable();
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
        Schema::dropIfExists('capsule_sales_back_ups');
    }
}
