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
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
    ];

    public function scopeDetail($query, $id){
        return $query->leftjoin('locations', 'cycle_counts.locations_id', '=', 'locations.id')
                     ->leftjoin('sub_locations', 'cycle_counts.sub_locations_id', '=', 'sub_locations.id')
                     ->leftjoin('cms_users as requestor', 'cycle_counts.created_by', '=', 'requestor.id')
                     ->select('cycle_counts.id as ct_id',
                              'cycle_counts.*',
                              'locations.*',
                              'sub_locations.*',
                              'requestor.name as requestor_name',
                              'cycle_counts.created_at as cycle_counts_created')
                     ->where('cycle_counts.id',$id)
                     ->first();
    }
}
