<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectRrTokens extends Model
{
    use HasFactory;
    protected $table = 'collect_rr_tokens';

    protected $fillable = [
        'reference_number',
        'statuses_id',
        'location_id',
        'collected_qty',
        'received_qty',
        'variance',
        'received_by',
        'received_at',
        'created_by',
        'updated_by',
    ];

    public function lines() : HasMany {
        return $this->hasMany(CollectRrTokenLines::class, 'collected_token_id');
    }

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
