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
        "variance",
        "created_at",
        "updated_at"
    ];

    public function scopeDetailBody($query, $id){
        return $query->leftjoin('gasha_machines', 'cycle_count_lines.gasha_machines_id', '=', 'gasha_machines.id')
            ->select('cycle_count_lines.id as id',
                    'cycle_count_lines.*',
                    'gasha_machines.*')
            ->where('cycle_count_lines.cycle_counts_id',$id)
            ->get();
    }
}
