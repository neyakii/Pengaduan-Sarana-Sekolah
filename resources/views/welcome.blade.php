<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Sarana Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050b18;
            --primary-glow: rgba(59, 130, 246, 0.15);
        }

        body {
            background-color: var(--bg-color);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Glow Orbs */
        body::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: #3b82f6;
            filter: blur(150px);
            border-radius: 50%;
            top: 10%;
            left: -100px;
            z-index: -1;
            opacity: 0.4;
        }

        body::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: #6366f1;
            filter: blur(150px);
            border-radius: 50%;
            bottom: 10%;
            right: -100px;
            z-index: -1;
            opacity: 0.3;
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }

        .badge-modern {
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 8px 16px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .display-4 {
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
        }

        .text-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-muted-modern {
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Modern Button Styles */
        .btn-primary-custom {
            background: #3b82f6;
            border: none;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            background: #2563eb;
        }

        .btn-outline-custom {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            color: white;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-outline-custom:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Floating Image Effect */
        .img-wrapper {
            position: relative;
        }

        .img-hero {
            position: relative;
            z-index: 2;
            animation: float 5s ease-in-out infinite;
            filter: drop-shadow(0 20px 50px rgba(0,0,0,0.5));
        }

        .img-bg-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(59,130,246,0.3) 0%, transparent 70%);
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(2deg); }
        }

        @media (max-width: 768px) {
            .hero { text-align: center; }
            .d-flex { justify-content: center; }
            .img-hero { width: 250px; margin-top: 3rem; }
        }
    </style>
</head>
<body>
    <div class="container hero">
        <div class="row w-100 align-items-center">
            <div class="col-lg-6">
                <span class="badge badge-modern mb-4">Facility Report</span>
                <h1 class="display-4 mb-4">
                    Sistem Pengaduan<br>
                    <span class="text-gradient">Sarana Sekolah</span>
                </h1>
                <p class="text-muted-modern mb-5 fs-5">
                    Laporkan kerusakan fasilitas sekolah dengan cepat. Kami memastikan setiap meja, kursi, dan proyektor Anda kembali berfungsi dengan optimal untuk kenyamanan belajar.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom">Login Sekarang</a>
                    <a href="{{ url('/register') }}" class="btn btn-outline-custom">Register</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="img-wrapper">
                    <div class="img-bg-glow"></div>
                    <img src="https://cdn-icons-png.flaticon.com/512/1048/1048940.png" class="img-hero" width="380" alt="Hero Image">
                </div>
            </div>
        </div>
    </div>
</body>
</html>