<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #11998e, #38ef7d); min-height: 100vh; }
        .table thead { background: #11998e; color: white; }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <a class="navbar-brand fw-bold text-success"><i class="bi bi-mortarboard-fill"></i> Sistem Ujian Online</a>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-danger btn-sm">Logout</button></form>
</nav>
<div class="container mt-4 mb-5">
    <a href="{{ route('murid.dashboard') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    <div class="card border-0 shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-file-earmark-medical"></i> Raport Nilai - {{ Auth::user()->nama }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead><tr><th>No</th><th>Mata Pelajaran</th><th>Kategori</th><th class="text-center">Benar</th><th class="text-center">Salah</th><th class="text-center">Nilai</th><th class="text-center">Predikat</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @forelse($hasil as $i => $h)
                    @php
                        $pred = $h->nilai>=85?'A':($h->nilai>=75?'B':($h->nilai>=65?'C':'D'));
                        $warna = $h->nilai>=75?'success':($h->nilai>=65?'warning':'danger');
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td><strong>{{ $h->mapel->nama_mapel ?? '-' }}</strong></td>
                        <td>{{ $h->kategori->nama_kategori ?? 'Kategori '.$h->kategori_id }}</td>
                        <td class="text-center text-success fw-bold">{{ $h->jumlah_benar }}</td>
                        <td class="text-center text-danger fw-bold">{{ $h->jumlah_salah }}</td>
                        <td class="text-center"><span class="badge bg-{{ $warna }}">{{ $h->nilai }}</span></td>
                        <td class="text-center"><span class="badge bg-{{ $warna }}">{{ $pred }}</span></td>
                        <td><small>{{ $h->tanggal }}</small></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data nilai</td></tr>
                    @endforelse
                </tbody>
                @if($hasil->count() > 0)
                <tfoot class="table-warning">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Rata-rata Nilai:</td>
                        <td class="text-center fw-bold">{{ round($hasil->avg('nilai'), 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
</body>
</html>