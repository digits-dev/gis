<?php

namespace App\Models;

use App\Models\Submaster\GashaMachinesBay;
use App\Models\Submaster\Locations;
use App\Models\Submaster\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectTokenHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'statuses_id',
        'collect_token_remarks_id',
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
        'updated_by',
        'created_at',
        'confirmed_at',
        'approved_at',
        'rejected_at',
        'updated_at',
        'deleted_at',
    ];

    public function history_lines() : HasMany {
        return $this->hasMany(CollectTokenHistoryLines::class, 'collect_token_id', 'id');
    }

    public function getLocation(): BelongsTo
    {
        return $this->belongsTo(Locations::class, 'location_id', 'id');
    }

    public function getBay(): BelongsTo
    {
        return $this->belongsTo(GashaMachinesBay::class, 'bay_id', 'id');
    }

    public function getStatus() : BelongsTo {
        return $this->belongsTo(Statuses::class, 'statuses_id', 'id');
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
        return $this->hasMany(CollectTokenMessage::class, 'collect_token_id', 'collect_token_remarks_id')->orderBy('created_at', 'desc');
    }

}
