<?php

namespace App\Models\CmsModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsModule extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'cms_moduls';

    public function scopeGetModuleByName($query,$name) {
        return $query->where('name',$name)->first();
    }
}
