<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCycleCountApprovalView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS cycle_counts_approval_view;");
        DB::statement("
            CREATE VIEW cycle_counts_approval_view AS
            SELECT 
                cycle_count_lines.status AS status_id,
                statuses.status_description AS status,
                MAX(locations.location_name) AS location_name,
                cycle_counts.locations_id AS location_id
            FROM cycle_count_lines
                LEFT JOIN cycle_counts ON cycle_count_lines.cycle_counts_id = cycle_counts.id
                LEFT JOIN locations ON cycle_counts.locations_id = locations.id
                LEFT JOIN statuses ON cycle_count_lines.status = statuses.id
            WHERE cycle_count_lines.status = 9
            GROUP BY 
                cycle_count_lines.status,
                cycle_counts.locations_id     
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS cycle_counts_approval_view;");
    }
}
