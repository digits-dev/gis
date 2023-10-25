<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCapsule extends Model
{
    use HasFactory;
    protected $table = 'inventory_capsules';

    public function scopeGetByLocation($query,$location_id) {
        return $query->where('locations_id', $location_id)->first();
    }
}
