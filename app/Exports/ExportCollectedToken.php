<?php

namespace App\Exports;

use App\Models\Audit\CollectRrTokens;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportCollectedToken implements FromCollection, WithHeadings, WithStyles
{
    protected $filterColumn;

    public function __construct($filter_column)
    {
        $this->filterColumn = $filter_column;
    }

    public function headings(): array
    {
        return [
            'Reference Number',
            'Status',
            'Location',
            'Bay',
            'Collected Qty',
            'Received Qty',
            'Has Variance',
            'Machine #',
            'JAN #',
            'No of Token',
            'Qty',
            'Variance',
            'Variance Type',
            'Projected Capsule Sales',
            'Actual Capsule Sales',
            'Current Capsule Inventory',
            'Actual Capsule Inventory',
            'Confirmed By',
            'Confirmed Date',
            'Approved By',
            'Approved Date',
            'Received By',
            'Received Date',
            'Created By',
            'Created Date',
        ];
    }

    public function collection()
    {
        $query = CollectRrTokens::with([
            'lines.inventory_capsule_lines.getInventoryCapsule.item',
            'getLocation',
            'getBay',
        ]);

        if ($this->filterColumn) {
            foreach ((array) $this->filterColumn as $key => $fc) {
                $value = $fc['value'] ?? null;
                $type = $fc['type'] ?? null;

                if (!empty($value) && !empty($type)) {
                    switch ($type) {
                        case 'empty':
                            $query->whereNull($key)->orWhere($key, '');
                            break;
                        case 'like':
                        case 'not like':
                            $query->where($key, $type, '%' . $value . '%');
                            break;
                        case 'in':
                        case 'not in':
                            $values = explode(',', $value);
                            $type === 'in' ? $query->whereIn($key, $values) : $query->whereNotIn($key, $values);
                            break;
                        case 'between':
                            $range = $value;
   
                            if (count($range) == 2) {
                                $startDate = date('Y-m-d', strtotime($range[0]));
                                $endDate = date('Y-m-d', strtotime($range[1]));
                                
                                $query->whereBetween('collect_rr_tokens.created_at', [$startDate, $endDate]);
                            }
                            break;
                        default:
                            $query->where($key, $type, $value);
                            break;
                    }
                }
            }
        }

        $collectedToken = $query->get();

        $collection = collect();

        foreach ($collectedToken as $perCollectedToken) {
            $lines = $perCollectedToken->lines;
            $maxLines = $lines->count();

            for ($i = 0; $i < $maxLines; $i++) {
                $collection->push([
                    'Reference Number' => $perCollectedToken->reference_number,
                    'Status' => $perCollectedToken->statuses_id,
                    'Location' => $perCollectedToken->getLocation->location_name,
                    'Bay' => $perCollectedToken->getBay->name,
                    'Collected Qty' => $perCollectedToken->collected_qty,
                    'Received Qty' => $perCollectedToken->received_qty,
                    'Has Variance' => $perCollectedToken->variance,
                    'Machine #' => $lines[$i]->machineSerial->serial_number ?? 'N/A',
                    'JAN #' => $lines[$i]->inventory_capsule_lines->map(function ($capsuleLine) {
                        return optional(optional($capsuleLine->getInventoryCapsule)->item)->digits_code;
                    })->join(', '),
                    'No of Token' => $lines[$i]->no_of_token,
                    'Qty' => $lines[$i]->qty,
                    'Variance' => $lines[$i]->variance,
                    'Variance Type' => $lines[$i]->variance_type,
                    'Projected Capsule Sales' => $lines[$i]->projected_capsule_sales,
                    'Actual Capsule Sales' => $lines[$i]->actual_capsule_sales,
                    'Current Capsule Inventory' => $lines[$i]->current_capsule_inventory,
                    'Actual Capsule Inventory' => $lines[$i]->actual_capsule_inventory,
                    'Confirmed By' => $perCollectedToken->confirmed_by,
                    'Confirmed Date' => $perCollectedToken->confirmed_at,
                    'Approved By' => $perCollectedToken->approved_by,
                    'Approved Date' => $perCollectedToken->approved_at,
                    'Received By' => $perCollectedToken->received_by,
                    'Received Date' => $perCollectedToken->received_at,
                    'Created By' => $perCollectedToken->created_by,
                    'Created Date' => $perCollectedToken->created_at,
                ]);
            }
        }

        return $collection;
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles for header row
        $sheet->getStyle('A1:Y1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFDD00',
                ],
            ],
        ]);

        $rowStart = 2;
        $highestRow = $sheet->getHighestRow();

        $currentReferenceNumber = null;
        $transactionStartRow = null;

        // Loop through each row to find the start and end of each transaction
        for ($row = $rowStart; $row <= $highestRow; $row++) {
            $referenceNumber = $sheet->getCell('A' . $row)->getValue();

            // When we encounter a new transaction, apply border for the previous one
            if ($referenceNumber !== $currentReferenceNumber && $transactionStartRow !== null) {
                $sheet->getStyle('A' . ($transactionStartRow) . ':Y' . ($row - 1))
                    ->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['rgb' => '008000'], 
                            ],
                        ],
                    ]);
            }

            $currentReferenceNumber = $referenceNumber;
            $transactionStartRow = $row;
        }

        // Apply green thick border to the last transaction's rows
        if ($transactionStartRow !== null) {
            $sheet->getStyle('A' . $transactionStartRow . ':Y' . $highestRow)
                ->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['rgb' => '008000'],
                        ],
                    ],
                ]);
        }

        // Auto-adjust column width
        foreach (range('A', 'Y') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}