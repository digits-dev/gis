<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleMerge extends Model
{
    use HasFactory;
    protected $table = 'capsule_merges';
    protected $guarded = [];

    public function scopeDetails($query, $id){
        return $query->where('capsule_merges.id', $id)
            ->leftJoin('locations as loc', 'loc.id', 'capsule_merges.locations_id')
            ->leftJoin('cms_users as cms', 'cms.id', 'capsule_merges.created_by')
            ->leftJoin('gasha_machines as to_machine', 'to_machine.id', 'capsule_merges.to_machines_id')
            ->leftJoin('gasha_machines as from_machine', 'from_machine.id', 'capsule_merges.from_machines_id')
            ->leftJoin('capsule_merge_lines as cml', 'cml.capsule_merges_id', 'capsule_merges.id')
            ->select('capsule_merges.*',
                'loc.location_name as location_name',
                'cms.name as cms_name',
                'to_machine.serial_number as to_machine_serial_number',
                'from_machine.serial_number as from_machine_serial_number',
                'cml.item_code as item_code',
                'cml.qty as qty');
    }
}
