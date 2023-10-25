<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectRrTokens extends Model
{
    use HasFactory;
    protected $table = 'collect_rr_tokens';

    public function scopeDetail($query, $id){
        return $query->leftjoin('statuses', 'collect_rr_tokens.statuses_id', '=', 'statuses.id')
                     ->leftjoin('locations', 'collect_rr_tokens.location_id', '=', 'locations.id')
                     ->leftjoin('cms_users as requestor', 'collect_rr_tokens.created_by', '=', 'requestor.id')
                     ->leftjoin('cms_users as receiver', 'collect_rr_tokens.received_by', '=', 'receiver.id')
                     ->select('collect_rr_tokens.id as ct_id',
                              'collect_rr_tokens.*',
                              'statuses.*',
                              'locations.*',
                              'requestor.name as requestor_name',
                              'receiver.name as receiver_name',
                              'collect_rr_tokens.created_at as collect_rr_tokens_created')
                     ->where('collect_rr_tokens.id',$id)
                     ->first();
    }
}
