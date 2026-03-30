<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050b18;
            --card-bg: rgba(30, 41, 59, 0.45);
            --accent-blue: #3b82f6;
        }

        body {
            background-color: var(--bg-color);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }

        .text-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 1.5rem;
        }

        /* Stat Cards */
        .stat-box {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Table Styling */
        .custom-table {
            border-collapse: separate;
            border-spacing: 0 12px;
        }
        .custom-table thead th {
            border: none;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding-left: 1.5rem;
        }
        .custom-table tbody tr {
            background: rgba(255, 255, 255, 0.03);
            transition: 0.3s;
        }
        .custom-table tbody td {
            border: none;
            padding: 1.2rem 1.5rem;
            vertical-align: middle;
        }
        .custom-table tbody td:first-child { border-radius: 16px 0 0 16px; }
        .custom-table tbody td:last-child { border-radius: 0 16px 16px 0; }

        /* Badge Pill */
        .badge-pill {
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .status-selesai { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
        .status-proses { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .status-menunggu { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }

        /* Modal FIX */
        .modal {
            background: rgba(5, 11, 24, 0.8);
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background: #0f172a;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }
        .modal-header, .modal-footer { border: none; }

        .img-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
        }

        .btn-action {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
        }
        
        /* Log Activity */
        .timeline { border-left: 2px solid rgba(255, 255, 255, 0.05); padding-left: 20px; }
        .timeline-item { position: relative; margin-bottom: 20px; font-size: 0.9rem; }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -27px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-blue);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- HEADER -->
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h2 class="fw-800 mb-1">Pusat Kendali <span class="text-gradient">Admin</span></h2>
                <p class="text-secondary">Dashboard pemantauan fasilitas • <span class="text-white fw-bold">{{ session('username') }}</span></p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="/logout" class="btn btn-outline-danger border-0 px-4">LogOut</a>
            </div>
        </div>

        <!-- STATS SECTION -->
        <div class="row g-4 mb-5 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="text-secondary small mb-1">Total Laporan</div>
                    <div class="h3 fw-bold mb-0 text-gradient">{{ count($laporan) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="text-secondary small mb-1">Menunggu</div>
                    <div class="h3 fw-bold mb-0 text-warning">
                        {{ count(array_filter($laporan->toArray(), fn($l) => ($l['aspirasi']['status'] ?? 'Menunggu') == 'Menunggu')) }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="text-secondary small mb-1">Dalam Proses</div>
                    <div class="h3 fw-bold mb-0 text-info">
                        {{ count(array_filter($laporan->toArray(), fn($l) => ($l['aspirasi']['status'] ?? '') == 'Proses')) }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="text-secondary small mb-1">Selesai</div>
                    <div class="h3 fw-bold mb-0 text-success">
                        {{ count(array_filter($laporan->toArray(), fn($l) => ($l['aspirasi']['status'] ?? '') == 'Selesai')) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- TABEL LAPORAN -->
            <div class="col-lg-8">
                <div class="glass-card">
                    <h5 class="fw-700 mb-4">Antrean Laporan Siswa</h5>
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>Pelapor</th>
                                    <th>Isi Laporan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporan as $l)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $l->siswa->nama ?? 'Siswa' }}</div>
                                        <div class="small text-secondary">{{ $l->nis }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('storage/'.$l->foto) }}" class="img-preview">
                                            <div class="small text-secondary">{{ Str::limit($l->ket, 30) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @php $status = $l->aspirasi->status ?? 'Menunggu'; @endphp
                                        <span class="badge-pill {{ $status == 'Selesai' ? 'status-selesai' : ($status == 'Proses' ? 'status-proses' : 'status-menunggu') }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Tombol Detail -->
                                        <button class="btn btn-action btn-sm" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-5 text-secondary">Tidak ada laporan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- LOG AKTIVITAS -->
            <div class="col-lg-4">
                <div class="glass-card">
                    <h5 class="fw-700 mb-4">Aktivitas Terbaru</h5>
                    <div class="timeline">
                        @foreach($logs as $log)
                        <div class="timeline-item">
                            <div class="fw-bold text-white small">{{ $log->nis ?? $log->username }}</div>
                            <div class="text-secondary" style="font-size: 0.8rem;">{{ $log->aktivitas }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SECTION (Diletakkan di luar loop tabel utama agar tidak error) -->
    @foreach($laporan as $l)
    <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="fw-800">Tindak Lanjut</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/admin/tanggapi') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_pelaporan" value="{{ $l->id_pelaporan }}">
                    <input type="hidden" name="id_kategori" value="{{ $l->id_kategori }}">
                    <div class="modal-body">
                        <img src="{{ asset('storage/'.$l->foto) }}" class="w-100 rounded-4 mb-3 shadow">
                        <div class="mb-3">
                            <label class="small text-secondary mb-2">Deskripsi Laporan</label>
                            <div class="p-3 bg-dark rounded-3 small">{{ $l->ket }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="small text-secondary mb-2">Update Status</label>
                            <select name="status" class="form-select bg-dark border-secondary text-white">
                                <option value="Menunggu" {{ ($l->aspirasi->status ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Proses" {{ ($l->aspirasi->status ?? '') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Selesai" {{ ($l->aspirasi->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="small text-secondary mb-2">Pesan Feedback</label>
                            <textarea name="feedback" class="form-control bg-dark border-secondary text-white" rows="3" required>{{ $l->aspirasi->feedback ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>