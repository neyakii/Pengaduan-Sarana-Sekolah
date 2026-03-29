<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: white; min-height: 100vh; display: flex; align-items: center; }
        .card { background: #1e293b; border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .form-control { background: #334155; border: 1px solid #475569; color: white; }
        .form-control:focus { background: #334155; color: white; border-color: #3b82f6; box-shadow: none; }
        .btn-primary { background: #3b82f6; border: none; padding: 10px; font-weight: 600; }
        .text-muted-dark { color: #cbd5e1; }
        a { text-decoration: none; color: #3b82f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="text-center mb-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/1048/1048940.png" width="80" class="mb-3">
                    <h3 class="fw-bold">Silakan Login</h3>
                    <p class="text-muted-dark">Gunakan NIS atau Username Admin</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
                @endif

                <!-- Cek Error dari Session (Hasil dari ->with('error', ...)) -->
                @if(session('error'))
                    <div class="alert alert-danger bg-danger text-white border-0">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Cek Error dari Validasi (Hasil dari $request->validate) -->
                @if($errors->any())
                    <div class="alert alert-danger bg-danger text-white border-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif

                <div class="card p-4">
                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted-dark">NIS / Username</label>
                            <input type="text" name="id_user" class="form-control" placeholder="Masukkan NIS atau Username" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted-dark">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3">Masuk Sekarang</button>
                        <div class="text-center">
                            <small class="text-muted-dark">Belum punya akun? <a href="{{ url('/register') }}">Daftar Siswa</a></small>
                        </div>
                    </form>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-muted-dark small">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>