<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-color: #050b18;
            --primary-blue: #3b82f6;
            --accent-purple: #a855f7;
            --card-glass: rgba(255, 255, 255, 0.03);
            --border-glass: rgba(255, 255, 255, 0.08);
            --dropdown-bg: #111827;
        }

        body {
            background-color: var(--bg-color);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Glow Orbs */
        body::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: var(--primary-blue);
            filter: blur(180px);
            border-radius: 50%;
            top: -10%;
            left: -100px;
            z-index: -1;
            opacity: 0.2;
        }

        body::after {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: var(--accent-purple);
            filter: blur(180px);
            border-radius: 50%;
            bottom: -10%;
            right: -100px;
            z-index: -1;
            opacity: 0.15;
        }

        .text-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-card {
            background: var(--card-glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

         /* --- PERBAIKAN SCROLL TABEL --- */
        .table-scroll-container {
            max-height: 500px; 
            overflow-y: auto;
            padding-right: 10px;
            margin-top: 10px;
        }

        /* Scrollbar Style */
        .table-scroll-container::-webkit-scrollbar { width: 5px; }
        .table-scroll-container::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
        .table-scroll-container::-webkit-scrollbar-thumb { 
            background: rgba(59, 130, 246, 0.5); 
            border-radius: 10px; 
        }

        /* Sticky Header Fix */
        .custom-table thead th {
            position: sticky;
            top: -1px; /* Menutup celah saat scroll */
            background: #0b1425; /* Warna solid agar teks tidak tumpuk */
            z-index: 20;
            border-bottom: 1px solid var(--border-glass);
            padding: 15px !important;
            color: rgba(255,255,255,0.6) !important;
        }

        /* Card inside table fix */
        .feedback-box {
            background: rgba(59, 130, 246, 0.08);
            border-left: 3px solid var(--primary-blue);
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
        }

        .img-thumbnail-custom {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--border-glass);
            transition: 0.3s;
        }

        .img-thumbnail-custom:hover {
            transform: scale(1.05);
            border-color: var(--primary-blue);
        }

        .profile-img-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(59, 130, 246, 0.5);
            padding: 4px;
        }

        .btn-edit-photo {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--primary-blue);
            color: white;
            border: 3px solid var(--bg-color);
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: 0.3s;
        }

        .stat-mini-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 18px;
            padding: 15px;
            border: 1px solid var(--border-glass);
            text-align: center;
        }

        .btn-primary-custom {
            background: #3b82f6;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            background: #2563eb;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-glass);
            color: #ffffff !important; /* Warna teks saat mengetik */
            border-radius: 12px;
            padding: 12px 15px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
            opacity: 1;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15);
            color: #ffffff;
        }

        /* Table Styling */
        .custom-table { border-spacing: 0 12px; border-collapse: separate; }
        .custom-table tbody tr { background: rgba(255, 255, 255, 0.02); transition: 0.3s; }
        .custom-table tbody tr:hover { background: rgba(255, 255, 255, 0.05); }
        .custom-table td { padding: 1.2rem; border: none; vertical-align: middle; border-top: 1px solid var(--border-glass); border-bottom: 1px solid var(--border-glass); }
        .custom-table td:first-child { border-left: 1px solid var(--border-glass); border-radius: 16px 0 0 16px; }
        .custom-table td:last-child { border-right: 1px solid var(--border-glass); border-radius: 0 16px 16px 0; }

        /* Badge Status */
        .badge-modern { padding: 6px 14px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .st-selesai { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
        .st-proses { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .st-menunggu { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }

        /* Modal & Form Fixes */
        .modal-content { background: #0f172a; border: 1px solid var(--border-glass); border-radius: 24px; backdrop-filter: blur(20px); }
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-glass);
            color: #ffffff !important;
            border-radius: 12px;
            padding: 12px 15px;
        }
        .form-select option { background-color: var(--dropdown-bg); color: #ffffff; }
        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15);
        }

        .timeline-item { border-left: 2px solid rgba(59, 130, 246, 0.2); padding-left: 15px; padding-bottom: 15px; position: relative; }
        .timeline-item::after { content: ''; position: absolute; left: -7px; top: 5px; width: 12px; height: 12px; background: var(--primary-blue); border-radius: 50%; box-shadow: 0 0 10px var(--primary-blue); }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row g-4">
            <!-- SIDEBAR LEFT -->
            <div class="col-lg-4">
                <div class="glass-card text-center h-100">
                    <div class="profile-img-wrapper mb-3">
                        @if($siswa->foto_profile)
                            <img src="{{ asset('storage/' . $siswa->foto_profile) }}" class="profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=3b82f6&color=fff&size=128" class="profile-img">
                        @endif
                        <button class="btn-edit-photo" data-bs-toggle="modal" data-bs-target="#modalProfile">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>

                    <h4 class="fw-800 mb-1">{{ $siswa->nama }}</h4>
                    <p class="text-white small mb-4 opacity-75">{{ $siswa->kelas }} • {{ $siswa->nis }}</p>
                    
                    <div class="d-grid mb-4">
                        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalLapor">
                            <i class="bi bi-megaphone-fill me-2"></i>Buat Laporan Baru
                        </button>
                    </div>

                    <div class="text-start border-top border-secondary border-opacity-10 pt-4">
                        <h6 class="fw-700 mb-3 small text-primary text-uppercase" style="letter-spacing: 1px;">Riwayat Aktivitas</h6>
                        <div class="log-container overflow-auto" style="max-height: 200px;">
                            @forelse($logs as $log)
                                <div class="timeline-item">
                                    <div class="text-white small fw-600">{{ $log->aktivitas }}</div>
                                    <div class="text-white-50" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <p class="small text-white-50">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4 pt-3">
                        <a href="/logout" class="text-danger text-decoration-none small fw-bold opacity-75">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT RIGHT -->
            <div class="col-lg-8">
                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-md-5">
                        <h2 class="fw-800 mb-1">Selamat Datang, <span class="text-gradient">{{ explode(' ', $siswa->nama)[0] }}!</span></h2>
                        <p class="text-white-50 small mb-0">Pantau status perbaikan fasilitas sekolahmu.</p>
                    </div>
                    <div class="col-md-7">
                        <!-- Grid disesuaikan menjadi col-3 agar 4 card sejajar sempurna (3x4=12) -->
                        <div class="row g-2">
                            <div class="col-3">
                                <div class="stat-mini-card">
                                    <div class="text-white-50 mb-1" style="font-size: 0.65rem;">TOTAL</div>
                                    <div class="h5 fw-800 mb-0">{{ count($pengaduan) }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-mini-card">
                                    <div class="text-warning mb-1" style="font-size: 0.65rem;">MENUNGGU</div>
                                    <div class="h5 fw-800 mb-0 text-warning">
                                        {{ $pengaduan->filter(function($p) {
                                            return ($p->aspirasi->status ?? 'Menunggu') == 'Menunggu';
                                        })->count() }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-mini-card">
                                    <!-- Warna diganti ke Biru (Primary/Info) -->
                                    <div class="text-primary mb-1" style="font-size: 0.65rem;">PROSES</div>
                                    <div class="h5 fw-800 mb-0 text-primary">{{ $pengaduan->where('aspirasi.status', 'Proses')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-mini-card">
                                    <div class="text-success mb-1" style="font-size: 0.65rem;">SELESAI</div>
                                    <div class="h5 fw-800 mb-0 text-success">{{ $pengaduan->where('aspirasi.status', 'Selesai')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- TABEL LAPORAN -->
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-800 mb-0">Laporan Saya</h5>
                            <p class="text-white-50 small mb-0">Riwayat pengaduan fasilitas sekolah Anda</p>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small" style="font-size: 0.7rem; border: 1px solid rgba(59,130,246,0.2)">
                            <i class="bi bi-arrow-repeat me-1"></i> Auto Refresh
                        </span>
                    </div>
                    
                    <div class="table-scroll-container">
                        <div class="table-responsive">
                            <table class="table custom-table text-white mb-0">
                                <thead>
                                    <tr class="text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                                        <th class="border-0">Laporan</th>
                                        <th class="border-0">Detail & Tanggapan</th>
                                        <th class="border-0 text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengaduan as $p)
                                    @php
                                        $status = $p->aspirasi->status ?? 'Menunggu';
                                        $badgeClass = ($status == 'Selesai') ? 'st-selesai' : (($status == 'Proses') ? 'st-proses' : 'st-menunggu');
                                    @endphp
                                    <tr>
                                        <!-- Kolom Foto & Info Dasar -->
                                        <td style="width: 140px;">
                                            <div class="position-relative">
                                                <label class="d-block text-black-50 fw-bold mb-2" style="font-size: 0.6rem; text-transform: uppercase;">Bukti Kerusakan</label>
                                                <a href="{{ asset('storage/'.$p->foto) }}" target="_blank">
                                                    <img src="{{ asset('storage/'.$p->foto) }}" class="img-thumbnail-custom shadow-sm">
                                                </a>
                                                <div class="mt-2 text-black-50" style="font-size: 0.65rem;">
                                                    <i class="bi bi-calendar3 me-1"></i> {{ $p->created_at->format('d M Y') }}
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Kolom Detail & Tanggapan -->
                                        <td>
                                            <div class="mb-1">
                                                <span class="badge bg-balck bg-opacity-10 text-black-50 fw-normal mb-2" style="font-size: 0.65rem;">
                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $p->lokasi_relasi->nama_lokasi ?? 'Lokasi tidak diketahui' }}
                                                </span>
                                                <p class="text-black opacity-75 small mb-0" style="line-height: 1.5; max-width: 400px;">
                                                    {{ $p->ket }}
                                                </p>
                                            </div>

                                            @if($p->aspirasi)
                                            <div class="feedback-box">
                                                <div class="d-flex align-items-start gap-2 mb-2">
                                                    <i class="bi bi-chat-square-text-fill text-primary"></i>
                                                    <div>
                                                        <label class="d-block text-primary fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Respon Administrator</label>
                                                        <p class="text-black-50 small mb-0">{{ $p->aspirasi->feedback ?? 'Sedang ditinjau...' }}</p>
                                                    </div>
                                                </div>

                                                @if($p->aspirasi->foto)
                                                <div class="mt-3 pt-2 border-top border-white border-opacity-10">
                                                    <label class="d-block text-success fw-bold mb-2" style="font-size: 0.65rem; text-transform: uppercase;">
                                                        <i class="bi bi-check-all me-1"></i> Bukti Hasil Perbaikan
                                                    </label>
                                                    <a href="{{ asset('storage/'.$p->aspirasi->foto) }}" target="_blank">
                                                        <img src="{{ asset('storage/'.$p->aspirasi->foto) }}" class="img-thumbnail-custom" style="width: 80px; height: 60px;">
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                            @endif

                                            <!-- Tombol Aksi -->
                                            @if($status == 'Menunggu')
                                            <div class="mt-3 d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-info py-1 px-3 rounded-pill" style="font-size: 0.65rem;"
                                                        onclick="editLaporan('{{ $p->id_pelaporan }}', '{{ $p->id_kategori }}', '{{ $p->id_lokasi }}', '{{ $p->ket }}')">
                                                    <i class="bi bi-pencil me-1"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger py-1 px-3 rounded-pill" style="font-size: 0.65rem;"
                                                        onclick="confirmDelete('{{ $p->id_pelaporan }}')">
                                                    <i class="bi bi-x-lg me-1"></i> Batal
                                                </button>
                                            </div>
                                            @endif
                                        </td>

                                        <!-- Kolom Status -->
                                        <td class="text-end">
                                            <span class="badge-modern {{ $badgeClass }}">{{ $status }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <i class="bi bi-clipboard-x fs-1 text-white-50 d-block mb-3"></i>
                                            <p class="text-black-50 small">Belum ada laporan yang diajukan.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

    <!-- MODAL PROFILE -->
    <div class="modal fade" id="modalProfile" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-800 text-white">Ganti Foto Profil</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/siswa/update-foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <input type="file" name="foto_profile" class="form-control" accept="image/*" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary-custom w-100">Simpan Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Tambahkan ini di bagian atas halaman untuk melihat pesan kesalahan -->
<div class="container mt-3">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; background: rgba(220, 53, 69, 0.2); color: white; border: 1px solid rgba(220, 53, 69, 0.4); backdrop-filter: blur(10px);">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger" style="border-radius: 15px;">
            {{ session('error') }}
        </div>
    @endif
</div>
    <!-- MODAL LAPOR BARU -->
    <div class="modal fade" id="modalLapor" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-800"><span class="text-gradient">Buat Laporan Baru</span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/siswa/lapor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-white mb-2 fw-600">Kategori Fasilitas</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-white mb-2 fw-600">Lokasi Spesifik</label>
                                <select name="id_lokasi" class="form-select" required>
                                    <option value="" disabled selected>Pilih Lokasi</option>
                                    @foreach($lokasi as $l)
                                        <option value="{{ $l->id_lokasi }}">{{ $l->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="small text-white mb-2 fw-600">Deskripsi Kerusakan</label>
                                <textarea name="ket" class="form-control" rows="4" placeholder="Jelaskan detail kerusakan..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="small text-white mb-2 fw-600">Unggah Foto Bukti</label>
                                <input type="file" name="foto_kerusakan" class="form-control" required>
                                <!-- Tambahkan teks ini -->
                                <small class="text-white-50" style="font-size: 0.7rem;">
                                    <i class="bi bi-info-circle me-1"></i> Maksimal ukuran foto: 2MB (Jika lebih, laporan tidak akan terkirim).
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary-custom w-100 py-3">Kirim Pengaduan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT LAPORAN -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-800"><span class="text-gradient">Edit Laporan</span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEdit" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-white mb-2 fw-600">Kategori Fasilitas</label>
                                <select name="id_kategori" id="edit_kategori" class="form-select" required>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Bagian Lokasi di Modal Edit -->
                            <div class="col-md-6">
                                <label class="small text-white mb-2 fw-600">Lokasi Spesifik</label>
                                <select name="id_lokasi" id="edit_lokasi" class="form-select" required>
                                    @foreach($lokasi as $l)
                                        <option value="{{ $l->id_lokasi }}">{{ $l->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="small text-white mb-2 fw-600">Deskripsi Kerusakan</label>
                                <textarea name="ket" id="edit_ket" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="small text-white mb-2 fw-600">Ganti Foto (Opsional)</label>
                                <input type="file" name="foto_kerusakan" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary-custom w-100 py-3">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="formDelete" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert for Success Message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                background: '#0f172a',
                color: '#ffffff',
                confirmButtonColor: '#3b82f6'
            });
        @endif

        // Logic for Edit Modal
        function editLaporan(id, kategori, id_lokasi, ket) { // Ganti parameter lokasi jadi id_lokasi
            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            const form = document.getElementById('formEdit');
            
            form.action = '/siswa/lapor/update/' + id;
            
            document.getElementById('edit_kategori').value = kategori;
            document.getElementById('edit_lokasi').value = id_lokasi; // Sekarang mengisi value ID ke dropdown
            document.getElementById('edit_ket').value = ket;
            
            modal.show();
        }
        // Logic for Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Batalkan Laporan?',
                text: "Laporan yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                background: '#0f172a',
                color: '#ffffff',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formDelete');
                    form.action = '/siswa/lapor/delete/' + id;
                    form.submit();
                }
            })
        }
    </script>
</body>
</html>