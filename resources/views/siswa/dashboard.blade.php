<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Siswa Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: white; font-family: 'Segoe UI', sans-serif; }
        .card { background: #1e293b; border: none; border-radius: 15px; color: white; }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #3b82f6;
        }
        .text-muted-dark { color: #cbd5e1; }
        .list-group-item { background: transparent; color: white; border-color: #334155; }
        .form-control, .form-select { background: #334155; border: 1px solid #475569; color: white; }
        .form-control:focus, .form-select:focus { background: #334155; color: white; border-color: #3b82f6; box-shadow: none; }
        .modal-content { background: #1e293b; color: white; border-radius: 15px; }
        .modal-header { border-bottom: 1px solid #334155; }
        .modal-footer { border-top: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="container py-5">
        
        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="alert alert-success bg-success text-white border-0 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Kolom Identitas Siswa -->
            <div class="col-md-4">
                <div class="card p-4 text-center mb-4">
                    <div class="mb-3">
                        @if($siswa->foto_profile)
                            <img src="{{ asset('storage/' . $siswa->foto_profile) }}" class="profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=3b82f6&color=fff&size=128" class="profile-img">
                        @endif
                    </div>
                    <h4 class="fw-bold mb-0">{{ $siswa->nama }}</h4>
                    <p class="text-primary mb-3">Siswa</p>
                    <hr class="border-secondary">
                    <div class="text-start">
                        <p class="mb-1 small text-muted-dark">Nomor Induk Siswa (NIS):</p>
                        <p class="fw-bold">{{ $siswa->nis }}</p>
                        <p class="mb-1 small text-muted-dark">Kelas:</p>
                        <p class="fw-bold">{{ $siswa->kelas }}</p>
                    </div>
                    <a href="/logout" class="btn btn-outline-danger w-100 mt-3">Logout</a>
                </div>
            </div>

            <!-- Kolom Aktivitas & Laporan -->
            <div class="col-md-8">
                <div class="card p-4 mb-4">
                    <h2 class="fw-bold">Halo, {{ $siswa->nama }}! 👋</h2>
                    <p class="text-muted-dark">Selamat datang di Sistem Pengaduan Sarana Sekolah. Silakan laporkan jika ada fasilitas sekolah yang rusak.</p>
                    <div class="mt-3">
                        <!-- TOMBOL TRIGGER MODAL -->
                        <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalLapor">
                            Buat Pengaduan Baru
                        </button>
                    </div>
                </div>

                <div class="card p-4 mb-4">
                    <h5 class="fw-bold mb-3">Riwayat Aktivitas Kamu</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($logs as $log)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $log->aktivitas }}</span>
                                <small class="text-muted-dark">{{ $log->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted-dark">Belum ada aktivitas.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Daftar Laporan Pengaduan Kamu</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Foto Bukti</th>
                                    <th>Lokasi</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengaduan as $p)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/'.$p->foto) }}" width="80" class="rounded shadow-sm">
                                    </td>
                                    <td>{{ $p->lokasi }}</td>
                                    <td>{{ $p->ket }}</td>
                                    <td>
                                        @if($p->aspirasi)
                                            @if($p->aspirasi->status == 'Selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($p->aspirasi->status == 'Proses')
                                                <span class="badge bg-info text-dark">Proses</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @endif
                                            <br><small class="text-muted-dark">Balasan: {{ $p->aspirasi->feedback }}</small>
                                        @else
                                            <span class="badge bg-secondary">Menunggu Tanggapan</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada laporan pengaduan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FORM LAPOR -->
    <div class="modal fade" id="modalLapor" tabindex="-1" aria-labelledby="modalLaporLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalLaporLabel">Form Lapor Kerusakan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/siswa/lapor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Kerusakan</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Kelas XII RPL 1 / Kantin" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan / Deskripsi</label>
                            <textarea name="ket" class="form-control" rows="3" placeholder="Jelaskan kerusakan sarana..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Bukti Kerusakan</label>
                            <input type="file" name="foto_kerusakan" class="form-control" required>
                            <small class="text-muted-dark">Unggah foto fasilitas yang rusak.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT BOOTSTRAP (WAJIB ADA BIAR MODAL BISA TERBUKA) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>