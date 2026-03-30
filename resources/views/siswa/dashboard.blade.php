<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark: #050b18;
            --card-glass: rgba(30, 41, 59, 0.5);
            --accent-blue: #3b82f6;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background-image: radial-gradient(circle at top right, rgba(59, 130, 246, 0.05), transparent 400px);
        }

        .text-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .glass-card {
            background: var(--card-glass);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 1.5rem;
            height: 100%;
        }

        /* Profile Section */
        .profile-wrapper {
            position: relative;
            display: inline-block;
        }
        .profile-img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--accent-blue);
            padding: 3px;
            background: var(--bg-dark);
        }
        .btn-edit-photo {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--accent-blue);
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-dark);
            transition: 0.3s;
        }
        .btn-edit-photo:hover {
            transform: scale(1.1);
            background: #2563eb;
        }

        /* Table & List Styling */
        .custom-table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        .custom-table tbody tr {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            transition: 0.3s;
        }
        .custom-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.06);
        }
        .custom-table td {
            padding: 1rem;
            vertical-align: middle;
            border: none;
        }
        .custom-table td:first-child { border-radius: 16px 0 0 16px; }
        .custom-table td:last-child { border-radius: 0 16px 16px 0; }

        .img-report {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
        }

        /* Badge Status */
        .badge-status {
            padding: 6px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .st-selesai { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
        .st-proses { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .st-menunggu { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }

        /* Form Controls */
        .form-control, .form-select {
            background: #111827;
            border: 1px solid #334155;
            color: white;
            border-radius: 12px;
            padding: 10px 15px;
        }
        .form-control:focus, .form-select:focus {
            background: #111827;
            border-color: var(--accent-blue);
            color: white;
            box-shadow: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 700;
        }

        /* Activity Timeline */
        .timeline-item {
            border-left: 2px solid rgba(59, 130, 246, 0.2);
            padding-left: 20px;
            position: relative;
            padding-bottom: 15px;
            font-size: 0.85rem;
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -7px;
            top: 4px;
            width: 12px;
            height: 12px;
            background: var(--accent-blue);
            border-radius: 50%;
        }

        .modal-content {
            background: #0f172a;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>

    <div class="container py-5">
        
        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success bg-success bg-opacity-10 text-success border-0 mb-4 rounded-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- LEFT: PROFILE CARD -->
            <div class="col-lg-4">
                <div class="glass-card text-center">
                    <div class="profile-wrapper mb-4">
                        @if($siswa->foto_profile)
                            <img src="{{ asset('storage/' . $siswa->foto_profile) }}" class="profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=3b82f6&color=fff&size=128" class="profile-img">
                        @endif
                        <!-- Trigger Modal Edit Foto -->
                        <button class="btn-edit-photo shadow" data-bs-toggle="modal" data-bs-target="#modalProfile">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>

                    <h4 class="fw-800 mb-1">{{ $siswa->nama }}</h4>
                    <p class="text-secondary small mb-4">NIS: {{ $siswa->nis }} • Kelas: {{ $siswa->kelas }}</p>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalLapor">
                            <i class="bi bi-plus-lg me-2"></i>Buat Pengaduan
                        </button>
                        <a href="/logout" class="btn btn-outline-danger border-0 rounded-3 mt-2">Logout</a>
                    </div>

                    <hr class="my-4 border-secondary opacity-25">

                    <div class="text-start">
                        <h6 class="fw-700 mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Aktivitas</h6>
                        <div class="ms-2">
                            @forelse($logs as $log)
                                <div class="timeline-item">
                                    <div class="text-white">{{ $log->aktivitas }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <p class="small text-secondary">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: REPORTS LIST -->
            <div class="col-lg-8">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-800 mb-0">Laporan Saya</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3">Total: {{ count($pengaduan) }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table custom-table text-white">
                            <thead>
                                <tr class="text-secondary small">
                                    <th>BUKTI</th>
                                    <th>LOKASI & KETERANGAN</th>
                                    <th>STATUS</th>
                                    <th>WAKTU</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengaduan as $p)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/'.$p->foto) }}" class="img-report shadow-sm">
                                    </td>
                                    <td>
                                        <div class="fw-bold mb-1">{{ $p->lokasi }}</div>
                                        <div class="small text-secondary text-truncate" style="max-width: 200px;">{{ $p->ket }}</div>
                                        @if($p->aspirasi && $p->aspirasi->feedback)
                                            <div class="mt-1 p-2 rounded-3 bg-dark bg-opacity-50" style="font-size: 0.75rem; border-left: 2px solid #3b82f6;">
                                                <span class="text-primary fw-bold">Feedback:</span> {{ $p->aspirasi->feedback }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->aspirasi)
                                            @php $st = $p->aspirasi->status; @endphp
                                            <span class="badge-status {{ $st == 'Selesai' ? 'st-selesai' : ($st == 'Proses' ? 'st-proses' : 'st-menunggu') }}">
                                                {{ $st }}
                                            </span>
                                        @else
                                            <span class="badge-status st-menunggu text-uppercase">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small">{{ $p->created_at->format('d M Y') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">Belum ada laporan pengaduan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 1: UPDATE FOTO PROFILE -->
    <div class="modal fade" id="modalProfile" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title fw-800">Ganti Foto Profil</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- Sesuaikan URL action-nya -->
                <form action="{{ url('/siswa/update-foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <input type="file" name="foto_profile" class="form-control mb-3" required>
                        <p class="small text-secondary">Gunakan format JPG/PNG berkualitas baik.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary-custom w-100">Simpan Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: FORM LAPOR -->
    <div class="modal fade" id="modalLapor" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-800">Buat Pengaduan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/siswa/lapor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small text-secondary mb-2">Kategori Sarana</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small text-secondary mb-2">Lokasi Spesifik</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lab Komputer 2 / Toilet Lt. 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-secondary mb-2">Deskripsi Kerusakan</label>
                            <textarea name="ket" class="form-control" rows="3" placeholder="Jelaskan detail kerusakannya..." required></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="small text-secondary mb-2">Foto Bukti</label>
                            <input type="file" name="foto_kerusakan" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary-custom w-100">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>