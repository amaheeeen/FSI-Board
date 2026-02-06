<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TgsController;

// Public Auth
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    
    // TGS (Tour Guide System)
    Route::prefix('tgs')->group(function () {
        Route::post('/start', [TgsController::class, 'startSession']); // Mutawwif
        Route::post('/join', [TgsController::class, 'joinSession']);   // Jamaah
        Route::post('/location', [TgsController::class, 'updateLocation']); // Jamaah Tracking
    });

    // Content
    Route::get('/packets/{id}/itinerary', [\App\Http\Controllers\Api\ContentController::class, 'itinerary']);
    Route::get('/content/prayers', [\App\Http\Controllers\Api\ContentController::class, 'prayers']);
    Route::get('/gallery', [\App\Http\Controllers\Api\ContentController::class, 'gallery']);

    // Bookings & History
    Route::get('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'index']); // Changed from my-bookings per requirement
    Route::get('/bookings/{id}/payment', [\App\Http\Controllers\Api\BookingController::class, 'payment']);
    
    // Tracking
    Route::post('/tracking', [TgsController::class, 'updateLocation']);
});
