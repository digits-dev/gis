<?php

namespace App\Imports;

use App\Models\Submaster\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class AdminItemsImport implements ToCollection, SkipsEmptyRows, WithHeadingRow
{
    /**
     * Handle the import collection.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row) {
            try {
                $this->validateRow($row);

                Item::updateOrInsert(
                    [
                        'digits_code' => trim($row['jan_no']),
                        'digits_code2' => trim($row['digits_code'])
                    ],
                    [
                        'digits_code' => trim($row['jan_no']),
                        'digits_code2' => trim($row['digits_code']),
                        'item_description' => $row['item_description'],
                        'no_of_tokens' => $row['no_of_tokens']
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Error importing row: ' . json_encode($row) . ' Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Validate the row data.
     *
     * @param array $row
     * @throws \Exception
     */
    private function validateRow($row)
    {
        if (!isset($row['jan_no']) || !isset($row['digits_code'])) {
            throw new \Exception('Missing required fields');
        }

        // Add more validations as needed
    }
}