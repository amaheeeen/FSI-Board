<?php

namespace App\Exports\Sheets;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinanceDetailSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return Payment::with(['transaction.package', 'transaction.agent', 'transaction.pilgrims'])
            ->latest('payment_date')
            ->get()
            ->map(function ($payment) {
                // Get first pilgrim name as representative or join names? 
                // Using first pilgrim name for simplicity as requested "Nama Jamaah"
                $pilgrimName = $payment->transaction->pilgrims->first()->full_name ?? '-';
                
                return [
                    $payment->payment_date,
                    $payment->transaction->transaction_code ?? '-',
                    $pilgrimName,
                    $payment->transaction->package->name ?? '-',
                    $payment->transaction->agent->name ?? 'Direct',
                    $payment->payment_method,
                    $payment->status,
                    $payment->amount,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No Invoice',
            'Nama Jamaah',
            'Paket',
            'Agent',
            'Metode Bayar',
            'Status',
            'Nominal (IDR)',
        ];
    }

    public function title(): string
    {
        return 'Data Detail';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
