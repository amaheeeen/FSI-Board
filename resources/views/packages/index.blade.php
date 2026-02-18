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
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ min(($package->transactions_count / $package->quota) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
            <a href="{{ route('packages.show', $package) }}" class="text-emerald-600 font-medium hover:text-emerald-800 text-sm">View Details →</a>
            @if($package->status == 'upcoming')
                <button class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
