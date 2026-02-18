@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('packages.index') }}" class="text-gray-500 hover:text-emerald-600 text-sm flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Packages
    </a>
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ $package->name }}</h1>
        <div class="flex space-x-3">
            <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">Edit Package</button>
            <a href="{{ route('packages.manifest', $package) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg flex items-center shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Manifest (CSV)
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Departure Date</h3>
        <p class="text-lg font-bold text-gray-900">{{ $package->departure_date->format('l, d F Y') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Package Price</h3>
        <p class="text-lg font-bold text-gray-900">IDR {{ number_format($package->price) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Quota Status</h3>
        <p class="text-lg font-bold text-gray-900">{{ $package->transactions->count() }} / {{ $package->quota }} Booked</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-lg font-bold text-gray-800">Passenger Manifest (Jamaah)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 w-10">No</th>
                    <th class="px-6 py-3">Full Name</th>
                    <th class="px-6 py-3">Passport No.</th>
                    <th class="px-6 py-3">Gender</th>
                    <th class="px-6 py-3">Agent</th>
                    <th class="px-6 py-3">Transaction Code</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $row_number = 0; @endphp
                @forelse($package->transactions as $transaction)
                    {{-- Loop through pilgrims in this transaction --}}
                    @foreach($transaction->pilgrims as $pilgrim)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pilgrim->full_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pilgrim->passport_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pilgrim->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->agent->name ?? 'Direct' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_code }}</td>
                    </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        No pilgrims have booked this package yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
