<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenAdjustment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'token_adjustments';
}
