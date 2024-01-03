<?php

namespace App\Exports;

use App\Models\Capsule\CapsuleSwapLines;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class CapsuleSwapExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'REFERENCE NUMBER',
            'LOCATION',
            'JAN #',
            'DIGITS CODE',
            'ITEM DESCRIPTION',
            'FROM MACHINE',
            'TO MACHINE',
            'QTY',
            'CREATED DATE',
            'CREATED BY',
        ];
    }

    public function map($row): array {
        return array_values($row->toArray());
    }

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $rows = CapsuleSwapLines::leftJoin('capsule_swap_headers as header', 'header.id', 'capsule_swap_lines.capsule_swap_id')
            ->select(
                'header.reference_number',
                'locations.location_name',
                'items.digits_code',
                'items.digits_code2',
                'items.item_description',
                'from_machine.serial_number as serial1',
                'to_machine.serial_number as serial2',
                'capsule_swap_lines.qty',
                'capsule_swap_lines.created_at as created_date',
                'user.name'
            )
            ->leftJoin('items', 'items.digits_code', 'capsule_swap_lines.jan_no')
            ->leftJoin('cms_users as user', 'user.id', 'header.created_by')
            ->leftJoin('gasha_machines as from_machine', 'from_machine.id', 'capsule_swap_lines.from_machine')
            ->leftJoin('gasha_machines as to_machine', 'to_machine.id', 'capsule_swap_lines.to_machine')
            ->leftJoin('locations', 'locations.id', 'capsule_swap_lines.location')
            ->orderBy('header.id', 'desc');

        if ($my_locations_id) {
            $rows = $rows->where('header.location', $my_locations_id);
        }

        return $rows;
    }
}
