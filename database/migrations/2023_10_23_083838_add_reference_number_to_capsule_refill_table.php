<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceNumberToCapsuleRefillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('capsule_refills', function (Blueprint $table) {
            $table->string('reference_number')->length(20)->nullable()->after('id');
            $table->integer('locations_id')->length(10)->unsigned()->nullable()->after('qty');
        });

        Schema::table('capsule_returns', function (Blueprint $table) {
            $table->string('reference_number')->length(20)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('capsule_refills', function (Blueprint $table) {
            $table->dropColumn('reference_number');
            $table->dropColumn('locations_id');
        });

        Schema::table('capsule_refills', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
}
