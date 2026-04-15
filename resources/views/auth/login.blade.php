<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pengaduan Sarana</title>
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
            position: relative;
            overflow-x: hidden;
        }

        /* Latar Belakang Glow (Sama dengan Landing Page agar Konsisten) */
        body::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(59, 130, 246, 0.15);
            filter: blur(150px);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            z-index: -1;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .brand-logo {
            filter: drop-shadow(0 0 15px rgba(59, 130, 246, 0.5));
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            padding: 12px 16px;
            transition: all 0.3s;
        }

        /* --- TAMBAHAN UNTUK PLACEHOLDER --- */
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
            opacity: 1;
        }

        .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            color: white;
        }
        

        .btn-login {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
            filter: brightness(1.1);
        }

        .alert {
            border-radius: 12px;
            font-size: 0.9rem;
            border: none;
        }

        .link-custom {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-7">
                
                <div class="text-center mb-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/1048/1048940.png" width="70" class="brand-logo mb-3">
                    <h3 class="fw-800">Selamat Datang</h3>
                    <p style="color: #94a3b8;">Masuk ke akun Anda untuk melapor</p>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success bg-success bg-opacity-25 text-success border-0 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error') || $errors->any())
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 mb-4">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="login-card">
                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">NIS / Username</label>
                            <input type="text" name="id_user" class="form-control" placeholder="Masukkan ID Anda" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-login w-100 mb-3">Masuk ke Sistem</button>
                        
                        <div class="text-center">
                            <small style="color: #94a3b8;">Belum punya akun? <a href="{{ url('/register') }}" class="link-custom">Daftar Siswa</a></small>
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