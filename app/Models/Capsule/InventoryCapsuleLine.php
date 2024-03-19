<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCapsuleLine extends Model
{
    use HasFactory;
    protected $table = 'inventory_capsule_lines';
    protected $fillable = [
        'inventory_capsules_id',
        'gasha_machines_id',
        'sub_locations_id',
        'qty',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

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
                'sub_locations.description as sub_location')
            ->where('inventory_capsules_id', $id)
            ->leftJoin('gasha_machines', 'inventory_capsule_lines.gasha_machines_id', '=','gasha_machines.id')
            ->leftJoin('sub_locations', 'inventory_capsule_lines.sub_locations_id','=', 'sub_locations.id')
            ->orderByRaw("CASE WHEN sub_locations.description LIKE 'STOCK ROOM%' THEN 1 ELSE 2 END")
            ->orderBy('sub_location', 'asc')
            ->get();
    }

    public function scopeGetInventoryByMachine($query, $machine_id){
        return $query->where('gasha_machines_id', $machine_id)
            ->where('inventory_capsule_lines.qty', '>', '0')
            ->leftJoin('inventory_capsules as ic', 'ic.id' , 'inventory_capsule_lines.inventory_capsules_id')
            ->leftJoin('items', 'items.digits_code2', 'ic.item_code' )
            ->select('inventory_capsule_lines.*',
                'items.digits_code as item_code',
                'items.item_description')
            ->get();
    }
}