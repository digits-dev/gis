<?php

namespace App\Models\Submaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';

    protected $fillable = [
        'digits_code ',
        'digits_code2 ',
        'item_description',
        'no_of_tokens',
        'created_at',
        'updated_at'
    ];
}
