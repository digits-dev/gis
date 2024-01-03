<?php

namespace App\Exports;

use App\Models\Audit\CycleCountLine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class CycleCountExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'REFERENCE NUMBER',
            'LOCATION',
            'JAN #',
            'DIGITS CODE',
            'ITEM DESCRIPTION',
            'SUB LOCATION',
            'MACHINE',
            'QTY',
            'VARIANCE',
            'CREATED DATE',
            'CREATED BY',
        ];
    }

    public function map($row): array {
        return array_values($row->toArray());
    }

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $rows = CycleCountLine::leftJoin('cycle_counts as header', 'header.id', 'cycle_count_lines.cycle_counts_id')
            ->select(
                'header.reference_number',
                'locations.location_name',
                'items.digits_code',
                'items.digits_code2',
                'items.item_description',
                'sub_locations.description as sub_location',
                'machine.serial_number as serial_number',
                'cycle_count_lines.qty',
                'cycle_count_lines.variance',
                'cycle_count_lines.created_at as created_date',
                'user.name'
            )
            ->leftJoin('items', 'items.digits_code', 'cycle_count_lines.digits_code')
            ->leftJoin('cms_users as user', 'user.id', 'header.created_by')
            ->leftJoin('gasha_machines as machine', 'machine.id', 'cycle_count_lines.gasha_machines_id')
            ->leftJoin('locations', 'locations.id', 'header.locations_id')
            ->leftJoin('sub_locations', 'sub_locations.id', 'header.sub_locations_id')
            ->orderBy('header.id', 'desc');

        if ($my_locations_id) {
            $rows = $rows->where('header.locations_id', $my_locations_id);
        }

        return $rows;
    }
}

