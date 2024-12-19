<?php

namespace App\Models\Submaster;

use App\Models\Audit\CollectRrTokenLines;
use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\CmsUsers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GashaMachines extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'gasha_machines';
    protected $fillable = [
        'serial_number',
        'description',
        'location_name',
        'location_id',
        'no_of_token',
        'bay',
        'bay_select_status',
        'bay_selected_by',
        'layer',
        'column',
        'machine_statuses_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function getCollectTokenLines() : HasMany {
         return $this->hasMany(CollectRrTokenLines::class, 'gasha_machines_id', 'id');
    }

    public function getInventoryItem() : HasMany {
        return $this->hasMany(InventoryCapsuleLine::class, 'gasha_machines_id')->orderBy('updated_at', 'DESC');
   }

    public function getBay() : BelongsTo {
        return $this->belongsTo(GashaMachinesBay::class, 'bay', 'id');
    }

    public function getBaySelector() : BelongsTo {
        return $this->belongsTo(CmsUsers::class, 'bay_selected_by', 'id');
    }

    public function scopeActiveMachines($query){
        return $query->where('status','ACTIVE')->get();
    }

    public function scopeGetMachineByLocation($query,$serial_number,$location_id) {
        return $query->where('status','ACTIVE')
            ->where('serial_number', $serial_number)
            ->where('location_id', $location_id)
            ->first();
    }

    public function scopeGetMachineWithBay($query)
    {
        return $query->selectRaw('gm.location_name, gm.location_id, GROUP_CONCAT(DISTINCT gm.bay ORDER BY gm.bay) AS bays')
            ->from('gasha_machines as gm')
            ->where('status','ACTIVE')
            ->groupBy('gm.location_name', 'gm.location_id');
    }    
}
