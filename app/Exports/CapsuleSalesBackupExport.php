<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Controllers\AdminMenuItemsController;
use CRUDBooster;
use DB;

class CapsuleSalesBackupExport implements FromArray, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($date) {
        $this->date = $date;
    }

    public function headings(): array {
        return [
            'REFERENCE #',
            'JAN #',
            'DIGITS CODE',
            'ITEM DESCRIPTION',
            'GASHA MACHINE',
            'LOCATION',
            'QTY',
            'SALES TYPE',
            'CREATED BY',
            'CREATED DATE',
        ];
    }

    public function map($row): array {
        return [
            $row->reference_number,
            $row->jan_no,
            $row->digits_code,
            $row->item_description,
            $row->serial_number,
            $row->location_name,
            $row->qty,
            $row->sales_type,
            $row->name,
            $row->created_at,
        ];
    }

    public function array() : array {
        $item = DB::table('capsule_sales_backup')
            ->where('backup_date', $this->date)
            ->first();

        $rows = json_decode($item->sales_data);
        return $rows ?: [];
    }
}
