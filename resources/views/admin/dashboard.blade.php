<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-color: #050b18;
            --primary-blue: #3b82f6;
            --accent-purple: #a855f7;
            --card-glass: rgba(255, 255, 255, 0.03);
            --border-glass: rgba(255, 255, 255, 0.08);
            --sidebar-width: 280px;
        }

        body {
            background-color: var(--bg-color);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background Glow Orbs */
        body::before {
            content: ""; position: fixed; width: 500px; height: 500px;
            background: var(--primary-blue); filter: blur(180px);
            border-radius: 50%; top: -10%; left: -100px; z-index: -1; opacity: 0.15;
        }
        body::after {
            content: ""; position: fixed; width: 400px; height: 400px;
            background: var(--accent-purple); filter: blur(180px);
            border-radius: 50%; bottom: -10%; right: -100px; z-index: -1; opacity: 0.1;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-glass);
            padding: 2rem 1.2rem;
            z-index: 1000;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2.5rem;
            min-height: 100vh;
        }

        .nav-link {
            color: #94a3b8;
            padding: 12px 18px;
            border-radius: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            border: none;
            width: 100%;
            background: transparent;
        }

        .nav-link i { font-size: 1.3rem; margin-right: 12px; }
        .nav-link:hover, .nav-link.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-blue);
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(168, 85, 247, 0.1) 100%);
            border-left: 4px solid var(--primary-blue);
            font-weight: 700;
        }

        /* Cards & Stats */
        .glass-card {
            background: var(--card-glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 1.8rem;
            height: 100%;
        }

        .text-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Modern Table */
        .custom-table { border-spacing: 0 10px; border-collapse: separate; }
        .custom-table tbody tr {
            background: rgba(255, 255, 255, 0.02);
            transition: 0.3s;
        }
        .custom-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-2px);
        }
        .custom-table td { 
            padding: 1.2rem; border: none; vertical-align: middle; 
            border-top: 1px solid var(--border-glass);
            border-bottom: 1px solid var(--border-glass);
        }
        .custom-table td:first-child { border-left: 1px solid var(--border-glass); border-radius: 16px 0 0 16px; }
        .custom-table td:last-child { border-right: 1px solid var(--border-glass); border-radius: 0 16px 16px 0; }

        /* Badges */
        .badge-modern {
            padding: 6px 14px; border-radius: 100px; font-size: 0.7rem;
            font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .st-waiting { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
        .st-process { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .st-done { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }

        /* Forms & Modals */
        .modal-content {
            background: #0f172a; border-radius: 24px; border: 1px solid var(--border-glass); backdrop-filter: blur(20px);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-glass);
            color: white; border-radius: 12px; padding: 12px;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.4) !important; }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.06); color: white; border-color: var(--primary-blue); box-shadow: none;
        }

        .btn-primary-custom {
            background: var(--primary-blue); border: none; border-radius: 12px;
            padding: 10px 24px; font-weight: 600; transition: 0.3s; color: white;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3); color: white; }

        .img-report { width: 55px; height: 55px; border-radius: 12px; object-fit: cover; }

        /* Log Section Styling (Scrollable) */
        .log-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .log-container::-webkit-scrollbar { width: 5px; }
        .log-container::-webkit-scrollbar-track { background: transparent; }
        .log-container::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }

        .log-item {
            position: relative; padding-left: 25px; padding-bottom: 1.5rem;
            border-left: 2px solid rgba(59, 130, 246, 0.2);
        }
        .log-item::before {
            content: ''; position: absolute; left: -7px; top: 5px;
            width: 12px; height: 12px; background: var(--primary-blue);
            border-radius: 50%; box-shadow: 0 0 10px var(--primary-blue);
        }

        @media (max-width: 992px) {
            .sidebar { width: 85px; padding: 2rem 0.5rem; }
            .sidebar span, .sidebar h5 { display: none; }
            .main-content { margin-left: 85px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="mb-5 px-3">
            <h5 class="fw-800"><i class="bi bi-shield-lock-fill text-primary"></i> <span class="ms-2 text-white">AdminFix</span></h5>
        </div>

        <nav class="nav flex-column" id="v-pills-tab" role="tablist">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-dash"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-laporan"><i class="bi bi-megaphone-fill"></i><span>Pengaduan</span></button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-kategori"><i class="bi bi-tags-fill"></i><span>Kategori</span></button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-user"><i class="bi bi-people-fill"></i><span>Data Siswa</span></button>
            
            <div class="mt-5 px-3 pt-4 border-top border-secondary border-opacity-25">
                <a href="/logout" class="nav-link text-danger opacity-75"><i class="bi bi-power"></i><span>Logout</span></a>
            </div>
        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-800 mb-1">Panel <span class="text-gradient">Administrator</span></h2>
                <p class="text-white opacity-75 small">Kelola laporan dan fasilitas sekolah secara efisien.</p>
            </div>
            <div class="d-flex align-items-center gap-3 glass-card py-2 px-3">
                <div class="text-end">
                    <p class="small fw-800 mb-0 text-white">{{ date('d M Y') }}</p>
                    <p class="small text-white-50 mb-0">System Admin</p>
                </div>
                <div class="bg-primary bg-opacity-10 rounded-3 p-2 border border-primary border-opacity-25">
                    <i class="bi bi-person-badge-fill text-primary fs-4"></i>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <!-- TAB 1: DASHBOARD -->
            <div class="tab-pane fade show active" id="pills-dash">
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="glass-card text-center">
                            <p class="text-white-50 small mb-1">TOTAL LAPORAN</p>
                            <h2 class="fw-800 mb-0 text-white">{{ count($laporan) }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card text-center" style="border-bottom: 3px solid #fbbf24;">
                            <p class="text-warning small mb-1">MENUNGGU</p>
                            <h2 class="fw-800 mb-0 text-warning">
                                {{ $laporan->filter(function($item) {
                                    return ($item->aspirasi->status ?? 'Menunggu') == 'Menunggu';
                                })->count() }}
                            </h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card text-center" style="border-bottom: 3px solid #4ade80;">
                            <p class="text-success small mb-1">SELESAI</p>
                            <h2 class="fw-800 mb-0 text-success">
                                {{ $laporan->filter(function($item) {
                                    return ($item->aspirasi->status ?? '') == 'Selesai';
                                })->count() }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="glass-card">
                            <h6 class="fw-800 mb-4 text-white">Aktivitas Pelaporan Terkini</h6>
                            <div class="table-responsive">
                                <table class="table text-white custom-table">
                                    <thead><tr><th>Siswa</th><th>Laporan</th><th>Status</th></tr></thead>
                                    <tbody>
                                        @foreach($laporan->take(5) as $l)
                                        <tr>
                                            <td class="fw-700">{{ $l->siswa->nama }}</td>
                                            <td class="text-white-50 small">{{ Str::limit($l->ket, 35) }}</td>
                                            <td>
                                                @php $st = $l->aspirasi->status ?? 'Menunggu'; @endphp
                                                <span class="badge-modern {{ $st == 'Selesai' ? 'st-done' : ($st == 'Proses' ? 'st-process' : 'st-waiting') }}">{{ $st }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="glass-card">
                            <h6 class="fw-800 mb-4 text-white">Log Sistem</h6>
                            <div class="log-container">
                                @forelse($logs as $log)
                                <div class="log-item">
                                    <p class="small fw-700 mb-0 text-info">{{ $log->username ?? $log->nis }}</p>
                                    <p class="small text-white-50 mb-0">{{ $log->aktivitas }}</p>
                                    <small class="opacity-50 text-white" style="font-size: 0.65rem;">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history text-white-50 fs-1 opacity-25"></i>
                                    <p class="text-white-50 small mt-2">Tidak ada log aktivitas.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: LAPORAN -->
            <div class="tab-pane fade" id="pills-laporan">
                <div class="glass-card">
                    <h5 class="fw-800 mb-4 text-white">Manajemen Pengaduan</h5>
                    <div class="table-responsive">
                        <table class="table text-white custom-table">
                            <thead><tr><th>Info Siswa</th><th>Bukti</th><th>Keterangan</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
                            <tbody>
                                @foreach($laporan as $l)
                                <tr>
                                    <td><div class="fw-700 small">{{ $l->siswa->nama }}</div><div class="text-white-50 small" style="font-size: 0.7rem;">NIS: {{ $l->nis }}</div></td>
                                    <td><img src="{{ asset('storage/'.$l->foto) }}" class="img-report shadow"></td>
                                    <td class="text-white-50 small">{{ Str::limit($l->ket, 50) }}</td>
                                    <td>
                                        @php $st = $l->aspirasi->status ?? 'Menunggu'; @endphp
                                        <span class="badge-modern {{ $st == 'Selesai' ? 'st-done' : ($st == 'Proses' ? 'st-process' : 'st-waiting') }}">{{ $st }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary-custom btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">Kelola</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: KATEGORI -->
            <div class="tab-pane fade" id="pills-kategori">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 text-white">Kategori Fasilitas</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAddKategori"><i class="bi bi-plus-lg me-2"></i>Kategori Baru</button>
                </div>
                <div class="glass-card">
                    <table class="table text-white custom-table">
                        <thead><tr><th>ID</th><th>Nama Kategori</th><th class="text-end">Opsi</th></tr></thead>
                        <tbody>
                            @foreach($kategori as $k)
                            <tr>
                                <td class="text-primary fw-800">#{{ $k->id_kategori }}</td>
                                <td>{{ $k->ket_kategori }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info border-0" data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $k->id_kategori }}"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="confirmDelete('/admin/kategori/hapus/{{ $k->id_kategori }}', 'Kategori ini akan dihapus permanen!')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: DATA SISWA -->
            <div class="tab-pane fade" id="pills-user">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 text-white">Data Master Siswa</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAddSiswa"><i class="bi bi-person-plus-fill me-2"></i>Tambah Siswa</button>
                </div>
                <div class="glass-card">
                    <table class="table text-white custom-table">
                        <thead><tr><th>NIS</th><th>Nama Lengkap</th><th class="text-end">Opsi</th></tr></thead>
                        <tbody>
                            @foreach($siswa as $s)
                            <tr>
                                <td class="fw-800">{{ $s->nis }}</td>
                                <td>{{ $s->nama }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="confirmDelete('/admin/siswa/hapus/{{ $s->nis }}', 'Akun siswa {{ $s->nama }} akan dihapus!')">
                                        <i class="bi bi-person-x-fill fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TANGGAPI -->
    @foreach($laporan as $l)
    <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form action="/admin/tanggapi" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="id_pelaporan" value="{{ $l->id_pelaporan }}">
                <input type="hidden" name="id_kategori" value="{{ $l->id_kategori }}">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-5 text-center">
                            <img src="{{ asset('storage/'.$l->foto) }}" class="img-fluid rounded-4 shadow-lg border border-white border-opacity-10">
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between mb-2">
                                <h5 class="fw-800 text-white">Proses Laporan</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <p class="text-white small mb-4">Pelapor: <span class="fw-bold">{{ $l->siswa->nama }}</span></p>
                            
                            <div class="mb-3">
                                <label class="small text-white mb-2 fw-600">Update Status</label>
                                <select name="status" class="form-select text-white">
                                    <option value="Menunggu" class="text-dark" {{ ($l->aspirasi->status ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Proses" class="text-dark" {{ ($l->aspirasi->status ?? '') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" class="text-dark" {{ ($l->aspirasi->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="small text-white mb-2 fw-600">Tanggapan Admin (Feedback)</label>
                                <textarea name="feedback" class="form-control" rows="4" placeholder="Jelaskan tindakan..." required>{{ $l->aspirasi->feedback ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100 py-3">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- MODAL EDIT KATEGORI (Perbaikan: Tambahkan loop ini) -->
    @foreach($kategori as $k)
    <div class="modal fade" id="modalEditKategori{{ $k->id_kategori }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/kategori/update" method="POST" class="modal-content p-3">
                @csrf
                <input type="hidden" name="id_kategori" value="{{ $k->id_kategori }}">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Edit Nama Kategori</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="ket_kategori" class="form-control" value="{{ $k->ket_kategori }}" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Modal Add Kategori -->
    <div class="modal fade" id="modalAddKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/kategori" method="POST" class="modal-content p-3">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Tambah Kategori Baru</h6>
                </div>
                <div class="modal-body">
                    <input type="text" name="ket_kategori" class="form-control" placeholder="Nama Kategori..." required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Add Siswa -->
    <div class="modal fade" id="modalAddSiswa" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/siswa" method="POST" class="modal-content p-3">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Daftarkan Siswa Baru</h6>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><input type="number" name="nis" class="form-control" placeholder="NIS" required></div>
                    <div class="mb-3"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
                    <div class="mb-3"><input type="text" name="kelas" class="form-control" placeholder="Kelas (Contoh: XII RPL 1)" required></div>
                    <div><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Daftarkan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Pop-up Konfirmasi Hapus -->
    <script>
        function confirmDelete(url, message) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }

        // Tampilkan pesan sukses jika ada session success
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
    </script>
</body>
</html>