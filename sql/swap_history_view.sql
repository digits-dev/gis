SELECT
    mop.id AS mode_of_payments_id,
    date_table.formatted_date,
    COALESCE(
        SUM(swap_histories.total_value),
        0
    ) AS total_value
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
    LEFT JOIN swap_histories ON mop.id = swap_histories.mode_of_payments_id
    AND date_table.formatted_date = DATE_FORMAT(
        swap_histories.created_at,
        '%Y-%m-%d'
    )
GROUP BY
    mop.id,
    date_table.formatted_date
ORDER BY
    mop.id,
    date_table.formatted_date;