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
        $request->validate(['ket_kategori' => 'required']);
        
        Kategori::create($request->only('ket_kategori'));

        // Catat Log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menambah kategori baru: ' . $request->ket_kategori
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateKategori(Request $request) {
        $request->validate(['id_kategori' => 'required', 'ket_kategori' => 'required']);

        Kategori::where('id_kategori', $request->id_kategori)->update([
            'ket_kategori' => $request->ket_kategori
        ]);

        // Catat Log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Mengubah nama kategori menjadi: ' . $request->ket_kategori
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyKategori($id) {
        Kategori::destroy($id);

        // Catat Log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menghapus kategori (ID: ' . $id . ')'
        ]);

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // --- CRUD SISWA ---
    public function storeSiswa(Request $request) {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'password' => 'required'
        ]);

        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'password' => Hash::make($request->password),
        ]);

        // Catat Log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Mendaftarkan siswa baru: ' . $request->nama . ' (NIS: ' . $request->nis . ')'
        ]);

        return back()->with('success', 'Siswa berhasil didaftarkan!');
    }

    public function destroySiswa($nis) {
        Siswa::where('nis', $nis)->delete();

        // Catat Log
        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Menghapus akun siswa dengan NIS: ' . $nis
        ]);

        return back()->with('success', 'Akun siswa berhasil dihapus!');
    }

    // --- CRUD LOKASI ---
    public function storeLokasi(Request $request) {
        $request->validate(['nama_lokasi' => 'required']);
        
        Lokasi::create($request->all());

        // Catat Log
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
        Lokasi::where('id_lokasi', $id)->delete();

        // Catat Log
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