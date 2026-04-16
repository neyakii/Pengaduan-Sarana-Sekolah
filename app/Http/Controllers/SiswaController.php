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

class SiswaController extends Controller
{
    public function dashboard() 
    {
        if (!Session::has('login_siswa')) return redirect('/login');
        
        $siswa = Siswa::where('nis', session('nis'))->first();
        $logs = LogAktivitas::where('nis', session('nis'))->orderBy('created_at', 'desc')->get();
        $kategori = Kategori::all();
        $lokasi = Lokasi::all();

        // Mengambil data pengaduan milik siswa yang sedang login
        $pengaduan = InputAspirasi::with(['aspirasi', 'lokasi_relasi'])
                    ->where('nis', session('nis'))
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('siswa.dashboard', compact('siswa', 'pengaduan', 'kategori', 'lokasi', 'logs'));
    }

    // 1. UPDATE FOTO PROFIL
    public function updateFoto(Request $request) 
    {
        $request->validate([
            // Menaikkan max ke 10240 (10MB) untuk menghindari error palsu
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        if ($request->hasFile('foto_profile')) {
            $siswa = Siswa::where('nis', session('nis'))->first();
            
            // Hapus foto profil lama jika ada
            if ($siswa->foto_profile) {
                Storage::disk('public')->delete($siswa->foto_profile);
            }

            $path = $request->file('foto_profile')->store('profile_siswa', 'public');
            
            $siswa->update(['foto_profile' => $path]);

            LogAktivitas::create([
                'nis' => session('nis'), 
                'aktivitas' => 'Memperbarui foto profil baru'
            ]);
            
            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    // 2. BUAT LAPORAN BARU
    public function storeLapor(Request $request) 
    {
        $request->validate([
            'id_kategori' => 'required', 
            'id_lokasi' => 'required', 
            'ket' => 'required', 
            // Ubah max menjadi 10240 (10MB) untuk memastikan file kecil tidak tertolak
            'foto_kerusakan' => 'required|image|mimes:jpeg,png,jpg|max:10240' 
        ], [
            'foto_kerusakan.max' => 'Ukuran file terbaca terlalu besar oleh sistem. Coba kompres foto Anda.',
            'foto_kerusakan.image' => 'File harus berupa gambar (JPG/PNG).',
            'required' => 'Kolom :attribute wajib diisi.'
        ]);

        try {
            if ($request->hasFile('foto_kerusakan')) {
                $path = $request->file('foto_kerusakan')->store('aspirasi', 'public');
                
                InputAspirasi::create([
                    'nis' => session('nis'),
                    'id_kategori' => $request->id_kategori,
                    'id_lokasi' => $request->id_lokasi,
                    'ket' => $request->ket,
                    'foto' => $path
                ]);

                $nama_lokasi = Lokasi::find($request->id_lokasi)->nama_lokasi ?? 'Lokasi tidak diketahui';

                LogAktivitas::create([
                    'nis' => session('nis'), 
                    'aktivitas' => 'Mengirim laporan pengaduan baru di: ' . $nama_lokasi
                ]);

                return back()->with('success', 'Laporan berhasil dikirim!');
            }
            
            return back()->with('error', 'File foto tidak terbaca. Pastikan form memiliki enctype="multipart/form-data"');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 3. UPDATE LAPORAN (EDIT)
    public function updateLapor(Request $request, $id) 
    {
        $request->validate([
            'id_kategori' => 'required', 
            'id_lokasi' => 'required', 
            'ket' => 'required',
            'foto_kerusakan' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        $pengaduan = InputAspirasi::findOrFail($id);
        
        if($pengaduan->nis != session('nis')) {
            return back()->with('error', 'Akses ditolak.');
        }

        $pengaduan->id_kategori = $request->id_kategori;
        $pengaduan->id_lokasi = $request->id_lokasi;
        $pengaduan->ket = $request->ket;

        if ($request->hasFile('foto_kerusakan')) {
            // Hapus foto lama agar storage tidak penuh
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            $path = $request->file('foto_kerusakan')->store('aspirasi', 'public');
            $pengaduan->foto = $path;
        }

        $pengaduan->save();

        $nama_lokasi = Lokasi::find($request->id_lokasi)->nama_lokasi ?? 'Lokasi tidak diketahui';

        LogAktivitas::create([
            'nis' => session('nis'),
            'aktivitas' => 'Memperbarui data laporan di: ' . $nama_lokasi
        ]);

        return back()->with('success', 'Laporan berhasil diperbarui!');
    }

    // 4. HAPUS LAPORAN (BATAL)
    public function destroyLapor($id) 
    {
        $pengaduan = InputAspirasi::findOrFail($id);
        
        if($pengaduan->nis != session('nis')) {
            return back()->with('error', 'Akses ditolak.');
        }

        // Hapus file foto dari folder storage
        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $lokasi_lama = Lokasi::find($pengaduan->id_lokasi)->nama_lokasi ?? 'Lokasi tidak diketahui';
        
        $pengaduan->delete();

        LogAktivitas::create([
            'nis' => session('nis'),
            'aktivitas' => 'Membatalkan/Menghapus laporan di: ' . $lokasi_lama
        ]);

        return back()->with('success', 'Laporan berhasil dibatalkan.');
    }
}