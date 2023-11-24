<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleSplit extends Model
{
    use HasFactory;
    protected $table = 'capsule_split';

       public function scopeDetails($query, $id){
        return $query->where('capsule_split.id', $id)
            ->leftJoin('locations as loc', 'loc.id', 'capsule_split.locations_id')
            ->leftJoin('cms_users as cms', 'cms.id', 'capsule_split.created_by')
            ->leftJoin('gasha_machines as to_machine', 'to_machine.id', 'capsule_split.to_machines_id')
            ->leftJoin('gasha_machines as from_machine', 'from_machine.id', 'capsule_split.from_machines_id')
            ->leftJoin('capsule_split_lines as cml', 'cml.capsule_split_id', 'capsule_split.id')
            ->select('capsule_split.*',
                'loc.location_name as location_name',
                'cms.name as cms_name',
                'to_machine.serial_number as to_machine_serial_number',
                'from_machine.serial_number as from_machine_serial_number',
                'cml.item_code as item_code',
                'cml.actual_qty',
                'cml.remaining_qty',
                'cml.transfer_qty',

            );
    }
}