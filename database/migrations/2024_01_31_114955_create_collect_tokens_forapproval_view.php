<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectTokensForapprovalView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS collect_tokens_forapproval_view;");
        DB::statement("
            CREATE VIEW collect_tokens_forapproval_view AS
            SELECT 
                collect_rr_token_lines.line_status AS line_status,
                statuses.status_description AS status,
                MAX(locations.location_name) AS location_name,
                collect_rr_tokens.location_id AS location_id
            FROM collect_rr_token_lines
                LEFT JOIN collect_rr_tokens ON collect_rr_token_lines.collected_token_id = collect_rr_tokens.id
                LEFT JOIN locations ON collect_rr_tokens.location_id = locations.id
                LEFT JOIN statuses ON collect_rr_token_lines.line_status = statuses.id
            WHERE collect_rr_token_lines.line_status = 9
            GROUP BY 
                collect_rr_token_lines.line_status,
                collect_rr_tokens.location_id;   
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS collect_tokens_forapproval_view;");
    }
}
