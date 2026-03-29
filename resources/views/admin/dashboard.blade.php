<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: white; font-family: 'Segoe UI', sans-serif; }
        .card { background: #1e293b; border: none; border-radius: 15px; color: white; margin-bottom: 20px; }
        .table { color: white; border-color: #334155; vertical-align: middle; }
        .text-muted-dark { color: #cbd5e1; }
        .form-control, .form-select { background: #334155; border: 1px solid #475569; color: white; }
        .modal-content { background: #1e293b; color: white; border-radius: 15px; }
        .modal-header, .modal-footer { border-color: #334155; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Dashboard Admin 🛡️</h2>
                <p class="text-muted-dark">Selamat datang kembali, <strong>{{ session('username') }}</strong></p>
            </div>
            <a href="/logout" class="btn btn-outline-danger px-4">Logout</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success bg-success text-white border-0 mb-4">{{ session('success') }}</div>
        @endif

        <div class="row">
            <!-- TABEL LAPORAN MASUK -->
            <div class="col-12">
                <div class="card p-4 shadow">
                    <h4 class="fw-bold mb-3">Daftar Pengaduan Siswa</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Foto</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporan as $l)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $l->siswa->nama ?? 'Siswa Terhapus' }}</span><br>
                                        <small class="text-muted-dark">NIS: {{ $l->nis }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $l->kategori->ket_kategori }}</span></td>
                                    <td>
                                        <small class="d-block"><strong>Lokasi:</strong> {{ $l->lokasi }}</small>
                                        {{ Str::limit($l->ket, 50) }}
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/'.$l->foto) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$l->foto) }}" width="60" class="rounded border border-secondary">
                                        </a>
                                    </td>
                                    <td>
                                        @php $status = $l->aspirasi->status ?? 'Menunggu'; @endphp
                                        @if($status == 'Selesai') <span class="badge bg-success">Selesai</span>
                                        @elseif($status == 'Proses') <span class="badge bg-info text-dark">Proses</span>
                                        @else <span class="badge bg-warning text-dark">Menunggu</span> @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">
                                            Tanggapi
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL TANGGAPI -->
                                <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content shadow-lg">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Tanggapi Laporan #{{ $l->id_pelaporan }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ url('/admin/tanggapi') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_pelaporan" value="{{ $l->id_pelaporan }}">
                                                <input type="hidden" name="id_kategori" value="{{ $l->id_kategori }}">
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <img src="{{ asset('storage/'.$l->foto) }}" width="100%" class="rounded mb-2 border border-secondary">
                                                        <p class="small text-muted-dark">{{ $l->ket }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Update Status</label>
                                                        <select name="status" class="form-select">
                                                            <option value="Menunggu" {{ ($l->aspirasi->status ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                            <option value="Proses" {{ ($l->aspirasi->status ?? '') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                                            <option value="Selesai" {{ ($l->aspirasi->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Balasan / Feedback</label>
                                                        <textarea name="feedback" class="form-control" rows="3" placeholder="Tulis tanggapan untuk siswa..." required>{{ $l->aspirasi->feedback ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary w-100">Simpan Tanggapan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted-dark">Belum ada laporan masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TABEL LOG AKTIVITAS (Dibawah) -->
            <div class="col-12">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Log Aktivitas Terbaru</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($logs as $log)
                        <li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between">
                            <span><strong>{{ $log->nis ?? $log->username }}</strong>: {{ $log->aktivitas }}</span>
                            <small class="text-muted-dark">{{ $log->created_at->diffForHumans() }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>