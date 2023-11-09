<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCapsuleLine extends Model
{
    use HasFactory;
    protected $table = 'inventory_capsule_lines';

    public function scopeGetMachineInv($query,$gasha_machines_id) {
        return $query->where('gasha_machines_id',$gasha_machines_id)->first();
    }

    public function scopeGetCapsuleNotZeroQty($query,$gasha_machines_id) {
        return $query->where('gasha_machines_id', $gasha_machines_id)
            ->where('qty', '>', 0)
            ->pluck('inventory_capsules_id');
    }

    public function scopeGetLineItems($query, $id) {
        return $query->select(
            'inventory_capsule_lines.qty',
            'gasha_machines.serial_number',
            'sub_locations.description as sub_location'
        )
        ->where('inventory_capsules_id', $id)
        ->leftJoin('gasha_machines', 'inventory_capsule_lines.gasha_machines_id', '=','gasha_machines.id')
        ->leftJoin('sub_locations', 'inventory_capsule_lines.sub_locations_id','=', 'sub_locations.id')
        ->get();
    }
}
