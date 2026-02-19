@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Transactions</h1>
    <a href="{{ route('transactions.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New Booking
    </a>
</div>

<!-- Chart Section -->
<div class="bg-white p-6 rounded-3xl shadow-[8px_8px_16px_rgba(167,243,208,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)] border border-emerald-50 mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800" id="chartTitle">{{ $chartTitle }}</h3>
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
    <div id="transactionChart"></div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Code</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Package</th>
                    <th class="px-6 py-3">Agent</th>
                    <th class="px-6 py-3 text-center">Pax</th>
                    <th class="px-6 py-3">Total Amount</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($transactions as $txn)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $txn->transaction_code }}</td>
                    <td class="px-6 py-4">{{ $txn->transaction_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $txn->package->name }}</td>
                    <td class="px-6 py-4">{{ $txn->agent->name ?? 'Direct' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md font-bold">{{ $txn->total_pax }}</span>
                    </td>
                    <td class="px-6 py-4 font-medium">IDR {{ number_format($txn->total_amount, 0) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColor = match($txn->status) {
                                'Paid' => 'bg-emerald-100 text-emerald-800',
                                'Down Payment' => 'bg-blue-100 text-blue-800',
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'Cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">{{ $txn->status }}</span>
                    </td>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <a href="{{ route('transactions.invoice', $txn) }}" target="_blank" class="text-gray-400 hover:text-emerald-600 transition-colors" title="Print Invoice">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </a>
                        <a href="{{ route('transactions.show', $txn) }}" class="text-emerald-600 hover:text-emerald-800 font-medium">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $transactions->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    let transactionChart;

    document.addEventListener('DOMContentLoaded', () => {
        renderChart({!! json_encode($chartLabels) !!}, {!! json_encode($chartData) !!});
    });

    async function updateChart() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;

        try {
            const response = await fetch(`{{ route('transactions.index') }}?month=${month}&year=${year}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            document.getElementById('chartTitle').innerText = data.chartTitle;
            
            transactionChart.updateOptions({
                xaxis: { categories: data.chartLabels }
            });
            transactionChart.updateSeries([{
                data: data.chartData
            }]);

        } catch (error) {
            console.error('Error fetching chart data:', error);
        }
    }

    function renderChart(labels, data) {
        const options = {
            series: [{
                name: 'Total Transaction Amount',
                data: data
            }],
            chart: {
                type: 'area', // Area chart for trend
                height: 350,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            colors: ['#10b981'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: labels,
                title: { text: 'Day of Month' }
            },
            yaxis: {
                title: { text: 'IDR Amount' },
                labels: {
                    formatter: function (value) {
                         return new Intl.NumberFormat('id-ID', { notation: "compact", compactDisplay: "short" }).format(value);
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2, // Faded Green Glass effect
                    stops: [0, 90, 100]
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "IDR " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        };

        transactionChart = new ApexCharts(document.querySelector("#transactionChart"), options);
        transactionChart.render();
    }
</script>
@endsection
