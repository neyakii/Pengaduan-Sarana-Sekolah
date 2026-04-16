<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\InputAspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

/**
 * ============================================================
 * CLASS: SiswaController
 * ============================================================
 * Controller ini menangani semua operasi yang dilakukan oleh siswa
 * di dashboard siswa, meliputi:
 * 
 * 1. Menampilkan dashboard siswa dengan data personal dan laporan
 * 2. Mengupdate foto profil siswa
 * 3. CRUD (Create, Read, Update, Delete) laporan pengaduan
 *    - Membuat laporan baru
 *    - Mengedit laporan (hanya jika status masih Menunggu)
 *    - Menghapus/membatalkan laporan (hanya jika status masih Menunggu)
 * 
 * @package App\Http\Controllers
 * @author  Tim Pengembang
 * @version 1.0
 * @since   2026
 * ============================================================
 */
class SiswaController extends Controller
{
    /**
     * ============================================================
     * METHOD: dashboard()
     * ============================================================
     * Menampilkan halaman utama dashboard siswa.
     * 
     * FUNGSI:
     * - Memeriksa apakah siswa sudah login (session 'login_siswa')
     * - Jika belum login, redirect ke halaman login
     * - Mengambil data yang diperlukan untuk ditampilkan:
     *   - Data profil siswa (berdasarkan NIS dari session)
     *   - Seluruh log aktivitas siswa (untuk timeline)
     *   - Data kategori (untuk dropdown form laporan)
     *   - Data lokasi (untuk dropdown form laporan)
     *   - Semua laporan pengaduan milik siswa (dengan relasi aspirasi & lokasi)
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * ============================================================
     */
    public function dashboard() 
    {
        // Cek apakah siswa sudah login, jika tidak redirect ke halaman login
        if (!Session::has('login_siswa')) return redirect('/login');
        
        // Ambil data siswa berdasarkan NIS dari session
        $siswa = Siswa::where('nis', session('nis'))->first();
        
        // Ambil semua log aktivitas siswa (diurutkan dari terbaru ke terlama)
        $logs = LogAktivitas::where('nis', session('nis'))
                ->orderBy('created_at', 'desc')
                ->get();
        
        // Ambil data master untuk dropdown form
        $kategori = Kategori::all();  // Semua kategori fasilitas
        $lokasi = Lokasi::all();      // Semua lokasi fasilitas

        /**
         * Ambil semua pengaduan milik siswa yang sedang login
         * - with(['aspirasi', 'lokasi_relasi']) untuk eager loading (menghindari N+1 query)
         * - where('nis', session('nis')) untuk filter berdasarkan siswa
         * - orderBy('created_at', 'desc') untuk menampilkan yang terbaru di atas
         */
        $pengaduan = InputAspirasi::with(['aspirasi', 'lokasi_relasi'])
                    ->where('nis', session('nis'))
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Tampilkan view dengan semua data yang sudah disiapkan
        return view('siswa.dashboard', compact('siswa', 'pengaduan', 'kategori', 'lokasi', 'logs'));
    }

