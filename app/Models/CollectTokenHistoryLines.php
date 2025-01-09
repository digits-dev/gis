<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
