<?php

namespace App\Exports\Sheets;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class FinanceSummarySheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return Payment::select(
            DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
            DB::raw("COUNT(id) as total_transactions"),
            DB::raw("SUM(amount) as total_income"),
            DB::raw("0 as total_expense") // Placeholder as we don't have expenses table yet
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Total Transaksi',
            'Total Pemasukan (IDR)',
            'Total Pengeluaran (IDR)',
        ];
    }

    public function title(): string
    {
        return 'Ringkasan Grafik';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
