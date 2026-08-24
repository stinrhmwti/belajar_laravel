<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95) !important; }
        .card-murid { border: none; border-radius: 12px; transition: all 0.3s; }
        .card-murid:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        table thead { background: #667eea; color: white; }
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
    <h4 class="text-white mb-4"><i class="bi bi-file-earmark-medical"></i> Raport Seluruh Siswa</h4>

    @foreach($murids as $murid)
    @php
        $hasilMurid = \App\Models\HasilUjian::with(['mapel','kategori'])
            ->where('user_id', $murid->id)->get();
        $rata = round($hasilMurid->avg('nilai') ?? 0, 2);
        $pred = $rata>=85?'A':($rata>=75?'B':($rata>=65?'C':'D'));
        $warna = $rata>=75?'success':($rata>=65?'warning':'danger');
    @endphp

    @if($hasilMurid->count() > 0)
    <div class="card card-murid shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#667eea; color:white; border-radius:12px 12px 0 0">
            <span><i class="bi bi-person-circle"></i> <strong>{{ $murid->nama }}</strong></span>
            <span>Rata-rata: <span class="badge bg-{{ $warna }} fs-6">{{ $rata }} ({{ $pred }})</span></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
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
                    @foreach($hasilMurid as $i => $h)
                    @php
                        $p = $h->nilai>=85?'A':($h->nilai>=75?'B':($h->nilai>=65?'C':'D'));
                        $w = $h->nilai>=75?'success':($h->nilai>=65?'warning':'danger');
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td><strong>{{ $h->mapel->nama_mapel ?? '-' }}</strong></td>
                        <td>{{ $h->kategori->nama_kategori ?? 'Kategori '.$h->kategori_id }}</td>
                        <td class="text-center text-success fw-bold">{{ $h->jumlah_benar }}</td>
                        <td class="text-center text-danger fw-bold">{{ $h->jumlah_salah }}</td>
                        <td class="text-center"><span class="badge bg-{{ $w }}">{{ $h->nilai }}</span></td>
                        <td class="text-center"><span class="badge bg-{{ $w }}">{{ $p }}</span></td>
                        <td><small>{{ $h->tanggal }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-warning">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Rata-rata:</td>
                        <td class="text-center fw-bold">{{ $rata }}</td>
                        <td class="text-center"><span class="badge bg-{{ $warna }}">{{ $pred }}</span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>