    /**
     * ============================================================
     * METHOD: updateFoto() - UPDATE FOTO PROFIL
     * ============================================================
     * Mengganti foto profil siswa yang sedang login.
     * 
     * PROSES:
     * 1. Validasi file foto yang diupload
     * 2. Hapus foto profil lama (jika ada) dari storage
     * 3. Simpan foto baru ke folder 'storage/app/public/profile_siswa'
     * 4. Update kolom foto_profile di database
     * 5. Catat aktivitas ke log sistem
     * 
     * VALIDASI:
     * - foto_profile: wajib diisi, harus gambar (jpeg/png/jpg), maksimal 10MB
     *   (Ukuran 10MB dipilih untuk mengakomodasi foto dari HP modern)
     * 
     * @param Request $request Berisi file foto_profile
     * @return \Illuminate\Http\RedirectResponse Redirect kembali dengan pesan sukses/error
     * ============================================================
     */
    public function updateFoto(Request $request) 
    {
        // Validasi file foto yang diupload
        $request->validate([
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:10240'  // max 10MB
        ]);

        // Proses upload jika ada file
        if ($request->hasFile('foto_profile')) {
            // Ambil data siswa dari database
            $siswa = Siswa::where('nis', session('nis'))->first();
            
            // Hapus foto profil lama jika ada (untuk menghemat storage)
            if ($siswa->foto_profile) {
                Storage::disk('public')->delete($siswa->foto_profile);
            }

            // Simpan foto baru ke storage
            $path = $request->file('foto_profile')->store('profile_siswa', 'public');
            
            // Update database dengan path foto baru
            $siswa->update(['foto_profile' => $path]);

            // Catat aktivitas ke log
            LogAktivitas::create([
                'nis' => session('nis'), 
                'aktivitas' => 'Memperbarui foto profil baru'
            ]);
            
            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    /**
     * ============================================================
     * METHOD: storeLapor() - BUAT LAPORAN BARU
     * ============================================================
     * Menyimpan laporan pengaduan baru dari siswa.
     * 
     * PROSES:
     * 1. Validasi semua input dari form
     * 2. Upload foto bukti kerusakan ke storage
     * 3. Simpan data laporan ke database
     * 4. Catat aktivitas ke log sistem
     * 
     * VALIDASI:
     * - id_kategori: wajib dipilih
     * - id_lokasi: wajib dipilih
     * - ket: wajib diisi (deskripsi kerusakan)
     * - foto_kerusakan: wajib, harus gambar (jpeg/png/jpg), maksimal 10MB
     * 
     * PENTING:
     * - Menggunakan try-catch untuk menangani error upload file
     * - Error handling yang baik untuk memberikan pesan yang jelas ke pengguna
     * 
     * @param Request $request Berisi id_kategori, id_lokasi, ket, foto_kerusakan
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses/error
     * ============================================================
     */
    public function storeLapor(Request $request) 
    {
        // Validasi input laporan
        $request->validate([
            'id_kategori' => 'required',      // Kategori fasilitas
            'id_lokasi' => 'required',        // Lokasi kerusakan
            'ket' => 'required',              // Deskripsi kerusakan
            'foto_kerusakan' => 'required|image|mimes:jpeg,png,jpg|max:10240'  // max 10MB
        ], [
            'foto_kerusakan.max' => 'Ukuran file terbaca terlalu besar oleh sistem. Coba kompres foto Anda.',
            'foto_kerusakan.image' => 'File harus berupa gambar (JPG/PNG).',
            'required' => 'Kolom :attribute wajib diisi.'
        ]);

        try {
            // Proses upload file foto jika ada
            if ($request->hasFile('foto_kerusakan')) {
                // Simpan foto ke folder 'storage/app/public/aspirasi'
                $path = $request->file('foto_kerusakan')->store('aspirasi', 'public');
                
                // Simpan data laporan ke database
                InputAspirasi::create([
                    'nis' => session('nis'),              // NIS siswa dari session
                    'id_kategori' => $request->id_kategori,
                    'id_lokasi' => $request->id_lokasi,
                    'ket' => $request->ket,
                    'foto' => $path                        // Path foto yang sudah disimpan
                ]);

                // Ambil nama lokasi untuk keperluan log (lebih informatif)
                $nama_lokasi = Lokasi::find($request->id_lokasi)->nama_lokasi ?? 'Lokasi tidak diketahui';

                // Catat aktivitas ke log sistem
                LogAktivitas::create([
                    'nis' => session('nis'), 
                    'aktivitas' => 'Mengirim laporan pengaduan baru di: ' . $nama_lokasi
                ]);

                return back()->with('success', 'Laporan berhasil dikirim!');
            }
            
            return back()->with('error', 'File foto tidak terbaca. Pastikan form memiliki enctype="multipart/form-data"');
            
        } catch (\Exception $e) {
            // Tangani error yang mungkin terjadi (misal: koneksi database, storage penuh, dll)
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * ============================================================
     * METHOD: updateLapor() - EDIT/UPDATE LAPORAN
     * ============================================================
     * Mengupdate data laporan yang sudah ada (hanya untuk laporan
     * dengan status "Menunggu" - validasi di sisi frontend).
     * 
     * PROSES:
     * 1. Validasi input yang akan diupdate
     * 2. Cek apakah laporan milik siswa yang sedang login (authorization)
     * 3. Update data laporan (kategori, lokasi, deskripsi)
     * 4. Jika ada foto baru, hapus foto lama dan upload foto baru
     * 5. Catat aktivitas ke log sistem
     * 
     * VALIDASI:
     * - id_kategori: wajib dipilih
     * - id_lokasi: wajib dipilih
     * - ket: wajib diisi
     * - foto_kerusakan: OPSIONAL, jika ada harus gambar (jpeg/png/jpg) max 10MB
     * 
     * KEAMANAN:
     * - findOrFail() akan throw exception jika laporan tidak ditemukan
     * - Cek NIS untuk memastikan siswa hanya bisa edit laporannya sendiri
     * 
     * @param Request $request Berisi data yang diupdate
     * @param int $id ID laporan yang akan diedit
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses/error
     * ============================================================
     */
    public function updateLapor(Request $request, $id) 
    {
        // Validasi input yang akan diupdate
        $request->validate([
            'id_kategori' => 'required', 
            'id_lokasi' => 'required', 
            'ket' => 'required',
            'foto_kerusakan' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'  // Opsional
        ]);

        // Cari laporan berdasarkan ID, throw exception jika tidak ditemukan
        $pengaduan = InputAspirasi::findOrFail($id);
        
        /**
         * AUTHORIZATION: Cek apakah laporan ini milik siswa yang sedang login
         * Jika bukan, tolak akses
         */
        if($pengaduan->nis != session('nis')) {
            return back()->with('error', 'Akses ditolak.');
        }

        // Update data laporan
        $pengaduan->id_kategori = $request->id_kategori;
        $pengaduan->id_lokasi = $request->id_lokasi;
        $pengaduan->ket = $request->ket;

        // Proses upload foto baru (jika ada)
        if ($request->hasFile('foto_kerusakan')) {
            // Hapus foto lama agar storage tidak penuh
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            // Simpan foto baru
            $path = $request->file('foto_kerusakan')->store('aspirasi', 'public');
            $pengaduan->foto = $path;
        }

        // Simpan perubahan ke database
        $pengaduan->save();

        // Ambil nama lokasi untuk log (lebih informatif)
        $nama_lokasi = Lokasi::find($request->id_lokasi)->nama_lokasi ?? 'Lokasi tidak diketahui';

        // Catat aktivitas ke log
        LogAktivitas::create([
            'nis' => session('nis'),
            'aktivitas' => 'Memperbarui data laporan di: ' . $nama_lokasi
        ]);

        return back()->with('success', 'Laporan berhasil diperbarui!');
    }

    /**
     * ============================================================
     * METHOD: destroyLapor() - HAPUS/BATALKAN LAPORAN
     * ============================================================
     * Menghapus laporan pengaduan (hanya untuk laporan dengan
     * status "Menunggu" - validasi di sisi frontend).
     * 
     * PROSES:
     * 1. Cari laporan berdasarkan ID
     * 2. Cek apakah laporan milik siswa yang sedang login (authorization)
     * 3. Hapus file foto dari storage (untuk menghemat tempat)
     * 4. Hapus record laporan dari database
     * 5. Catat aktivitas ke log sistem
     * 
     * KEAMANAN:
     * - findOrFail() akan throw exception jika laporan tidak ditemukan
     * - Cek NIS untuk memastikan siswa hanya bisa hapus laporannya sendiri
     * 
     * @param int $id ID laporan yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses/error
     * ============================================================
     */
    public function destroyLapor($id) 
    {
        // Cari laporan berdasarkan ID
        $pengaduan = InputAspirasi::findOrFail($id);
        
        /**
         * AUTHORIZATION: Cek apakah laporan ini milik siswa yang sedang login
         * Jika bukan, tolak akses
         */
        if($pengaduan->nis != session('nis')) {
            return back()->with('error', 'Akses ditolak.');
        }

        /**
         * Hapus file foto dari storage
         * Penting: Hapus file TERLEBIH DAHULU sebelum menghapus record database
         * untuk mencegah orphan files (file tanpa pemilik)
         */
        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        // Ambil nama lokasi untuk keperluan log (sebelum record dihapus)
        $lokasi_lama = Lokasi::find($pengaduan->id_lokasi)->nama_lokasi ?? 'Lokasi tidak diketahui';
        
        // Hapus record dari database
        $pengaduan->delete();

        // Catat aktivitas ke log
        LogAktivitas::create([
            'nis' => session('nis'),
            'aktivitas' => 'Membatalkan/Menghapus laporan di: ' . $lokasi_lama
        ]);

        return back()->with('success', 'Laporan berhasil dibatalkan.');
    }
}

