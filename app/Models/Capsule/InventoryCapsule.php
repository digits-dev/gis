<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
