@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Create New Package</h1>
        <p class="text-gray-500">Design a new travel package for pilgrims.</p>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50">
        <form method="POST" action="{{ route('packages.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Package Name -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="e.g. Umrah Syawal 2026 - Gold">
                </div>

                <!-- Departure Date -->
                <div class="col-span-1">
                    <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-2">Departure Date</label>
                    <input type="date" id="departure_date" name="departure_date" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Duration -->
                <div class="col-span-1">
                    <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Duration (Days)</label>
                    <input type="number" id="duration_days" name="duration_days" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="9">
                </div>

                <!-- Quota -->
                <div class="col-span-1">
                    <label for="quota" class="block text-sm font-medium text-gray-700 mb-2">Total Quota (Pax)</label>
                    <input type="number" id="quota" name="quota" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="45">
                </div>

                 <!-- Spacer -->
                 <div class="hidden md:block col-span-1"></div>

                <div class="col-span-2 border-t border-gray-100 my-2"></div>
                <h3 class="col-span-2 text-lg font-bold text-gray-600">Pricing Configuration (IDR)</h3>

                <!-- Price Quad -->
                <div class="col-span-1 md:col-span-1">
                    <label for="price_quad" class="block text-sm font-medium text-gray-700 mb-2">Quad Price</label>
                    <input type="number" id="price_quad" name="price_quad" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="30000000">
                </div>

                <!-- Price Triple -->
                <div class="col-span-1 md:col-span-1">
                    <label for="price_triple" class="block text-sm font-medium text-gray-700 mb-2">Triple Price</label>
                    <input type="number" id="price_triple" name="price_triple" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="32000000">
                </div>

                <!-- Price Double -->
                <div class="col-span-1 md:col-span-1">
                    <label for="price_double" class="block text-sm font-medium text-gray-700 mb-2">Double Price</label>
                    <input type="number" id="price_double" name="price_double" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                        placeholder="35000000">
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('packages.index') }}" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 font-bold transition-all">Cancel</a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-[5px_5px_10px_#bebebe,-5px_-5px_10px_#ffffff] active:shadow-inner transform transition hover:-translate-y-0.5">
                    Create Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
