<?php

namespace App\Models;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPosLines extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = CRUDBooster::myId() ?? auth()->user()->id;
            $model->updated_at = null;
        });
        static::updating(function($model)
        {
            $model->updated_by = CRUDBooster::myId() ?? auth()->user()->id;
        });
    }


    protected $fillable = [
        'item_pos_id',
        'digits_code',
        'jan_number',
        'item_description',
        'qty',
        'current_srp',
        'total_price',
        'locations_id',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
