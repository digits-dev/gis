<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Capsule\InventoryCapsuleLine;
use CRUDBooster;
use DB;

class CapsuleInventoryExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    protected $filter_column;
    
    public function __construct($fields){
        $this->filter_column  = $fields;
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

    public function map($inventory_lines): array {
        return [
            $inventory_lines->digits_code2,
            $inventory_lines->digits_code,
            $inventory_lines->item_description,
            $inventory_lines->location_name,
            $inventory_lines->from_description,
            $inventory_lines->qty,

        ];
    }

    public function query() {
        $inventory_lines = InventoryCapsuleLine::leftJoin('inventory_capsules', 'inventory_capsules.id', 'inventory_capsule_lines.inventory_capsules_id')
            ->leftJoin('inventory_capsule_view', 'inventory_capsule_view.inventory_capsules_id', 'inventory_capsules.id')
            ->leftJoin('items', 'items.digits_code2', 'inventory_capsules.item_code')
            ->leftJoin('locations', 'locations.id', 'inventory_capsules.locations_id')
            ->leftJoin('sub_locations', 'sub_locations.id', 'inventory_capsule_lines.sub_locations_id')
            ->leftJoin('gasha_machines', 'gasha_machines.id', 'inventory_capsule_lines.gasha_machines_id')
            ->select(
                'items.digits_code2',
                'items.digits_code',
                'items.item_description',
                'locations.location_name',
                DB::raw('COALESCE(sub_locations.description, gasha_machines.serial_number) AS from_description'),
                'inventory_capsule_lines.qty'
            );

        if ($this->filter_column) {
            $filter_column = $this->filter_column;

            $inventory_lines->where(function($w) use ($filter_column) {
                foreach($filter_column as $key=>$fc) {

                    $value = @$fc['value'];
                    $type  = @$fc['type'];

                    if($type == 'empty') {
                        $w->whereNull($key)->orWhere($key,'');
                        continue;
                    }

                    if($value=='' || $type=='') continue;

                    if($type == 'between') continue;

                    switch($type) {
                        default:
                            if($key && $type && $value) $w->where($key,$type,$value);
                        break;
                        case 'like':
                        case 'not like':
                            $value = '%'.$value.'%';
                            if($key && $type && $value) $w->where($key,$type,$value);
                        break;
                        case 'in':
                        case 'not in':
                            if($value) {
                                if($key && $value) $w->whereIn($key,$value);
                            }
                        break;
                    }
                }
            });

            foreach($filter_column as $key=>$fc) {
                $value = @$fc['value'];
                $type  = @$fc['type'];
                $sorting = @$fc['sorting'];

                if($sorting!='') {
                    if($key) {
                        $inventory_lines->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type=='between') {
                    // if($key && $value) $inventory_lines->whereBetween($key,$value);
                    if($key && $value && is_array($value) && count($value) == 2) {
                        // Assuming $value is an array with start and end date
                        $start_date = date('Y-m-d', strtotime($value[0]));
                        $end_date = date('Y-m-d', strtotime($value[1]));
                        $inventory_lines->whereBetween($key, [$start_date, $end_date]);
                    }
                }

                else {
                    continue;
                }
            }
        }

        return $inventory_lines;
    }
}
