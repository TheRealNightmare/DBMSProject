<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

// --- Public Routes ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

// --- Protected Routes (Require Login) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard & Stats
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    // Old: Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // Events
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);

    Route::get('/books/{id}/read/{chapter}', [BookController::class, 'getContent']);
    Route::post('/books/{id}/annotate', [BookController::class, 'storeAnnotation']);

    // Community
    Route::get('/community/groups', [CommunityController::class, 'getGroups']);
    Route::get('/community/groups/{groupId}/messages', [CommunityController::class, 'getMessages']);
    Route::post('/community/groups/{groupId}/messages', [CommunityController::class, 'sendMessage']);

    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users/{id}/follow', [UserController::class, 'follow']);
    Route::delete('/users/{id}/follow', [UserController::class, 'unfollow']);
    Route::get('/users/search', [UserController::class, 'search']);
});