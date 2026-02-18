@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Transactions</h1>
    <a href="{{ route('transactions.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New Booking
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Code</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Package</th>
                    <th class="px-6 py-3">Agent</th>
                    <th class="px-6 py-3 text-center">Pax</th>
                    <th class="px-6 py-3">Total Amount</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($transactions as $txn)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $txn->transaction_code }}</td>
                    <td class="px-6 py-4">{{ $txn->transaction_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $txn->package->name }}</td>
                    <td class="px-6 py-4">{{ $txn->agent->name ?? 'Direct' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md font-bold">{{ $txn->total_pax }}</span>
                    </td>
                    <td class="px-6 py-4 font-medium">IDR {{ number_format($txn->total_amount, 0) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColor = match($txn->status) {
                                'Paid' => 'bg-emerald-100 text-emerald-800',
                                'Down Payment' => 'bg-blue-100 text-blue-800',
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'Cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">{{ $txn->status }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('transactions.show', $txn) }}" class="text-emerald-600 hover:text-emerald-800 font-medium">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
