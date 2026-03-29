<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Sarana Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .text-muted-dark {
            color: #cbd5e1;
        }

        .btn-custom {
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: 0.3s;
        }

        .img-hero {
            filter: drop-shadow(0 10px 20px rgba(59, 130, 246, 0.5));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>
    <div class="container hero">
        <div class="row w-100 align-items-center">
            <div class="col-md-6">
                <span class="badge bg-primary mb-3">Sistem Informasi Sekolah</span>
                <h1 class="fw-bold mb-3 display-4">
                    Sistem Pengaduan<br>
                    <span class="text-primary">Sarana Sekolah</span>
                </h1>
                <p class="text-muted-dark mb-4 fs-5">
                    Website ini digunakan untuk melaporkan kerusakan fasilitas sekolah seperti kursi, meja, proyektor, dan sarana lainnya agar dapat segera ditindaklanjuti oleh pihak sekolah.
                </p>
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-custom me-3">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-outline-light btn-custom">Register</a>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/1048/1048940.png" class="img-hero" width="350" alt="Hero Image">
            </div>
        </div>
    </div>
</body>
</html>