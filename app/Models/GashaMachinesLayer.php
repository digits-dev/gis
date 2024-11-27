<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GashaMachinesLayer extends Model
{
    use HasFactory;

    protected $table = 'gasha_machines_layers';

    public function scopeActive($query){
        return $query->where('status','ACTIVE')->get();
    }
}
