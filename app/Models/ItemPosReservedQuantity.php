<?php

namespace App\Models;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPosReservedQuantity extends Model
{
    use HasFactory;


    protected $fillable = [

        'id',
        'user_id',
        'locations_id',
        'digits_code',
        'jan_number',
        'item_description',
        'qty',
        'created_at',
        'updated_at',

    ];
}
