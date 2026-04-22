<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PortfolioController;

// Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

use App\Http\Controllers\CreatorController;

// Halaman Cari Kreator
Route::get('/kreator', [CreatorController::class, 'index'])->name('kreator.index');

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

// Dashboard & Profile & Projects & Portfolio
Route::middleware('auth')->group(function () {
    Route::get('/kreator/{id}', [CreatorController::class, 'show'])->name('kreator.show');

    Route::get('/dashboard/umkm', [DashboardController::class, 'umkmDashboard'])->name('dashboard.umkm');
    Route::get('/dashboard/creative', [DashboardController::class, 'creativeWorkerDashboard'])->name('dashboard.creative');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Project Routes
    Route::get('/cari-proyek', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/proyek/buat', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/proyek', [ProjectController::class, 'store'])->name('projects.store');

    // Portfolio Routes
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::delete('/portfolio/{id}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
});
