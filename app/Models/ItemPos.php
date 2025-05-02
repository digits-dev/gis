<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemPos extends Model
{
    use HasFactory;

    public function item_lines(): HasMany
    {
        return $this->hasMany(ItemPosLines::class, 'item_pos_id');
    }

}
