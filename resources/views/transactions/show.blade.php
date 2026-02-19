@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-emerald-600 text-sm flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Transactions
    </a>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                {{ $transaction->transaction_code }}
                <span class="ml-3 px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $transaction->status == 'Paid' ? 'bg-emerald-100 text-emerald-800' : 
                       ($transaction->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                    {{ $transaction->status }}
                </span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">Booked on {{ $transaction->transaction_date->format('d M Y') }} • {{ $transaction->total_pax }} Pax</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.invoice', $transaction) }}" target="_blank" class="bg-white border border-gray-200 text-gray-600 hover:text-emerald-600 hover:border-emerald-600 font-bold py-2 px-4 rounded-xl shadow-sm flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </a>
            <a href="{{ route('transactions.edit', $transaction) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl shadow-md transition-transform transform hover:-translate-y-0.5">
                Edit Transaction
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Pilgrims List -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-800">Data Jamaah</h3>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-md">{{ $transaction->total_pax }} Persons</span>
                </div>
                <a href="{{ route('transactions.pilgrims.edit', $transaction) }}" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Bulk Edit
                </a>
            </div>
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 font-medium">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Passport</th>
                        <th class="px-6 py-3">Gender</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaction->pilgrims as $pilgrim)
                    <tr>
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $pilgrim->full_name }}</td>
                        <td class="px-6 py-3 font-mono text-gray-600">{{ $pilgrim->passport_number }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $pilgrim->gender }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Payments Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">Payment History</h3>
            </div>
            <div class="p-6">
                <!-- Add Payment Form -->
                @if($transaction->status != 'Paid' && $transaction->status != 'Cancelled')
                <form action="{{ route('payments.store') }}" method="POST" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6 flex gap-4 items-end">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                    <div class="flex-grow">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Amount</label>
                        <input type="number" name="amount_paid" class="w-full rounded-md border-gray-300 text-sm" placeholder="e.g. 5000000" required>
                    </div>
                    <div class="flex-grow">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 text-sm" required>
                    </div>
                    <div class="flex-grow">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Method</label>
                        <select name="payment_method" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-bold text-sm">Add Payment</button>
                </form>
                @endif

                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-100">
                            <th class="pb-3">Date</th>
                            <th class="pb-3">Method</th>
                            <th class="pb-3 text-right">Amount</th>
                            <th class="pb-3 text-center">Proof</th>
                            <th class="pb-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transaction->payments as $payment)
                        <tr>
                            <td class="py-3">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="py-3">{{ $payment->payment_method }}</td>
                            <td class="py-3 text-right font-mono text-emerald-700 font-bold">+ {{ number_format($payment->amount_paid) }}</td>
                            <td class="py-3 text-center">
                                @if($payment->proof_of_payment)
                                    <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline text-xs mr-2">View</a>
                                @else
                                    <span class="text-gray-400 text-xs mr-2">-</span>
                                @endif
                                <button onclick="window.print()" class="text-gray-500 hover:text-gray-800" title="Print Receipt">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </button>
                            </td>
                            <td class="py-3 text-right">
                                <span class="text-xs px-2 py-1 rounded-full 
                                    {{ $payment->status == 'Verified' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500 italic">Belum ada pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t border-gray-200">
                        <tr>
                            <td colspan="2" class="pt-4 font-bold text-right text-gray-600">Total Paid</td>
                            <td class="pt-4 text-right font-bold text-gray-900 text-lg">{{ number_format($transaction->payments->sum('amount_paid')) }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="font-bold text-right text-red-500">Remaining</td>
                            <td class="text-right font-bold text-red-500 text-lg">{{ number_format($transaction->total_amount - $transaction->payments->sum('amount_paid')) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide">Package Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="block text-gray-500 text-xs">Package Name</span>
                    <span class="font-medium text-gray-900">{{ $transaction->package->name }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 text-xs">Departure Date</span>
                    <span class="font-medium text-gray-900">{{ $transaction->package->departure_date->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 text-xs">Hotels</span>
                    <span class="text-sm text-gray-600">{{ $transaction->package->hotel_makkah }} / {{ $transaction->package->hotel_madinah }}</span>
                </div>
            </div>
        </div>

        @if($transaction->agent)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide">Agent Details</h3>
            <div class="space-y-3">
                <div>
                    <span class="block text-gray-500 text-xs">Agent Name</span>
                    <span class="font-medium text-gray-900">{{ $transaction->agent->name }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 text-xs">Contact</span>
                    <span class="font-medium text-gray-900">{{ $transaction->agent->contact_number }}</span>
                </div>
            </div>
        </div>
        @endif
        
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
             <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full text-red-600 hover:text-red-800 font-bold text-sm py-2">
                    Cancel Booking
                </button>
             </form>
        </div>
    </div>
</div>
    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('paymentModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Pembayaran Baru</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Bayar</label>
                                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah (IDR)</label>
                                        <input type="number" name="amount_paid" required min="0" class="w-full px-4 py-2 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500" placeholder="Contoh: 5000000">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Metode Pembayaran</label>
                                        <select name="payment_method" required class="w-full px-4 py-2 rounded-xl bg-gray-50 border-none shadow-inner focus:ring-2 focus:ring-emerald-500">
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cash">Cash / Tunai</option>
                                            <option value="Credit Card">Credit Card</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Bukti Bayar (Opsional)</label>
                                        <input type="file" name="proof_of_payment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
