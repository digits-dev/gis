<?php

namespace App\Models\Audit;

use App\Models\CmsUsers;
use App\Models\CollectTokenMessage;
use App\Models\Submaster\GashaMachinesBay;
use App\Models\Submaster\Locations;
use App\Models\Submaster\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class CollectRrTokens extends Model
{
    use HasFactory;
    protected $table = 'collect_rr_tokens';

    protected $fillable = [
        'reference_number',
        'statuses_id',
        'location_id',
        'bay_id',
        'collected_qty',
        'received_qty',
        'variance',
        'received_by',
        'received_at',
        'created_by',
        'confirmed_by',
        'approved_by',
        'rejected_by',
        'voided_by',
        'updated_by',
        'created_at',
        'confirmed_at',
        'approved_at',
        'rejected_at',
        'voided_at',
        'updated_at',
        'deleted_at',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(CollectRrTokenLines::class, 'collected_token_id');
    }

    public function getLocation(): BelongsTo
    {
        return $this->belongsTo(Locations::class, 'location_id', 'id');
    }

    public function getStatus() : BelongsTo {
        return $this->belongsTo(Statuses::class, 'statuses_id', 'id');
    }

    public function getBay(): BelongsTo
    {
        return $this->belongsTo(GashaMachinesBay::class, 'bay_id', 'id');
    }

    public function getCreatedBy(): BelongsTo
    {
        return $this->belongsTo(CmsUsers::class, 'created_by', 'id');
    }

    public function getConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(CmsUsers::class, 'confirmed_by', 'id');
    }

    public function getApprovedBy(): BelongsTo
    {
        return $this->belongsTo(CmsUsers::class, 'approved_by', 'id');
    }

    public function getReceivedBy(): BelongsTo
    {
        return $this->belongsTo(CmsUsers::class, 'received_by', 'id');
    }

    public function collectTokenMessages(): HasMany
    {
        return $this->hasMany(CollectTokenMessage::class, 'collect_token_id', 'id')->orderBy('created_at', 'desc');
    }

    public function scopeDetail($query, $id)
    {
        return $query->leftjoin('statuses', 'collect_rr_tokens.statuses_id', '=', 'statuses.id')
            ->leftjoin('locations', 'collect_rr_tokens.location_id', '=', 'locations.id')
            ->leftjoin('cms_users as requestor', 'collect_rr_tokens.created_by', '=', 'requestor.id')
            ->leftjoin('cms_users as receiver', 'collect_rr_tokens.received_by', '=', 'receiver.id')
            ->select(
                'collect_rr_tokens.id as ct_id',
                'collect_rr_tokens.*',
                'statuses.*',
                'locations.*',
                'requestor.name as requestor_name',
                'receiver.name as receiver_name',
                'collect_rr_tokens.created_at as collect_rr_tokens_created'
            )
            ->where('collect_rr_tokens.id', $id)
            ->first();
    }
}
