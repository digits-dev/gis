<?php

namespace App\Models;

use App\Models\Audit\CollectRrTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectTokenMessage extends Model
{
    use HasFactory;
    
    public function getUser() : BelongsTo {
        return $this->belongsTo(CmsUsers::class, 'created_by', 'id');
    }

    public function messagedToCollecToken() : HasMany {
        return $this->hasMany(CollectRrTokens::class, 'collect_token_id', 'id');
    }    
}