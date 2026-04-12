<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --bg-darker: #0b1120;
            --bg-card: #1e293b;
            --accent: #3b82f6;
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
        }

        body {
            background-color: var(--bg-darker);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: #0f172a;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem 1.5rem;
            z-index: 1000;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        .nav-link {
            color: var(--text-sub);
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            transition: 0.3s;
            border: none;
            width: 100%;
            background: transparent;
            text-align: left;
        }

        .nav-link i { font-size: 1.2rem; margin-right: 15px; }
        .nav-link:hover, .nav-link.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent);
        }
        .nav-link.active { font-weight: 600; }

        /* Card Styling */
        .glass-card {
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 1.5rem;
            height: 100%;
        }

        /* Table Styling */
        .table { color: var(--text-main); }
        .table thead th {
            color: var(--text-sub);
            font-size: 0.75rem;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem;
        }
        .table td { padding: 1.2rem 1rem; border: none; vertical-align: middle; }
        .table tbody tr { border-bottom: 1px solid rgba(255, 255, 255, 0.02); }

        /* Badge Status */
        .badge-status {
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .st-waiting { background: rgba(245, 158, 11, 0.1); color: #fbbf24; }
        .st-process { background: rgba(59, 130, 246, 0.1); color: #60a5fa; }
        .st-done { background: rgba(34, 197, 94, 0.1); color: #4ade80; }

        /* Log Timeline */
        .log-container { max-height: 500px; overflow-y: auto; }
        .log-item {
            position: relative;
            padding-left: 25px;
            padding-bottom: 1.5rem;
            border-left: 2px solid rgba(255,255,255,0.05);
        }
        .log-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 5px;
            width: 12px;
            height: 12px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--accent);
        }

        .img-report { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; }
        
        .form-control, .form-select {
            background: #0f172a; border: 1px solid #334155; color: white; border-radius: 10px;
        }

        @media (max-width: 992px) {
            .sidebar { width: 80px; padding: 2rem 0.5rem; }
            .sidebar span { display: none; }
            .main-content { margin-left: 80px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="mb-5 px-3">
            <h5 class="fw-bold"><i class="bi bi-shield-check text-primary"></i> <span class="ms-2">SkoFix</span></h5>
        </div>

        <nav class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-dash"><i class="bi bi-grid-1x2"></i><span>Dashboard</span></button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-laporan"><i class="bi bi-chat-left-text"></i><span>Laporan</span></button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-kategori"><i class="bi bi-tags"></i><span>Kategori</span></button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-user"><i class="bi bi-people"></i><span>Data Siswa</span></button>
            
            <div class="mt-5 px-3 pt-4 border-top border-secondary border-opacity-25">
                <a href="/logout" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i><span>Keluar</span></a>
            </div>
        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h4 class="fw-bold mb-0 text-white">Selamat Datang Admin</h4>
                <p class="text-secondary small">Pantau dan kelola laporan sarana sekolah di sini.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <p class="small fw-bold mb-0">{{ date('d M Y') }}</p>
                    <p class="small text-secondary mb-0">Administrator</p>
                </div>
                <div class="bg-primary rounded-circle" style="width: 45px; height: 45px; display: grid; place-items: center;">
                    <i class="bi bi-person-fill text-white fs-5"></i>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <!-- TAB 1: DASHBOARD (Overview + Log) -->
            <div class="tab-pane fade show active" id="pills-dash">
                <div class="row g-4 mb-4 text-center">
                    <div class="col-md-4">
                        <div class="glass-card">
                            <p class="text-secondary small mb-1">Total Pengaduan</p>
                            <h2 class="fw-bold mb-0 text-white">{{ count($laporan) }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card">
                            <p class="text-secondary small mb-1">Menunggu Respon</p>
                            <h2 class="fw-bold mb-0 text-warning">{{ count($laporan->where('aspirasi.status', 'Menunggu')) }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card">
                            <p class="text-secondary small mb-1">Sudah Selesai</p>
                            <h2 class="fw-bold mb-0 text-success">{{ count($laporan->where('aspirasi.status', 'Selesai')) }}</h2>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="glass-card">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Laporan Terbaru</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead><tr><th>PELAPOR</th><th>JUDUL LAPORAN</th><th>STATUS</th></tr></thead>
                                    <tbody>
                                        @foreach($laporan->take(5) as $l)
                                        <tr>
                                            <td><small class="fw-bold">{{ $l->siswa->nama }}</small></td>
                                            <td><small class="text-secondary">{{ Str::limit($l->ket, 40) }}</small></td>
                                            <td>
                                                @php $st = $l->aspirasi->status ?? 'Menunggu'; @endphp
                                                <span class="badge-status {{ $st == 'Selesai' ? 'st-done' : ($st == 'Proses' ? 'st-process' : 'st-waiting') }}">{{ $st }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- TABEL LOG AKTIVITAS -->
                    <div class="col-lg-4">
                        <div class="glass-card">
                            <h6 class="fw-bold mb-4">Aktivitas Terakhir</h6>
                            <div class="log-container px-2">
                                @forelse($logs as $log)
                                <div class="log-item">
                                    <p class="small fw-bold mb-1 text-white">{{ $log->username ?? $log->nis }}</p>
                                    <p class="small text-secondary mb-1">{{ $log->aktivitas }}</p>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                                @empty
                                <p class="text-center text-secondary small">Belum ada aktivitas.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: LAPORAN (Detail) -->
            <div class="tab-pane fade" id="pills-laporan">
                <div class="glass-card">
                    <h5 class="fw-bold mb-4">Kelola Pengaduan Siswa</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Info Siswa</th><th>Foto</th><th>Keterangan</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
                            <tbody>
                                @foreach($laporan as $l)
                                <tr>
                                    <td><div class="fw-bold small">{{ $l->siswa->nama }}</div><div class="text-muted small">{{ $l->nis }}</div></td>
                                    <td><img src="{{ asset('storage/'.$l->foto) }}" class="img-report shadow-sm"></td>
                                    <td><small class="text-secondary">{{ Str::limit($l->ket, 50) }}</small></td>
                                    <td>
                                        @php $st = $l->aspirasi->status ?? 'Menunggu'; @endphp
                                        <span class="badge-status {{ $st == 'Selesai' ? 'st-done' : ($st == 'Proses' ? 'st-process' : 'st-waiting') }}">{{ $st }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">Kelola</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: KATEGORI (CRUD) -->
            <div class="tab-pane fade" id="pills-kategori">
                <div class="d-flex justify-content-between mb-4">
                    <h5 class="fw-bold">Master Kategori Sarana</h5>
                    <button class="btn btn-primary rounded-pill btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalAddKategori">+ Tambah Kategori</button>
                </div>
                <div class="glass-card">
                    <table class="table">
                        <thead><tr><th>ID</th><th>Keterangan Kategori</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            @foreach($kategori as $k)
                            <tr>
                                <td>{{ $k->id_kategori }}</td>
                                <td>{{ $k->ket_kategori }}</td>
                                <td class="text-end">
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-sm btn-outline-warning border-0 me-2" data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $k->id_kategori }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <!-- Tombol Hapus -->
                                    <a href="/admin/kategori/hapus/{{ $k->id_kategori }}" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus kategori?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: DATA SISWA (CRUD) -->
            <div class="tab-pane fade" id="pills-user">
                <div class="d-flex justify-content-between mb-4">
                    <h5 class="fw-bold">Database Akun Siswa</h5>
                    <button class="btn btn-primary rounded-pill btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalAddSiswa">+ Akun Baru</button>
                </div>
                <div class="glass-card">
                    <table class="table">
                        <thead><tr><th>NIS</th><th>Nama Siswa</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            @foreach($siswa as $s)
                            <tr>
                                <td class="fw-bold">{{ $s->nis }}</td>
                                <td>{{ $s->nama }}</td>
                                <td class="text-end">
                                    <a href="/admin/siswa/hapus/{{ $s->nis }}" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus akun ini?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SEMUA MODAL -->
    <!-- Modal Tanggapi -->
    @foreach($laporan as $l)
    <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form action="/admin/tanggapi" method="POST" class="modal-content glass-card p-0 border-0 shadow-lg">
                @csrf
                <input type="hidden" name="id_pelaporan" value="{{ $l->id_pelaporan }}">
                <input type="hidden" name="id_kategori" value="{{ $l->id_kategori }}">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <img src="{{ asset('storage/'.$l->foto) }}" class="w-100 rounded-4 shadow">
                        </div>
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-1">Detail Laporan</h5>
                            <p class="small text-secondary mb-3">Pelapor: {{ $l->siswa->nama }} ({{ $l->nis }})</p>
                            <div class="bg-dark p-3 rounded-3 mb-3 small text-white-50 border border-secondary border-opacity-25">{{ $l->ket }}</div>
                            <div class="mb-3">
                                <label class="small text-secondary mb-1">Status Penanganan</label>
                                <select name="status" class="form-select">
                                    <option value="Menunggu" {{ ($l->aspirasi->status ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Proses" {{ ($l->aspirasi->status ?? '') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ ($l->aspirasi->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="small text-secondary mb-1">Tanggapan/Feedback</label>
                                <textarea name="feedback" class="form-control" rows="3" required placeholder="Tulis instruksi atau hasil perbaikan...">{{ $l->aspirasi->feedback ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Modal Add Kategori -->
    <div class="modal fade" id="modalAddKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/kategori" method="POST" class="modal-content glass-card p-4 border-0">
                @csrf
                <h6 class="fw-bold mb-3">Tambah Kategori Baru</h6>
                <input type="text" name="ket_kategori" class="form-control mb-4" placeholder="Keterangan kategori..." required>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-100 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT KATEGORI (Looping) -->
    @foreach($kategori as $k)
    <div class="modal fade" id="modalEditKategori{{ $k->id_kategori }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/kategori/update/{{ $k->id_kategori }}" method="POST" class="modal-content glass-card p-4 border-0">
                @csrf
                <h6 class="fw-bold mb-3 text-white">Edit Kategori</h6>
                <div class="mb-4">
                    <label class="small text-secondary mb-1">Nama / Keterangan Kategori</label>
                    <input type="text" name="ket_kategori" class="form-control" value="{{ $k->ket_kategori }}" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-100 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning w-100 rounded-pill text-dark fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Modal Add Siswa -->
    <div class="modal fade" id="modalAddSiswa" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/siswa" method="POST" class="modal-content glass-card p-4 border-0">
                @csrf
                <h6 class="fw-bold mb-3">Daftarkan Akun Siswa</h6>
                <div class="mb-3"><input type="number" name="nis" class="form-control" placeholder="NIS" required></div>
                <div class="mb-3"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
                <div class="mb-4"><input type="password" name="password" class="form-control" placeholder="Password Akun" required></div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-100 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Daftarkan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>