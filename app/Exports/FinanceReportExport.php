<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Facades\Response;

class FinanceReportExport
{
    public function download()
    {
        $fileName = 'Finance-Report-' . date('Y-m-d') . '.csv';
        
        // Query Data: Payments join Transactions join Packages
        // We can use Eloquent relationships for cleaner code, or Join for performance if needed.
        // Given the requirement "Amount In (Debit)", "Status", etc.
        $payments = Payment::with(['transaction.user', 'transaction.agent', 'transaction.package'])
            ->latest()
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Date', 'Invoice Number', 'Payer Name', 'Package Name', 'Amount In (Debit)', 'Status');

        $callback = function() use($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                $payerName = $payment->transaction->agent->name ?? ($payment->transaction->user->name ?? 'N/A');
                
                $row['Date']  = $payment->payment_date->format('Y-m-d');
                $row['Invoice Number'] = $payment->transaction->transaction_code;
                $row['Payer Name'] = $payerName;
                $row['Package Name'] = $payment->transaction->package->name;
                $row['Amount In (Debit)'] = $payment->amount_paid;
                $row['Status'] = $payment->status;

                fputcsv($file, array(
                    $row['Date'], 
                    $row['Invoice Number'], 
                    $row['Payer Name'], 
                    $row['Package Name'], 
                    $row['Amount In (Debit)'], 
                    $row['Status']
                ));
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
