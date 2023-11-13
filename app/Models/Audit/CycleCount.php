<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleCount extends Model
{
    use HasFactory;
    protected $table = 'cycle_counts';
    protected $fillable = [
        "reference_number",
        "locations_id",
        "sub_locations_id",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
    ];
}
