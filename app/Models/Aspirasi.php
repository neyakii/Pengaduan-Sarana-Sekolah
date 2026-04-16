<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    protected $table = 'aspirasi';
    protected $primaryKey = 'id_aspirasi';
    
    // Sesuaikan dengan kolom di database Anda (Hasil screenshot sebelumnya)
    protected $fillable = ['id_pelaporan', 'status', 'feedback', 'foto'];

    // Relasi balik ke laporan
    public function input_aspirasi()
    {
        return $this->belongsTo(InputAspirasi::class, 'id_pelaporan', 'id_pelaporan');
    }
}