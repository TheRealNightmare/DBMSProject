<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommunityController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/books', [BookController::class, 'index']); // Home Page data
Route::get('/books/{id}', [BookController::class, 'show']); // Detail Page

// Protected Routes (Require Login)
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Storage & History
    Route::get('/shelves/{name}', [BookController::class, 'getShelf']); 
});