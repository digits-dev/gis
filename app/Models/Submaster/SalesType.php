<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'sales_types';

    public function scopeGetByDescription($query,$description) {
        return $query->where('description',$description)->first();
    }
}
