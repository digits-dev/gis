<?php

namespace App\Models\Submaster;

use App\Models\Capsule\InventoryCapsule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';

    protected $fillable = [
        'digits_code',
        'digits_code2',
        'item_description',
        'no_of_tokens',
        'created_at',
        'updated_at'
    ];

    public function capsule_inventory()
    {
        return $this->belongsTo(InventoryCapsule::class, 'item_code', 'digits_code2');
    }
}
