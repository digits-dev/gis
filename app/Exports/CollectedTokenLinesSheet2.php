<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection; // For creating collections

class CollectedTokenLinesSheet2 implements FromCollection, WithHeadings
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
        // Create dummy data for the line items
        return new Collection([
            [
                'reference_number' => 'RT12345',
                'location' => 'New York',
                'bay' => 'Bay 1',
                'total_quantity' => 150,
                'machine_number' => 'M-001',
                'jan_number' => 'JAN123456',
                'no_of_tokens' => 300,
                'variance' => 5,
                'capsule_sales' => 500,
                'machine_inventory' => 1000,
                'variance_type' => 'Positive',
                'confirm_by' => 'John Doe',
                'confirm_date' => '2024-11-01',
                'approved_by' => 'Admin',
                'approved_date' => '2024-11-02',
                'received_by' => 'Mark Brown',
                'received_date' => '2024-11-03',
                'created_by' => 'Admin',
                'created_date' => '2024-11-01',
            ],
            [
                'reference_number' => 'RT12346',
                'location' => 'Los Angeles',
                'bay' => 'Bay 2',
                'total_quantity' => 200,
                'machine_number' => 'M-002',
                'jan_number' => 'JAN123457',
                'no_of_tokens' => 400,
                'variance' => 10,
                'capsule_sales' => 600,
                'machine_inventory' => 1200,
                'variance_type' => 'Negative',
                'confirm_by' => 'Jane Smith',
                'confirm_date' => '2024-11-02',
                'approved_by' => 'Admin',
                'approved_date' => '2024-11-03',
                'received_by' => 'James White',
                'received_date' => '2024-11-04',
                'created_by' => 'Admin',
                'created_date' => '2024-11-02',
            ],
            [
                'reference_number' => 'RT12347',
                'location' => 'Chicago',
                'bay' => 'Bay 3',
                'total_quantity' => 120,
                'machine_number' => 'M-003',
                'jan_number' => 'JAN123458',
                'no_of_tokens' => 240,
                'variance' => 0,
                'capsule_sales' => 400,
                'machine_inventory' => 800,
                'variance_type' => 'None',
                'confirm_by' => 'Mark Brown',
                'confirm_date' => '2024-11-03',
                'approved_by' => 'Admin',
                'approved_date' => '2024-11-04',
                'received_by' => 'Nancy Green',
                'received_date' => '2024-11-05',
                'created_by' => 'Admin',
                'created_date' => '2024-11-03',
            ],
        ]);
    }

    /**
     * Define headings for Sheet2 (line item data).
     */
    public function headings(): array
    {
        return [
            'Reference Number',
            'Location',
            'Bay',
            'Total Quantity',
            'Machine #',
            'JAN #',
            'No of Token',
            'Variance',
            'Capsule Sales',
            'Machine Inventory',
            'Variance Type',
            'Confirm By',
            'Confirm Date',
            'Approved By',
            'Approved Date',
            'Recieved By',
            'Recieved Date',
            'Created By',
            'Created Date'
        ];
    }
}
