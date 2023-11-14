<?php

namespace App\Models\PosFrontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwapHistory extends Model
{
    use HasFactory;
    protected $table = 'swap_histories';

    public function scopeGetTotalValue($query, $id) {

       $swapHistory =  $query->where('locations_id', $id)->where('status', '!=', 'VOID');
       $totalCashValue = $swapHistory->sum('cash_value');
       $totalTokenValue = $swapHistory->sum('token_value');

        return ['totalCashValue' => $totalCashValue, 'totalTokenValue' => $totalTokenValue];
    }

    public function scopeFilter($query, array $filters) {
        if($filters['search'] ?? false) {
             $query->where('reference_number', 'like', '%' . request('search') . '%')
                 ->orWhere('total_value', 'like', '%' . request('search') . '%')
                 ->orWhere('token_value', 'like', '%' . request('search') . '%')
                 ->orWHere('mode_of_payments.payment_description', 'like', '%' . request('search') . '%')
                 ->orWhere('payment_reference', 'like', '%' . request('search') . '%')
                 ->orWhere('locations.location_name', 'like', '%' . request('search') . '%')
                 ->orWHere('cms_users.name', 'like', '%' . request('search') . '%')
                 ->orWHere('swap_histories.status', 'like', '%' . request('search') . '%');
        }
     }
}