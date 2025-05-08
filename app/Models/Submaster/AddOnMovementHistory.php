<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOnMovementHistory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'add_on_movement_histories';

    protected $fillable = [
        'id',
        'reference_number',
        'digits_code',
        'add_on_action_types_id',
        'locations_id',
        'qty',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_at',
    ];
}
