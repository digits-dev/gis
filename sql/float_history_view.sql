-- create view float_history_view as

select
    cash_float_histories.id as cash_float_histories_id,
    CONCAT('FH-', LPAD(cash_float_histories.id, 8, '0')) as reference_number,
    locations_id,
    float_types_id,
    entry_date,
    cash_entries.cash_value,
    token_entries.token_value,
    token_entries.token_qty,
    cash_float_histories.conversion_rate
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
            value as token_value,
            qty as token_qty
        from
            cash_float_history_lines
        where
            float_entries_id = 14
    ) as token_entries on token_entries.cash_float_histories_id = cash_float_histories.id;