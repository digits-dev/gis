<?php

namespace App\Models\Audit;

use App\Models\Capsule\InventoryCapsuleLine;
use App\Models\Submaster\GashaMachines;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectRrTokenLines extends Model
{
    use HasFactory;
    protected $table = 'collect_rr_token_lines';

    protected $fillable = [
        'line_status', 
        'collected_token_id', 
        'gasha_machines_id',
        'no_of_token',
        'qty',
        'variance',
        'location_id',
        'current_cash_value',
        'created_at	',
        'updated_at',
        'deleted_at	',
    ];

    public function machineSerial() : BelongsTo {
        return $this->belongsTo(GashaMachines::class, 'gasha_machines_id');
    }
    
    public function inventory_capsule_lines() : HasMany {
        return $this->hasMany(InventoryCapsuleLine::class, 'gasha_machines_id', 'gasha_machines_id')->where('qty', '>', 0);
    }

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

    public function scopeDetailApprovalBody($query, $id){
        return $query->leftjoin('gasha_machines', 'collect_rr_token_lines.gasha_machines_id', '=', 'gasha_machines.id')
                     ->leftjoin('collect_rr_tokens', 'collect_rr_token_lines.collected_token_id', '=', 'collect_rr_tokens.id')
                     ->select('collect_rr_tokens.reference_number',
                            'collect_rr_token_lines.id as id',
                            'collect_rr_token_lines.*',
                            'collect_rr_token_lines.no_of_token as no_of_token_line',
                            'gasha_machines.*'
                            )
                     ->where('collect_rr_token_lines.line_status',9)
                     ->where('collect_rr_tokens.location_id',$id)
                     ->get();
    }
}
