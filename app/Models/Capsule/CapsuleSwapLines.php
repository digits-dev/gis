<?php

namespace App\Models\Capsule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapsuleSwapLines extends Model
{
    use HasFactory;

    protected $table = 'capsule_swap_lines';
    public function scopeDetailBody($query, $id){
        return $query->leftjoin('gasha_machines as from_machine', 'capsule_swap_lines.from_machine', '=', 'from_machine.id')
                     ->leftjoin('gasha_machines as to_machine', 'capsule_swap_lines.to_machine', '=', 'to_machine.id')
                     ->leftjoin('items', 'capsule_swap_lines.jan_no','=','items.digits_code')
                     ->select('capsule_swap_lines.*',
                              'from_machine.serial_number as from_machine_serial',
                              'to_machine.serial_number as to_machine_serial',
                              'items.*'
                              )
                     ->where('capsule_swap_lines.capsule_swap_id',$id)
                     ->get();
    }
}
