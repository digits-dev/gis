<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GashaMachinesBay extends Model
{
    use HasFactory;

    protected $table = 'gasha_machines_bay';

    public function scopeActive($query){
        return $query->where('status','ACTIVE')->get();
    }
}
