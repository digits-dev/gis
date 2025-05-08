<?php

namespace App\Exports;

use App\Models\ItemPosLines;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;
use Carbon\Carbon;

class ItemPosLinesExport implements FromQuery, WithHeadings, WithMapping
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
            'QTY',
            'CURRENT SRP',
            'TOTAL PRICE',
            'STATUS',
            'CREATED BY',
            'CREATED DATE',
            'UPDATED BY',
            'UPDATED AT'
        ];
    }

    public function map($row): array {
        return [
            $row->reference_number,
            $row->location_name ?? '',
            $row->jan_number ?? '',
            $row->digits_code ?? '',
            $row->item_description ?? '',
            $row->qty ?? '',
            $row->current_srp ?? '',
            $row->total_price ?? '',
            $row->status ?? '',
            $row->creator_name ?? '',
            Carbon::parse($row->created_at)->format('Y-m-d H:i A'),
            $row->updater_name ?? '',
            Carbon::parse($row->updated_at)->format('Y-m-d H:i A'),
            $row->status ?? '',
        ];
    }

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $rows = ItemPosLines::leftJoin('item_pos as header', 'header.id', 'item_pos_lines.item_pos_id')
            ->select(
             'header.reference_number',
             'locations.location_name',
             'item_pos_lines.jan_number',
             'item_pos_lines.digits_code',
             'item_pos_lines.item_description',
             'item_pos_lines.qty',
             'item_pos_lines.current_srp',
             'item_pos_lines.total_price',
             'item_pos_lines.status',
             'creator.name as creator_name',
             'header.created_at',
             'updator.name as updater_name',
             'header.updated_at'
            )
            ->leftJoin('cms_users as creator', 'creator.id', 'header.created_by')
            ->leftJoin('cms_users as updator', 'updator.id', 'header.updated_by')
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
                        $rows->whereBetween('item_pos.created_at', [$start_date, $end_date]);
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
