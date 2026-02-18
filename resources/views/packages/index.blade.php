@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Umrah & Hajj Packages</h1>
        <p class="text-gray-500 text-sm">Manage travel packages and quotas.</p>
    </div>
    <a href="{{ route('packages.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center transition-transform transform hover:scale-105">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        New Package
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($packages as $package)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-1 rounded-full uppercase tracking-wide">{{ $package->status }}</span>
                <span class="text-gray-500 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $package->departure_date->format('d M Y') }}
                </span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
            <p class="text-2xl font-bold text-gold-500 mb-4">IDR {{ number_format($package->price / 1000000, 1) }}M</p>
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Available / Total Quota:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $package->available_quota > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $package->available_quota }} / {{ $package->quota }}
                    </span>
                </div>
                <!-- Progress Bar -->
                <!-- Progress Bar & Scarcity Logic -->
                @php
                    $percentage = ($package->transactions_count / $package->quota) * 100;
                    $remaining = $package->quota - $package->transactions_count;
                    // Colors: Green if safe, Yellow if warning (<20 seats), Red if critical (<10 seats)
                    // Or percentage based? Let's stick to simple "Available" logic.
                    // If remaining > 20 => Green. < 10 => Red.
                    $colorClass = 'bg-emerald-500';
                    if($remaining < 10) $colorClass = 'bg-red-500';
                    elseif($remaining < 20) $colorClass = 'bg-yellow-500';
                @endphp
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="{{ $colorClass }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ min($percentage, 100) }}%"></div>
                </div>
                
                @if($package->hotel_makkah || $package->airlines)
                <div class="mt-3 pt-3 border-t border-dashed border-gray-200 text-xs text-gray-500">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="truncate">{{ $package->hotel_makkah ?: 'Hotel -' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <span class="truncate">{{ $package->airlines ?: 'Airline -' }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
            <a href="{{ route('packages.show', $package) }}" class="text-emerald-600 font-medium hover:text-emerald-800 text-sm">View Details →</a>
            @if($package->status == 'upcoming')
                <a href="{{ route('packages.edit', $package->id) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
