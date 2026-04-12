<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --bg-color: #050b18; --card-bg: rgba(30, 41, 59, 0.45); --accent-blue: #3b82f6; }
        body { background-color: var(--bg-color); color: #ffffff; font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; }
        .text-gradient { background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; }
        .glass-card { background: var(--card-bg); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 1.5rem; }
        
        /* Nav Tabs Custom */
        .nav-pills .nav-link { color: #94a3b8; border-radius: 12px; transition: 0.3s; }
        .nav-pills .nav-link.active { background: var(--accent-blue); color: white; }
        
        /* Table Styling */
        .custom-table { border-collapse: separate; border-spacing: 0 8px; }
        .custom-table tbody tr { background: rgba(255, 255, 255, 0.03); border-radius: 12px; }
        .custom-table td { border: none; padding: 1rem; vertical-align: middle; }
        .custom-table td:first-child { border-radius: 12px 0 0 12px; }
        .custom-table td:last-child { border-radius: 0 12px 12px 0; }

        .img-preview { width: 45px; height: 45px; object-fit: cover; border-radius: 10px; }
        .status-badge { padding: 5px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; }
        .modal-content { background: #0f172a; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .form-control, .form-select { background: #1e293b; border: 1px solid #334155; color: white; }
        .form-control:focus { background: #1e293b; color: white; border-color: var(--accent-blue); }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-800 mb-0">Pusat <span class="text-gradient">Kendali</span></h3>
                <p class="text-secondary small mb-0">Admin: {{ session('username') }}</p>
            </div>
            <a href="/logout" class="btn btn-sm btn-outline-danger px-3 rounded-pill">Logout</a>
        </div>

        <!-- NAVIGATION TABS -->
        <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-laporan"><i class="bi bi-chat-left-text me-2"></i>Laporan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-kategori"><i class="bi bi-grid me-2"></i>Kategori</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-users"><i class="bi bi-people me-2"></i>Pengguna</button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB 1: LAPORAN -->
            <div class="tab-pane fade show active" id="tab-laporan">
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><div class="glass-card text-center p-3 small text-secondary">Total: <b class="text-white">{{ count($laporan) }}</b></div></div>
                    <div class="col-md-3"><div class="glass-card text-center p-3 small text-secondary">Menunggu: <b class="text-warning">{{ count($laporan->where('aspirasi.status', 'Menunggu')) }}</b></div></div>
                </div>
                <div class="glass-card">
                    <div class="table-responsive">
                        <table class="table custom-table text-white">
                            <thead><tr class="text-secondary small"><th>PELAPOR</th><th>ISI LAPORAN</th><th>STATUS</th><th>AKSI</th></tr></thead>
                            <tbody>
                                @foreach($laporan as $l)
                                <tr>
                                    <td><div class="fw-bold small">{{ $l->siswa->nama ?? 'Siswa' }}</div><div class="text-muted" style="font-size: 0.7rem;">{{ $l->nis }}</div></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('storage/'.$l->foto) }}" class="img-preview">
                                            <span class="small">{{ Str::limit($l->ket, 25) }}</span>
                                        </div>
                                    </td>
                                    <td><span class="status-badge {{ $l->aspirasi->status == 'Selesai' ? 'bg-success' : ($l->aspirasi->status == 'Proses' ? 'bg-primary' : 'bg-warning') }}">{{ $l->aspirasi->status ?? 'Menunggu' }}</span></td>
                                    <td><button class="btn btn-sm btn-info text-white rounded-3" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">Detail</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: KATEGORI (CRUD) -->
            <div class="tab-pane fade" id="tab-kategori">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h5 class="mb-0">Manajemen Kategori</h5>
                    <button class="btn btn-primary btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalAddKategori">+ Kategori</button>
                </div>
                <div class="glass-card">
                    <table class="table custom-table text-white">
                        <thead><tr class="text-secondary small"><th>NAMA KATEGORI</th><th class="text-end">OPSI</th></tr></thead>
                        <tbody>
                            @foreach($kategori as $k)
                            <tr>
                                <td>{{ $k->nama_kategori }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ url('/admin/kategori/'.$k->id_kategori) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: USERS (CRUD) -->
            <div class="tab-pane fade" id="tab-users">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h5 class="mb-0">Daftar Pengguna</h5>
                    <button class="btn btn-primary btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalAddUser">+ Pengguna</button>
                </div>
                <div class="glass-card">
                    <table class="table custom-table text-white">
                        <thead><tr class="text-secondary small"><th>NAMA</th><th>NIS/USERNAME</th><th>ROLE</th><th class="text-end">OPSI</th></tr></thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr>
                                <td>{{ $u->nama }}</td>
                                <td>{{ $u->nis ?? $u->username }}</td>
                                <td><span class="badge bg-secondary">{{ isset($u->nis) ? 'Siswa' : 'Admin' }}</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-light"><i class="bi bi-gear"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH KATEGORI -->
    <div class="modal fade" id="modalAddKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ url('/admin/kategori') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0"><h5 class="fw-bold">Tambah Kategori</h5></div>
                    <div class="modal-body">
                        <label class="small text-secondary mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Fasilitas Kelas" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL ADD USER (Ringkasan) -->
    <div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ url('/admin/users') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0"><h5 class="fw-bold">Registrasi User Baru</h5></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="small mb-1">Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
                        <div class="mb-3"><label class="small mb-1">NIS / Username</label><input type="text" name="identity" class="form-control" required></div>
                        <div class="mb-3"><label class="small mb-1">Password</label><input type="password" name="password" class="form-control" required></div>
                    </div>
                    <div class="modal-footer border-0 px-3"><button type="submit" class="btn btn-primary w-100">Buat Akun</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Gunakan Loop Modal Tanggapi yang sudah kamu buat sebelumnya di sini -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>