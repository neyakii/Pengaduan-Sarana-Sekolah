<?php

/**
 * ============================================================
 * FILE: routes/web.php
 * ============================================================
 * File ini mendefinisikan semua URL endpoint (routes) yang tersedia
 * dalam aplikasi Pengaduan Sarana.
 * 
 * SETIAP ROUTE MEMILIKI:
 * - HTTP Method (GET, POST, PUT, DELETE)
 * - URL Pattern (contoh: /admin/dashboard)
 * - Controller & Method yang menangani request
 * 
 * STRUKTUR ROUTING:
 * 1. Halaman Utama (Landing Page / Welcome)
 * 2. Authentication (Login, Register, Logout)
 * 3. Admin Dashboard & CRUD Operations
 * 4. Siswa Dashboard & Laporan Operations
 * 
 * @package Routes
 * @author  Tim Pengembang
 * @version 1.0
 * @since   2026
 * ============================================================
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN UTAMA (LANDING PAGE / WELCOME)
|--------------------------------------------------------------------------
| Route untuk halaman depan aplikasi yang dapat diakses oleh publik.
| Menampilkan informasi tentang sistem pengaduan sarana.
| Method: GET
| URL: /
| View: welcome.blade.php
*/
Route::get('/', function () { 
    return view('welcome'); 
});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATION (LOGIN & REGISTER)
|--------------------------------------------------------------------------
| Routes untuk proses autentikasi pengguna.
| Mendukung dua role: ADMIN dan SISWA
|--------------------------------------------------------------------------
*/

/**
 * Halaman Login
 * Method: GET
 * URL: /login
 * View: auth.login.blade.php
 * Nama Route: 'login' (digunakan untuk redirect setelah middleware auth)
 */
Route::get('/login', function () { 
    return view('auth.login'); 
})->name('login');

/**
 * Halaman Registrasi Siswa
 * Method: GET
 * URL: /register
 * View: auth.register.blade.php
 */
Route::get('/register', function () { 
    return view('auth.register'); 
});

/**
 * Proses Login (Admin atau Siswa)
 * Method: POST
 * URL: /login
 * Controller: AuthController@login
 * Request Body: id_user (username/NIS), password
 */
Route::post('/login', [AuthController::class, 'login']);

/**
 * Proses Registrasi Siswa Baru
 * Method: POST
 * URL: /register
 * Controller: AuthController@registerSiswa
 * Request Body: nis, nama, kelas, password, foto_profile (optional)
 */
Route::post('/register', [AuthController::class, 'registerSiswa']);

/**
 * Proses Logout (Admin atau Siswa)
 * Method: GET
 * URL: /logout
 * Controller: AuthController@logout
 * Menghapus semua session dan redirect ke halaman utama
 */
Route::get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| 3. ADMIN DASHBOARD & CRUD OPERATIONS
|--------------------------------------------------------------------------
| Routes untuk administrator sistem.
| SEMUA ROUTE DI BAWAH INI HARUS DIAKSES OLEH ADMIN YANG SUDAH LOGIN.
| 
| FITUR YANG TERSEDIA:
| - Dashboard utama admin
| - CRUD Kategori Fasilitas
| - CRUD Data Siswa
| - CRUD Lokasi Fasilitas
| - Menanggapi laporan siswa (update status & feedback)
|--------------------------------------------------------------------------
*/

/**
 * Dashboard Admin
 * Method: GET
 * URL: /admin/dashboard
 * Controller: AdminController@dashboard
 * Menampilkan statistik, daftar laporan, dan log aktivitas
 */
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

/* ==================== CRUD KATEGORI ==================== */

/**
 * Menambah Kategori Baru
 * Method: POST
 * URL: /admin/kategori
 * Controller: AdminController@storeKategori
 * Request Body: ket_kategori (nama kategori)
 */
Route::post('/admin/kategori', [AdminController::class, 'storeKategori']);

/**
 * Menghapus Kategori
 * Method: GET (sebaiknya diubah ke DELETE untuk RESTful)
 * URL: /admin/kategori/hapus/{id}
 * Controller: AdminController@destroyKategori
 * Parameter: id (ID kategori yang akan dihapus)
 * CATATAN: Hanya bisa dihapus jika tidak ada laporan yang menggunakan kategori ini
 */
Route::get('/admin/kategori/hapus/{id}', [AdminController::class, 'destroyKategori']);

/**
 * Mengupdate Kategori
 * Method: POST
 * URL: /admin/kategori/update
 * Controller: AdminController@updateKategori
 * Request Body: id_kategori, ket_kategori (nama kategori baru)
 */
Route::post('/admin/kategori/update', [AdminController::class, 'updateKategori']);

/* ==================== CRUD SISWA ==================== */

/**
 * Menambah Data Siswa Baru
 * Method: POST
 * URL: /admin/siswa
 * Controller: AdminController@storeSiswa
 * Request Body: nis, nama, kelas, password
 */
