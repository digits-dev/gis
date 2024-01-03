<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToCapsuleSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('capsule_sales', function (Blueprint $table) {
            $table->string('status')->length(20)->default('ACTIVE')->nullable()->after('sales_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('capsule_sales', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
