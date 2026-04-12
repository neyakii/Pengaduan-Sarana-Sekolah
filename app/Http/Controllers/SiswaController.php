<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\InputAspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SiswaController extends Controller
{
    public function dashboard() 
    {
    if (!Session::has('login_siswa')) return redirect('/login');
    
    $siswa = Siswa::where('nis', session('nis'))->first();
    $logs = LogAktivitas::where('nis', session('nis'))->orderBy('created_at', 'desc')->get();
    $kategori = Kategori::all();

        // TAMBAHKAN with('aspirasi') DI SINI
    $pengaduan = InputAspirasi::with('aspirasi')
                ->where('nis', session('nis'))
                ->orderBy('created_at', 'desc')
                ->get();

    return view('siswa.dashboard', compact('siswa', 'logs', 'kategori', 'pengaduan'));
    }

    // Contoh logic di Controller
    public function updateFoto(Request $request) 
    {
        $request->validate([
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('foto_profile')) {
            // Simpan file ke folder storage/app/public/profile_siswa
            $path = $request->file('foto_profile')->store('profile_siswa', 'public');
            
            // Update database (Pastikan kolom foto_profile ada di tabel siswas)
            Siswa::where('nis', session('nis'))->update([
                'foto_profile' => $path
            ]);

            // Catat di log
            LogAktivitas::create([
                'nis' => session('nis'), 
                'aktivitas' => 'Memperbarui foto profil'
            ]);
            
            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    public function simpanAspirasi(Request $request) 
    {
        $request->validate(['id_kategori' => 'required', 'lokasi' => 'required', 'ket' => 'required', 'foto_kerusakan' => 'required|image']);

        if ($request->hasFile('foto_kerusakan')) {
            $file = $request->file('foto_kerusakan');
            $nama_foto = time() . '_' . session('nis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/aspirasi'), $nama_foto);
            
            InputAspirasi::create([
                'nis' => session('nis'),
                'id_kategori' => $request->id_kategori,
                'lokasi' => $request->lokasi,
                'ket' => $request->ket,
                'foto' => 'aspirasi/' . $nama_foto
            ]);

            LogAktivitas::create(['nis' => session('nis'), 'aktivitas' => 'Mengirim laporan pengaduan baru']);

            return back()->with('success', 'Laporan berhasil dikirim!');
        }
    }
}