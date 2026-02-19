@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('pilgrims.index') }}" class="text-gray-500 hover:text-emerald-600 text-sm flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Jamaah
    </a>
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ $pilgrim->full_name }}</h1>
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $pilgrim->status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            {{ $pilgrim->status ?? 'Registered' }}
        </span>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-[4px_4px_8px_#d1d5db,-4px_-4px_8px_#ffffff] border border-white p-6">
        <h3 class="font-bold text-gray-800 mb-4 uppercase tracking-wide text-sm border-b pb-2">Personal Information</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-xs text-gray-400 uppercase">Passport Number</label>
                <div class="font-mono text-lg text-gray-700">{{ $pilgrim->passport_number }}</div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 uppercase">NIK (KTP)</label>
                <div class="font-mono text-lg text-gray-700">{{ $pilgrim->nik ?? '-' }}</div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-400 uppercase">Gender</label>
                    <div class="text-gray-700">{{ $pilgrim->gender }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 uppercase">City</label>
                    <div class="text-gray-700">{{ $pilgrim->city ?? '-' }}</div>
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-400 uppercase">Address</label>
                <div class="text-gray-700">{{ $pilgrim->address ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Transaction & Agent Info -->
    <div class="bg-white rounded-xl shadow-[4px_4px_8px_#d1d5db,-4px_-4px_8px_#ffffff] border border-white p-6">
        <h3 class="font-bold text-gray-800 mb-4 uppercase tracking-wide text-sm border-b pb-2">Booking Details</h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-xs text-gray-400 uppercase">Associated Agent</label>
                @if($pilgrim->agent)
                <a href="{{ route('agents.show', $pilgrim->agent_id) }}" class="flex items-center text-emerald-600 hover:text-emerald-800 font-medium">
                    {{ $pilgrim->agent->name }}
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
                @else
                <span class="text-gray-500 italic">Direct Booking / No Agent</span>
                @endif
            </div>

            <div class="pt-4 border-t border-gray-100">
                <label class="block text-xs text-gray-400 uppercase mb-2">Transaction</label>
                @if($pilgrim->transaction)
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <a href="{{ route('transactions.show', $pilgrim->transaction_id) }}" class="text-sm font-bold text-emerald-600 hover:underline">
                                {{ $pilgrim->transaction->transaction_code }}
                            </a>
                            <div class="text-xs text-gray-500 mt-1">{{ optional($pilgrim->transaction->package)->name }}</div>
                        </div>
                        <span class="text-xs px-2 py-1 bg-white border rounded text-gray-600">{{ $pilgrim->transaction->status }}</span>
                    </div>
                </div>
                @else
                <div class="text-gray-500 italic text-sm">No transaction linked.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
