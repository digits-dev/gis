<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreRrToken extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'store_rr_token';

    public function scopegetDatas($query, $id){
        return $query->leftjoin('locations as from_location', 'store_rr_token.from_locations_id', '=', 'from_location.id')
        ->leftjoin('locations as to_location', 'store_rr_token.to_locations_id', '=', 'to_location.id')
        ->leftjoin('statuses', 'store_rr_token.statuses_id', '=', 'statuses.id')
        ->leftjoin('cms_users as requested', 'store_rr_token.created_by','=', 'requested.id')
        ->leftjoin('cms_users as receive_by', 'store_rr_token.received_by','=', 'receive_by.id')
        ->leftjoin('cms_users as updated_by', 'store_rr_token.updated_by','=', 'updated_by.id')
        ->select(
                'store_rr_token.id as dt_id',
                'store_rr_token.*',
                'statuses.*',
                'from_location.location_name as from_location',
                'to_location.location_name as to_location',
                'requested.name as requested_name',
                'receive_by.name as receive_by_name',
                'updated_by.name as updated_by_name'
                )
        ->where('store_rr_token.id', $id)->first();
    }
}
