<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\CommunityController; // COMMENT THIS OUT

// --- Public Routes ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

// --- Protected Routes (Require Login) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth Management
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard & Stats
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Events
    Route::get('/events', [EventController::class, 'index']);

    // Community Routes - Commented out until Controller is created
    // Route::get('/community/channels', [CommunityController::class, 'getChannels']);
    // ...
});