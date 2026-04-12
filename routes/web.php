<?php

use Illuminate\Support\Facades\Route;

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

// Halaman Login & Registrasi (Placeholder)
Route::get('/masuk', function () {
    return "Halaman Login";
})->name('login');

Route::get('/daftar', function () {
    return view('auth.register-role');
})->name('register');
