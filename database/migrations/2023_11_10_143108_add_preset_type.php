<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPresetType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presets', function (Blueprint $table) {
            $table->string('value',50)->change();
            $table->string('preset_type',30)->default('token')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presets', function (Blueprint $table) {
            $table->dropColumn('preset_type');
        });
    }
}