Route::post('/admin/siswa', [AdminController::class, 'storeSiswa']);

/**
 * Menghapus Data Siswa
 * Method: GET (sebaiknya diubah ke DELETE untuk RESTful)
 * URL: /admin/siswa/hapus/{nis}
 * Controller: AdminController@destroySiswa
 * Parameter: nis (NIS siswa yang akan dihapus)
 * CATATAN: Hanya bisa dihapus jika siswa belum pernah membuat laporan
 */
Route::get('/admin/siswa/hapus/{nis}', [AdminController::class, 'destroySiswa']);

/* ==================== CRUD LOKASI ==================== */

/**
 * Menambah Lokasi Baru
 * Method: POST
 * URL: /admin/lokasi
 * Controller: AdminController@storeLokasi
 * Request Body: nama_lokasi (contoh: Gedung A, Laboratorium, dll)
 */
Route::post('/admin/lokasi', [AdminController::class, 'storeLokasi']);

/**
 * Mengupdate Nama Lokasi
 * Method: POST
 * URL: /admin/lokasi/update
 * Controller: AdminController@updateLokasi
 * Request Body: id_lokasi, nama_lokasi (nama baru)
 */
Route::post('/admin/lokasi/update', [AdminController::class, 'updateLokasi']);

/**
 * Menghapus Lokasi
 * Method: GET (sebaiknya diubah ke DELETE untuk RESTful)
 * URL: /admin/lokasi/hapus/{id}
 * Controller: AdminController@destroyLokasi
 * Parameter: id (ID lokasi yang akan dihapus)
 * CATATAN: Hanya bisa dihapus jika tidak ada laporan yang menggunakan lokasi ini
 */
Route::get('/admin/lokasi/hapus/{id}', [AdminController::class, 'destroyLokasi']);

/* ==================== TANGGAPI LAPORAN ==================== */

/**
 * Menanggapi / Memproses Laporan Siswa
 * Method: POST
 * URL: /admin/tanggapi
 * Controller: AdminController@tanggapi
 * Request Body: 
 *   - id_pelaporan: ID laporan
 *   - status: Menunggu/Proses/Selesai
 *   - feedback: Teks tanggapan admin
 *   - foto_bukti: File foto bukti perbaikan (optional)
 */
Route::post('/admin/tanggapi', [AdminController::class, 'tanggapi']);

/*
|--------------------------------------------------------------------------
| 4. SISWA DASHBOARD & LAPORAN OPERATIONS
|--------------------------------------------------------------------------
| Routes untuk siswa (pengguna biasa).
| SEMUA ROUTE DI BAWAH INI HARUS DIAKSES OLEH SISWA YANG SUDAH LOGIN.
| 
| FITUR YANG TERSEDIA:
| - Dashboard siswa (lihat profil & riwayat laporan)
| - Update foto profil
| - CRUD Laporan Pengaduan (Create, Update, Delete)
|--------------------------------------------------------------------------
*/

/**
 * Dashboard Siswa
 * Method: GET
 * URL: /siswa/dashboard
 * Controller: SiswaController@dashboard
 * Menampilkan profil siswa, riwayat laporan, dan statistik pribadi
 */
Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard']);

/**
 * Update Foto Profil Siswa
 * Method: POST
 * URL: /siswa/update-foto
 * Controller: SiswaController@updateFoto
 * Request Body: foto_profile (file gambar)
 * CATATAN: Maksimal ukuran file 10MB, format JPG/PNG
 */
Route::post('/siswa/update-foto', [SiswaController::class, 'updateFoto']);

/**
 * Membuat Laporan Pengaduan Baru
 * Method: POST
 * URL: /siswa/lapor
 * Controller: SiswaController@storeLapor
 * Request Body:
 *   - id_kategori: ID kategori fasilitas
 *   - id_lokasi: ID lokasi kejadian
 *   - ket: Deskripsi kerusakan
 *   - foto_kerusakan: File foto bukti
 */
Route::post('/siswa/lapor', [SiswaController::class, 'storeLapor']);

/**
 * Mengupdate Laporan Pengaduan (Edit)
 * Method: PUT
 * URL: /siswa/lapor/update/{id}
 * Controller: SiswaController@updateLapor
 * Parameter: id (ID laporan yang akan diedit)
 * Request Body: id_kategori, id_lokasi, ket, foto_kerusakan (optional)
 * CATATAN: Hanya bisa diedit jika status laporan masih "Menunggu"
 */
Route::put('/siswa/lapor/update/{id}', [SiswaController::class, 'updateLapor']);

/**
 * Menghapus / Membatalkan Laporan Pengaduan
 * Method: DELETE
 * URL: /siswa/lapor/delete/{id}
 * Controller: SiswaController@destroyLapor
 * Parameter: id (ID laporan yang akan dihapus)
 * CATATAN: Hanya bisa dihapus jika status laporan masih "Menunggu"
 */
Route::delete('/siswa/lapor/delete/{id}', [SiswaController::class, 'destroyLapor']);

