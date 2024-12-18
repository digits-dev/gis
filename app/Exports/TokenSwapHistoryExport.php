<?php

namespace App\Exports;

use App\Models\PosFrontend\SwapHistory;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TokenSwapHistoryExport implements FromQuery, WithHeadings, WithMapping
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
                'updater.name as updater_name', // Fix updater_name
                'swap_histories.updated_at',
                'swap_histories.status'
            );

        if ($this->date_from && $this->date_to) {
            $query = $query->whereBetween('swap_histories.created_at', [$this->date_from, $this->date_to]);
        }

        if ($my_locations_id) {
            $query = $query->where('swap_histories.locations_id', $my_locations_id);
        }

        return $query;
    }

    public function map($swapHistory): array
    {
        return [
            $swapHistory->reference_number,
            $swapHistory->token_value,
            $swapHistory->total_value,
            $swapHistory->change_value,
            $swapHistory->payment_description ?? '',
            $swapHistory->payment_reference ?? '',
            $swapHistory->description ?? '',
            $swapHistory->location_name ?? '',
            $swapHistory->creator_name ?? '',
            Carbon::parse($swapHistory->created_at)->format('Y-m-d H:i:s'),
            $swapHistory->updater_name ?? '',
            Carbon::parse($swapHistory->updated_at)->format('Y-m-d H:i:s'),
            $swapHistory->status ?? '',
        ];
    }
}
