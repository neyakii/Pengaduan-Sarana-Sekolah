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
            --bg-dark: #050b18;
            --card-glass: rgba(30, 41, 59, 0.4);
            --accent-blue: #3b82f6;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background-image: radial-gradient(circle at top right, rgba(59, 130, 246, 0.05), transparent 400px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }

        .glass-card {
            background: var(--card-glass);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 1.5rem;
            height: 100%;
        }

        /* Profile Section */
        .profile-img {
            width: 100px; height: 100px; object-fit: cover;
            border-radius: 50%; border: 3px solid var(--accent-blue);
            padding: 3px; background: var(--bg-dark);
        }
        .btn-edit-photo {
            position: absolute; bottom: 5px; right: 5px;
            background: var(--accent-blue); color: white;
            border-radius: 50%; width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--bg-dark); font-size: 0.8rem;
        }

        /* Compact Stats Row */
        .stat-mini-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px; padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        /* Scrollable Log */
        .log-container {
            max-height: 250px;
            overflow-y: auto;
            padding-right: 8px;
        }
        .timeline-item {
            border-left: 2px solid rgba(59, 130, 246, 0.2);
            padding-left: 15px; position: relative; padding-bottom: 15px;
        }
        .timeline-item::after {
            content: ''; position: absolute; left: -6px; top: 5px;
            width: 10px; height: 10px; background: var(--accent-blue);
            border-radius: 50%;
        }

        /* Table Laporan Card Style */
        .custom-table { border-collapse: separate; border-spacing: 0 12px; }
        .custom-table tbody tr {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px; transition: 0.3s;
        }
        .custom-table tbody tr:hover { background: rgba(255, 255, 255, 0.07); }
        .custom-table td { padding: 1.2rem 1rem; border: none; vertical-align: middle; }
        .custom-table td:first-child { border-radius: 15px 0 0 15px; padding-left: 1.2rem; }
        .custom-table td:last-child { border-radius: 0 15px 15px 0; padding-right: 1.2rem; }

        .img-report-box {
            width: 80px; height: 80px; object-fit: cover;
            border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            border: none; border-radius: 12px; padding: 10px 20px; font-weight: 700; font-size: 0.9rem;
        }

        .badge-status { padding: 6px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 700; }
        .st-selesai { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
        .st-proses { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .st-menunggu { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
    </style>
</head>
<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>

    <div class="container py-4">
        <div class="row g-4">
            <!-- LEFT SIDE -->
            <div class="col-lg-4">
                <div class="glass-card text-center">
                    <div class="position-relative d-inline-block mb-3">
                        @if($siswa->foto_profile)
                            <img src="{{ asset('storage/' . $siswa->foto_profile) }}" class="profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=3b82f6&color=fff&size=128" class="profile-img">
                        @endif
                        <button class="btn-edit-photo shadow" data-bs-toggle="modal" data-bs-target="#modalProfile">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>

                    <h5 class="fw-800 mb-1">{{ $siswa->nama }}</h5>
                    <p class="text-secondary small mb-4">{{ $siswa->kelas }} • {{ $siswa->nis }}</p>
                    
                    <div class="d-grid mb-4">
                        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalLapor">
                            <i class="bi bi-megaphone-fill me-2"></i>Buat Laporan Baru
                        </button>
                    </div>

                    <div class="text-start border-top border-secondary border-opacity-10 pt-4">
                        <h6 class="fw-700 mb-3 small text-primary text-uppercase">Riwayat Aktivitas</h6>
                        <div class="log-container">
                            @forelse($logs as $log)
                                <div class="timeline-item">
                                    <div class="text-white small fw-600">{{ $log->aktivitas }}</div>
                                    <div class="text-secondary" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <p class="small text-secondary">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="/logout" class="text-danger text-decoration-none small fw-bold">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-8">
                <!-- Welcome & Stats Row (Compact) -->
                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-md-5">
                        <h4 class="fw-800 mb-0">Halo, {{ explode(' ', $siswa->nama)[0] }}! 👋</h4>
                        <p class="text-secondary small mb-0">Status laporan sarana kamu.</p>
                    </div>
                    <div class="col-md-7">
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="stat-mini-card">
                                    <div class="text-secondary" style="font-size: 0.65rem;">Total</div>
                                    <div class="fw-800">{{ count($pengaduan) }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-mini-card">
                                    <div class="text-warning" style="font-size: 0.65rem;">Proses</div>
                                    <div class="fw-800 text-warning">{{ $pengaduan->where('aspirasi.status', 'Proses')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-mini-card">
                                    <div class="text-success" style="font-size: 0.65rem;">Selesai</div>
                                    <div class="fw-800 text-success">{{ $pengaduan->where('aspirasi.status', 'Selesai')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Section -->
                <div class="glass-card">
                    <h6 class="fw-800 mb-3">Daftar Laporan Saya</h6>
                    <div class="table-responsive">
                        <table class="table custom-table text-white">
                            <thead>
                                <tr class="text-secondary" style="font-size: 0.7rem; text-transform: uppercase;">
                                    <th>Bukti</th>
                                    <th>Detail Laporan</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengaduan as $p)
                                <tr>
                                    <td style="width: 100px;">
                                        <img src="{{ asset('storage/'.$p->foto) }}" class="img-report-box shadow-sm">
                                    </td>
                                    <td>
                                        <div class="fw-800 mb-1">{{ $p->lokasi }}</div>
                                        <p class="text-secondary small mb-2" style="font-size: 0.8rem;">{{ $p->ket }}</p>
                                        
                                        @if($p->aspirasi && $p->aspirasi->feedback)
                                            <div class="p-2 rounded bg-primary bg-opacity-10 border-start border-primary border-3" style="font-size: 0.75rem;">
                                                <span class="text-primary fw-bold">Admin:</span> <span class="text-50">{{ $p->aspirasi->feedback }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($p->aspirasi)
                                            <span class="badge-status {{ $p->aspirasi->status == 'Selesai' ? 'st-selesai' : ($p->aspirasi->status == 'Proses' ? 'st-proses' : 'st-menunggu') }}">
                                                {{ $p->aspirasi->status }}
                                            </span>
                                        @else
                                            <span class="badge-status st-menunggu">Menunggu</span>
                                        @endif
                                        <div class="text-muted mt-2" style="font-size: 0.65rem;">{{ $p->created_at->format('d M Y') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5 text-secondary small">Belum ada laporan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UPDATE FOTO PROFILE -->
<div class="modal fade" id="modalProfile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow-lg" style="background: #0f172a; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-800 text-white">Ganti Foto Profil</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/siswa/update-foto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Pilih File Foto (JPG/PNG)</label>
                        <input type="file" name="foto_profile" class="form-control bg-dark border-secondary text-white" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100 py-2" style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%); border: none; border-radius: 12px; font-weight: 700; color: white;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- MODAL BUAT LAPORAN -->
    <div class="modal fade" id="modalLapor" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-800">Buat Pengaduan Sarana</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/siswa/lapor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-secondary mb-2">Kategori Fasilitas</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-secondary mb-2">Lokasi (Ruangan/Area)</label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lab RPL, Kantin" required>
                            </div>
                            <div class="col-12">
                                <label class="small text-secondary mb-2">Deskripsi Kerusakan</label>
                                <textarea name="ket" class="form-control" rows="4" placeholder="Jelaskan secara detail kerusakan yang terjadi..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="small text-secondary mb-2">Foto Bukti Kerusakan</label>
                                <input type="file" name="foto_kerusakan" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary-custom w-100 py-3">Kirim Laporan Pengaduan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TARUH DI SINI (Script Pop-out SweetAlert) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Pop-out untuk Berhasil Kirim Laporan / Update Foto
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    background: '#0f172a',
                    color: '#ffffff',
                    confirmButtonColor: '#3b82f6',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // 2. Pop-out untuk Berhasil Login
            @if(session('login_success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil!',
                    text: 'Selamat datang kembali, {{ $siswa->nama }}!',
                    background: '#0f172a',
                    color: '#ffffff',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    iconColor: '#3b82f6'
                });
            @endif

            // 3. Pop-out jika ada Error/Validasi Gagal
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ $errors->first() }}',
                    background: '#0f172a',
                    color: '#ffffff',
                    confirmButtonColor: '#ef4444'
                });
            @endif
        });
    </script>
</body>
</html>