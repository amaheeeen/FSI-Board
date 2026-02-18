@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('agents.index') }}" class="text-gray-500 hover:text-emerald-600 text-sm flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Agents
    </a>
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ $agent->name }}</h1>
        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Active Agent</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Profile -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide">Contact Info</h3>
            <div class="space-y-4">
                <div>
                    <span class="block text-gray-500 text-xs">Phone Number</span>
                    <span class="font-medium text-gray-900">{{ $agent->phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 text-xs">Email</span>
                    <span class="font-medium text-gray-900">{{ $agent->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 text-xs">Location</span>
                    <span class="font-medium text-gray-900">{{ $agent->location ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide">Performance</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-emerald-50 p-4 rounded-lg text-center">
                    <span class="block text-2xl font-bold text-emerald-700">{{ $agent->pilgrims->count() }}</span>
                    <span class="text-xs text-emerald-600">Total Pilgrims</span>
                </div>
                 <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <span class="block text-2xl font-bold text-blue-700">{{ $agent->transactions->count() }}</span>
                    <span class="text-xs text-blue-600">Transactions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Pilgrim List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Pilgrims Brought</h3>
                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-md">{{ $agent->pilgrims->count() }} Persons</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-medium">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Passport</th>
                            <th class="px-6 py-3">Package</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($agent->pilgrims as $pilgrim)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $pilgrim->full_name }}</td>
                            <td class="px-6 py-4 font-mono text-gray-500">{{ $pilgrim->passport_number }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $pilgrim->transaction->package->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $pilgrim->transaction && $pilgrim->transaction->status == 'Paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $pilgrim->transaction->status ?? 'Registered' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">
                                No pilgrims associated with this agent yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
