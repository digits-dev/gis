<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenInventory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'token_inventories';
    protected $fillable = [
        'qty', 
        'locations_id',
        'created_by',
        'created_at	',
        'updated_by',
        'updated_at	',
   
    ];

    public function scopeGetQty($query, $id){
        return $query->where('locations_id','=',$id)->first();
    }
}
