<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class InventoryCapsule extends Model
{
    use HasFactory;
    protected $table = 'inventory_capsules';

    public function scopeGetByLocation($query,$location_id) {
        return $query->where('inventory_capsules.locations_id', $location_id)
            ->leftJoin('locations','inventory_capsules.locations_id','=','locations.id')
            ->select('inventory_capsules.item_code','inventory_capsules.onhand_qty','locations.location_name')
            ->first();
    }

    public function scopeGetInventoryByLocation($query,$location_id) {
        return $query->where('inventory_capsules.locations_id', $location_id)
        ->leftJoin('inventory_capsule_view', 'inventory_capsules.id', 'inventory_capsule_view.inventory_capsules_id')
        ->leftJoin('items', 'inventory_capsules.item_code', 'items.digits_code2')
        ->leftJoin('locations', 'locations.id', '=','inventory_capsules.locations_id')
        ->select('items.digits_code','items.digits_code2','items.item_description','stockroom_capsule_qty','location_name')
        ->get();
    }

    public function scopeGetHeader($query, $id){
        return $query->where('inventory_capsules.id', $id)
            ->leftJoin('inventory_capsule_view', 'inventory_capsules.id', 'inventory_capsule_view.inventory_capsules_id')
            ->leftJoin('items', 'inventory_capsules.item_code', 'items.digits_code2')
            ->leftJoin('locations', 'locations.id', '=','inventory_capsules.locations_id')
            ->first();
    }

    public function scopeGetMachine($query, $item_code) {
        return $query->leftJoin('inventory_capsule_lines', 'inventory_capsule_lines.inventory_capsules_id', 'inventory_capsules.id')
            ->leftJoin('gasha_machines', 'inventory_capsule_lines.gasha_machines_id', 'gasha_machines.id')
            ->whereNotNull('gasha_machines.id')
            ->where('inventory_capsules.item_code', $item_code)
            ->where('inventory_capsule_lines.qty', '>', 0)
            ->where('inventory_capsules.locations_id', CRUDBooster::myLocationId())
            ->get();
    }
}
