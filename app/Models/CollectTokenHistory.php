<?php

namespace App\Models;

use App\Models\Submaster\GashaMachinesBay;
use App\Models\Submaster\Locations;
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

    public function getLocation(): BelongsTo
    {
        return $this->belongsTo(Locations::class, 'location_id', 'id');
    }

    public function getBay(): BelongsTo
    {
        return $this->belongsTo(GashaMachinesBay::class, 'bay_id', 'id');
    }

    public function history_lines() : HasMany {
        return $this->hasMany(CollectTokenHistoryLines::class, 'collect_token_id', 'id');
    }
}