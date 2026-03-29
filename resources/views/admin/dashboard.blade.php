<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: white; }
        .card { background: #1e293b; border: none; color: white; }
        .table { color: white; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Admin 🛡️</h2>
            <a href="/logout" class="btn btn-danger">Logout</a>
        </div>
         <!-- Tabel Laporan Masuk -->
        <div class="card p-4 shadow">
            <h4>Daftar Laporan Pengaduan Masuk</h4>
            <div class="table-responsive mt-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pelapor (NIS)</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $l)
                        <tr>
                            <td>{{ $l->siswa->nama }} ({{ $l->nis }})</td>
                            <td>{{ $l->kategori->ket_kategori }}</td>
                            <td>{{ $l->lokasi }}</td>
                            <td>{{ $l->ket }}</td>
                            <td>
                                <a href="{{ asset('storage/'.$l->foto) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$l->foto) }}" width="80" class="rounded">
                                </a>
                            </td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">
                                Tanggapi
                            </button>

                            <!-- MODAL TANGGAPAN -->
                            <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content bg-dark border-secondary">
                                        <div class="modal-header border-secondary">
                                            <h5 class="modal-title">Tanggapi Laporan #{{ $l->id_pelaporan }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ url('/admin/tanggapi') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_pelaporan" value="{{ $l->id_pelaporan }}">
                                            <input type="hidden" name="id_kategori" value="{{ $l->id_kategori }}">
                                            
                                            <div class="modal-body text-start">
                                                <div class="mb-3 text-center">
                                                    <img src="{{ asset('storage/'.$l->foto) }}" width="200" class="rounded border border-secondary">
                                                    <p class="mt-2 text-muted-dark small">{{ $l->ket }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ubah Status</label>
                                                    <select name="status" class="form-select bg-dark text-white border-secondary">
                                                        <option value="Menunggu">Menunggu</option>
                                                        <option value="Proses">Proses</option>
                                                        <option value="Selesai">Selesai</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Feedback / Balasan</label>
                                                    <textarea name="feedback" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Tulis instruksi atau balasan..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-secondary">
                                                <button type="submit" class="btn btn-success">Simpan Tanggapan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada laporan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card p-4">
            <h4>Riwayat Aktivitas Seluruh User</h4>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User (NIS/Username)</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->nis ?? $log->username }}</td>
                        <td>{{ $log->aktivitas }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>