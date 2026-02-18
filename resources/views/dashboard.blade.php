@extends('layouts.admin')

@section('content')
<div class="clay-card p-6 mb-8 border-l-8 border-emerald-500">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-700">Executive Dashboard</h1>
            <p class="text-slate-500 mt-1">Overview of your travel business performance.</p>
        </div>
        <div>
            <span class="bg-emerald-100 text-emerald-800 text-sm font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-sm">
                {{ date('F Y') }}
            </span>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Revenue</h3>
            <div class="p-3 bg-emerald-100 rounded-2xl text-emerald-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-700">IDR {{ number_format($totalRevenue / 1000000, 1) }}M</p>
        <span class="text-xs text-emerald-500 font-bold mt-2 block">+12% from last month</span>
    </div>

    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Active Pilgrims</h3>
            <div class="p-3 bg-blue-100 rounded-2xl text-blue-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-700">{{ $activePilgrims }}</p>
        <span class="text-xs text-gray-500 font-medium mt-2 block">Currently in Saudi</span>
    </div>

    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Unpaid Balance</h3>
            <div class="p-3 bg-rose-100 rounded-2xl text-rose-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-700">IDR {{ number_format($unpaidAmount / 1000000, 1) }}M</p>
        <span class="text-xs text-rose-500 font-bold mt-2 block">Needs attention</span>
    </div>

    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Next Departure</h3>
            <div class="p-3 bg-purple-100 rounded-2xl text-purple-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <p class="text-xl font-extrabold text-slate-700 truncate">{{ $upcomingPackage->name ?? 'None' }}</p>
        <span class="text-xs text-purple-600 font-bold mt-2 block">{{ $remainingQuota }} seats remaining</span>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Line Chart -->
    <div class="clay-card p-8 lg:col-span-2">
        <h3 class="text-lg font-bold text-slate-700 mb-6 flex items-center">
            <span class="w-2 h-6 bg-emerald-500 rounded-full mr-3"></span>
            Registration Trend
        </h3>
        <canvas id="registrationChart" height="150"></canvas>
    </div>

    <!-- Doughnut Chart -->
    <div class="clay-card p-8">
        <h3 class="text-lg font-bold text-slate-700 mb-6 flex items-center">
             <span class="w-2 h-6 bg-yellow-500 rounded-full mr-3"></span>
             Demographics
        </h3>
        <canvas id="genderChart" height="200"></canvas>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="clay-card overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="text-xl font-bold text-slate-700">Recent Transactions</h3>
        <a href="{{ route('transactions.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-bold hover:underline">View All &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-8 py-4">Jamaah</th>
                    <th class="px-6 py-4">Package</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Agent</th>
                    <th class="px-8 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recentTransactions as $transaction)
                <tr class="hover:bg-emerald-50/30 transition-colors duration-150">
                    <td class="px-8 py-4">
                        <span class="block font-bold text-slate-700">{{ $transaction->pilgrims->first()->full_name ?? 'No Pilgrim' }}</span>
                        @if($transaction->total_pax > 1)
                            <span class="text-xs text-slate-400 font-medium">+ {{ $transaction->total_pax - 1 }} others</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-600">{{ $transaction->package->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $transaction->transaction_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-mono font-bold text-slate-700">IDR {{ number_format($transaction->total_amount) }}</td>
                    <td class="px-6 py-4">
                         <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">{{ $transaction->agent->name ?? 'Direct' }}</span>
                    </td>
                    <td class="px-8 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm border border-opacity-20
                            {{ $transaction->status == 'Paid' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                               ($transaction->status == 'Pending' ? 'bg-amber-100 text-amber-700 border-amber-200' : 
                               ($transaction->status == 'Down Payment' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-gray-100 text-gray-700 border-gray-200')) }}">
                            {{ $transaction->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Registration Chart
    const ctxReg = document.getElementById('registrationChart').getContext('2d');
    new Chart(ctxReg, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'New Registrations',
                data: {!! json_encode($registrations) !!},
                borderColor: '#10b981', // Emerald 500
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { weight: 'bold', color: '#9ca3af' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold', color: '#9ca3af' } }
                }
            }
        }
    });

    // Gender Chart
    const ctxGender = document.getElementById('genderChart').getContext('2d');
    new Chart(ctxGender, {
        type: 'doughnut',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: {!! json_encode($genderStats) !!},
                backgroundColor: ['#10b981', '#fbbf24'], // Emerald 500, Amber 400
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold', color: '#6b7280' } } }
            }
        }
    });
</script>
@endsection
