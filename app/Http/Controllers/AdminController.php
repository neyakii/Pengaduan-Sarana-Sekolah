<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\InputAspirasi;
use App\Models\Aspirasi;
use App\Models\Kategori; 
use App\Models\Siswa;   
use App\Models\Lokasi;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

/**
 * ============================================================
 * CLASS: AdminController
 * ============================================================
 * Controller ini menangani semua operasi di halaman admin dashboard,
 * termasuk:
 * 1. CRUD (Create, Read, Update, Delete) untuk Kategori
 * 2. CRUD untuk Data Siswa
 * 3. CRUD untuk Lokasi Fasilitas
 * 4. Menanggapi / memproses laporan pengaduan dari siswa
 * 5. Pencatatan log aktivitas admin
 * 
 * @package App\Http\Controllers
 * @author  Tim Pengembang
 * @version 1.0
 * @since   2026
 * ============================================================
 */
class AdminController extends Controller
{
    /**
     * ============================================================
     * METHOD: dashboard()
     * ============================================================
     * Menampilkan halaman utama dashboard admin.
     * 
     * FUNGSI:
     * - Memeriksa apakah admin sudah login (session 'login_admin')
     * - Jika belum login, redirect ke halaman login
     * - Mengambil data yang diperlukan untuk ditampilkan di dashboard:
     *   - 10 log aktivitas terbaru (untuk timeline)
     *   - Semua data laporan pengaduan (dengan relasi siswa, kategori, aspirasi)
     *   - Semua data kategori, siswa, lokasi (untuk dropdown dan tabel CRUD)
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * ============================================================
     */
    public function dashboard() {
        // Cek apakah admin sudah login, jika tidak redirect ke halaman login
        if (!Session::has('login_admin')) return redirect('/login');
        
        // Ambil 10 log aktivitas terbaru (diurutkan dari yang terbaru)
        $logs = LogAktivitas::orderBy('created_at', 'desc')->take(10)->get();
        
        // Ambil semua laporan dengan relasi yang diperlukan
        // with() digunakan untuk menghindari N+1 query problem
        $laporan = InputAspirasi::with(['siswa', 'kategori', 'aspirasi'])
                    ->orderBy('created_at', 'desc')->get();
        
        // Ambil semua data master (untuk dropdown dan tabel CRUD)
        $kategori = Kategori::all();   // Data kategori fasilitas
        $siswa = Siswa::all();          // Data seluruh siswa
        $lokasi = Lokasi::all();        // Data lokasi fasilitas

        // Tampilkan view admin.dashboard dengan data yang sudah disiapkan
        return view('admin.dashboard', compact('logs', 'laporan', 'kategori', 'siswa', 'lokasi'));
    }

    /**
     * ============================================================
     * SECTION: CRUD KATEGORI
     * ============================================================
     * Method untuk menambah, mengubah, dan menghapus data kategori
     * Kategori digunakan untuk mengklasifikasikan jenis fasilitas yang rusak
     * ============================================================
     */

