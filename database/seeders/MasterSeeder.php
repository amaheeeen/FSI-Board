<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\ChartOfAccount;
use App\Models\Packet;
use App\Models\Inventory;
use App\Models\User;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Chart of Accounts (SAK ETAP Standard)
        $coas = [
            ['code' => '1101', 'name' => 'Cash at Bank', 'type' => 'asset', 'normal_balance' => 'debit'],
            ['code' => '1102', 'name' => 'Cash on Hand', 'type' => 'asset', 'normal_balance' => 'debit'],
            ['code' => '2101', 'name' => 'Unearned Revenue', 'type' => 'liability', 'normal_balance' => 'credit'], // Uang Muka Jamaah
            ['code' => '4101', 'name' => 'Revenue - Umrah', 'type' => 'equity', 'normal_balance' => 'credit'], // Recognized Revenue
            ['code' => '5101', 'name' => 'Cost of Sales (HPP)', 'type' => 'expense', 'normal_balance' => 'debit'],
        ];

        foreach ($coas as $coa) {
            ChartOfAccount::firstOrCreate(['code' => $coa['code']], $coa);
        }

        // 2. Inventory Items
        $items = [
            ['item_name' => 'Suitcase', 'stock' => 100, 'reorder_level' => 20],
            ['item_name' => 'Ihram Set', 'stock' => 100, 'reorder_level' => 20],
            ['item_name' => 'Batik Uniform', 'stock' => 100, 'reorder_level' => 20],
        ];

        foreach ($items as $item) {
            Inventory::firstOrCreate(['item_name' => $item['item_name']], $item);
        }

        // 3. User (Admin)
        User::firstOrCreate(
            ['email' => 'admin@fsi-board.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 4. Packets
        Packet::firstOrCreate(
            ['name' => 'Umrah Akbar 2025'],
            [
                'type' => 'umrah', // Added missing required column
                'price' => 25000000,
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonth()->addDays(9),
                'status' => true, // Converted 'active' string to boolean if schema is boolean, schema says boolean default true
                'description' => 'Paket Umrah Eksklusif 9 Hari',
            ]
        );
    }
}
