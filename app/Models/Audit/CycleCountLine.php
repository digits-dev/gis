<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleCountLine extends Model
{
    use HasFactory;
    protected $table = 'cycle_count_lines';
    protected $fillable = [
        "cycle_counts_id",
        "gasha_machines_id",
        "digits_code",
        "qty",
        "created_at",
        "updated_at"
    ];
}
