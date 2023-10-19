<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GashaMachines extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'gasha_machines';

    public function scopeActiveMachines($query){
        return $query->where('status','ACTIVE')->get();
    }
}
