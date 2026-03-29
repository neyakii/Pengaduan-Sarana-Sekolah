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
                            <td>
                                <button class="btn btn-sm btn-primary">Tanggapi</button>
                            </td>
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