<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PortfolioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes API
Route::post('/register', [AuthController::class, 'apiRegister']);
Route::post('/login', [AuthController::class, 'apiLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::put('/profile', [ProfileController::class, 'apiUpdate']);
    Route::post('/profile', [ProfileController::class, 'apiUpdate']);
    Route::get('/profile/detail', [ProfileController::class, 'apiShow']);

    Route::get('/portfolios', [PortfolioController::class, 'apiIndex']);
    Route::post('/portfolios', [PortfolioController::class, 'apiStore']);
    Route::get('/portfolios/{id}', [PortfolioController::class, 'apiShow']);
    Route::delete('/portfolios/{id}', [PortfolioController::class, 'apiDestroy']);

    Route::get('/projects', [ProjectController::class, 'apiIndex']);
    Route::get('/projects/{id}', [ProjectController::class, 'apiShow']);
});
