<?php

namespace App\Models\Capsule;

use App\Models\Audit\CollectRrTokenLines;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CapsuleSales extends Model
{
    use HasFactory;
    protected $guarded = [];
}
