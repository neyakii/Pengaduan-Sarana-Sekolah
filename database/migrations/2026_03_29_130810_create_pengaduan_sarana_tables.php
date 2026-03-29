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
            $table->string('password'); // Tambahan kolom password
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

        // 4. Tabel Input Aspirasi
        Schema::create('input_aspirasi', function (Blueprint $table) {
            $table->bigIncrements('id_pelaporan');
            $table->char('nis', 10);
            $table->unsignedBigInteger('id_kategori');
            $table->string('lokasi', 50);
            $table->string('ket', 100);
            $table->string('foto', 100);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // 5. Tabel Aspirasi (Tanggapan/Status)
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->bigIncrements('id_aspirasi');
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->unsignedBigInteger('id_kategori');
            $table->text('feedback')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // 6. Tabel Log Aktivitas
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
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('siswa');
    }
};