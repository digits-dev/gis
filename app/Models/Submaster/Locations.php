<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'locations';

    public function scopeActive($query){
        return $query->where('status','ACTIVE')->get();
    }

    public function scopeActiveDisburseToken($query){
        return $query->where('id','!=',1)->where('status','ACTIVE')->get();
    }
}
