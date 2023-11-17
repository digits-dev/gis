<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleCount extends Model
{
    use HasFactory;
    protected $table = 'cycle_counts';
    protected $fillable = [
        "reference_number",
        "locations_id",
        "sub_locations_id",
        "total_qty",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
    ];

    public function scopeDetail($query, $id){
        return $query->leftjoin('locations', 'cycle_counts.locations_id', '=', 'locations.id')
            ->leftjoin('sub_locations', 'cycle_counts.sub_locations_id', '=', 'sub_locations.id')
            ->leftjoin('cms_users as requestor', 'cycle_counts.created_by', '=', 'requestor.id')
            ->select('cycle_counts.*',
                    'locations.location_name',
                    'sub_locations.description as sub_location_name',
                    'requestor.name as requestor_name')
            ->where('cycle_counts.id',$id)
            ->first();
    }
}
