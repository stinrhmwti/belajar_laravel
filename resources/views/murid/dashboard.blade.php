<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Murid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95) !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .welcome-card { background: linear-gradient(135deg, #11998e, #38ef7d); color: white; border-radius: 15px; padding: 25px 30px; margin-bottom: 25px; border: 1px solid rgba(255,255,255,0.3); }
        .stat-card { border: none; border-radius: 15px; color: white; padding: 20px; transition: transform 0.3s; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { font-size: 2.5rem; opacity: 0.3; position: absolute; right: 15px; top: 15px; }
        .menu-card { border: none; border-radius: 15px; transition: all 0.3s; }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .table-hasil thead { background: #11998e; color: white; }
        .badge-nilai { padding: 6px 14px; border-radius: 20px; font-weight: 500; }
        .progress { height: 10px; }
        .progress-bar { background-color: #11998e; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <span class="navbar-brand fw-bold text-success"><i class="bi bi-mortarboard-fill"></i> Sistem Ujian Online</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted"><i class="bi bi-person-circle"></i> {{ Auth::user()->nama }} (Murid)</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="welcome-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">🎓 Halo, {{ Auth::user()->nama }}!</h2>
                <p class="mb-0 opacity-75">Semangat belajar dan raih nilai terbaik! 💪</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="fs-5"><i class="bi bi-calendar3"></i> {{ now()->format('d F Y') }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary">
                <i class="bi bi-clipboard-check stat-icon"></i>
                <div class="fs-6 text-uppercase fw-bold opacity-75 mb-1">Ujian Dikerjakan</div>
                <div class="display-6 fw-bold">{{ $total_ujian }}</div>
                <small>Kali ujian</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card" style="background:#1a7a5e">
                <i class="bi bi-graph-up stat-icon"></i>
                <div class="fs-6 text-uppercase fw-bold opacity-75 mb-1">Rata-rata Nilai</div>
                <div class="display-6 fw-bold">{{ $rata_nilai }}</div>
                @php
                    $pred = $rata_nilai >= 85 ? 'A' : ($rata_nilai >= 75 ? 'B' : ($rata_nilai >= 65 ? 'C' : 'D'));
                @endphp
                <span class="badge bg-white text-dark">{{ $pred }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="fs-6 text-uppercase fw-bold opacity-75 mb-1">Total Benar</div>
                <div class="display-6 fw-bold">{{ $total_benar }}</div>
                <small>Jawaban benar</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning">
                <i class="bi bi-x-circle stat-icon"></i>
                <div class="fs-6 text-uppercase fw-bold opacity-75 mb-1">Total Salah</div>
                <div class="display-6 fw-bold">{{ $total_salah }}</div>
                <small>Jawaban salah</small>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-2"><i class="bi bi-bar-chart"></i> Progress Mata Pelajaran</h6>
            <p class="text-muted mb-2">
                Mapel yang sudah diikuti
                <span class="float-end fw-bold">{{ $mapel_diikuti }} dari {{ $total_mapel }} mapel</span>
            </p>
            <div class="progress">
    <div class="progress-bar bg-success" role="progressbar" 
         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"
         style="min-width: 2em; width: 50%">
    </div>
</div>
        </div>
    </div>

    <h5 class="text-white mb-3"><i class="bi bi-grid-fill"></i> Menu Utama</h5>
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card menu-card bg-white shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="fs-1 text-success mb-3"><i class="bi bi-pencil-square"></i></div>
                    <h5 class="fw-bold">Mulai Ujian</h5>
                    <p class="text-muted">Pilih mata pelajaran dan kerjakan ujian</p>
                    <a href="{{ route('murid.pilih-mapel') }}" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-play-fill"></i> Mulai Ujian
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card menu-card bg-white shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="fs-1 text-info mb-3"><i class="bi bi-file-earmark-medical"></i></div>
                    <h5 class="fw-bold">Lihat Raport</h5>
                    <p class="text-muted">Lihat rekap nilai ujian kamu</p>
                    <a href="{{ route('murid.raport') }}" class="btn btn-info text-white btn-lg w-100">
                        <i class="bi bi-arrow-right"></i> Lihat Raport
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Ujian Terbaru</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-hasil">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Kategori</th>
                            <th class="text-center">Benar</th>
                            <th class="text-center">Salah</th>
                            <th class="text-center">Nilai</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasil as $i => $h)
                        @php
                            $warna = $h->nilai >= 75 ? 'success' : ($h->nilai >= 65 ? 'warning' : 'danger');
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td><strong>{{ $h->mapel->nama_mapel ?? '-' }}</strong></td>
                            <td>{{ $h->kategori->nama_kategori ?? 'Kategori '.$h->kategori_id }}</td>
                            <td class="text-center text-success fw-bold">{{ $h->jumlah_benar }}</td>
                            <td class="text-center text-danger fw-bold">{{ $h->jumlah_salah }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $warna }} badge-nilai">{{ $h->nilai }}</span>
                            </td>
                            <td><small>{{ $h->tanggal }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat ujian</td>
                        </tr>
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