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
// CRUD Kategori
Route::post('/admin/kategori', [AdminController::class, 'storeKategori']);
Route::get('/admin/kategori/hapus/{id}', [AdminController::class, 'destroyKategori']);
Route::post('/admin/kategori/update/{id}', [AdminController::class, 'updateKategori']);
// CRUD Siswa
Route::post('/admin/siswa', [AdminController::class, 'storeSiswa']);
Route::get('/admin/siswa/hapus/{nis}', [AdminController::class, 'destroySiswa']);
Route::post('/admin/lokasi', [AdminController::class, 'storeLokasi']);
Route::post('/admin/lokasi/update', [AdminController::class, 'updateLokasi']);
Route::get('/admin/lokasi/hapus/{id}', [AdminController::class, 'destroyLokasi']);

// 4. Dashboard Siswa (Pindah ke SiswaController)
Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard']);
Route::post('/siswa/lapor', [SiswaController::class, 'storeLapor']);
Route::post('/siswa/update-foto', [SiswaController::class, 'updateFoto']);
Route::post('/admin/tanggapi', [AdminController::class, 'tanggapi']);
Route::put('/siswa/lapor/update/{id}', [SiswaController::class, 'updateLapor']);
Route::delete('/siswa/lapor/delete/{id}', [SiswaController::class, 'destroyLapor']);