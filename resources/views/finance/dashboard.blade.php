@extends('layouts.admin')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Financial Overview</h1>
        <p class="text-gray-500">Real-time cashflow analysis and forecasting.</p>
    </div>
    <div class="space-x-2">
        <a href="{{ route('finance.index') }}" class="bg-white border border-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-50">
            Transaction History
        </a>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg">
            Download Report
        </button>
    </div>
</div>

<!-- Stats Cards (Claymorphism) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
    <!-- Total Revenue -->
    <div class="bg-white p-8 rounded-3xl shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Revenue</h3>
            <div class="p-3 bg-emerald-100 rounded-2xl text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-700">IDR {{ number_format($totalRevenue / 1000000, 2) }}M</p>
        <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 70%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-2">70% of target achieved</p>
    </div>

    <!-- Accounts Receivable -->
    <div class="bg-white p-8 rounded-3xl shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Accounts Receivable</h3>
            <div class="p-3 bg-amber-100 rounded-2xl text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-700">IDR {{ number_format($accountsReceivable / 1000000, 2) }}M</p>
        <span class="text-xs text-amber-600 font-bold mt-2 block">Pending Payments from Transactions</span>
    </div>

    <!-- Operational Cost (Placeholder) -->
    <div class="bg-white p-8 rounded-3xl shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50 grayscale opacity-70">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Operational Cost</h3>
            <div class="p-3 bg-rose-100 rounded-2xl text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-700">IDR 0.00</p>
        <span class="text-xs text-gray-400 mt-2 block">Feature coming soon</span>
    </div>
</div>

<!-- Chart -->
<div class="bg-white p-8 rounded-3xl shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50">
    <h3 class="text-lg font-bold text-slate-700 mb-6 border-b border-gray-100 pb-4">Monthly Revenue Stream</h3>
    <canvas id="revenueChart" height="100"></canvas>
</div>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Income (IDR)',
                data: {!! json_encode($incomeStats) !!},
                backgroundColor: '#10b981', // Emerald 500
                borderRadius: 8,
                barThickness: 20
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
                    ticks: { callback: function(value) { return 'IDR ' + value / 1000000 + 'M'; } }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection
