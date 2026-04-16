<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Admin;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * ============================================================
 * CLASS: AuthController
 * ============================================================
 * Controller ini menangani semua proses otentikasi (authentication)
 * dalam sistem, meliputi:
 * 1. Registrasi akun siswa baru
 * 2. Login untuk admin dan siswa
 * 3. Logout untuk kedua jenis pengguna
 * 
 * Sistem mendukung dua role pengguna:
 * - Admin: menggunakan username dan password
 * - Siswa: menggunakan NIS (Nomor Induk Siswa) dan password
 * 
 * @package App\Http\Controllers
 * @author  Tim Pengembang
 * @version 1.0
 * @since   2026
 * ============================================================
 */
class AuthController extends Controller
{
    /**
     * ============================================================
     * METHOD: registerSiswa()
     * ============================================================
     * Mendaftarkan akun siswa baru ke dalam sistem.
     * 
     * PROSES:
     * 1. Validasi data input dari form registrasi
     * 2. Upload foto profil (jika ada) ke folder storage/profiles
     * 3. Simpan data siswa ke database dengan password ter-hash
     * 4. Redirect ke halaman login dengan pesan sukses
     * 
     * VALIDASI:
     * - nis: wajib diisi dan harus UNIK (tidak boleh duplikat)
     * - nama: wajib diisi
     * - password: wajib diisi minimal 3 karakter
     * - foto_profile: opsional (tidak wajib)
     * 
     * @param Request $request Berisi data: nis, nama, kelas, password, foto_profile
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman login
     * ============================================================
     */
    public function registerSiswa(Request $request) {
        // Validasi input registrasi siswa
        // unique:siswa,nis memastikan tidak ada NIS yang sama di database
        $request->validate([
            'nis' => 'required|unique:siswa,nis',    // NIS harus unik
            'nama' => 'required',                     // Nama wajib diisi
            'password' => 'required|min:3'            // Password minimal 3 karakter
        ]);

        /**
         * LOGIC UPLOAD FOTO PROFIL
         * - Jika siswa mengupload foto profil:
         *   1. Ambil file dari request
         *   2. Buat nama file unik: NIS_timestamp.extension
         *      (Contoh: 12345_1699123456.jpg)
         *   3. Pindahkan file ke folder 'public/storage/profiles'
         *   4. Simpan path relatif ke database
         * - Jika tidak upload foto, set null
         */
        $nama_foto = null;
        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            // Membuat nama file unik untuk menghindari konflik
            $nama_foto = $request->nis . '_' . time() . '.' . $file->getClientOriginalExtension();
            // Pindahkan file ke folder storage/profiles (public disk)
            $file->move(public_path('storage/profiles'), $nama_foto);
        }

        /**
         * SIMPAN DATA SISWA KE DATABASE
         * - Hash::make() digunakan untuk mengenkripsi password (bcrypt)
         * - Foto profil disimpan dengan path relatif 'profiles/nama_file'
         */
        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'password' => Hash::make($request->password),     // Enkripsi password
            'foto_profile' => $nama_foto ? 'profiles/' . $nama_foto : null,
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Berhasil daftar, silakan login!');
    }

    /**
     * ============================================================
     * METHOD: login()
     * ============================================================
     * Memproses login untuk kedua role (admin dan siswa).
     * 
     * PROSES:
     * 1. Cek apakah input id_user adalah admin (username)
     * 2. Jika bukan admin, cek apakah id_user adalah siswa (NIS)
     * 3. Verifikasi password menggunakan Hash::check()
     * 4. Jika berhasil, simpan session dan catat log aktivitas
     * 5. Redirect ke dashboard masing-masing role
     * 
     * SESSION VARIABLES:
     * Untuk Admin:
     *   - login_admin: true
     *   - username: username admin
     * 
     * Untuk Siswa:
     *   - login_siswa: true
     *   - nis: NIS siswa
     *   - nama: nama siswa
     * 
     * @param Request $request Berisi id_user (username/NIS) dan password
     * @return \Illuminate\Http\RedirectResponse Redirect ke dashboard atau kembali ke login
     * ============================================================
     */
    public function login(Request $request) {
        
        /**
         * ========================================================
         * CEK LOGIN ADMIN
         * ========================================================
         * Mencari admin berdasarkan username yang diinput
         */
        $admin = Admin::where('username', $request->id_user)->first();
        
        // Verifikasi: apakah admin ditemukan DAN password cocok
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Set session untuk admin
            Session::put('login_admin', true);
            Session::put('username', $admin->username);
            
            // Catat aktivitas login ke log sistem
            LogAktivitas::create([
                'username' => $admin->username, 
                'aktivitas' => 'Login ke sistem'
            ]);
            
            // Redirect ke dashboard admin
            return redirect('/admin/dashboard');
        }

        /**
         * ========================================================
         * CEK LOGIN SISWA
         * ========================================================
         * Mencari siswa berdasarkan NIS yang diinput
         */
        $siswa = Siswa::where('nis', $request->id_user)->first();
        
        // Verifikasi: apakah siswa ditemukan DAN password cocok
        if ($siswa && Hash::check($request->password, $siswa->password)) {
            // Set session untuk siswa
            Session::put('login_siswa', true);
            Session::put('nis', $siswa->nis);
            Session::put('nama', $siswa->nama);
            
            // Catat aktivitas login ke log sistem (menggunakan NIS sebagai identifier)
            LogAktivitas::create([
                'nis' => $siswa->nis, 
                'aktivitas' => 'Login ke sistem'
            ]);
            
            // Redirect ke dashboard siswa
            return redirect('/siswa/dashboard');
        }
        
        /**
         * ========================================================
         * LOGIN GAGAL
         * ========================================================
         * Jika tidak ada admin maupun siswa yang cocok,
         * atau password salah, kembali ke halaman login dengan pesan error
         */
        return back()->with('error', 'Login Gagal!');
    }

    /**
     * ============================================================
     * METHOD: logout()
     * ============================================================
     * Memproses logout untuk kedua role (admin dan siswa).
     * 
     * PROSES:
     * 1. Cek role pengguna yang sedang login (admin atau siswa)
     * 2. Catat aktivitas logout ke log sistem (sebelum session dihapus)
     * 3. Hapus semua data session (Session::flush())
     * 4. Redirect ke halaman utama (/)
     * 
     * PENTING:
     * - Log aktivitas dicatat SEBELUM session dihapus
     *   karena kita masih membutuhkan data session untuk mencatat siapa yang logout
     * - Setelah session dihapus, semua data session (termasuk login status) hilang
     * 
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman utama
     * ============================================================
     */
    public function logout() {
        
        /**
         * CATAT AKTIVITAS LOGOUT BERDASARKAN ROLE
         * - Jika admin yang logout, gunakan session 'username'
         * - Jika siswa yang logout, gunakan session 'nis'
         * - Log dicatat sebelum session dihapus
         */
        if(Session::has('login_admin')) {
            // Admin logout: catat dengan username
            LogAktivitas::create([
                'username' => session('username'), 
                'aktivitas' => 'Logout dari sistem'
            ]);
        } else if(Session::has('login_siswa')) {
            // Siswa logout: catat dengan NIS
            LogAktivitas::create([
                'nis' => session('nis'), 
                'aktivitas' => 'Logout dari sistem'
            ]);
        }
        
        /**
         * HAPUS SEMUA SESSION
         * Session::flush() menghapus semua data session yang tersimpan
         * Ini akan menghilangkan status login dan data pengguna lainnya
         */
        Session::flush();
        
        // Redirect ke halaman utama (landing page)
        return redirect('/');
    }
}
