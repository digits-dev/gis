<?php

namespace App\Exports;

use App\Models\Capsule\HistoryCapsule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use CRUDBooster;
use DB;

class HistoryCapsuleExport implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison
{
    use Exportable;
    protected $filter_column;

    public function __construct($fields)
    {
        $this->filter_column  = $fields;
    }

    public function headings(): array
    {
        return [
            'REFERENCE #',
            'JAN #',
            'DIGITS CODE',
            'ITEM DESCRIPTION',
            'CAPSULE ACTION TYPE',
            'LOCATION',
            'FROM',
            'TO',
            'QTY',
            'CREATED BY',
            'CREATED DATE',
        ];
    }

    public function map($history_capsules): array
    {
        return [
            $history_capsules->reference_number,
            $history_capsules->digits_code,
            $history_capsules->digits_code2,
            $history_capsules->item_description,
            $history_capsules->action_type,
            $history_capsules->location_name,
            $history_capsules->from_description,
            $history_capsules->to_description,
            $history_capsules->qty,
            $history_capsules->name,
            $history_capsules->created_at,

        ];
    }

    public function query()
    {

        $my_locations_id = CRUDBooster::myLocationId();

        $history_capsules = HistoryCapsule::leftJoin('items', 'items.digits_code2', 'history_capsules.item_code')
            ->where('history_capsules.status', 'ACTIVE')
            ->whereNull('history_capsules.deleted_at')
            ->select(
                'history_capsules.reference_number',
                'items.digits_code',
                'items.digits_code2',
                'items.item_description',
                'cat.description as action_type',
                'locations.location_name',
                'hcv.from_description',
                'hcv.to_description',
                'history_capsules.qty',
                'cms_users.name',
                'history_capsules.created_at',
            )
            ->leftJoin('capsule_action_types as cat', 'cat.id', 'history_capsules.capsule_action_types_id')
            ->leftJoin('locations', 'locations.id', 'history_capsules.locations_id')
            ->leftJoin('history_capsule_view as hcv', 'hcv.history_capsules_id', 'history_capsules.id')
            ->leftJoin('cms_users', 'cms_users.id', 'history_capsules.created_by');

        if ($my_locations_id) {
            $history_capsules = $history_capsules->where('history_capsules.locations_id', $my_locations_id);
        }

        if ($this->filter_column) {
            $filter_column = $this->filter_column;

            $history_capsules->where(function ($w) use ($filter_column) {
                foreach ($filter_column as $key => $fc) {

                    $value = @$fc['value'];
                    $type  = @$fc['type'];

                    if ($type == 'empty') {
                        $w->whereNull($key)->orWhere($key, '');
                        continue;
                    }

                    if ($value == '' || $type == '') continue;

                    if ($type == 'between') continue;

                    switch ($type) {
                        default:
                            if ($key && $type && $value) $w->where($key, $type, $value);
                            break;
                        case 'like':
                        case 'not like':
                            $value = '%' . $value . '%';
                            if ($key && $type && $value) $w->where($key, $type, $value);
                            break;
                        case 'in':
                        case 'not in':
                            if ($value) {
                                if ($key && $value) $w->whereIn($key, $value);
                            }
                            break;
                    }
                }
            });

            foreach ($filter_column as $key => $fc) {
                $value = @$fc['value'];
                $type  = @$fc['type'];
                $sorting = @$fc['sorting'];

                if ($sorting != '') {
                    if ($key) {
                        $history_capsules->orderby($key, $sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    // if($key && $value) $history_capsules->whereBetween($key,$value);
                    if ($key && $value && is_array($value) && count($value) == 2) {
                        // Assuming $value is an array with start and end date
                        $start_date = date('Y-m-d', strtotime($value[0]));
                        $end_date = date('Y-m-d', strtotime($value[1]));
                        $history_capsules->whereBetween($key, [$start_date, $end_date]);
                    }
                } else {
                    continue;
                }
            }
        }

        return $history_capsules;
    }
}
