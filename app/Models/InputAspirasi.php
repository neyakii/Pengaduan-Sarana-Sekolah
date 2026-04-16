<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputAspirasi extends Model
{
    use HasFactory;

    protected $table = 'input_aspirasi';
    protected $primaryKey = 'id_pelaporan';

    // Update fillable: hapus 'lokasi' (teks), ganti dengan 'id_lokasi' (ID)
    protected $fillable = [
        'nis', 
        'id_kategori', 
        'id_lokasi', 
        'ket', 
        'foto'
    ];

    // Relasi ke tabel Siswa
    public function siswa() {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    // Relasi ke tabel Kategori
    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // Relasi ke tabel Lokasi (PENTING: Agar bisa ambil nama_lokasi)
    public function lokasi_relasi() {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    // Relasi ke tabel Aspirasi (Tanggapan Admin)
    public function aspirasi()
    {
        // Menghubungkan id_pelaporan dengan id_aspirasi di tabel aspirasi
        return $this->hasOne(Aspirasi::class, 'id_pelaporan', 'id_pelaporan');
    }
}