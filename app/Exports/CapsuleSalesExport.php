<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Capsule\CapsuleSales;
use CRUDBooster;
use DB;

class CapsuleSalesExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($date_from = null, $date_to = null) {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
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

    public function map($capsule_sales): array {
        return [
            $capsule_sales->reference_number,
            $capsule_sales->digits_code,
            $capsule_sales->digits_code2,
            $capsule_sales->item_description,
            $capsule_sales->serial_number,
            $capsule_sales->location_name,
            $capsule_sales->qty,
            $capsule_sales->sales_type,
            $capsule_sales->name,
            $capsule_sales->created_at,

        ];
    }

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $capsule_sales = CapsuleSales::leftJoin('items', 'items.digits_code', 'capsule_sales.item_code')
            ->where('capsule_sales.status', 'ACTIVE')
            ->select(
                'capsule_sales.reference_number',
                'items.digits_code',
                'items.digits_code2',
                'items.item_description',
                'gasha_machines.serial_number',
                'locations.location_name',
                'capsule_sales.qty',
                'sales_types.description as sales_type',
                'cms_users.name',
                'capsule_sales.created_at',
            )
            ->leftJoin('gasha_machines', 'gasha_machines.id', 'capsule_sales.gasha_machines_id')
            ->leftJoin('sales_types', 'sales_types.id', 'capsule_sales.sales_type_id')
            ->leftJoin('locations', 'locations.id', 'capsule_sales.locations_id')
            ->leftJoin('cms_users', 'cms_users.id', 'capsule_sales.created_by');

        if ($this->date_from && $this->date_to) {
            $capsule_sales = $capsule_sales->whereBetween('capsule_sales.created_at', [$this->date_from, $this->date_to]);
        }

        if ($my_locations_id) {
            $capsule_sales = $capsule_sales->where('capsule_sales.locations_id', $my_locations_id);
        }

        return $capsule_sales;
    }
}
