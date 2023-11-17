<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectRrTokenLines extends Model
{
    use HasFactory;
    protected $table = 'collect_rr_token_lines';

    protected $fillable = [
        'collected_token_id', 
        'gasha_machines_id',
        'gasha_machines_id',
        'created_at	',
        'updated_at',
        'deleted_at	',
    ];

    public function scopeDetailBody($query, $id){
        return $query->leftjoin('gasha_machines', 'collect_rr_token_lines.gasha_machines_id', '=', 'gasha_machines.id')
                     ->select('collect_rr_token_lines.id as id',
                              'collect_rr_token_lines.*',
                              'collect_rr_token_lines.no_of_token as no_of_token_line',
                              'gasha_machines.*'
                              )
                     ->where('collect_rr_token_lines.collected_token_id',$id)
                     ->get();
    }
}
