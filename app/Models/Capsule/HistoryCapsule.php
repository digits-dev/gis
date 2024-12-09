<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryCapsule extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'history_capsules';

    protected $fillable = [
        'reference_number',
        'item_code',
        'capsule_action_types_id',
        'locations_id',
        'gasha_machines_id',
        'created_by',
        'updated_by',
        'qty',
        'from_sub_locations_id',
        'to_machines_id',
        'from_machines_id',
        'to_sub_locations_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
