select
    cash_float_histories.id as cash_float_histories_id,
    float_history_view.cash_value,
    float_history_view.token_value,
    cash_float_history_lines.qty as token_qty
from cash_float_histories
    left join float_history_view on cash_float_histories.id = float_history_view.cash_float_histories_id
    left join cash_float_history_lines on cash_float_histories.id = cash_float_history_lines.cash_float_histories_id
where
    cash_float_history_lines.float_entries_id = 14