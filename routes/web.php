<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman Cari Kreator (Placeholder)
Route::get('/kreator', function () {
    return "Halaman Cari Kreator - Segera Hadir";
})->name('kreator.index');

// Halaman Proyek UMKM (Placeholder)
Route::get('/umkm', function () {
    return "Halaman Proyek UMKM - Segera Hadir";
})->name('umkm.index');

// Halaman Tentang Kami (Placeholder)
Route::get('/tentang-kami', function () {
    return "Halaman Tentang Kami - Segera Hadir";
})->name('about');

// ===== AUTHENTICATION ROUTES =====
Route::prefix('auth')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login.show')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login')->middleware('guest');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
});

// ===== DASHBOARD ROUTES (Protected) =====
Route::middleware('auth')->group(function () {
    // Creative Worker Dashboard
    Route::prefix('dashboard/creative')->group(function () {
        Route::get('/', [DashboardController::class, 'creativeWorkerDashboard'])->name('dashboard.creative');
    });

    // UMKM Dashboard
    Route::prefix('dashboard/umkm')->group(function () {
        Route::get('/', [DashboardController::class, 'umkmDashboard'])->name('dashboard.umkm');
    });

    // Update Profile (for both types)
    Route::put('/profile/update', [DashboardController::class, 'updateProfile'])->name('dashboard.update-profile');
});

// Redirect login/register routes to auth
Route::get('/masuk', function () {
    return redirect(route('auth.login.show'));
})->name('login');

Route::get('/daftar', function () {
    return redirect(route('auth.register.show'));
})->name('register');
