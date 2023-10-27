SELECT
    YEAR(swap_histories.created_at) AS year,
    MONTH(swap_histories.created_at) AS month,
    swap_histories.locations_id,
    SUM(swap_histories.token_value) token_value,
    SUM(swap_histories.total_value) total_value
FROM swap_histories
WHERE
    swap_histories.status = 'POSTED'
GROUP BY
    MONTH(swap_histories.created_at),
    YEAR(swap_histories.created_at),
    swap_histories.locations_id
ORDER BY year and month