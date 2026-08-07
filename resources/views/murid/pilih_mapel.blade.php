<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Mapel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #11998e, #38ef7d); min-height: 100vh; }
        .card-mapel { border: none; border-radius: 15px; transition: all 0.3s; cursor: pointer; }
        .card-mapel:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <a class="navbar-brand fw-bold text-success"><i class="bi bi-mortarboard-fill"></i> Sistem Ujian Online</a>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted"><i class="bi bi-person-circle"></i> {{ Auth::user()->nama }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-danger btn-sm">Logout</button></form>
    </div>
</nav>
<div class="container mt-4">
    <a href="{{ route('murid.dashboard') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    <h4 class="text-white mb-4"><i class="bi bi-book"></i> Pilih Mata Pelajaran</h4>
    <div class="row g-4">
        @foreach($mapels as $mapel)
        <div class="col-md-3">
            <div class="card card-mapel shadow">
                <div class="card-body text-center p-4">
                    <div class="fs-1 mb-3">📚</div>
                    <h5 class="fw-bold">{{ $mapel->nama_mapel }}</h5>
                    <span class="badge bg-primary mb-3">{{ $mapel->soals_count }} Soal</span><br>
                    <a href="{{ route('murid.pilih-kategori', $mapel->id) }}" class="btn btn-success w-100">
                        <i class="bi bi-play-fill"></i> Mulai
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</body>
</html>