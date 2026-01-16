<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Broadcast;
Broadcast::routes(['middleware' => ['auth:sanctum']]);
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

    Route::get('/community/groups', [CommunityController::class, 'getGroups']);
    Route::get('/community/groups/{id}/messages', [CommunityController::class, 'getMessages']);
    Route::post('/community/groups/{id}/messages', [CommunityController::class, 'sendMessage']);
});