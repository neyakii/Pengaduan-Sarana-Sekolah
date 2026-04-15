<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aspirasi; // Tambahkan di atas

class Aspirasi extends Model
{
    protected $table = 'aspirasi';
    protected $primaryKey = 'id_aspirasi';
    protected $fillable = ['id_pelaporan', 'status', 'feedback', 'foto'];

    public function tanggapi(Request $request) {
        $request->validate([
            'id_pelaporan' => 'required',
            'status' => 'required',
            'feedback' => 'required'
        ]);

        // Kita simpan ke tabel aspirasi
        // Kita gunakan id_pelaporan sebagai id_aspirasi agar sinkron
        Aspirasi::updateOrCreate(
            ['id_aspirasi' => $request->id_pelaporan], // Cari kalau sudah ada
            [
                'status' => $request->status,
                'id_kategori' => $request->id_kategori,
                'feedback' => $request->feedback
            ]
        );

        return back()->with('success', 'Berhasil memberikan tanggapan!');
    }
}
