select
    inventory_capsules.id as inventory_capsules_id,
    coalesce(stock_room_qty.qty, 0) as stockroom_capsule_qty,
    coalesce(machine_qty.qty, 0) as machine_capsule_qty,
    coalesce(stock_room_qty.qty, 0) + coalesce(machine_qty.qty, 0) as onhand_qty
from inventory_capsules
    left JOIN (
        select
            inventory_capsule_lines.inventory_capsules_id,
            SUM(inventory_capsule_lines.qty) as qty
        from
            inventory_capsule_lines as inventory_capsule_lines
        where
            inventory_capsule_lines.sub_locations_id is not null
        group by inventory_capsule_lines.inventory_capsules_id
    ) as stock_room_qty on stock_room_qty.inventory_capsules_id = inventory_capsules.id
    left join (
        select
            inventory_capsule_lines.inventory_capsules_id,
            SUM(inventory_capsule_lines.qty) as qty
        from
            inventory_capsule_lines as inventory_capsule_lines
        where
            inventory_capsule_lines.gasha_machines_id is not null
        group by inventory_capsule_lines.inventory_capsules_id
    ) as machine_qty on machine_qty.inventory_capsules_id = inventory_capsules.id