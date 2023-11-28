<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFloatHistory extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'cash_float_histories';

    public function scopeFilter($query, array $filters) {
        if($filters['search'] ?? false) {
             $query->where('float_history_view.reference_number', 'like', '%' . request('search') . '%')
                 ->orWhere('float_history_view.cash_value', 'like', '%' . request('search') . '%')
                 ->orWhere('float_history_view.token_value', 'like', '%' . request('search') . '%')
                 ->orWhere('float_history_view.token_qty', 'like', '%' . request('search') . '%')
                 ->orWhere('float_history_view.entry_date', 'like', '%' . request('search') . '%')
                 ->orWhere('cash_float_histories.created_at', 'like', '%' . request('search') . '%')
                 ->orWhere('locations.location_name', 'like', '%' . request('search') . '%')
                 ->orWHere('cms_users.name', 'like', '%' . request('search') . '%');
        }
     }

}