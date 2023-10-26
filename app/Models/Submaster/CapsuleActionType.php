<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleActionType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'capsule_action_types';

    public function scopeGetByDescription($query,$description) {
        return $query->where('description',$description)->first();
    }
}
