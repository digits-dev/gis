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
}