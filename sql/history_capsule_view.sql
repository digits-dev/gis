SELECT
    history_capsules.id as history_capsules_id,
    COALESCE(
        from_sub_locations.description,
        from_machines.serial_number
    ) as from_description,
    COALESCE(
        to_machines.serial_number,
        to_sub_locations.description
    ) as to_description
FROM history_capsules
    LEFT JOIN sub_locations AS from_sub_locations ON from_sub_locations.id = history_capsules.from_sub_locations_id
    LEFT JOIN gasha_machines AS to_machines ON to_machines.id = history_capsules.to_machines_id
    LEFT JOIN gasha_machines AS from_machines ON from_machines.id = history_capsules.from_machines_id
    LEFT JOIN sub_locations AS to_sub_locations ON to_sub_locations.id = history_capsules.to_sub_locations_id