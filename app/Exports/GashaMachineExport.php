<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Submaster\GashaMachines;
use CRUDBooster;
use DB;

class GashaMachineExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'SERIAL NUMBER',
            'LOCATION',
            'NO OF TOKEN',
            'BAY',
            'LAYER',
            'COLUMN',
            'MACHINE STATUS',
            'STATUS',
            'CREATED BY',
            'CREATED DATE',
            'UPDATED BY',
            'UPDATED DATE',
        ];

    }

    public function map($row): array {
        return [
            $row->serial_number,
            $row->location_name,
            $row->no_of_token,
            $row->bay,
            $row->layer,
            $row->column,
            $row->status_description,
            $row->status,
            $row->creator,
            $row->created_at,
            $row->updator,
            $row->updated_at,

        ];
    }

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $rows = GashaMachines::select(
                'gasha_machines.serial_number',
                'locations.location_name',
                'gasha_machines.no_of_token',
                'gasha_machines.bay',
                'gasha_machines.layer',
                'gasha_machines.column',
                'statuses.status_description',
                'gasha_machines.status',
                'created_by.name as creator',
                'gasha_machines.created_at',
                'updated_by.name as updator',
                'gasha_machines.updated_at',
            )
            ->leftJoin('locations', 'locations.id', 'gasha_machines.location_id')
            ->leftJoin('statuses', 'statuses.id', 'gasha_machines.machine_statuses_id')
            ->leftJoin('cms_users as created_by', 'created_by.id', 'gasha_machines.created_by')
            ->leftJoin('cms_users as updated_by', 'updated_by.id', 'gasha_machines.updated_by');

        if ($my_locations_id) {
            $rows = $rows->where('gasha_machines.location_id', $my_locations_id);
        }

        return $rows;
    }
}
