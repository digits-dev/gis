<?php

namespace App\Models\PosFrontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonsHistory extends Model
{
    use HasFactory;
    protected $table = 'addons_history';

    protected $fillable = [
        'id',
        'token_swap_id',
        'item_pos_id',
        'digits_code',
        'qty',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}