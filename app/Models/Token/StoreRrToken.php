<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreRrToken extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'store_rr_token';
}
