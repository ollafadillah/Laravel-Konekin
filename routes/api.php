<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');  // Ubah dari 'auth:sanctum' ke 'auth:api'

// Auth Routes API
Route::post('/register', [AuthController::class, 'apiRegister']);
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/auth/google', [GoogleController::class, 'apiGoogleLogin']);

Route::middleware('auth:api')->group(function () {  // Ubah dari 'auth:sanctum' ke 'auth:api'
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']); // Endpoint baru untuk refresh token
});