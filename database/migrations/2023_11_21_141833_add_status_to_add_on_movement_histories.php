<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAddOnMovementHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_on_movement_histories', function (Blueprint $table) {
            $table->enum('status', ['POSTED', 'VOID'])->default('POSTED')->after('qty');
            $table->integer('updated_by')->length(10)->unsigned()->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_on_movement_histories', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('updated_by');
        });
    }
}