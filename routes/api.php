<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\EscrowController;


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

    // Creative Worker Dashboard & Profile
    Route::get('/creative/dashboard', [DashboardController::class, 'apiCreativeWorkerDashboard']);
    Route::post('/profile/update', [DashboardController::class, 'apiUpdateProfile']);
    
    // Onboarding
    Route::post('/creative/onboarding', [OnboardingController::class, 'apiStore']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'apiIndex']);
    Route::get('/projects/{id}', [ProjectController::class, 'apiShow']);
    Route::post('/projects/{id}/apply', [ProjectController::class, 'apiApply']);
    Route::get('/creative/projects', [ProjectController::class, 'apiCreativeProgress']);
    Route::post('/creative/projects/{id}/progress', [ProjectController::class, 'apiStoreCreativeProgress']);

    // Portfolio
    Route::get('/portfolios', [PortfolioController::class, 'apiIndex']);
    Route::post('/portfolios', [PortfolioController::class, 'apiStore']);
    Route::get('/portfolios/{id}', [PortfolioController::class, 'apiShow']);
    Route::delete('/portfolios/{id}', [PortfolioController::class, 'apiDestroy']);

    // Ratings
    Route::get('/creative/ratings', [RatingController::class, 'apiIndex']);
    Route::post('/umkm/ratings', [RatingController::class, 'apiStore']);

    // UMKM Specific
    Route::get('/umkm/dashboard', [DashboardController::class, 'apiUMKMDashboard']);
    Route::post('/umkm/projects', [ProjectController::class, 'apiStore']);
    Route::get('/umkm/projects/progress', [ProjectController::class, 'apiUMKMProjectProgress']);
    Route::get('/umkm/projects/{id}/applications', [ProjectController::class, 'apiGetApplications']);
    Route::post('/umkm/projects/{id}/approve/{applicationId}', [ProjectController::class, 'apiApproveApplication']);
    Route::post('/umkm/projects/{id}/pay', [EscrowController::class, 'apiSimulatePayment']);
});