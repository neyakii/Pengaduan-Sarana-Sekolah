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
                    <div class="col-md-6">
                        <h2 class="fw-800 mb-1">Selamat Datang, <span class="text-gradient">{{ explode(' ', $siswa->nama)[0] }}!</span></h2>
                        <p class="text-white-50 small">Pantau status perbaikan fasilitas sekolahmu di sini.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="stat-mini-card">
                                    <div class="text-white-50 mb-1" style="font-size: 0.65rem;">TOTAL</div>
                                    <div class="h5 fw-800 mb-0">{{ count($pengaduan) }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-mini-card">
                                    <div class="text-warning mb-1" style="font-size: 0.65rem;">PROSES</div>
                                    <div class="h5 fw-800 mb-0 text-warning">{{ $pengaduan->where('aspirasi.status', 'Proses')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-mini-card">
                                    <div class="text-success mb-1" style="font-size: 0.65rem;">SELESAI</div>
                                    <div class="h5 fw-800 mb-0 text-success">{{ $pengaduan->where('aspirasi.status', 'Selesai')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-800 mb-0">Laporan Saya</h5>
                        <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-3 py-2 small" style="font-size: 0.7rem;">Real-time Update</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table custom-table text-white">
                            <thead>
                                <tr class="text-black-50" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                                    <th>Bukti</th>
                                    <th>Detail Kerusakan</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengaduan as $p)
                                @php
                                    $status = $p->aspirasi->status ?? 'Menunggu';
                                    $badgeClass = ($status == 'Selesai') ? 'st-selesai' : (($status == 'Proses') ? 'st-proses' : 'st-menunggu');
                                @endphp
                                <tr>
                                    <td style="width: 100px;">
                                        <img src="{{ asset('storage/'.$p->foto) }}" class="rounded-3 shadow" style="width: 70px; height: 70px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-700 mb-1 text-black">{{ $p->lokasi }}</div>
                                        <p class="text-black-50 small mb-2" style="line-height: 1.4;">{{ $p->ket }}</p>
                                        
                                        @if($p->aspirasi && $p->aspirasi->feedback)
                                            <div class="p-2 mb-2 rounded bg-primary bg-opacity-10 border-start border-primary border-3" style="font-size: 0.75rem;">
                                                <i class="bi bi-chat-left-dots-fill me-1 text-primary"></i>
                                                <span class="text-black">{{ $p->aspirasi->feedback }}</span>
                                            </div>
                                        @endif

                                        <!-- ACTION BUTTONS (Only if Status is Menunggu) -->
                                        @if($status == 'Menunggu')
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-info me-1 py-1 px-3 rounded-pill" 
                                                    style="font-size: 0.7rem;"
                                                    onclick="editLaporan('{{ $p->id_pengaduan }}', '{{ $p->id_kategori }}', '{{ $p->lokasi }}', '{{ $p->ket }}')">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger py-1 px-3 rounded-pill" 
                                                    style="font-size: 0.7rem;"
                                                    onclick="confirmDelete('{{ $p->id_pengaduan }}')">
                                                <i class="bi bi-trash me-1"></i> Batal
                                            </button>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="badge-modern {{ $badgeClass }}">{{ $status }}</span>
                                        <div class="text-white-50 mt-2" style="font-size: 0.65rem;">{{ $p->created_at->format('d M Y') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="opacity-25 mb-3">
                                        <p class="text-white-50 small">Belum ada laporan yang diajukan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Kelas D202" required>
                            </div>
                            <div class="col-12">
                                <label class="small text-white mb-2 fw-600">Deskripsi Kerusakan</label>
                                <textarea name="ket" class="form-control" rows="4" placeholder="Jelaskan detail kerusakan..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="small text-white mb-2 fw-600">Unggah Foto Bukti</label>
                                <input type="file" name="foto_kerusakan" class="form-control" required>
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
                            <div class="col-md-6">
                                <label class="small text-white mb-2 fw-600">Lokasi Spesifik</label>
                                <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
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
        function editLaporan(id, kategori, lokasi, ket) {
            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            const form = document.getElementById('formEdit');
            
            // Set action URL secara dinamis sesuai ID
            form.action = '/siswa/lapor/update/' + id;
            
            // Isi data ke dalam field
            document.getElementById('edit_kategori').value = kategori;
            document.getElementById('edit_lokasi').value = lokasi;
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