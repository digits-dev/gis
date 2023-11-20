<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwapHistoryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS swap_history_view;");
        DB::statement("
            CREATE VIEW swap_history_view AS
            SELECT
                date_table.formatted_date,
                mop.id AS mode_of_payments_id,
                COALESCE(
                    SUM(swap_histories.total_value),
                    0
                ) AS total_value,
                swap_histories.created_by,
                swap_histories.locations_id
            FROM (
                    SELECT id
                    FROM
                        mode_of_payments
                ) AS mop
                CROSS JOIN (
                    SELECT
                        DISTINCT DATE_FORMAT(created_at, '%Y-%m-%d') AS formatted_date
                    FROM
                        swap_histories
                ) AS date_table
                LEFT JOIN (
                    SELECT *
                    FROM swap_histories
                    WHERE
                        swap_histories.status = 'POSTED'
                ) AS swap_histories ON mop.id = swap_histories.mode_of_payments_id
                AND date_table.formatted_date = DATE_FORMAT(
                    swap_histories.created_at,
                    '%Y-%m-%d'
                )
            GROUP BY
                mop.id,
                date_table.formatted_date,
                swap_histories.locations_id,
                swap_histories.created_by
            ORDER BY
                date_table.formatted_date,
                mop.id;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS swap_history_view;");
    }
}
