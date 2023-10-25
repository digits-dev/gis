<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloatEntryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS float_entry_view;");
        DB::statement("
            CREATE VIEW float_entry_view AS
            SELECT entry_date,
                    locations_id,
                    MAX(CASE WHEN float_types_id = 1 THEN created_at END) AS sod,
                    MAX(CASE WHEN float_types_id = 2 THEN created_at END) AS eod
            FROM cash_float_histories
            GROUP BY entry_date, locations_id;        
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS float_entry_view;");
    }
}
