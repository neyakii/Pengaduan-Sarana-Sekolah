<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            text-align: left;
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

        .custom-table { border-spacing: 0 10px; border-collapse: separate; }
        .custom-table tbody tr { background: rgba(255, 255, 255, 0.02); transition: 0.3s; }
        .custom-table tbody tr:hover { background: rgba(255, 255, 255, 0.05); transform: translateY(-2px); }
        .custom-table td { 
            padding: 1.2rem; border: none; vertical-align: middle; 
            border-top: 1px solid var(--border-glass);
            border-bottom: 1px solid var(--border-glass);
        }
        .custom-table td:first-child { border-left: 1px solid var(--border-glass); border-radius: 16px 0 0 16px; }
        .custom-table td:last-child { border-right: 1px solid var(--border-glass); border-radius: 0 16px 16px 0; }

        .badge-modern {
            padding: 6px 14px; border-radius: 100px; font-size: 0.7rem;
            font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .st-waiting { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
        .st-process { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .st-done { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }

        .modal-content {
            background: #0f172a; border-radius: 24px; border: 1px solid var(--border-glass); backdrop-filter: blur(20px);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-glass);
            color: white; border-radius: 12px; padding: 12px;
        }
        .form-select option { background: #0f172a; color: white; }
        
        .btn-primary-custom {
            background: var(--primary-blue); border: none; border-radius: 12px;
            padding: 10px 24px; font-weight: 600; transition: 0.3s; color: white;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3); color: white; }

        .img-report { width: 55px; height: 55px; border-radius: 12px; object-fit: cover; }

        .log-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }
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
            <button class="nav-link active" id="tab-dash" data-bs-toggle="pill" data-bs-target="#pills-dash" type="button" role="tab"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></button>
            <button class="nav-link" id="tab-laporan" data-bs-toggle="pill" data-bs-target="#pills-laporan" type="button" role="tab"><i class="bi bi-megaphone-fill"></i><span>Pengaduan</span></button>
            <button class="nav-link" id="tab-kategori" data-bs-toggle="pill" data-bs-target="#pills-kategori" type="button" role="tab"><i class="bi bi-tags-fill"></i><span>Kategori</span></button>
            <button class="nav-link" id="tab-lokasi" data-bs-toggle="pill" data-bs-target="#pills-lokasi" type="button" role="tab"><i class="bi bi-geo-alt-fill"></i><span>Lokasi</span></button>
            <button class="nav-link" id="tab-user" data-bs-toggle="pill" data-bs-target="#pills-user" type="button" role="tab"><i class="bi bi-people-fill"></i><span>Data Siswa</span></button>
            
            <div class="mt-5 px-3 pt-4 border-top border-secondary border-opacity-25">
                <a href="/logout" class="nav-link text-danger opacity-75"><i class="bi bi-power"></i><span>Logout</span></a>
            </div>
        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
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

        <div class="tab-content" id="v-pills-tabContent">
            <!-- TAB 1: DASHBOARD -->
            <div class="tab-pane fade show active" id="pills-dash" role="tabpanel">
                <!-- Grid diubah menjadi col-md-3 agar 4 card sejajar -->
                <div class="row g-3 mb-5"> 
                    <!-- CARD TOTAL -->
                    <div class="col-md-3 col-6">
                        <div class="glass-card text-center">
                            <p class="text-white-50 small mb-1">TOTAL LAPORAN</p>
                            <h2 class="fw-800 mb-0 text-white">{{ count($laporan) }}</h2>
                        </div>
                    </div>

                    <!-- CARD MENUNGGU -->
                    <div class="col-md-3 col-6">
                        <div class="glass-card text-center" style="border-bottom: 3px solid #fbbf24;">
                            <p class="text-warning small mb-1">MENUNGGU</p>
                            <h2 class="fw-800 mb-0 text-warning">
                                {{ $laporan->filter(fn($i) => ($i->aspirasi->status ?? 'Menunggu') == 'Menunggu')->count() }}
                            </h2>
                        </div>
                    </div>

                    <!-- CARD PROSES -->
                    <div class="col-md-3 col-6">
                        <div class="glass-card text-center" style="border-bottom: 3px solid #3b82f6;">
                            <p class="text-primary small mb-1">PROSES</p>
                            <h2 class="fw-800 mb-0 text-primary">
                                {{ $laporan->filter(fn($i) => ($i->aspirasi->status ?? '') == 'Proses')->count() }}
                            </h2>
                        </div>
                    </div>

                    <!-- CARD SELESAI -->
                    <div class="col-md-3 col-6">
                        <div class="glass-card text-center" style="border-bottom: 3px solid #4ade80;">
                            <p class="text-success small mb-1">SELESAI</p>
                            <h2 class="fw-800 mb-0 text-success">
                                {{ $laporan->filter(fn($i) => ($i->aspirasi->status ?? '') == 'Selesai')->count() }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="glass-card">
                            <h6 class="fw-800 mb-4 text-white">Aktivitas Pelaporan Terkini</h6>
                            <div class="table-responsive">
                                <table class="table text-black custom-table">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Laporan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($laporan->take(5) as $l)
                                        <tr>
                                            <td class="fw-700">{{ $l->siswa->nama }}</td>
                                            <td class="text-black-50 small">{{ Str::limit($l->ket, 35) }}</td>
                                            <td>
                                                @php $st = $l->aspirasi->status ?? 'Menunggu'; @endphp
                                                <span class="badge-modern {{ $st == 'Selesai' ? 'st-done' : ($st == 'Proses' ? 'st-process' : 'st-waiting') }}">
                                                    {{ $st }}
                                                </span>
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
                                    <small class="opacity-50 text-white" style="font-size: 0.65rem;">
                                        {{ $log->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @empty
                                <p class="text-white-50 small">Tidak ada log.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: LAPORAN -->
<div class="tab-pane fade" id="pills-laporan" role="tabpanel">
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-800 mb-0 text-white">Manajemen Pengaduan</h5>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill small">
                <i class="bi bi-filter-left me-1"></i> Urutan Terbaru
            </span>
        </div>
        
        <div class="table-responsive">
            <table class="table text-white custom-table">
                <thead>
                    <tr class="text-white-50 small" style="text-transform: uppercase; letter-spacing: 1px;">
                        <th>Bukti</th>
                        <th>Pelapor & Kategori</th>
                        <th>Detail Kerusakan & Lokasi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $l)
                    <tr>
                        <!-- Bukti Foto -->
                        <td style="width: 80px;">
                            <div class="position-relative">
                                <img src="{{ asset('storage/'.$l->foto) }}" class="img-report shadow-sm border border-white border-opacity-10">
                            </div>
                        </td>

                        <!-- Info Siswa & Kategori -->
                        <td>
                            <div class="fw-700 text-black mb-1">
                                <i class="bi bi-person-circle me-1 text-primary-emphasis"></i> {{ $l->siswa->nama }}
                            </div>
                            <div class="badge bg-white bg-opacity-10 text-black-50 fw-normal" style="font-size: 0.65rem;">
                                <i class="bi bi-tag-fill me-1"></i> {{ $l->kategori->ket_kategori ?? 'Umum' }}
                            </div>
                        </td>

                        <!-- Keterangan & Lokasi (INI YANG DITAMBAHKAN) -->
                        <td>
                            <div class="text-black small fw-600 mb-1">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $l->lokasi_relasi->nama_lokasi ?? 'Lokasi tidak ada' }}
                            </div>
                            <p class="text-black-50 small mb-0" style="max-width: 250px; line-height: 1.4;">
                                {{ Str::limit($l->ket, 60) }}
                            </p>
                        </td>

                        <!-- Status Badge -->
                        <td>
                            @php $st = $l->aspirasi->status ?? 'Menunggu'; @endphp
                            <div class="d-flex flex-column align-items-start">
                                <span class="badge-modern {{ $st == 'Selesai' ? 'st-done' : ($st == 'Proses' ? 'st-process' : 'st-waiting') }}">
                                    {{ $st }}
                                </span>
                                <small class="text-black-50 mt-1" style="font-size: 0.6rem;">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $l->created_at->format('d/m/y') }}
                                </small>
                            </div>
                        </td>

                        <!-- Tombol Kelola -->
                        <td class="text-center">
                            <button class="btn btn-primary-custom btn-sm px-3 shadow-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">
                                <i class="bi bi-gear-fill me-1"></i> Kelola
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

            <!-- TAB 3: KATEGORI -->
            <div class="tab-pane fade" id="pills-kategori" role="tabpanel">
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
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="confirmDelete('/admin/kategori/hapus/{{ $k->id_kategori }}', 'Hapus kategori ini?')"><i class="bi bi-trash-fill"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: LOKASI (YANG BARU) -->
            <div class="tab-pane fade" id="pills-lokasi" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 text-white">Daftar Lokasi Fasilitas</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAddLokasi">
                        <i class="bi bi-plus-lg me-2"></i>Lokasi Baru
                    </button>
                </div>
                <div class="glass-card">
                    <table class="table text-white custom-table">
                        <thead><tr><th>ID</th><th>Nama Lokasi</th><th class="text-end">Opsi</th></tr></thead>
                        <tbody>
                            @foreach($lokasi as $l)
                            <tr>
                                <td class="text-primary fw-800">#{{ $l->id_lokasi }}</td>
                                <td>{{ $l->nama_lokasi }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info border-0" data-bs-toggle="modal" data-bs-target="#modalEditLokasi{{ $l->id_lokasi }}"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="confirmDelete('/admin/lokasi/hapus/{{ $l->id_lokasi }}', 'Hapus lokasi ini?')"><i class="bi bi-trash-fill"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 5: DATA SISWA -->
            <div class="tab-pane fade" id="pills-user" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-800 text-white">Data Master Siswa</h5>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAddSiswa"><i class="bi bi-person-plus-fill me-2"></i>Tambah Siswa</button>
                </div>
                <div class="glass-card">
                    <table class="table text-white custom-table">
                        <thead><tr><th>NIS</th><th>Nama Lengkap</th><th class="text-end">Opsi</th></tr></thead>
                        <tbody>
                            @foreach($siswa as $s)
                            <tr><td>{{ $s->nis }}</td><td>{{ $s->nama }}</td><td class="text-end"><button class="btn btn-sm btn-outline-danger border-0" onclick="confirmDelete('/admin/siswa/hapus/{{ $s->nis }}', 'Hapus siswa?')"><i class="bi bi-person-x-fill fs-5"></i></button></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SEMUA MODAL -->
    
    <!-- MODAL ADD LOKASI -->
    <div class="modal fade" id="modalAddLokasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/lokasi" method="POST" class="modal-content p-3">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Tambah Lokasi Baru</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="nama_lokasi" class="form-control" placeholder="Nama Lokasi (Contoh: Gedung A)" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT LOKASI -->
    @foreach($lokasi as $l)
    <div class="modal fade" id="modalEditLokasi{{ $l->id_lokasi }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/lokasi/update" method="POST" class="modal-content p-3">
                @csrf
                <input type="hidden" name="id_lokasi" value="{{ $l->id_lokasi }}">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Edit Nama Lokasi</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="nama_lokasi" class="form-control" value="{{ $l->nama_lokasi }}" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- MODAL ADD KATEGORI -->
    <div class="modal fade" id="modalAddKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/kategori" method="POST" class="modal-content p-3">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Tambah Kategori Baru</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="ket_kategori" class="form-control" placeholder="Nama Kategori..." required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT KATEGORI -->
    @foreach($kategori as $k)
    <div class="modal fade" id="modalEditKategori{{ $k->id_kategori }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/kategori/update" method="POST" class="modal-content p-3">
                @csrf
                <input type="hidden" name="id_kategori" value="{{ $k->id_kategori }}">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Edit Kategori</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="ket_kategori" class="form-control" value="{{ $k->ket_kategori }}" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- MODAL TANGGAPI -->
    @foreach($laporan as $l)
    <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <!-- TAMBAHKAN enctype="multipart/form-data" UNTUK UPLOAD FILE -->
            <form action="/admin/tanggapi" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <input type="hidden" name="id_pelaporan" value="{{ $l->id_pelaporan }}">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <label class="small text-white-50 mb-2">Foto Laporan Siswa:</label>
                            <img src="{{ asset('storage/'.$l->foto) }}" class="img-fluid rounded-4 shadow border border-white border-opacity-10 mb-3">
                            
                            <!-- Tampilkan foto bukti jika sudah ada sebelumnya -->
                            @if(isset($l->aspirasi->foto))
                            <label class="small text-success mb-2">Foto Bukti Perbaikan Saat Ini:</label>
                            <img src="{{ asset('storage/'.$l->aspirasi->foto) }}" class="img-fluid rounded-4 border border-success border-opacity-50">
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h5 class="fw-800 text-white mb-3">Kelola Laporan</h5>
                            
                            <div class="mb-3">
                                <label class="small text-white-50 mb-2">Update Status</label>
                                <select name="status" class="form-select">
                                    <option value="Menunggu" {{ ($l->aspirasi->status ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Proses" {{ ($l->aspirasi->status ?? '') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Selesai" {{ ($l->aspirasi->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <!-- INPUT FILE UNTUK BUKTI PERBAIKAN -->
                            <div class="mb-3">
                                <label class="small text-white-50 mb-2">Unggah Bukti Perbaikan (Opsional)</label>
                                <input type="file" name="foto_bukti" class="form-control" accept="image/*">
                                <small class="text-white-50" style="font-size: 0.7rem;">Format: JPG, PNG, JPEG. Maks 2MB</small>
                            </div>

                            <div class="mb-4">
                                <label class="small text-white-50 mb-2">Tanggapan Admin</label>
                                <textarea name="feedback" class="form-control" rows="4" required>{{ $l->aspirasi->feedback ?? '' }}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary-custom w-100">Simpan Tanggapan & Foto</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach
    
    <!-- MODAL TAMBAH SISWA -->
    <div class="modal fade" id="modalAddSiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/siswa" method="POST" class="modal-content p-3">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-800 text-white">Daftarkan Siswa</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><input type="number" name="nis" class="form-control" placeholder="NIS" required></div>
                    <div class="mb-3"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
                    <div class="mb-3"><input type="text" name="kelas" class="form-control" placeholder="Kelas" required></div>
                    <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary-custom w-100">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(url, message) {
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                background: '#0f172a',
                color: '#ffffff'
            }).then((result) => { if (result.isConfirmed) window.location.href = url; })
        }

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