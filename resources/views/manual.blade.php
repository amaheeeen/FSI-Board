@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto pb-10">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-slate-800 mb-2">Pusat Bantuan</h1>
        <p class="text-gray-500">Panduan lengkap penggunaan Sistem Manajemen Umrah & Haji.</p>
    </div>

    <!-- Accordion Container -->
    <div x-data="{ active: 1 }" class="space-y-4">
        
        <!-- Guide 1: Pendahuluan -->
        <div class="bg-white rounded-2xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] border border-gray-50 overflow-hidden">
            <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left bg-white hover:bg-gray-50 flex justify-between items-center transition-colors">
                <span class="text-lg font-bold text-slate-700 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center mr-3 text-sm font-bold">1</span>
                    Pendahuluan & Akun
                </span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="active === 1" x-collapse class="px-8 py-6 border-t border-gray-100 bg-slate-50/50">
                <div class="prose text-gray-600 max-w-none">
                    <h4 class="font-bold text-slate-800">Login & Logout</h4>
                    <p>Gunakan Username atau Email yang terdaftar untuk masuk. Pastikan logout setelah selesai menggunakan sistem untuk keamanan.</p>
                    <h4 class="font-bold text-slate-800 mt-4">Edit Profil</h4>
                    <p>Klik menu <strong>Profile</strong> di sidebar untuk:</p>
                    <ul class="list-disc ml-5">
                        <li>Mengganti Foto Profil (Avatar).</li>
                        <li>Memperbarui Username dan Email.</li>
                        <li>Mengganti Password.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Guide 2: Manajemen Paket -->
        <div class="bg-white rounded-2xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] border border-gray-50 overflow-hidden">
            <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left bg-white hover:bg-gray-50 flex justify-between items-center transition-colors">
                <span class="text-lg font-bold text-slate-700 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 text-sm font-bold">2</span>
                    Manajemen Paket
                </span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="active === 2" x-collapse class="px-8 py-6 border-t border-gray-100 bg-emerald-50/30">
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Buka menu <strong>Packages</strong>.</li>
                    <li>Klik <strong>New Package</strong>.</li>
                    <li>Isi Nama Paket, Tanggal Keberangkatan, Kuota, dan Hotel.</li>
                    <li>Tentukan <strong>Harga Jual</strong> berdasarkan tipe kamar (Quad, Triple, Double).</li>
                    <li>Klik <strong>Create</strong>. Paket status 'Open' siap untuk booking.</li>
                </ol>
            </div>
        </div>

        <!-- Guide 3: Manajemen Jamaah -->
        <div class="bg-white rounded-2xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] border border-gray-50 overflow-hidden">
            <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left bg-white hover:bg-gray-50 flex justify-between items-center transition-colors">
                <span class="text-lg font-bold text-slate-700 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 text-sm font-bold">3</span>
                    Manajemen Jamaah (Import/Export)
                </span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="active === 3" x-collapse class="px-8 py-6 border-t border-gray-100 bg-blue-50/30">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-blue-800 mb-2">Input Manual</h4>
                        <p class="text-sm text-gray-600 mb-2">Untuk input satu per satu:</p>
                        <ol class="list-decimal list-inside text-sm text-gray-700">
                            <li>Menu <strong>Jamaah (Pilgrims)</strong>.</li>
                            <li>Klik <strong>New Jamaah</strong>.</li>
                            <li>Isi form lengkap.</li>
                        </ol>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-800 mb-2">Import CSV (Masal)</h4>
                        <p class="text-sm text-gray-600 mb-2">Untuk upload banyak data sekaligus:</p>
                        <ol class="list-decimal list-inside text-sm text-gray-700">
                            <li>Menu <strong>Jamaah</strong> -> Klik <strong>Import Data</strong>.</li>
                            <li>Upload file CSV dengan format kolom: <code>Full Name, Passport, NIK, Gender, City</code>.</li>
                            <li>System akan melewatkan data duplikat (Passport sama).</li>
                        </ol>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-blue-100">
                    <h4 class="font-bold text-blue-800 mb-2">Export Data</h4>
                    <p class="text-sm text-gray-600">Klik tombol hijau <strong>Export Data</strong> di halaman Jamaah untuk mengunduh seluruh database jamaah ke format CSV.</p>
                </div>
            </div>
        </div>

        <!-- Guide 4: Transaksi & Booking -->
        <div class="bg-white rounded-2xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] border border-gray-50 overflow-hidden">
            <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-4 text-left bg-white hover:bg-gray-50 flex justify-between items-center transition-colors">
                <span class="text-lg font-bold text-slate-700 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center mr-3 text-sm font-bold">4</span>
                    Transaksi & Booking
                </span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="active === 4" x-collapse class="px-8 py-6 border-t border-gray-100 bg-orange-50/30">
                <p class="text-gray-700 mb-4">Gunakan ini untuk mendaftarkan Jamaah (Satu orang atau Keluarga) ke dalam Paket.</p>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Menu <strong>Transactions</strong> -> <strong>New Booking</strong>.</li>
                    <li>Pilih <strong>Paket</strong> dan Agen (jika ada).</li>
                    <li>Pilih <strong>Tipe Kamar</strong> (Quad/Triple/Double). Harga akan menyesuaikan.</li>
                    <li>Isi data Jamaah. Klik <strong>Add Another Pilgrim</strong> untuk tambah anggota keluarga dalam 1 invoice.</li>
                    <li>Simpan. Status awal: <strong>Pending</strong>.</li>
                </ol>
            </div>
        </div>

        <!-- Guide 5: Keuangan -->
        <div class="bg-white rounded-2xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] border border-gray-50 overflow-hidden">
            <button @click="active = (active === 5 ? null : 5)" class="w-full px-6 py-4 text-left bg-white hover:bg-gray-50 flex justify-between items-center transition-colors">
                <span class="text-lg font-bold text-slate-700 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3 text-sm font-bold">5</span>
                    Keuangan & Manifest
                </span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 5 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="active === 5" x-collapse class="px-8 py-6 border-t border-gray-100 bg-purple-50/30">
                <h4 class="font-bold text-purple-800 mb-2">Pembayaran</h4>
                <ol class="list-decimal list-inside space-y-1 text-gray-700 mb-4">
                    <li>Buka Detail Transaksi.</li>
                    <li>Di bagian <strong>Payment History</strong>, input jumlah bayar.</li>
                    <li>Sistem otomatis menghitung sisa tagihan.</li>
                </ol>

                <h4 class="font-bold text-purple-800 mb-2">Cetak Manifest</h4>
                <p class="text-gray-700">Masuk ke menu Paket -> Detail Paket -> Klik <strong>Export Manifest</strong>. File ini berisi daftar penumpang untuk maskapai/visa.</p>
            </div>
        </div>

        <!-- Guide 6: Troubleshooting -->
        <div class="bg-white rounded-2xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] border border-gray-50 overflow-hidden">
            <button @click="active = (active === 6 ? null : 6)" class="w-full px-6 py-4 text-left bg-white hover:bg-gray-50 flex justify-between items-center transition-colors">
                <span class="text-lg font-bold text-slate-700 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-3 text-sm font-bold">6</span>
                    FAQ / Masalah Umum
                </span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 6 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="active === 6" x-collapse class="px-8 py-6 border-t border-gray-100 bg-red-50/30">
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2 font-bold">Q:</span>
                        <span><strong>Gagal Import CSV?</strong><br>Pastikan format tanggal benar (YYYY-MM-DD) dan tidak ada baris kosong di file Excel/CSV.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2 font-bold">Q:</span>
                        <span><strong>Kuota Paket Penuh?</strong><br>Sistem menolak booking jika kuota habis. Edit Paket untuk menambah kuota jika diperlukan.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 mr-2 font-bold">Q:</span>
                        <span><strong>Lupa Password?</strong><br>Hubungi IT Support untuk reset password manual lewat database jika fitur reset email belum aktif.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
