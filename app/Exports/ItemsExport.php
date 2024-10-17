<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Submaster\Item;
use CRUDBooster;
use DB;

class ItemsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {
        return [
            'JAN #',
            'DIGITS CODE',
            'ITEM DESCRIPTION',
            'NO OF TOKEN'
        ];

    }

    public function map($capsule_sales): array {
        return [
            $capsule_sales->jan_code,
            $capsule_sales->digits_code,
            $capsule_sales->item_description,
            $capsule_sales->no_of_tokens
        ];
    }

    public function query() {

        $items = Item::select(
                'digits_code as jan_code',
                'digits_code2 as digits_code',
                'item_description',
                'no_of_tokens',
                'created_at'
            );
        if ($this->filter_column) {
            $filter_column = $this->filter_column;

            $items->where(function($w) use ($filter_column) {
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
                        $items->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type=='between') {
                    // if($key && $value) $items->whereBetween($key,$value);
                    if($key && $value && is_array($value) && count($value) == 2) {
                        // Assuming $value is an array with start and end date
                        $start_date = date('Y-m-d', strtotime($value[0]));
                        $end_date = date('Y-m-d', strtotime($value[1]));
                        $items->whereBetween('created_at', [$start_date, $end_date]);
                    }
                }

                else {
                    continue;
                }
            }
        }

        return $items;
    }
}