    /**
     * METHOD: storeKategori()
     * Menyimpan data kategori baru ke database
     * 
     * VALIDASI:
     * - ket_kategori: wajib diisi dan harus UNIK (tidak boleh ada duplikat)
     * 
     * @param Request $request Berisi data dari form (ket_kategori)
     * @return \Illuminate\Http\RedirectResponse Redirect kembali dengan pesan sukses
     */
    public function storeKategori(Request $request) {
        // Validasi input: nama kategori harus unik (tidak boleh sama dengan yang sudah ada)
        $request->validate([
            'ket_kategori' => 'required|unique:kategori,ket_kategori'
        ], [
            'ket_kategori.unique' => 'Kategori ini sudah ada!'  // Pesan error kustom
        ]);
        
        // Simpan data kategori ke database
        Kategori::create($request->only('ket_kategori'));
        
        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * METHOD: updateKategori()
     * Mengupdate data kategori yang sudah ada
     * 
     * VALIDASI:
     * - id_kategori: wajib ada (untuk identifikasi record yang akan diupdate)
     * - ket_kategori: wajib diisi dan UNIK kecuali untuk record dengan ID yang sama
     * 
     * @param Request $request Berisi id_kategori dan ket_kategori baru
     * @return \Illuminate\Http\RedirectResponse Redirect kembali dengan pesan sukses
     */
    public function updateKategori(Request $request) {
        // Validasi: nama kategori baru harus unik, kecuali untuk kategori yang sedang diedit
        $request->validate([
            'id_kategori' => 'required',
            'ket_kategori' => 'required|unique:kategori,ket_kategori,' . $request->id_kategori . ',id_kategori'
        ], [
            'ket_kategori.unique' => 'Nama kategori sudah digunakan!'  // Pesan error kustom
        ]);

        // Update data kategori berdasarkan id_kategori
        Kategori::where('id_kategori', $request->id_kategori)->update([
            'ket_kategori' => $request->ket_kategori
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * METHOD: destroyKategori()
     * Menghapus data kategori dari database
     * 
     * PENGECEKAN:
     - Sebelum menghapus, cek apakah kategori ini sudah digunakan dalam laporan siswa.
     * - Jika sudah digunakan, hapus ditolak untuk menjaga integritas data.
     * 
     * @param int $id ID kategori yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses atau error
     */
    public function destroyKategori($id) {
        // Cek apakah kategori ini sudah digunakan di tabel input_aspirasi (laporan siswa)
        $terpakai = InputAspirasi::where('id_kategori', $id)->exists();

        // Jika sudah digunakan, tolak penghapusan dan beri pesan error
        if ($terpakai) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah digunakan dalam laporan siswa.');
        }

        // Jika tidak digunakan, hapus kategori
        Kategori::destroy($id);

        // Catat aktivitas penghapusan ke log
        LogAktivitas::create([
            'username' => session('username'),  // Nama admin yang sedang login
            'aktivitas' => 'Menghapus kategori (ID: ' . $id . ')'
        ]);

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * ============================================================
     * SECTION: CRUD DATA SISWA
     * ============================================================
     * Method untuk menambah dan menghapus data siswa
     * (Update siswa tidak disediakan dalam controller ini)
     * ============================================================
     */

    /**
     * METHOD: storeSiswa()
     * Mendaftarkan akun siswa baru ke dalam sistem
     * 
     * VALIDASI:
     * - nis: wajib diisi dan UNIK (primary key di tabel siswa)
     * - nama: wajib diisi
     * - password: wajib diisi (akan di-hash sebelum disimpan)
     * 
     * @param Request $request Berisi nis, nama, kelas, password
     * @return \Illuminate\Http\RedirectResponse Redirect kembali dengan pesan sukses
     */
    public function storeSiswa(Request $request) {
        // Validasi input siswa
        $request->validate([
            'nis' => 'required|unique:siswa,nis',      // NIS harus unik
            'nama' => 'required',                       // Nama wajib diisi
            'password' => 'required'                    // Password wajib diisi
        ], [
            'nis.unique' => 'NIS ini sudah terdaftar!'  // Pesan error kustom
        ]);

        // Simpan data siswa dengan password yang di-hash (enkripsi)
        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'password' => Hash::make($request->password),  // Hash password untuk keamanan
        ]);

        // Catat aktivitas penambahan siswa ke log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Mendaftarkan siswa baru: ' . $request->nama
        ]);

        return back()->with('success', 'Siswa berhasil didaftarkan!');
    }

