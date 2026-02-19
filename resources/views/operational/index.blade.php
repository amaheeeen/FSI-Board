@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        <!-- Header & Budget Overview -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h2 class="text-3xl font-extrabold text-emerald-900 tracking-tight">Operational Expenses</h2>
                <p class="text-emerald-600 mt-1 font-medium">Manage daily expenses and track budget.</p>
            </div>
            
            <!-- Expense Cards -->
            <div class="flex flex-col md:flex-row gap-4 w-full md:w-2/3 lg:w-1/2">
                <!-- Monthly Card -->
                <div class="bg-white p-6 rounded-3xl shadow-[8px_8px_16px_rgba(167,243,208,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)] border border-emerald-50 flex-1">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Monthly Expenses</div>
                    <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($monthlyTotalExpenses, 0, ',', '.') }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ date('F Y') }}</div>
                </div>

                <!-- Yearly Card -->
                <div class="bg-white p-6 rounded-3xl shadow-[8px_8px_16px_rgba(167,243,208,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)] border border-emerald-50 flex-1">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Yearly Expenses</div>
                    <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($yearlyTotalExpenses, 0, ',', '.') }}</div>
                    <div class="text-xs text-gray-400 mt-1">Year {{ date('Y') }}</div>
                </div>
            </div>

            <a href="{{ route('operational-costs.create') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-[6px_6px_12px_rgba(16,185,129,0.3),-6px_-6px_12px_rgba(255,255,255,0.8)] transition-all transform hover:-translate-y-0.5 hover:shadow-[8px_8px_16px_rgba(16,185,129,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Expense
            </a>
        </div>

        <!-- Chart Section -->
        <div class="bg-white p-6 rounded-3xl shadow-[8px_8px_16px_rgba(167,243,208,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)] border border-emerald-50 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Cost Distribution</h3>
                <div class="flex items-center gap-2">
                    <select id="filterMonth" onchange="updateChart()" class="px-4 py-2 rounded-xl bg-gray-50 border-none text-sm font-bold text-gray-700 shadow-sm focus:ring-emerald-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                    <select id="filterYear" onchange="updateChart()" class="px-4 py-2 rounded-xl bg-gray-50 border-none text-sm font-bold text-gray-700 shadow-sm focus:ring-emerald-500">
                        @foreach(range(date('Y')-5, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="costChart"></div>
        </div>

        <!-- Expenses Table -->
        <div class="bg-white rounded-3xl shadow-[8px_8px_16px_rgba(167,243,208,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)] border border-emerald-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-emerald-50/50 text-left">
                            <th class="px-8 py-5 text-xs font-bold text-emerald-800 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-5 text-xs font-bold text-emerald-800 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-5 text-xs font-bold text-emerald-800 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-5 text-xs font-bold text-emerald-800 uppercase tracking-wider text-right">Amount</th>
                            <th class="px-6 py-5 text-xs font-bold text-emerald-800 uppercase tracking-wider text-center">Receipt</th>
                            <th class="px-6 py-5 text-xs font-bold text-emerald-800 uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-emerald-50/30 transition-colors duration-200">
                            <td class="px-8 py-5 text-sm font-medium text-gray-900 border-l-4 border-transparent hover:border-emerald-500 transition-all">
                                {{ $expense->expense_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-700 font-medium">
                                {{ $expense->title }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm font-bold text-gray-800 text-right">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($expense->receipt_path)
                                    <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" class="text-xs p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center space-x-2">
                                <a href="{{ route('operational-costs.edit', $expense->id) }}" class="text-yellow-600 hover:text-yellow-800 transition-colors inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('operational-costs.destroy', $expense->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">No expenses recorded yet.</p>
                                    <p class="text-sm mt-1">Start by adding a new operational expense.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
                <div class="px-8 py-5 border-t border-emerald-50 bg-emerald-50/30">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let costChart;

    document.addEventListener('DOMContentLoaded', () => {
        renderChart({!! json_encode($chartCategories) !!}, {!! json_encode($chartSeries) !!});
    });

    async function updateChart() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;

        try {
            const response = await fetch(`{{ route('operational-costs.index') }}?month=${month}&year=${year}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            // Check if there is data
            if (data.chartCategories.length === 0) {
                 costChart.updateOptions({
                    labels: ['No Data'],
                    colors: ['#e5e7eb']
                 });
                 costChart.updateSeries([1]);
            } else {
                 costChart.updateOptions({
                    labels: data.chartCategories,
                    colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6']
                 });
                 costChart.updateSeries(data.chartSeries);
            }

        } catch (error) {
            console.error('Error fetching chart data:', error);
        }
    }

    function renderChart(categories, series) {
        // Handle empty data case for initial render
        const hasData = categories.length > 0;
        
        const options = {
            series: hasData ? series : [1],
            labels: hasData ? categories : ['No Data'],
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'Inter, sans-serif'
            },
            colors: hasData ? ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'] : ['#e5e7eb'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    if (!hasData) return '0';
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                                }
                            }
                        }
                    }
                }
            },
            legend: { position: 'bottom' },
            tooltip: {
                y: {
                    formatter: function(val) {
                        if (!hasData) return '';
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        };

        costChart = new ApexCharts(document.querySelector("#costChart"), options);
        costChart.render();
    }
</script>
@endsection
