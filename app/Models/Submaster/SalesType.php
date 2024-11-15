<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'sales_types';

    public const RETURN = 1;
    public const CYCLECOUNT = 2;
    public const CYCLEOUT = 3;
    public const MERGE = 4;
    public const SWAP = 5;
    public const SPLIT = 6;
    public const COLLECTTOKEN = 7;

    public function scopeGetByDescription($query,$description) {
        return $query->where('description',$description)->first();
    }
}
