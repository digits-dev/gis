<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloatHistoryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS float_history_view;");
        DB::statement("
            CREATE VIEW float_history_view AS
            select
                cash_float_histories.id as cash_float_histories_id,
                locations_id,
                float_types_id,
                entry_date,
                cash_entries.cash_value,
                token_entries.token_value
            from cash_float_histories
                left JOIN (
                    select
                        cash_float_histories_id,
                        sum(value) as cash_value
                    from
                        cash_float_history_lines
                    where
                        mode_of_payments_id is not null
                    group by
                        cash_float_histories_id
                ) as cash_entries on cash_entries.cash_float_histories_id = cash_float_histories.id
                left join (
                    select
                        cash_float_histories_id,
                        value as token_value
                    from
                        cash_float_history_lines
                    where
                        float_entries_id = 14
                ) as token_entries on token_entries.cash_float_histories_id = cash_float_histories.id;       
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS float_history_view;");

    }
}