    /**
     * METHOD: destroySiswa()
     * Menghapus akun siswa dari database
     * 
     * PENGECEKAN:
     * - Cek apakah siswa ini sudah pernah membuat laporan.
     * - Jika sudah, hapus ditolak untuk menjaga histori laporan.
     * 
     * @param string $nis NIS siswa yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses atau error
     */
    public function destroySiswa($nis) {
        // Cek apakah siswa ini sudah pernah melapor (memiliki riwayat laporan)
        $terpakai = InputAspirasi::where('nis', $nis)->exists();

        // Jika sudah pernah melapor, tolak penghapusan
        if ($terpakai) {
            return back()->with('error', 'Data siswa tidak bisa dihapus karena siswa ini memiliki riwayat laporan.');
        }

        // Jika belum pernah melapor, hapus akun siswa
        Siswa::where('nis', $nis)->delete();

        // Catat aktivitas penghapusan ke log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menghapus akun siswa dengan NIS: ' . $nis
        ]);

        return back()->with('success', 'Akun siswa berhasil dihapus!');
    }

    /**
     * ============================================================
     * SECTION: CRUD LOKASI
     * ============================================================
     * Method untuk menambah, mengubah, dan menghapus data lokasi fasilitas
     * Lokasi digunakan untuk menentukan di mana fasilitas berada (Gedung A, Lapangan, dll)
     * ============================================================
     */

    /**
     * METHOD: storeLokasi()
     * Menambahkan lokasi baru ke database
     * 
     * VALIDASI:
     * - nama_lokasi: wajib diisi dan UNIK
     * 
     * @param Request $request Berisi nama_lokasi
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses
     */
    public function storeLokasi(Request $request) {
        // Validasi: nama lokasi harus unik (tidak boleh duplikat)
        $request->validate([
            'nama_lokasi' => 'required|unique:lokasi,nama_lokasi'
        ], [
            'nama_lokasi.unique' => 'Lokasi ini sudah ada!'
        ]);
        
        // Simpan data lokasi ke database
        Lokasi::create($request->all());

        // Catat aktivitas penambahan lokasi ke log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menambah lokasi baru: ' . $request->nama_lokasi
        ]);

        return back()->with('success', 'Lokasi berhasil ditambahkan');
    }

    /**
     * METHOD: updateLokasi()
     * Mengupdate nama lokasi yang sudah ada
     * 
     * VALIDASI:
     * - id_lokasi: wajib ada (identifikasi record)
     * - nama_lokasi: wajib diisi
     * 
     * @param Request $request Berisi id_lokasi dan nama_lokasi baru
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses
     */
    public function updateLokasi(Request $request) {
        // Validasi input
        $request->validate([
            'id_lokasi' => 'required',   // ID lokasi harus ada
            'nama_lokasi' => 'required'  // Nama lokasi baru wajib diisi
        ]);

        // Update nama lokasi berdasarkan ID
        Lokasi::where('id_lokasi', $request->id_lokasi)->update([
            'nama_lokasi' => $request->nama_lokasi
        ]);

        // Catat aktivitas perubahan lokasi ke log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Mengubah nama lokasi menjadi: ' . $request->nama_lokasi
        ]);

        return back()->with('success', 'Lokasi berhasil diperbarui');
    }

    /**
     * METHOD: destroyLokasi()
     * Menghapus data lokasi dari database
     * 
     * PENGECEKAN:
     * - Cek apakah lokasi ini sudah digunakan dalam laporan siswa.
     * - Jika sudah, hapus ditolak untuk menjaga integritas data.
     * 
     * @param int $id ID lokasi yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses atau error
     */
    public function destroyLokasi($id) {
        // Cek apakah lokasi ini sudah digunakan di tabel input_aspirasi (laporan siswa)
        $terpakai = InputAspirasi::where('id_lokasi', $id)->exists();

        // Jika sudah digunakan, tolak penghapusan
        if ($terpakai) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena sudah digunakan dalam laporan.');
        }

        // Jika tidak digunakan, hapus lokasi
        Lokasi::where('id_lokasi', $id)->delete();

        // Catat aktivitas penghapusan lokasi ke log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menghapus lokasi (ID: ' . $id . ')'
        ]);

        return back()->with('success', 'Lokasi berhasil dihapus');
    }

    /**
     * ============================================================
     * SECTION: TANGGAPI / PROSES LAPORAN
     * ============================================================
     * Method untuk memberikan tanggapan/respon terhadap laporan siswa
     * dan mengupdate status laporan (Menunggu -> Proses -> Selesai)
     * ============================================================
     */

    /**
     * METHOD: tanggapi()
     * Menyimpan atau mengupdate tanggapan admin terhadap laporan siswa
     * 
     * FITUR:
     * - Mengubah status laporan (Menunggu/Proses/Selesai)
     * - Menambahkan feedback/tanggapan dari admin
     * - Upload bukti foto perbaikan (opsional)
     * - Menghapus foto lama jika diganti dengan yang baru
     * 
     * VALIDASI:
     * - id_pelaporan: wajib ada (identifikasi laporan)
     * - status: wajib dipilih
     * - feedback: wajib diisi
     * - foto_bukti: optional, jika ada harus file image (jpeg,png,jpg) maksimal 2MB
     * 
     * @param Request $request Berisi id_pelaporan, status, feedback, foto_bukti
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses
     */
    public function tanggapi(Request $request)
    {
        // Validasi input dari form tanggapan
        $request->validate([
            'id_pelaporan' => 'required',           // ID laporan harus ada
            'status' => 'required',                 // Status harus dipilih
            'feedback' => 'required',               // Feedback/tanggapan harus diisi
            'foto_bukti' => 'image|mimes:jpeg,png,jpg|max:2048'  // Foto opsional dengan batasan
        ]);

        /**
         * Mencari data aspirasi berdasarkan id_pelaporan
         * firstOrNew() akan mencari data yang sudah ada,
         * jika tidak ditemukan akan membuat instance baru (belum disimpan)
         */
        $aspirasi = Aspirasi::firstOrNew(['id_pelaporan' => $request->id_pelaporan]);
        
        // Set nilai status dan feedback dari form
        $aspirasi->status = $request->status;
        $aspirasi->feedback = $request->feedback;

        /**
         * LOGIC UPLOAD FOTO BUKTI PERBAIKAN
         * - Jika admin mengupload foto baru:
         *   1. Hapus foto lama jika ada (untuk menghemat storage)
         *   2. Simpan foto baru ke folder 'storage/app/public/bukti_aspirasi'
         *   3. Simpan path-nya ke database
         */
        if ($request->hasFile('foto_bukti')) {
            // Hapus foto lama jika ada di storage (public disk)
            if ($aspirasi->foto && \Storage::disk('public')->exists($aspirasi->foto)) {
                \Storage::disk('public')->delete($aspirasi->foto);
            }

            // Simpan foto baru ke storage
            $path = $request->file('foto_bukti')->store('bukti_aspirasi', 'public');
            $aspirasi->foto = $path; 
        }

        // Simpan semua perubahan ke database
        $aspirasi->save();
        
        /**
         * Catat aktivitas ke log sistem dengan detail yang informatif
         * Memudahkan tracing jika ada masalah di kemudian hari
         */
        $detailLog = "Menanggapi laporan #{$request->id_pelaporan}. " .
                    "Status: {$request->status}. " .
                    "Tanggapan: " . \Str::limit($request->feedback, 50);  // Batasi panjang log

        LogAktivitas::create([
            'username' => session('username'),      // Admin yang melakukan aksi
            'aktivitas' => $detailLog
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Tanggapan dan bukti berhasil disimpan!');
    }
}
