<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFloatHistory extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'cash_float_histories';

}
