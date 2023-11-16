UPDATE history_capsules
    LEFT JOIN sub_locations ON sub_locations.location_id = history_capsules.locations_id
SET
    history_capsules.to_sub_locations_id = sub_locations.id
WHERE
    history_capsules.capsule_action_types_id = 1;

UPDATE history_capsules
    LEFT JOIN sub_locations ON sub_locations.location_id = history_capsules.locations_id
SET
    history_capsules.qty = history_capsules.qty * -1,
    history_capsules.to_machines_id = history_capsules.gasha_machines_id,
    history_capsules.from_sub_locations_id = sub_locations.id
WHERE
    capsule_action_types_id = 2
    AND history_capsules.qty > 0;

INSERT INTO
    history_capsules(
        reference_number,
        item_code,
        capsule_action_types_id,
        locations_id,
        gasha_machines_id,
        created_at,
        created_by,
        qty,
        from_machines_id,
        to_sub_locations_id
    )
SELECT
    reference_number,
    item_code,
    capsule_action_types_id,
    history_capsules.locations_id,
    gasha_machines_id,
    history_capsules.created_at,
    history_capsules.created_by,
    qty * -1 as qty,
    gasha_machines_id,
    sub_locations.id as sub_locations_id
from history_capsules
    left join sub_locations on sub_locations.location_id = history_capsules.locations_id
where
    capsule_action_types_id = 2;