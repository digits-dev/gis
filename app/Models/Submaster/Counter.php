<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'counters';

    public function scopeGetNextReference($query,$module){

        $ref = $query->where('status','ACTIVE')->where('cms_moduls_id',$module)->first();
        $query->where('status','ACTIVE')->where('cms_moduls_id',$module)->increment('reference_number');
        $refNumber = str_pad($ref->reference_number,8,"0",STR_PAD_LEFT);
        return "$ref->reference_code-$refNumber";
    }

    public function scopeGetNextMachineReference($query,$module){

        $ref = $query->where('status','ACTIVE')->where('cms_moduls_id',$module)->first();
        $query->where('status','ACTIVE')->where('cms_moduls_id',$module)->increment('reference_number');
        $refNumber = str_pad($ref->reference_number,5,"0",STR_PAD_LEFT);
        return "$ref->reference_code-$refNumber";
    }
}
