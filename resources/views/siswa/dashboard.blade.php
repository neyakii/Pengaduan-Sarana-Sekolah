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
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <!-- Kolom Identitas Siswa -->
            <div class="col-md-4">
                <div class="card p-4 text-center mb-4">
                    <!-- Di bagian gambar profil -->
                <div class="mb-3">
                    @if($siswa->foto_profile)
                        <!-- Menampilkan foto asli hasil upload -->
                        <img src="{{ asset('storage/' . $siswa->foto_profile) }}" class="profile-img">
                    @else
                        <!-- Menampilkan inisial jika foto tidak ada -->
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

            <!-- Kolom Aktivitas -->
            <div class="col-md-8">
                <div class="card p-4 mb-4">
                    <h2 class="fw-bold">Halo, {{ $siswa->nama }}! 👋</h2>
                    <p class="text-muted-dark">Selamat datang di Sistem Pengaduan Sarana Sekolah. Silakan laporkan jika ada fasilitas sekolah yang rusak.</p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-primary px-4">Buat Pengaduan Baru</a>
                    </div>
                </div>

                <div class="card p-4">
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

                <!-- Tambahkan ini di bawah Card Riwayat Aktivitas -->
                <div class="card p-4 mt-4">
                    <h5 class="fw-bold mb-3">Daftar Laporan Pengaduan Kamu</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Foto</th>
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
                                        <img src="{{ asset('storage/'.$p->foto) }}" width="80" class="rounded">
                                    </td>
                                    <td>{{ $p->lokasi }}</td>
                                    <td>{{ $p->ket }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    </td>
                                    <td>{{ $p->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Bel_um ada laporan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>