<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Soal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95) !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-light shadow-sm px-4">
    <span class="navbar-brand fw-bold" style="color:#667eea"><i class="bi bi-mortarboard-fill"></i> Sistem Ujian Online</span>
    <div class="d-flex gap-3 align-items-center">
        <span class="text-muted"><i class="bi bi-person-circle"></i> {{ Auth::user()->nama }} (Guru)</span>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-danger btn-sm">Logout</button></form>
    </div>
</nav>
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('guru.dashboard') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="{{ route('guru.tambah-soal') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Soal</a>
    </div>
    @if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif
    <div class="card border-0 shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Soal ({{ $soals->count() }} soal)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Pertanyaan</th>
                            <th>Pilihan A</th>
                            <th>Pilihan B</th>
                            <th>Pilihan C</th>
                            <th>Pilihan D</th>
                            <th class="text-center">Kunci</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($soals as $i => $soal)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td><span class="badge bg-primary">{{ $soal->mapel->nama_mapel ?? '-' }}</span></td>
                            <td>{{ Str::limit($soal->pertanyaan, 40) }}</td>
                            <td>{{ Str::limit($soal->pilihan_a, 20) }}</td>
                            <td>{{ Str::limit($soal->pilihan_b, 20) }}</td>
                            <td>{{ Str::limit($soal->pilihan_c, 20) }}</td>
                            <td>{{ Str::limit($soal->pilihan_d, 20) }}</td>
                            <td class="text-center"><span class="badge bg-success">{{ strtoupper($soal->kunci_jawaban) }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('guru.edit-soal', $soal->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('guru.hapus-soal', $soal->id) }}" class="d-inline" onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada soal</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>