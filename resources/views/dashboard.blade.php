@extends('layouts.admin')

@section('content')
<div class="clay-card p-6 mb-8 border-l-8 border-emerald-500">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-700">Executive Dashboard</h1>
            <p class="text-slate-500 mt-1">Overview of your travel business performance.</p>
        </div>
        <div>
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <select name="month" onchange="this.form.submit()" class="px-4 py-2 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 text-sm font-bold text-gray-700 cursor-pointer">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ (isset($selectedMonth) ? $selectedMonth : date('n')) == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="px-4 py-2 rounded-xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 text-sm font-bold text-gray-700 cursor-pointer">
                    @foreach(range(date('Y')-5, date('Y')+1) as $y)
                        <option value="{{ $y }}" {{ (isset($selectedYear) ? $selectedYear : date('Y')) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>

<!-- Stats Cards (Phase 28 Redesign) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    <!-- Card 1: Monthly Revenue -->
    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Revenue (Selected)</h3>
            <div class="p-3 bg-emerald-100 rounded-2xl text-emerald-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-emerald-600">IDR {{ number_format($monthlyRevenue / 1000000, 1) }}M</p>
        <span class="text-xs text-gray-400 font-bold mt-2 block">Income from payments</span>
    </div>

    <!-- Card 2: Operational Costs -->
    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Ops Expenses</h3>
            <div class="p-3 bg-rose-100 rounded-2xl text-rose-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-rose-600">IDR {{ number_format($opsCost / 1000000, 1) }}M</p>
        
        <!-- Budget Progress Bar -->
            <!-- Budget Progress Bar -->
            <div class="mt-4">
                <x-hero-progress 
                    label="Budget Used" 
                    :value="$opsCost" 
                    :maxValue="$monthlyBudget ?? 1" 
                    :color="$budgetProgress > 100 ? 'bg-rose-500' : 'bg-emerald-500'" 
                />
            </div>
    </div>

    <!-- Card 3: Operating Profit -->
    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300 {{ $operatingProfit < 0 ? 'bg-rose-50 border-2 border-rose-100' : '' }}">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold {{ $operatingProfit < 0 ? 'text-rose-400' : 'text-gray-400' }} uppercase tracking-wider">Operating Profit</h3>
            <div class="p-3 {{ $operatingProfit < 0 ? 'bg-rose-200 text-rose-700' : 'bg-blue-100 text-blue-600' }} rounded-2xl shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold {{ $operatingProfit < 0 ? 'text-rose-700' : 'text-slate-700' }}">
            IDR {{ number_format($operatingProfit / 1000000, 1) }}M
        </p>
        <div class="flex justify-between items-center mt-2">
            <span class="text-xs {{ $operatingProfit < 0 ? 'text-rose-500' : 'text-emerald-500' }} font-bold block">
                {{ $operatingProfit >= 0 ? 'Profitable' : 'Loss' }}
            </span>
            <span class="text-xs font-extrabold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">
                Margin: {{ number_format($profitMargin, 1) }}%
            </span>
        </div>
    </div>

    <!-- Card 4: Active Pilgrims -->
    <div class="clay-card p-8 transition-transform hover:-translate-y-1 duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Jamaah Aktif</h3>
            <div class="p-3 bg-purple-100 rounded-2xl text-purple-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-700">{{ $activePilgrims }}</p>
        <span class="text-xs text-gray-500 font-medium mt-2 block">Currently in Saudi</span>
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
