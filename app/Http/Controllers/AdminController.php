<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\InputAspirasi;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard() {
        if (!Session::has('login_admin')) return redirect('/login');
        
        $logs = LogAktivitas::orderBy('created_at', 'desc')->take(10)->get();
        
        // Ambil semua laporan beserta data siswa, kategori, dan tanggapan (jika ada)
        $laporan = InputAspirasi::with(['siswa', 'kategori', 'aspirasi'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.dashboard', compact('logs', 'laporan'));
    }

    public function tanggapi(Request $request) {
        $request->validate([
            'id_pelaporan' => 'required',
            'status' => 'required',
            'feedback' => 'required'
        ]);

        // Simpan atau Update Tanggapan (1 laporan = 1 tanggapan)
        // Kita gunakan id_pelaporan sebagai id_aspirasi agar sinkron 1:1
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