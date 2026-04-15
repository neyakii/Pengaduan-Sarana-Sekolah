<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk menentukan nama tabel secara manual
    protected $table = 'lokasi'; 

    // Jika primary key Anda namanya 'id_lokasi', tambahkan ini:
    protected $primaryKey = 'id_lokasi';

    // Jika tabel lokasi tidak memiliki kolom created_at & updated_at, tambahkan ini:
    public $timestamps = false;
    
    // Tambahkan fillable agar data bisa diinput
    protected $fillable = ['nama_lokasi']; 
}