<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCycleCountFloorView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS cycle_count_floor_view;");
        DB::statement("
            CREATE VIEW cycle_count_floor_view AS
            SELECT 
                gasha_machines.id AS gasha_machines_id,
                gasha_machines.serial_number,
                cycle_counts.reference_number,
                cycle_count_lines.digits_code,
                cycle_count_lines.qty,
                cycle_counts.locations_id
            FROM cycle_count_lines
                LEFT JOIN gasha_machines ON cycle_count_lines.gasha_machines_id = gasha_machines.id
                LEFT JOIN cycle_counts ON cycle_count_lines.cycle_counts_id = cycle_counts.id
            WHERE 
                cycle_count_lines.status = 9 AND
                cycle_count_lines.cycle_count_type = 'FLOOR'
            GROUP BY
                cycle_count_lines.id    
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS cycle_count_floor_view;");
    }
}
