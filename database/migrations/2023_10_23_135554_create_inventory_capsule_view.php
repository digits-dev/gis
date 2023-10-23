<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryCapsuleView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement("DROP VIEW IF EXISTS inventory_capsule_view;");
       DB::statement("
        create view inventory_capsule_view as
            select
                inventory_capsules.id as inventory_capsules_id,
                consolidation.stockroom_capsule_qty,
                consolidation.machine_capsule_qty,
                consolidation.stockroom_capsule_qty + consolidation.machine_capsule_qty as onhand_qty
            from inventory_capsules
                left JOIN (
                    select
                        inventory_capsule_lines.inventory_capsules_id,
                        COALESCE(
                            inventory_capsule_lines.qty,
                            0
                        ) as stockroom_capsule_qty,
                        COALESCE(
                            subquery.machine_capsule_qty,
                            0
                        ) as machine_capsule_qty
                    from
                        inventory_capsule_lines
                        left join (
                            select
                                inner_query.inventory_capsules_id,
                                SUM(qty) as machine_capsule_qty
                            from
                                inventory_capsule_lines as inner_query
                            where
                                inner_query.gasha_machines_id is not null
                            GROUP BY
                                inner_query.inventory_capsules_id
                        ) as subquery on subquery.inventory_capsules_id = inventory_capsule_lines.inventory_capsules_id
                    where
                        inventory_capsule_lines.sub_locations_id is not null
                ) as consolidation on consolidation.inventory_capsules_id = inventory_capsules.id
       ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS inventory_capsule_view;");
    }
}
