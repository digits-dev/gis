<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleSwapHeader extends Model
{
    use HasFactory;
    protected $table = 'capsule_swap_headers';

    public function scopeDetail($query, $id){
        return $query->leftjoin('locations', 'capsule_swap_headers.location', '=', 'locations.id')
                     ->leftjoin('cms_users as requestor', 'capsule_swap_headers.created_by', '=', 'requestor.id')
                     ->select('capsule_swap_headers.*',
                              'locations.*',
                              'requestor.name as requestor_name',
                              'capsule_swap_headers.created_at as capsule_swap_headers_created')
                     ->where('capsule_swap_headers.id',$id)
                     ->first();
    }
}
