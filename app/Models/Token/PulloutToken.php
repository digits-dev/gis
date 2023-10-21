<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulloutToken extends Model
{
    use HasFactory;
    protected $table = 'pullout_tokens';
    protected $guarded = [];

    public function scopegetDatas($query, $id){
        return $query->leftjoin('locations as from_location', 'pullout_tokens.locations_id', '=', 'from_location.id')
        ->leftjoin('locations as to_location', 'pullout_tokens.to_locations_id', '=', 'to_location.id')
        ->leftjoin('statuses', 'pullout_tokens.statuses_id', '=', 'statuses.id')
        ->leftjoin('cms_users as requested', 'pullout_tokens.created_by','=', 'requested.id')
        ->leftjoin('cms_users as receive_by', 'pullout_tokens.received_by','=', 'receive_by.id')
        ->leftjoin('cms_users as updated_by', 'pullout_tokens.updated_by','=', 'updated_by.id')
        ->select(
                'pullout_tokens.id as pt_id',
                'pullout_tokens.*',
                'statuses.*',
                'from_location.location_name as from_location',
                'to_location.location_name as to_location',
                'requested.name as requested_name',
                'receive_by.name as receive_by_name',
                'updated_by.name as updated_by_name'
                )
        ->where('pullout_tokens.id', $id)->first();
    }
}
