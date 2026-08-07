<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95) !important; }
        table thead { background: #667eea; color: white; }
        .badge-nilai { padding: 6px 14px; border-radius: 20px; }
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
    <a href="{{ route('guru.dashboard') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    <div class="card border-0 shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Nilai Seluruh Siswa</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Kategori</th>
                            <th class="text-center">Benar</th>
                            <th class="text-center">Salah</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Predikat</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasil as $i => $h)
                        @php
                            $pred = $h->nilai>=85?'A':($h->nilai>=75?'B':($h->nilai>=65?'C':'D'));
                            $warna = $h->nilai>=75?'success':($h->nilai>=65?'warning':'danger');
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td><strong>{{ $h->murid->nama ?? '-' }}</strong></td>
                            <td>{{ $h->mapel->nama_mapel ?? '-' }}</td>
                            <td>{{ $h->kategori->nama_kategori ?? 'Kategori '.$h->kategori_id }}</td>
                            <td class="text-center text-success fw-bold">{{ $h->jumlah_benar }}</td>
                            <td class="text-center text-danger fw-bold">{{ $h->jumlah_salah }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $warna }} badge-nilai">{{ $h->nilai }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $warna }}">{{ $pred }}</span>
                            </td>
                            <td><small>{{ $h->tanggal }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada data nilai</td></tr>
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