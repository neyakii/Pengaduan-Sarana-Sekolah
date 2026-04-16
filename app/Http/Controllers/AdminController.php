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

class AdminController extends Controller
{
    public function dashboard() {
        if (!Session::has('login_admin')) return redirect('/login');
        
        $logs = LogAktivitas::orderBy('created_at', 'desc')->take(10)->get();
        $laporan = InputAspirasi::with(['siswa', 'kategori', 'aspirasi'])
                    ->orderBy('created_at', 'desc')->get();
        
        $kategori = Kategori::all();
        $siswa = Siswa::all();
        $lokasi = Lokasi::all(); 

        return view('admin.dashboard', compact('logs', 'laporan', 'kategori', 'siswa', 'lokasi'));
    }

    // --- CRUD KATEGORI ---
    public function storeKategori(Request $request) {
        // Tambahkan 'unique:nama_tabel,nama_kolom'
        $request->validate([
            'ket_kategori' => 'required|unique:kategori,ket_kategori'
        ], [
            'ket_kategori.unique' => 'Kategori ini sudah ada!'
        ]);
        
        Kategori::create($request->only('ket_kategori'));
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

     public function updateKategori(Request $request) {
        $request->validate([
            'id_kategori' => 'required',
            // Validasi unik kecuali untuk ID yang sedang diedit
            'ket_kategori' => 'required|unique:kategori,ket_kategori,' . $request->id_kategori . ',id_kategori'
        ], [
            'ket_kategori.unique' => 'Nama kategori sudah digunakan!'
        ]);

        Kategori::where('id_kategori', $request->id_kategori)->update([
            'ket_kategori' => $request->ket_kategori
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyKategori($id) {
        // Cek apakah kategori ini sudah digunakan di tabel input_aspirasi
        $terpakai = InputAspirasi::where('id_kategori', $id)->exists();

        if ($terpakai) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah digunakan dalam laporan siswa.');
        }

        Kategori::destroy($id);

        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menghapus kategori (ID: ' . $id . ')'
        ]);

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // --- CRUD SISWA ---
    public function storeSiswa(Request $request) {
        // Anda sudah punya unique:siswa,nis di sini, tinggal tambahkan pesan kustom jika mau
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'password' => 'required'
        ], [
            'nis.unique' => 'NIS ini sudah terdaftar!' 
        ]);

        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'password' => Hash::make($request->password),
        ]);

        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Mendaftarkan siswa baru: ' . $request->nama
        ]);

        return back()->with('success', 'Siswa berhasil didaftarkan!');
    }

    public function destroySiswa($nis) {
        // Cek apakah siswa ini sudah pernah melapor
        $terpakai = InputAspirasi::where('nis', $nis)->exists();

        if ($terpakai) {
            return back()->with('error', 'Data siswa tidak bisa dihapus karena siswa ini memiliki riwayat laporan.');
        }

        Siswa::where('nis', $nis)->delete();

        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menghapus akun siswa dengan NIS: ' . $nis
        ]);

        return back()->with('success', 'Akun siswa berhasil dihapus!');
    }

    // --- CRUD LOKASI ---
    public function storeLokasi(Request $request) {
        // Tambahkan 'unique:lokasi,nama_lokasi'
        $request->validate([
            'nama_lokasi' => 'required|unique:lokasi,nama_lokasi'
        ], [
            'nama_lokasi.unique' => 'Lokasi ini sudah ada!'
        ]);
        
        Lokasi::create($request->all());

        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menambah lokasi baru: ' . $request->nama_lokasi
        ]);

        return back()->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function updateLokasi(Request $request) {
        $request->validate(['id_lokasi' => 'required', 'nama_lokasi' => 'required']);

        Lokasi::where('id_lokasi', $request->id_lokasi)->update([
            'nama_lokasi' => $request->nama_lokasi
        ]);

        // Catat Log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Mengubah nama lokasi menjadi: ' . $request->nama_lokasi
        ]);

        return back()->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroyLokasi($id) {
        // Cek apakah lokasi ini sudah digunakan di tabel input_aspirasi
        // (Asumsi: di tabel input_aspirasi ada kolom id_lokasi)
        $terpakai = InputAspirasi::where('id_lokasi', $id)->exists();

        if ($terpakai) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena sudah digunakan dalam laporan.');
        }

        Lokasi::where('id_lokasi', $id)->delete();

        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menghapus lokasi (ID: ' . $id . ')'
        ]);

        return back()->with('success', 'Lokasi berhasil dihapus');
    }

    // --- TANGGAPI / PROSES LAPORAN ---
    public function tanggapi(Request $request)
        {
            $request->validate([
                'id_pelaporan' => 'required',
                'status' => 'required',
                'feedback' => 'required',
                'foto_bukti' => 'image|mimes:jpeg,png,jpg|max:2048' 
            ]);

            // Menggunakan updateOrCreate agar lebih ringkas
            // Mencari berdasarkan id_pelaporan, jika tidak ada maka buat baru
            $aspirasi = Aspirasi::firstOrNew(['id_pelaporan' => $request->id_pelaporan]);
            
            $aspirasi->status = $request->status;
            $aspirasi->feedback = $request->feedback;

            // LOGIC UPLOAD FOTO
            if ($request->hasFile('foto_bukti')) {
                // Hapus foto lama jika ada di storage
                if ($aspirasi->foto && \Storage::disk('public')->exists($aspirasi->foto)) {
                    \Storage::disk('public')->delete($aspirasi->foto);
                }

                // Simpan foto baru
                $path = $request->file('foto_bukti')->store('bukti_aspirasi', 'public');
                $aspirasi->foto = $path; 
            }

            $aspirasi->save();
            
            // Log yang lebih detail agar admin tahu "apa" yang ditulis
            $detailLog = "Menanggapi laporan #{$request->id_pelaporan}. " .
                        "Status: {$request->status}. " .
                        "Tanggapan: " . \Str::limit($request->feedback, 50);

            LogAktivitas::create([
                'username' => session('username'),
                'aktivitas' => $detailLog
            ]);

            return back()->with('success', 'Tanggapan dan bukti berhasil disimpan!');
        }
}