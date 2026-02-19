@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('transactions.show', $transaction) }}" class="text-gray-500 hover:text-emerald-600 text-sm flex items-center mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Detail
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Transaction: {{ $transaction->transaction_code }}</h1>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-100">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Package -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Package</label>
                    <select name="package_id" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ $transaction->package_id == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} ({{ $package->departure_date->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Agent -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Agent</label>
                    <select name="agent_id" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Direct Booking</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ $transaction->agent_id == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Transaction Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Transaction Date</label>
                    <input type="date" name="transaction_date" value="{{ $transaction->transaction_date->format('Y-m-d') }}"
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_5px_#d1d5db,inset_-2px_-2px_5px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="Pending" {{ $transaction->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Down Payment" {{ $transaction->status == 'Down Payment' ? 'selected' : '' }}>Down Payment</option>
                        <option value="Paid" {{ $transaction->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Cancelled" {{ $transaction->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-[4px_4px_8px_#d1d5db,-4px_-4px_8px_#ffffff] transition-transform transform hover:-translate-y-0.5">
                    Update Transaction
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
