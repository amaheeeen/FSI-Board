<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\FinanceSummarySheet;
use App\Exports\Sheets\FinanceDetailSheet;

class FinanceMultiSheetExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new FinanceSummarySheet(),
            new FinanceDetailSheet(),
        ];
    }
}
