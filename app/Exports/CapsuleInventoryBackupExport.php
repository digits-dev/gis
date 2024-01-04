<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Controllers\AdminMenuItemsController;
use CRUDBooster;
use DB;

class CapsuleInventoryBackupExport implements FromArray, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($date) {
        $this->date = $date;
    }

    public function headings(): array {
        return [
            'DIGITS CODE',
            'JAN #',
            'ITEM DESCRIPTION',
            'LOCATION',
            'FROM',
            'QTY',
        ];
    }

    public function map($row): array {
        return [
            $row->digits_code,
            $row->jan_no,
            $row->item_description,
            $row->location_name,
            $row->from_description,
            $row->qty,
        ];
    }

    public function array() : array {
        $item = DB::table('capsule_inventory_backup')
            ->where('backup_date', $this->date)
            ->first();

        $rows = json_decode($item->inventory_data);
        return $rows ?: [];
    }


}
