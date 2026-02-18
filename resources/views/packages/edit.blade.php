@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Package</h1>
        <p class="text-gray-500">Update travel package details.</p>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-[8px_8px_16px_#d1d5db,-8px_-8px_16px_#ffffff] border border-gray-50">
        <form method="POST" action="{{ route('packages.update', $package->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Package Name -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Departure Date -->
                <div class="col-span-1">
                    <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-2">Departure Date</label>
                    <input type="date" id="departure_date" name="departure_date" value="{{ old('departure_date', $package->departure_date->format('Y-m-d')) }}" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Duration -->
                <div class="col-span-1">
                    <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Duration (Days)</label>
                    <input type="number" id="duration_days" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Quota -->
                <div class="col-span-1">
                    <label for="quota" class="block text-sm font-medium text-gray-700 mb-2">Total Quota (Pax)</label>
                    <input type="number" id="quota" name="quota" value="{{ old('quota', $package->quota) }}" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>

                 <!-- Travel Details (Optional) -->
                 <div class="col-span-2 border-t border-gray-100 my-2"></div>
                 <h3 class="col-span-2 text-lg font-bold text-gray-600">Travel Details (Optional)</h3>

                 <!-- Hotel Makkah -->
                 <div class="col-span-1">
                     <label for="hotel_makkah" class="block text-sm font-medium text-gray-700 mb-2">Hotel Makkah</label>
                     <input type="text" id="hotel_makkah" name="hotel_makkah" list="hotel_makkah_list" value="{{ old('hotel_makkah', $package->hotel_makkah) }}"
                         class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                         placeholder="Select or Type Hotel">
                     <datalist id="hotel_makkah_list">
                         @foreach($hotelsMakkah as $hotel)
                             <option value="{{ $hotel }}">
                         @endforeach
                     </datalist>
                 </div>

                 <!-- Hotel Madinah -->
                 <div class="col-span-1">
                     <label for="hotel_madinah" class="block text-sm font-medium text-gray-700 mb-2">Hotel Madinah</label>
                     <input type="text" id="hotel_madinah" name="hotel_madinah" list="hotel_madinah_list" value="{{ old('hotel_madinah', $package->hotel_madinah) }}"
                         class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                         placeholder="Select or Type Hotel">
                     <datalist id="hotel_madinah_list">
                         @foreach($hotelsMadinah as $hotel)
                             <option value="{{ $hotel }}">
                         @endforeach
                     </datalist>
                 </div>

                 <!-- Airlines -->
                 <div class="col-span-2">
                     <label for="airlines" class="block text-sm font-medium text-gray-700 mb-2">Airlines</label>
                     <input type="text" id="airlines" name="airlines" list="airlines_list" value="{{ old('airlines', $package->airlines) }}"
                         class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400"
                         placeholder="Select or Type Airlines">
                     <datalist id="airlines_list">
                         @foreach($airlines as $airline)
                             <option value="{{ $airline }}">
                         @endforeach
                     </datalist>
                 </div>

                <div class="col-span-2 border-t border-gray-100 my-2"></div>
                <h3 class="col-span-2 text-lg font-bold text-gray-600">Pricing Configuration (IDR)</h3>

                <!-- Price Quad -->
                <div class="col-span-1 md:col-span-1">
                    <label for="price_quad" class="block text-sm font-medium text-gray-700 mb-2">Quad Price</label>
                    <input type="number" id="price_quad" name="price_quad" value="{{ old('price_quad', $package->price_quad) }}" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Price Triple -->
                <div class="col-span-1 md:col-span-1">
                    <label for="price_triple" class="block text-sm font-medium text-gray-700 mb-2">Triple Price</label>
                    <input type="number" id="price_triple" name="price_triple" value="{{ old('price_triple', $package->price_triple) }}" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>

                <!-- Price Double -->
                <div class="col-span-1 md:col-span-1">
                    <label for="price_double" class="block text-sm font-medium text-gray-700 mb-2">Double Price</label>
                    <input type="number" id="price_double" name="price_double" value="{{ old('price_double', $package->price_double) }}" required
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder-gray-400">
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('packages.index') }}" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 font-bold transition-all">Cancel</a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-[5px_5px_10px_#bebebe,-5px_-5px_10px_#ffffff] active:shadow-inner transform transition hover:-translate-y-0.5">
                    Update Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
