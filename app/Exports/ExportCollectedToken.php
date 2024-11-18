<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class ExportCollectedToken implements WithMultipleSheets
{
    protected $filterColumn;
    protected $filter;

    public function __construct($filterColumn = null)
    {
        $this->filterColumn = $filterColumn['filter_column'];
        $this->filter = $filterColumn['filters'];
    }

    public function sheets(): array
    {
        return [
            'Sheet1' => new CollectedTokenHeaderSheet1($this->filterColumn, $this->filter),
            'Sheet2' => new CollectedTokenLinesSheet2($this->filterColumn, $this->filter),
        ];
    }
}
