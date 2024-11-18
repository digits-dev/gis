<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection; // For creating collections

class CollectedTokenHeaderSheet1 implements FromCollection, WithHeadings
{
    protected $filterColumn;
    protected $filter;

    public function __construct($filterColumn = null, $filters = null)
    {
        $this->filterColumn = $filterColumn;
        $this->filter = $filters;
    }

    /**
     * Generate dummy data for the collection.
     */
    public function collection()
    {
        // Create dummy data for the headers
        return new Collection([
            [
                'reference_number' => 'RT12345',
                'status' => 'Approved',
                'location' => 'New York',
                'bay' => 'Bay 1',
                'collected_qty' => 150,
                'variance' => 5,
                'received_by' => 'John Doe',
                'received_date' => '2024-11-01',
                'created_by' => 'Admin',
                'created_date' => '2024-11-01',
            ],
            [
                'reference_number' => 'RT12346',
                'status' => 'Pending',
                'location' => 'Los Angeles',
                'bay' => 'Bay 2',
                'collected_qty' => 200,
                'variance' => 10,
                'received_by' => 'Jane Smith',
                'received_date' => '2024-11-02',
                'created_by' => 'Admin',
                'created_date' => '2024-11-02',
            ],
            [
                'reference_number' => 'RT12347',
                'status' => 'Rejected',
                'location' => 'Chicago',
                'bay' => 'Bay 3',
                'collected_qty' => 120,
                'variance' => 0,
                'received_by' => 'Mark Brown',
                'received_date' => '2024-11-03',
                'created_by' => 'Admin',
                'created_date' => '2024-11-03',
            ],
        ]);
    }

    /**
     * Define headings for Sheet1 (header data).
     */
    public function headings(): array
    {
        return [
            'Reference Number',
            'Status',
            'Location',
            'Bay',
            'Collected Qty',
            'Variance',
            'Recieved By',
            'Recieved Date',
            'Created By',
            'Created Date'
        ];
    }

    /**
     * Optional: Apply styles to the sheet (optional).
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style example: bold the headings
            1 => ['font' => ['bold' => true]]
        ];
    }
}
