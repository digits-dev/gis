<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleMergeLine extends Model
{
    use HasFactory;
    protected $table = 'capsule_merge_lines';
    protected $guarded = [];

    public function scopeDetails($query, $id){
        return $query->where('capsule_merges_id', $id)
            ->leftJoin('capsule_merges as cmg', 'cmg.id', 'capsule_merge_lines.capsule_merges_id')
            ->leftJoin('items as from', 'from.digits_code', 'capsule_merge_lines.item_code')
            ->leftJoin('cms_users as cms', 'cms.id', 'cmg.created_by')
            ->select('capsule_merge_lines.qty',
                'capsule_merge_lines.item_code',
                'from.item_description as from_item_description',
                'cms.name as cms_name',
                'cmg.created_at');
    }
}
