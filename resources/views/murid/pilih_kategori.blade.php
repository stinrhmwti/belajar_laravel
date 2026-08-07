<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #11998e, #38ef7d); min-height: 100vh; }
        .card-kat { border: none; border-radius: 15px; transition: all 0.3s; }
        .card-kat:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <a class="navbar-brand fw-bold text-success"><i class="bi bi-mortarboard-fill"></i> Sistem Ujian Online</a>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-danger btn-sm">Logout</button></form>
</nav>
<div class="container mt-4">
    <a href="{{ route('murid.pilih-mapel') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    <h4 class="text-white mb-4"><i class="bi bi-list-check"></i> Pilih Kategori - {{ $mapel->nama_mapel }}</h4>
    <div class="row g-4">
        @forelse($kategoris as $i => $kat)
        <div class="col-md-4">
            <div class="card card-kat shadow">
                <div class="card-body text-center p-4">
                    <div class="fs-1 mb-2">📝</div>
                    <h5 class="fw-bold">Kategori {{ $i+1 }}</h5>
                    <p class="text-muted">{{ $kat->nama_kategori ?? 'Paket '.$kat->id }}</p>
                    <a href="{{ route('murid.ujian', [$mapel->id, $kat->id]) }}" class="btn btn-success w-100">
                        <i class="bi bi-play-fill"></i> Mulai Ujian
                    </a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-white">Belum ada kategori untuk mapel ini.</p>
        @endforelse
    </div>
</div>
</body>
</html>
