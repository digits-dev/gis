<?php

namespace App\Models\Submaster;

use App\Models\Audit\CollectRrTokens;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GashaMachinesBay extends Model
{
    use HasFactory;

    protected $table = 'gasha_machines_bay';

    public function scopeActive($query){
        return $query->where('status','ACTIVE')->get();
    }

    public function getCollectionStatus() : HasMany {
        return $this->hasMany(CollectRrTokens::class, 'bay_id')->where('location_id', CRUDBooster::myLocationId())->where('qty', '>', 0);
        // ->where('statuses_id', '<>', 5) if need
    }

    public function getGashaMachine() : HasMany {
        return $this->hasMany(GashaMachines::class, 'bay');
    }
}
