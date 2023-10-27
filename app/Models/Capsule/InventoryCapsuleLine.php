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
}
