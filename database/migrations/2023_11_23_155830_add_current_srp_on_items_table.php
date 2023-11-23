<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentSrpOnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('msrp', 18, 2)->nullable()->unsigned()->after('no_of_tokens');
            $table->decimal('current_srp', 18, 2)->nullable()->unsigned()->after('no_of_tokens');
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
            $table->dropColumn('msrp');
            $table->dropColumn('current_srp');
        });
    }
}
