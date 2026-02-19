<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->transaction_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4; margin: 0; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            /* Force A4 container size */
            .a4-container {
                width: 210mm;
                height: 296mm; /* 1mm less to prevent blank page */
                margin: 0;
                padding: 20mm;
                box-sizing: border-box;
                page-break-after: always;
            }
        }
        /* Screen display */
        @media screen {
            .a4-container {
                width: 210mm;
                min-height: 297mm;
                margin: 20px auto;
                background: white;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                padding: 20mm;
            }
            body { background: #f3f4f6; }
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="a4-container flex flex-col justify-between relative">
        
        <div>
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-gray-100 pb-8 mb-8">
                <div class="flex items-center">
                    <!-- Logo Placeholder -->
                   <div class="bg-emerald-600 text-white font-bold text-xl p-3 rounded-lg mr-4">
                       FSI
                   </div>
                   <div>
                       <h1 class="text-xl font-bold text-emerald-900">Farhan Surya Indah Tour & Travel</h1>
                       <p class="text-sm text-gray-500">Official Umrah & Hajj Provider</p>
                       <p class="text-sm text-gray-500">Jakarta, Indonesia</p>
                   </div>
                </div>
                <div class="text-right">
                    <h2 class="text-4xl font-bold text-gray-200 uppercase tracking-widest mb-2">Invoice</h2>
                    <p class="text-emerald-600 font-mono font-medium text-lg">#{{ $transaction->transaction_code }}</p>
                    <p class="text-sm text-gray-500">Date: {{ $transaction->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Bill To -->
            <div class="mb-10">
                <h3 class="text-gray-500 uppercase text-xs font-bold tracking-wider mb-2">Bill To:</h3>
                @if($transaction->agent)
                    <h4 class="text-xl font-bold text-gray-800">{{ $transaction->agent->name }}</h4>
                    <p class="text-gray-600">{{ $transaction->agent->phone ?? '-' }}</p>
                    <p class="text-gray-600">{{ $transaction->agent->location ?? '-' }}</p>
                @else
                    <h4 class="text-xl font-bold text-gray-800">{{ $transaction->user->name ?? 'Guest' }}</h4>
                    <p class="text-gray-600">Direct Pilgrim / User</p>
                @endif
            </div>

            <!-- Transaction Details -->
            <div class="mb-10">
                <h3 class="text-gray-500 uppercase text-xs font-bold tracking-wider mb-4">Transaction Details</h3>
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-sm">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">Description</th>
                            <th class="px-4 py-3 text-center">Departure</th>
                            <th class="px-4 py-3 text-center">Pax</th>
                            <th class="px-4 py-3 text-right">Price/Pax</th>
                            <th class="px-4 py-3 text-right rounded-r-lg">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <tr>
                            <td class="px-4 py-4 font-medium">
                                {{ $transaction->package->name }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                {{ $transaction->package->departure_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                {{ $transaction->total_pax }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                IDR {{ number_format($transaction->total_amount / $transaction->total_pax, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right font-bold text-gray-900">
                                IDR {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payment History Summary -->
            <div class="mb-12">
                <h3 class="text-gray-500 uppercase text-xs font-bold tracking-wider mb-4">Payment History</h3>
                @if($transaction->payments->count() > 0)
                    <table class="w-full text-sm text-left border-collapse">
                        <tr class="border-b border-gray-100 text-gray-500">
                            <th class="py-2">Date</th>
                            <th class="py-2">Method</th>
                            <th class="py-2 text-right">Amount</th>
                        </tr>
                        @foreach($transaction->payments as $payment)
                        <tr class="border-b border-gray-50">
                            <td class="py-2">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="py-2">{{ $payment->payment_method }}</td>
                            <td class="py-2 text-right">IDR {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="font-bold text-emerald-800 bg-emerald-50">
                            <td class="py-3 px-2" colspan="2">Total Paid</td>
                            <td class="py-3 px-2 text-right">IDR {{ number_format($transaction->payments->sum('amount_paid'), 0, ',', '.') }}</td>
                        </tr>
                         <tr class="font-bold text-red-600">
                            <td class="py-3 px-2" colspan="2">Balance Due</td>
                            <td class="py-3 px-2 text-right">IDR {{ number_format($transaction->total_amount - $transaction->payments->sum('amount_paid'), 0, ',', '.') }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-gray-500 text-sm italic">No payments recorded yet.</p>
                @endif
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="flex justify-between pt-10 px-10">
            <div class="text-center">
                <p class="font-bold text-gray-800 mb-16">Authorized Signature</p>
                <div class="border-t border-gray-300 w-48 mx-auto"></div>
                <p class="text-xs text-gray-400 mt-2">Al-Maheen Travel Management</p>
            </div>
            <div class="text-center">
                <p class="font-bold text-gray-800 mb-16">Receiver / Payer</p>
                <div class="border-t border-gray-300 w-48 mx-auto"></div>
                <p class="text-xs text-gray-400 mt-2">{{ $transaction->agent->name ?? ($transaction->user->name ?? 'Jamaah') }}</p>
            </div>
        </div>
    </div>

        <!-- Print Button (Hidden in Print) -->
        <div class="mt-10 text-center no-print">
            <button onclick="window.print()" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-emerald-700 transition">
                Print Invoice
            </button>
            <a href="{{ route('transactions.show', $transaction->id) }}" class="ml-4 text-gray-500 hover:text-gray-700 underline">Back to Transaction</a>
        </div>

    </div>

    <script>
        window.onload = function() {
            // Auto open print dialog
            window.print();
        }
    </script>
</body>
</html>
