<?php

namespace App\Exports;

use App\Models\Capsule\CapsuleMergeLine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class CapsuleMergeExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    protected $filter_column;
    
    public function __construct($fields){
        $this->filter_column  = $fields;
    }

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

        $rows = CapsuleMergeLine::leftJoin('capsule_merges as header', 'header.id', 'capsule_merge_lines.capsule_merges_id')
            ->select(
                'header.reference_number',
                'locations.location_name',
                'items.digits_code',
                'items.digits_code2',
                'items.item_description',
                'from_machine.serial_number as serial1',
                'to_machine.serial_number as serial2',
                'capsule_merge_lines.qty',
                'capsule_merge_lines.created_at as created_date',
                'user.name'
            )
            ->leftJoin('items', 'items.digits_code', 'capsule_merge_lines.item_code')
            ->leftJoin('cms_users as user', 'user.id', 'header.created_by')
            ->leftJoin('gasha_machines as from_machine', 'from_machine.id', 'header.from_machines_id')
            ->leftJoin('gasha_machines as to_machine', 'to_machine.id', 'header.to_machines_id')
            ->leftJoin('locations', 'locations.id', 'header.locations_id')
            ->orderBy('header.id', 'desc');

        if ($my_locations_id) {
            $rows = $rows->where('header.locations_id', $my_locations_id);
        }

        if ($this->filter_column) {
            $filter_column = $this->filter_column;

            $rows->where(function($w) use ($filter_column) {
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
                        $rows->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type=='between') {
                    // if($key && $value) $rows->whereBetween($key,$value);
                    if($key && $value && is_array($value) && count($value) == 2) {
                        // Assuming $value is an array with start and end date
                        $start_date = date('Y-m-d', strtotime($value[0]));
                        $end_date = date('Y-m-d', strtotime($value[1]));
                        $rows->whereBetween('capsule_merge_lines.created_at', [$start_date, $end_date]);
                    }
                }

                else {
                    continue;
                }
            }
        }

        return $rows;
    }
}
