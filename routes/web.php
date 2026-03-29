<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;

// 1. Halaman Utama (Landing Page)
Route::get('/', function () { 
    return view('welcome'); 
});

// 2. Auth (Login & Register)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'registerSiswa']);
Route::get('/logout', [AuthController::class, 'logout']);

// 3. Dashboard Admin (Pindah ke AdminController)
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

// 4. Dashboard Siswa (Pindah ke SiswaController)
Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard']);
Route::post('/siswa/lapor', [SiswaController::class, 'simpanAspirasi']);