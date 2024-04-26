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

    public function headings(): array {
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

    public function map($history_capsules): array {
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

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $history_capsules = HistoryCapsule::leftJoin('items', 'items.digits_code2', 'history_capsules.item_code')
            ->where('history_capsules.status', 'ACTIVE')
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

        return $history_capsules;
    }
}
