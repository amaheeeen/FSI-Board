@extends('layouts.admin')

@section('content')
    <div class="flex justify-center">
        <div class="w-full max-w-2xl">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-extrabold text-emerald-900 tracking-tight">Record Expense</h2>
                <p class="text-emerald-600 mt-2 font-medium">Add a new operational cost to the system.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-[8px_8px_16px_rgba(167,243,208,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)] border border-emerald-50 relative overflow-hidden">
                <!-- Decorative Circle -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full opacity-50 blur-3xl pointer-events-none"></div>

                <form method="POST" action="{{ route('operational-costs.store') }}" enctype="multipart/form-data" class="space-y-6 relative z-10">
                    @csrf

                    <!-- Date & Amount Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Expense Date</label>
                            <div class="relative">
                                <input type="date" name="expense_date" required value="{{ old('expense_date', date('Y-m-d')) }}"
                                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-700 font-medium">
                            </div>
                            @error('expense_date') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Amount (IDR)</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 transform -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                <input type="number" name="amount" required min="0" step="1000" value="{{ old('amount') }}" placeholder="0"
                                    class="w-full pl-14 pr-6 py-4 rounded-2xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-700 font-bold text-lg placeholder-gray-300">
                            </div>
                            @error('amount') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Title / Purpose</label>
                        <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g., Monthly Office Rent"
                            class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-700 placeholder-gray-300">
                         @error('title') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Category</label>
                        <div class="relative">
                            <select name="category" required
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-700 appearance-none cursor-pointer">
                                <option value="" disabled selected>Select Category</option>
                                <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing & Ads</option>
                                <option value="Salary" {{ old('category') == 'Salary' ? 'selected' : '' }}>Staff Salary</option>
                                <option value="Utilities" {{ old('category') == 'Utilities' ? 'selected' : '' }}>Utilities (Electricity, Internet)</option>
                                <option value="Office" {{ old('category') == 'Office' ? 'selected' : '' }}>Office Supplies & Rent</option>
                                <option value="Others" {{ old('category') == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                            <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @error('category') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Receipt Upload -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Receipt / Proof (Optional)</label>
                        <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full px-6 py-3 rounded-2xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 focus:outline-none text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition-all cursor-pointer">
                         @error('receipt') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                         <p class="text-xs text-gray-400 mt-2 ml-1">Max 2MB (JPG, PNG, PDF)</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Notes / Description</label>
                        <textarea name="description" rows="3" placeholder="Additional details..."
                            class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none shadow-[inset_2px_2px_4px_rgba(0,0,0,0.05),inset_-2px_-2px_4px_rgba(255,255,255,1)] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all text-gray-700 placeholder-gray-300 resize-none">{{ old('description') }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 gap-4">
                        <a href="{{ route('operational-costs.index') }}" class="w-1/3 py-4 text-center text-gray-600 hover:text-gray-800 font-semibold transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="w-2/3 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-[6px_6px_12px_rgba(16,185,129,0.3),-6px_-6px_12px_rgba(255,255,255,0.8)] transform transition hover:-translate-y-1 hover:shadow-[8px_8px_16px_rgba(16,185,129,0.4),-8px_-8px_16px_rgba(255,255,255,0.8)]">
                            Save Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
