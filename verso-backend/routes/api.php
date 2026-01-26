<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

// --- Public Routes ---
// Added ->name('login') to prevent RouteNotFoundException if auth fails
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login'); 
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
    Route::post('/community/channels/create', [CommunityController::class, 'createChannel']);
    Route::post('/community/channels/join', [CommunityController::class, 'joinChannel']);

    // --- USERS ROUTES (FIXED ORDER) ---
    // 1. Specific routes FIRST
    Route::get('/users/search', [UserController::class, 'search']); 
    
    // 2. Wildcard routes LAST
    Route::get('/users/{id}', [UserController::class, 'show']);
    
    Route::post('/users/{id}/follow', [UserController::class, 'follow']);
    Route::delete('/users/{id}/follow', [UserController::class, 'unfollow']);

    Route::post('/authors/{id}/follow', [AuthorController::class, 'toggleFollow']);
    
    Route::get('/history', [BookController::class, 'getHistory']);
    Route::delete('/history/{id}', [BookController::class, 'removeFromHistory']);
});