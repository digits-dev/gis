<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulloutToken extends Model
{
    use HasFactory;
    protected $table = 'pullout_tokens';
    protected $guarded = [];
}
