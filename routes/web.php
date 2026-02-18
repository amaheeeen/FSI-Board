<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AgentController;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/pilgrims/export', [\App\Http\Controllers\PilgrimController::class, 'export'])->name('pilgrims.export');
    Route::post('/pilgrims/import', [\App\Http\Controllers\PilgrimController::class, 'import'])->name('pilgrims.import');
    Route::resource('pilgrims', \App\Http\Controllers\PilgrimController::class);
    Route::resource('packages', \App\Http\Controllers\PackageController::class);
    Route::get('packages/{package}/manifest', [\App\Http\Controllers\PackageController::class, 'exportManifest'])->name('packages.manifest');

    Route::get('logs', App\Http\Controllers\ActivityLogController::class)->name('logs.index');
    Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');
    Route::resource('transactions', TransactionController::class);
    Route::resource('agents', AgentController::class);
    Route::get('finance/dashboard', [FinanceController::class, 'dashboard'])->name('finance.dashboard');
    Route::get('finance/export', [FinanceController::class, 'export'])->name('finance.export');
    Route::get('finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('payments', [FinanceController::class, 'store'])->name('payments.store');

    Route::view('manual', 'manual')->name('manual');
    
    // Data Integration Routes
    Route::post('imports/agents', [App\Http\Controllers\ImportController::class, 'importAgents'])->name('imports.agents');
    Route::post('imports/pilgrims', [App\Http\Controllers\ImportController::class, 'importPilgrims'])->name('imports.pilgrims');
    Route::get('imports/template/{type}', [App\Http\Controllers\ImportController::class, 'downloadTemplate'])->name('imports.template');
    Route::get('exports/database', [App\Http\Controllers\ImportController::class, 'exportDatabase'])->name('exports.database');
});

require __DIR__.'/auth.php';
