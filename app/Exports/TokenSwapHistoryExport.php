<?php

namespace App\Exports;

use App\Models\PosFrontend\SwapHistory;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TokenSwapHistoryExport implements FromQuery, WithHeadings
{
    public $date_from;
    public $date_to;

    public function __construct($date_from, $date_to) {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    public function headings(): array {
        return [
            'REFERENCE #',
            'TOKEN QTY',
            'TOKEN VALUE',
            'CHANGE',
            'MODE OF PAYMENT',
            'PAYMENT REFERENCE',
            'TYPE',
            'LOCATION',
            'CREATED BY',
            'CREATED DATE',
            'UPDATED BY',
            'UPDATED DATE',
            'STATUS',
        ];
    }

    public function query() {

        $my_locations_id = CRUDBooster::myLocationId();

        $query = SwapHistory::query()
            ->leftJoin('mode_of_payments', 'mode_of_payments.id', 'swap_histories.mode_of_payments_id')
            ->leftJoin('token_action_types', 'token_action_types.id', 'swap_histories.type_id')
            ->leftJoin('locations', 'locations.id', 'swap_histories.locations_id')
            ->leftJoin('cms_users as creator', 'creator.id', 'swap_histories.created_by')
            ->leftJoin('cms_users as updater', 'updater.id', 'swap_histories.updated_by')
            ->select(
                'swap_histories.reference_number',
                'swap_histories.token_value',
                'swap_histories.total_value',
                'swap_histories.change_value',
                'mode_of_payments.payment_description',
                'swap_histories.payment_reference',
                'token_action_types.description',
                'locations.location_name',
                'creator.name as creator_name',
                'swap_histories.created_at',
                'updater.name as updater_name',
                'swap_histories.updated_at',
                'swap_histories.status',
            );

        if ($this->date_from && $this->date_to) {
            $query = $query->whereBetween('swap_histories.created_at', [$this->date_from, $this->date_to]);
        }

        if ($my_locations_id) {
            $query = $query->where('swap_histories.locations_id', $my_locations_id);
        }

        return $query;
    }
}
