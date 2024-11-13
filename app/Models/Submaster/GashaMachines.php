<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GashaMachines extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'gasha_machines';

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
