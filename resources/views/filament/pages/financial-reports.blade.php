<x-filament-panels::page>
    <div x-data="{ activeTab: 'profit-loss' }">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'profit-loss'" 
                    :class="{ 'border-emerald-500 text-emerald-600': activeTab === 'profit-loss', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profit-loss' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Profit & Loss
                </button>
                <button @click="activeTab = 'balance-sheet'" 
                    :class="{ 'border-emerald-500 text-emerald-600': activeTab === 'balance-sheet', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'balance-sheet' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Balance Sheet
                </button>
            </nav>
        </div>

        <div x-show="activeTab === 'profit-loss'" class="pv-4 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <x-filament::section>
                    <x-slot name="heading">Total Revenue</x-slot>
                    <div class="text-3xl font-bold text-emerald-600">
                        {{ number_format($pl['revenue'], 0, ',', '.') }} IDR
                    </div>
                </x-filament::section>
                
                 <x-filament::section>
                    <x-slot name="heading">Total Expense</x-slot>
                    <div class="text-3xl font-bold text-rose-600">
                        {{ number_format($pl['expense'], 0, ',', '.') }} IDR
                    </div>
                </x-filament::section>
                
                 <x-filament::section>
                    <x-slot name="heading">Net Profit</x-slot>
                    <div class="text-3xl font-bold {{ $pl['net_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ number_format($pl['net_profit'], 0, ',', '.') }} IDR
                    </div>
                </x-filament::section>
            </div>
        </div>

        <div x-show="activeTab === 'balance-sheet'" class="pv-4 mt-6" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-filament::section>
                    <x-slot name="heading">Assets</x-slot>
                    <div class="text-3xl font-bold text-emerald-600">
                        {{ number_format($bs['assets'], 0, ',', '.') }} IDR
                    </div>
                </x-filament::section>
                
                <x-filament::section>
                    <x-slot name="heading">Liabilities + Equity</x-slot>
                    <div class="text-3xl font-bold text-blue-600">
                        {{ number_format($bs['liabilities'] + $bs['equity'], 0, ',', '.') }} IDR
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        Liabilities: {{ number_format($bs['liabilities'], 0, ',', '.') }}<br>
                        Equity: {{ number_format($bs['equity'], 0, ',', '.') }}
                    </div>
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
