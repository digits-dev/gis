<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLengthOnDigitsCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('digits_code')->length(50)->change();
        });

        Schema::table('history_capsules', function (Blueprint $table) {
            $table->string('item_code')->length(50)->change();
        });

        Schema::table('inventory_capsules', function (Blueprint $table) {
            $table->string('item_code')->length(50)->change();
        });

        Schema::table('cycle_count_lines', function (Blueprint $table) {
            $table->string('digits_code')->length(50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('digits_code')->length(10)->change();
        });

        Schema::table('history_capsules', function (Blueprint $table) {
            $table->string('item_code')->length(10)->change();
        });

        Schema::table('inventory_capsules', function (Blueprint $table) {
            $table->string('item_code')->length(10)->change();
        });

        Schema::table('cycle_count_lines', function (Blueprint $table) {
            $table->string('digits_code')->length(10)->change();
        });
    }
}
