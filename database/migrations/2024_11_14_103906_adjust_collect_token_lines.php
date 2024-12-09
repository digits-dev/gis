<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustCollectTokenLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {

            $table->string('variance_type')->after('variance')->nullable();
            $table->integer('projected_capsule_sales')->after('variance_type')->nullable();
            $table->integer('actual_capsule_sales')->after('projected_capsule_sales')->nullable();
            $table->integer('current_capsule_inventory')->after('actual_capsule_sales')->nullable();
            $table->integer('actual_capsule_inventory')->after('current_capsule_inventory')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collect_rr_token_lines', function (Blueprint $table) {
            $table->dropColumn('variance_type');
            $table->dropColumn('projected_capsule_sales');
            $table->dropColumn('actual_capsule_sales');
            $table->dropColumn('current_capsule_inventory');
            $table->dropColumn('actual_capsule_inventory');
        });
    }
}
