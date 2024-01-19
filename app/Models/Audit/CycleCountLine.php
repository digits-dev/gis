<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class CycleCountLine extends Model
{
    use HasFactory;
    protected $table = 'cycle_count_lines';
    protected $fillable = [
        "status",
        "cycle_counts_id",
        "gasha_machines_id",
        "digits_code",
        "qty",
        "variance",
        "created_at",
        "updated_at",
        "cycle_count_type"
    ];

    public function scopeDetailBody($query, $id){
        return $query->leftjoin('gasha_machines', 'cycle_count_lines.gasha_machines_id', '=', 'gasha_machines.id')
            ->select('cycle_count_lines.id as id',
                    'cycle_count_lines.*',
                    'gasha_machines.*')
            ->where('cycle_count_lines.cycle_counts_id',$id)
            ->get();
    }

    public function scopeDetailApprovalBody($query, $id){
        return $query->leftjoin('gasha_machines', 'cycle_count_lines.gasha_machines_id', '=', 'gasha_machines.id')
            ->leftjoin('cycle_counts', 'cycle_count_lines.cycle_counts_id', '=', 'cycle_counts.id')
            ->leftjoin('items', 'cycle_count_lines.digits_code', '=', 'items.digits_code')
            ->leftjoin('inventory_capsules', 'items.digits_code2', '=', 'inventory_capsules.item_code')
            ->leftjoin('inventory_capsule_lines', 'inventory_capsules.id', '=', 'inventory_capsule_lines.inventory_capsules_id')
            //->leftjoin('cycle_count_floor_view','cycle_count_lines.digits_code','=','cycle_count_floor_view.digits_code')
            ->leftJoin('cycle_count_floor_view', function($join) use($id)
			 {
				 $join->on('cycle_count_lines.digits_code', '=', 'cycle_count_floor_view.digits_code')
				 ->where('cycle_count_floor_view.locations_id',$id);
			 })
            ->select('cycle_count_lines.digits_code AS st_digits_code',
                    'cycle_counts.reference_number AS st_ref_number',
                    DB::raw('MAX(inventory_capsule_lines.qty) AS st_system_qty'),
                    'cycle_count_lines.qty AS st_actual_qty',
                    DB::raw('MAX(cycle_count_floor_view.reference_number) AS floor_ref'),
                    DB::raw('MAX(cycle_count_floor_view.serial_number) AS floor_machine'),
                    DB::raw('MAX(cycle_count_floor_view.system_qty) AS floor_system_qty'),
                    DB::raw('MAX(cycle_count_floor_view.qty) AS floor_actual_qty'),
                    )
            ->where('cycle_count_lines.status',9)
            ->where('cycle_count_lines.cycle_count_type','STOCK ROOM')
            ->groupBy('cycle_count_lines.id','cycle_count_floor_view.serial_number')
            ->get();
    }
}
