<?php

namespace App\Models;

use App\Models\Submaster\GashaMachines;
use App\Models\Submaster\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectTokenHistoryLines extends Model
{
    use HasFactory;

    protected $fillable = [
        'collect_token_id',
        'gasha_machines_id',
        'jan_number',
        'no_of_token',
        'qty',
        'variance',
        'variance_type',
        'projected_capsule_sales',
        'actual_capsule_sales',
        'current_capsule_inventory',
        'actual_capsule_inventory',
        'created_at',
        'updated_at',
    ];

    public function get_item_desc() : BelongsTo {
        return $this->belongsTo(Item::class, 'jan_number', 'digits_code');
    }

    public function get_serial_number() : BelongsTo {
        return $this->belongsTo(GashaMachines::class, 'gasha_machines_id', 'id');
    }
}
