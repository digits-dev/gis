<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectTokenHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'statuses_id',
        'location_id',
        'bay_id',
        'collected_qty',
        'received_qty',
        'variance',
        'received_by',
        'received_at',
        'created_by',
        'confirmed_by',
        'approved_by',
        'rejected_by',
        'updated_by',
        'created_at',
        'confirmed_at',
        'approved_at',
        'rejected_at',
        'updated_at',
        'deleted_at',
    ];
}
