<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Package;
use App\Models\Pilgrim;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache stats for 60 minutes, cleared by TransactionObserver
        $stats = \Illuminate\Support\Facades\Cache::remember('dashboard_stats', 60, function () {
            // Stats Cards
            $totalRevenue = Transaction::sum('total_amount');
            
            // Active Pilgrims: Those in transactions where status is Paid/Down Payment and package is active/departed
            $activePilgrims = Transaction::where('status', '!=', 'Cancelled')->sum('total_pax');

            $unpaidAmount = Transaction::where('status', 'Pending')
                ->orWhere('status', 'Down Payment')
                ->get()
                ->sum(function($transaction) {
                    return $transaction->total_amount - $transaction->payments->sum('amount_paid');
                });
            
            $upcomingPackage = Package::where('status', 'Open')
                ->orderBy('departure_date', 'asc')
                ->first();
                
            $remainingQuota = $upcomingPackage ? $upcomingPackage->available_quota : 0;

            // Charts Data
            // 1. Line Chart: Registrations (Transactions) vs Departures per Month (Last 12 Months)
            $months = [];
            $registrations = [];
            $departures = [];
            
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');
                $registrations[] = Transaction::whereMonth('transaction_date', $date->month)
                    ->whereYear('transaction_date', $date->year)
                    ->sum('total_pax'); // Counting pax, not just transactions
                // Approximating departures based on package dates for now
                $departures[] = 0; // Placeholder logic
            }

            // 2. Doughnut: Gender (Need to query Pilgrims table directly)
            $genderStats = [
                Pilgrim::where('gender', 'Male')->count(),
                Pilgrim::where('gender', 'Female')->count(),
            ];

            return compact(
                'totalRevenue', 
                'activePilgrims', 
                'unpaidAmount', 
                'upcomingPackage', 
                'remainingQuota',
                'months',
                'registrations',
                'departures',
                'genderStats'
            );
        });

        // Recent Transactions (Real-time, not cached or short cache)
        $recentTransactions = Transaction::with(['user', 'package', 'agent', 'pilgrims'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', array_merge($stats, compact('recentTransactions')));
    }
}
