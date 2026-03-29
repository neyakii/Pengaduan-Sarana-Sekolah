<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Session;
use App\Models\InputAspirasi; // Tambahkan ini di atas

class AdminController extends Controller
{
    public function dashboard() {
        if (!Session::has('login_admin')) return redirect('/login');
        
        $logs = LogAktivitas::orderBy('created_at', 'desc')->get();
        
        // AMBIL SEMUA LAPORAN MASUK
        // Kita pakai 'with' agar bisa ambil data siswa (relasi)
        $laporan = InputAspirasi::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('logs', 'laporan'));
        }   
}