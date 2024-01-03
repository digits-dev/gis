<?php

namespace App\Exports;

use App\Models\Capsule\CapsuleSplitLine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class CapsuleSplitExport implements FromQuery, WithHeadings, WithMapping
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
            'ACTUAL QTY',
            'REMAINING QTY',
            'TRANSFER QTY',
            'CREATED DATE',
            'CREATED BY',
        ];
    }

    public function map($row): array {
        return array_values($row->toArray());
    }

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $rows = CapsuleSplitLine::leftJoin('capsule_split as header', 'header.id', 'capsule_split_lines.capsule_split_id')
            ->select(
                'header.reference_number',
                'locations.location_name',
                'items.digits_code',
                'items.digits_code2',
                'items.item_description',
                'from_machine.serial_number as serial1',
                'to_machine.serial_number as serial2',
                'capsule_split_lines.actual_qty',
                'capsule_split_lines.remaining_qty',
                'capsule_split_lines.transfer_qty',
                'capsule_split_lines.created_at as created_date',
                'user.name'
            )
            ->leftJoin('items', 'items.digits_code', 'capsule_split_lines.item_code')
            ->leftJoin('cms_users as user', 'user.id', 'header.created_by')
            ->leftJoin('gasha_machines as from_machine', 'from_machine.id', 'header.from_machines_id')
            ->leftJoin('gasha_machines as to_machine', 'to_machine.id', 'header.to_machines_id')
            ->leftJoin('locations', 'locations.id', 'header.locations_id')
            ->orderBy('header.id', 'desc');

        if ($my_locations_id) {
            $rows = $rows->where('header.locations_id', $my_locations_id);
        }

        return $rows;
    }
}
