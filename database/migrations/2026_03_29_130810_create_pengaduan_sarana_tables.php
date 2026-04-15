<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Siswa
        Schema::create('siswa', function (Blueprint $table) {
            $table->char('nis', 10)->primary();
            $table->string('nama', 100);
            $table->string('kelas', 10);
            $table->string('foto_profile', 255)->nullable();
            $table->string('password');
            $table->timestamps();
        });

        // 2. Tabel Admin
        Schema::create('admin', function (Blueprint $table) {
            $table->string('username', 255)->primary();
            $table->string('password', 255);
            $table->timestamps();
        });

        // 3. Tabel Kategori
        Schema::create('kategori', function (Blueprint $table) {
            $table->bigIncrements('id_kategori');
            $table->string('ket_kategori', 30);
            $table->timestamps();
        });

        // 4. Tabel Lokasi (BARU)
        Schema::create('lokasi', function (Blueprint $table) {
            $table->bigIncrements('id_lokasi');
            $table->string('nama_lokasi', 50);
            $table->timestamps();
        });

        // 5. Tabel Input Aspirasi
        Schema::create('input_aspirasi', function (Blueprint $table) {
            $table->bigIncrements('id_pelaporan');
            $table->char('nis', 10);
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_lokasi'); // DIUBAH: Menghubungkan ke tabel lokasi
            $table->string('ket', 100);
            $table->string('foto', 100);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('cascade'); // BARU
        });

        // 6. Tabel Aspirasi (Tanggapan/Status)
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->bigIncrements('id_aspirasi');
            $table->unsignedBigInteger('id_pelaporan'); // <--- TAMBAHKAN INI
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->text('feedback')->nullable();
            $table->string('foto', 255)->nullable(); 
            $table->timestamps();

            // Foreign Key ke tabel input_aspirasi
            $table->foreign('id_pelaporan')->references('id_pelaporan')->on('input_aspirasi')->onDelete('cascade');
        });

        // 7. Tabel Log Aktivitas
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->char('nis', 10)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('aktivitas', 150);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('nis')->references('nis')->on('siswa')->onDelete('set null');
            $table->foreign('username')->references('username')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('aspirasi');
        Schema::dropIfExists('input_aspirasi');
        Schema::dropIfExists('lokasi'); // BARU
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('siswa');
    }
};