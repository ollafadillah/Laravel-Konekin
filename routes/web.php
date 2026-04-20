<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

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

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register-role', function () {
    return view('auth.register-role'); // Halaman pilih role
})->name('register.role');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Dashboard & Profile
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/umkm', [DashboardController::class, 'umkmDashboard'])->name('dashboard.umkm');
    Route::get('/dashboard/creative', [DashboardController::class, 'creativeWorkerDashboard'])->name('dashboard.creative');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
