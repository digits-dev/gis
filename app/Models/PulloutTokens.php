<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulloutTokens extends Model
{
    use HasFactory;
    protected $table = 'pullout_tokens';
    protected $guarded = [];
}
