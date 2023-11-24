<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleSplitLine extends Model
{
    use HasFactory;
    protected $table = 'capsule_split_lines';

    public function scopeDetails($query, $id){
        return $query->where('capsule_split_id', $id)
            ->leftJoin('capsule_split as cmg', 'cmg.id', 'capsule_split_lines.capsule_split_id')
            ->leftJoin('items as from', 'from.digits_code', 'capsule_split_lines.item_code')
            ->leftJoin('cms_users as cms', 'cms.id', 'cmg.created_by')
            ->select('capsule_split_lines.actual_qty',
                'capsule_split_lines.remaining_qty',
                'capsule_split_lines.transfer_qty',
                'capsule_split_lines.item_code',
                'from.item_description as from_item_description',
                'cms.name as cms_name',
                'cmg.created_at');
    }

}