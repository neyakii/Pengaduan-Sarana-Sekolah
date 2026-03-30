<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Siswa - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050b18;
            --card-bg: rgba(30, 41, 59, 0.7);
            --accent-color: #3b82f6;
        }

        body {
            background-color: var(--bg-color);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 50px 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Glow Orbs */
        body::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.15);
            filter: blur(150px);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
            z-index: -1;
        }

        .register-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            padding: 11px 16px;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            color: white;
        }

        /* Styling for file input */
        .form-control[type="file"] {
            padding: 10px;
        }
        .form-control[type="file"]::file-selector-button {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: none;
            border-radius: 8px;
            padding: 4px 12px;
            margin-right: 15px;
            cursor: pointer;
            transition: 0.3s;
        }
        .form-control[type="file"]::file-selector-button:hover {
            background: rgba(59, 130, 246, 0.3);
        }

        .btn-register {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
            filter: brightness(1.1);
        }

        .invalid-feedback {
            font-size: 0.8rem;
            color: #fb7185;
        }

        .link-custom {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .link-custom:hover {
            color: #60a5fa;
            text-decoration: underline;
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .back-link:hover {
            color: white;
        }

        h3 {
            font-weight: 800;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                
                <div class="text-center mb-4">
                    <h3 class="mb-2">Daftar Akun Siswa</h3>
                    <p style="color: #94a3b8;">Lengkapi data diri untuk akses pelaporan</p>
                </div>

                <div class="register-card">
                    <form action="{{ url('/register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" placeholder="Contoh: 1220..." required>
                                @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" placeholder="XII RPL 1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap sesuai absen" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" name="foto_profile" class="form-control @error('foto_profile') is-invalid @enderror">
                            <div class="mt-2 small" style="color: #64748b;">Gunakan foto formal (Format: JPG/PNG)</div>
                            @error('foto_profile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 3 karakter" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-register w-100 mb-4">Buat Akun Sekarang</button>
                        
                        <div class="text-center">
                            <small style="color: #94a3b8;">Sudah punya akun? <a href="{{ url('/login') }}" class="link-custom">Login di sini</a></small>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="back-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>