<?php

namespace App\Exports;

use App\Models\Submaster\User;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class CollectTokenApprovalExport implements FromCollection, WithHeadings
{
    public function headings(): array {
        return [
            'REFERENCE NUMBER',
            'LOCATION',
            'GASHA MACHINE',
            'QTY',
            'CREATED BY',
            'CREATED DATE',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $query = DB::table('collect_rr_token_lines')->where('line_status', 9)
            ->leftJoin('collect_rr_tokens', 'collect_rr_token_lines.collected_token_id', 'collect_rr_tokens.id')
            ->leftJoin('gasha_machines', 'collect_rr_token_lines.gasha_machines_id', 'gasha_machines.id')
            ->leftJoin('locations', 'collect_rr_token_lines.location_id', 'locations.id')
            ->leftJoin('cms_users', 'collect_rr_tokens.created_by', 'cms_users.id')
            ->select('collect_rr_tokens.reference_number',
                'locations.location_name',
                'gasha_machines.serial_number',
                'collect_rr_token_lines.qty',
                'cms_users.name',
                'collect_rr_token_lines.created_at'
        );

        if (CRUDBooster::myLocationId()) {
            $query->where('collect_rr_token_lines.location_id', CRUDBooster::myLocationId());
        }

        return $query->get();
        
    }
}
