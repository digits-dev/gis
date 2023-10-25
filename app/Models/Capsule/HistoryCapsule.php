<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryCapsule extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'history_capsules';
}
