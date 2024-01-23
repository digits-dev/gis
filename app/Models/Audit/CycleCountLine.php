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
            ->leftjoin('statuses', 'cycle_count_lines.status', '=', 'statuses.id')
            ->select('cycle_count_lines.id as id',
                    'statuses.status_description AS ccl_status',
                    'cycle_count_lines.*',
                    'gasha_machines.*')
            ->where('cycle_count_lines.cycle_counts_id',$id)
            ->get();
    }

    public function scopeDetailApprovalBody($query, $id){
        return $query->leftjoin('gasha_machines', 'cycle_count_lines.gasha_machines_id', '=', 'gasha_machines.id')
            ->leftjoin('cycle_counts', 'cycle_count_lines.cycle_counts_id', '=', 'cycle_counts.id')
            ->leftJoin('cycle_count_stockroom_view', function($join) use($id)
            {
                $join->on('cycle_count_lines.digits_code', '=', 'cycle_count_stockroom_view.digits_code')
                ->where('cycle_count_stockroom_view.locations_id',$id)
                ->orderBy(DB::raw('ISNULL(cycle_count_stockroom_view.digits_code), cycle_count_stockroom_view.digits_code'), 'ASC');
            })
            ->leftJoin('cycle_count_floor_view', function($join) use($id)
			 {
				 $join->on('cycle_count_lines.digits_code', '=', 'cycle_count_floor_view.digits_code')
				 ->where('cycle_count_floor_view.locations_id',$id)
                 ->orderBy(DB::raw('ISNULL(cycle_count_floor_view.digits_code), cycle_count_floor_view.digits_code'), 'ASC');
			})
            ->select(
                    DB::raw('MAX(cycle_counts.locations_id) AS st_location_id'),
                    'cycle_count_lines.digits_code AS st_digits_code',
                    DB::raw('MAX(cycle_counts.reference_number) AS st_ref_number'),
                    DB::raw('MAX(cycle_count_stockroom_view.qty) AS st_actual_qty'),
                    DB::raw('MAX(cycle_count_floor_view.locations_id) AS floor_location_id'),
                    DB::raw('MAX(cycle_count_floor_view.reference_number) AS floor_ref'),
                    DB::raw('MAX(cycle_count_floor_view.serial_number) AS floor_machine'),
                    DB::raw('MAX(cycle_count_floor_view.qty) AS floor_actual_qty'),
                    DB::raw('MAX(cycle_count_floor_view.gasha_machines_id) AS gasha_machines_id'),
                    )
            ->where('cycle_count_lines.status',9)
            ->groupBy('cycle_count_lines.digits_code','cycle_count_floor_view.serial_number')
            ->orderBy(DB::raw('ISNULL(cycle_count_floor_view.digits_code), cycle_count_floor_view.digits_code'), 'ASC')
            ->get();
    }

    public function scopeGetApprovalLinesForProcess($query,$id,$type){
        return $query->leftjoin('cycle_counts','cycle_count_lines.cycle_counts_id','=','cycle_counts.id')
        ->where('locations_id',$id)
        ->where('cycle_count_lines.cycle_count_type',$type)
        ->where('cycle_count_lines.status',9)
        ->select('cycle_counts.*','cycle_count_lines.*','cycle_count_lines.id AS ccl_id')
        ->get();
    }
}
