<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Sistem Ujian Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95) !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { color: #667eea !important; font-weight: bold; }
        .card-stat { border: none; border-radius: 15px; transition: transform 0.3s; overflow: hidden; }
        .card-stat:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .card-stat .card-body { padding: 25px; }
        .card-stat .icon { font-size: 3rem; opacity: 0.3; position: absolute; right: 20px; top: 20px; }
        .card-stat h2 { font-size: 2.5rem; font-weight: bold; margin: 0; }
        .card-stat h5 { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .card-menu { border: none; border-radius: 15px; transition: all 0.3s; overflow: hidden; }
        .card-menu:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .card-menu .card-body { padding: 30px; text-align: center; }
        .card-menu .icon-menu { font-size: 4rem; margin-bottom: 15px; }
        .welcome-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; border: 1px solid rgba(255,255,255,0.3); }
        .badge-custom { padding: 8px 15px; border-radius: 20px; font-weight: 500; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand"><i class="bi bi-mortarboard-fill"></i> Sistem Ujian Online</a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted"><i class="bi bi-person-circle"></i> {{ Auth::user()->nama }} (Guru)</span>
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
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="welcome-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">👋 Selamat Datang, {{ Auth::user()->nama }}!</h1>
                <p class="mb-0">Kelola ujian dan pantau perkembangan siswa dengan mudah.</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="fs-5"><i class="bi bi-calendar3"></i> {{ now()->format('d F Y') }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-stat bg-primary text-white position-relative">
                <i class="bi bi-file-earmark-text icon"></i>
                <div class="card-body">
                    <h5>Total Soal</h5>
                    <h2>{{ $total_soal }}</h2>
                    <small>Soal tersedia</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat bg-success text-white position-relative">
                <i class="bi bi-people-fill icon"></i>
                <div class="card-body">
                    <h5>Total Murid</h5>
                    <h2>{{ $total_murid }}</h2>
                    <small>{{ $murid_aktif }} sudah ujian</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat bg-warning text-white position-relative">
                <i class="bi bi-clipboard-check icon"></i>
                <div class="card-body">
                    <h5>Total Ujian</h5>
                    <h2>{{ $total_ujian }}</h2>
                    <small>Ujian dikerjakan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat bg-info text-white position-relative">
                <i class="bi bi-book-fill icon"></i>
                <div class="card-body">
                    <h5>Mata Pelajaran</h5>
                    <h2>{{ $total_mapel }}</h2>
                    <small>Mapel tersedia</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="text-muted mb-3">Rata-rata Nilai Seluruh Siswa</h5>
                    <h1 class="display-3 text-primary">{{ $rata_semua }}</h1>
                    @php
                        $predikat = $rata_semua >= 85 ? 'A' : ($rata_semua >= 75 ? 'B' : ($rata_semua >= 65 ? 'C' : 'D'));
                        $warna_rata = $rata_semua >= 75 ? 'success' : 'danger';
                    @endphp
                    <span class="badge bg-{{ $warna_rata }} badge-custom fs-5">{{ $predikat }}</span>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3 text-white"><i class="bi bi-grid-fill"></i> Menu Utama</h4>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-menu bg-white">
                <div class="card-body">
                    <div class="icon-menu text-primary"><i class="bi bi-pencil-square"></i></div>
                    <h4>Kelola Soal</h4>
                    <p class="text-muted">Tambah, ubah, dan hapus soal ujian per mata pelajaran</p>
                    <a href="{{ route('guru.soal') }}" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-arrow-right"></i> Buka
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-menu bg-white">
                <div class="card-body">
                    <div class="icon-menu text-success"><i class="bi bi-graph-up"></i></div>
                    <h4>Lihat Nilai</h4>
                    <p class="text-muted">Lihat hasil ujian dan nilai semua siswa</p>
                    <a href="{{ route('guru.nilai') }}" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-arrow-right"></i> Buka
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-menu bg-white">
                <div class="card-body">
                    <div class="icon-menu text-info"><i class="bi bi-file-earmark-medical"></i></div>
                    <h4>Raport Siswa</h4>
                    <p class="text-muted">Lihat raport lengkap per siswa per mata pelajaran</p>
                    <a href="{{ route('guru.raport') }}" class="btn btn-info text-white btn-lg w-100">
                        <i class="bi bi-arrow-right"></i> Buka
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Soal per Mata Pelajaran</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#667eea; color:white">
                            <tr><th>Mata Pelajaran</th><th class="text-center">Jumlah Soal</th></tr>
                        </thead>
                        <tbody>
                            @forelse($soal_per_mapel as $m)
                            <tr>
                                <td><strong>{{ $m->nama_mapel }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-primary badge-custom">{{ $m->soals_count }} soal</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">Belum ada soal</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Ujian Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @if($ujian_terbaru->isEmpty())
                    <p class="text-center text-muted py-4">Belum ada ujian yang dikerjakan</p>
                    @else
                    <table class="table table-hover mb-0">
                        <thead style="background:#198754; color:white">
                            <tr>
                                <th>Siswa</th>
                                <th>Mapel</th>
                                <th class="text-center">Nilai</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ujian_terbaru as $u)
                            @php $warna = $u->nilai >= 75 ? 'success' : 'danger'; @endphp
                            <tr>
                                <td><strong>{{ $u->murid->nama ?? '-' }}</strong></td>
                                <td>{{ $u->mapel->nama_mapel ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $warna }} badge-custom">{{ $u->nilai }}</span>
                                </td>
                                <td><small>{{ $u->tanggal ?? '-' }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>