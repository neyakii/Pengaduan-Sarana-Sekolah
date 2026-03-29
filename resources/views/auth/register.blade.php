<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Siswa - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: white; min-height: 100vh; display: flex; align-items: center; padding: 40px 0; }
        .card { background: #1e293b; border: none; border-radius: 15px; }
        .form-control { background: #334155; border: 1px solid #475569; color: white; }
        .form-control:focus { background: #334155; color: white; border-color: #3b82f6; box-shadow: none; }
        .btn-primary { background: #3b82f6; border: none; font-weight: 600; }
        .text-muted-dark { color: #cbd5e1; }
        a { text-decoration: none; color: #3b82f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Daftar Akun Siswa</h3>
                    <p class="text-muted-dark">Lengkapi data diri untuk melaporkan sarana</p>
                </div>

                <div class="card p-4 shadow">
                    <!-- PERHATIKAN BARIS DI BAWAH INI: SUDAH SAYA TAMBAHKAN enctype -->
                    <form action="{{ url('/register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted-dark">NIS</label>
                                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" placeholder="Contoh: 12209123" required>
                                @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted-dark">Kelas</label>
                                <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" placeholder="XII RPL 1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted-dark">Foto Profil</label>
                            <!-- Pastikan name-nya "foto_profile" -->
                            <input type="file" name="foto_profile" class="form-control @error('foto_profile') is-invalid @enderror">
                            <small class="text-muted-dark">Pilih foto apa saja (JPG/PNG)</small>
                            @error('foto_profile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted-dark">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Nama sesuai absen" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted-dark">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 3 karakter" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Daftar Sekarang</button>
                        <div class="text-center">
                            <small class="text-muted-dark">Sudah punya akun? <a href="{{ url('/login') }}">Login di sini</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>