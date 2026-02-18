@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Financial Records</h1>
        <p class="text-gray-500 text-sm">Track all incoming payments and transactions.</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('finance.export') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl shadow-[4px_4px_8px_#d1d5db,-4px_-4px_8px_#ffffff] flex items-center transition-transform transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export to Excel/CSV
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Invoice #</th>
                    <th class="px-6 py-3">Payer / Agent</th>
                    <th class="px-6 py-3">Package</th>
                    <th class="px-6 py-3 text-right">Amount (IDR)</th>
                    <th class="px-6 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">{{ $payment->payment_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $payment->transaction->transaction_code }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $payment->transaction->agent->name ?? ($payment->transaction->user->name ?? 'Guest') }}
                    </td>
                    <td class="px-6 py-4">{{ $payment->transaction->package->name }}</td>
                    <td class="px-6 py-4 text-right font-bold text-emerald-700">
                        {{ number_format($payment->amount_paid, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                         <span class="px-2 py-1 rounded-full text-xs font-semibold 
                            {{ $payment->status == 'Verified' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $payment->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">No financial records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $payments->links() }}
    </div>
</div>
@endsection
