<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\InputAspirasi;
use App\Models\Aspirasi;
use App\Models\Kategori; // Pastikan model ini ada
use App\Models\Siswa;   // Pastikan model ini ada
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
        
        // Tambahan data untuk CRUD
        $kategori = Kategori::all();
        $siswa = Siswa::all();

        return view('admin.dashboard', compact('logs', 'laporan', 'kategori', 'siswa'));
    }

    // --- CRUD KATEGORI ---
    public function storeKategori(Request $request) {
        Kategori::create($request->only('ket_kategori'));
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroyKategori($id) {
        Kategori::destroy($id);
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    public function updateKategori(Request $request, $id) {
        $request->validate([
            'ket_kategori' => 'required'
        ]);

        \App\Models\Kategori::where('id_kategori', $id)->update([
            'ket_kategori' => $request->ket_kategori
        ]);

        // Opsional: Catat di Log
        \App\Models\LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Memperbarui kategori ID: ' . $id
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
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
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Siswa berhasil didaftarkan!');
    }

    public function destroySiswa($nis) {
        Siswa::where('nis', $nis)->delete();
        return back()->with('success', 'Akun siswa berhasil dihapus!');
    }

    // --- TANGGAPI ---
    public function tanggapi(Request $request) {
        $request->validate(['id_pelaporan' => 'required', 'status' => 'required', 'feedback' => 'required']);

        Aspirasi::updateOrCreate(
            ['id_aspirasi' => $request->id_pelaporan], 
            [
                'status' => $request->status,
                'id_kategori' => $request->id_kategori,
                'feedback' => $request->feedback
            ]
        );

        LogAktivitas::create([
            'username' => session('username'),
            'aktivitas' => 'Memberikan tanggapan pada laporan #' . $request->id_pelaporan
        ]);

        return back()->with('success', 'Tanggapan berhasil disimpan!');
    }
}