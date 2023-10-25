<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleReturn extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'capsule_returns';
}
