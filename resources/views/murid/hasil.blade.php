<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Ujian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #11998e, #38ef7d); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .hasil-card { border: none; border-radius: 20px; max-width: 500px; width: 100%; }
        .nilai-besar { font-size: 80px; font-weight: 800; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @php
                $pred = $hasil->nilai>=85?'A':($hasil->nilai>=75?'B':($hasil->nilai>=65?'C':'D'));
                $warna = $hasil->nilai>=75?'success':($hasil->nilai>=65?'warning':'danger');
                $emoji = $hasil->nilai>=75?'🎉':'😔';
            @endphp
            <div class="card hasil-card shadow-lg text-center">
                <div class="card-header bg-{{ $warna }} text-white py-4" style="border-radius:20px 20px 0 0">
                    <h3>{{ $emoji }} Ujian Selesai!</h3>
                    <p class="mb-0">{{ $hasil->mapel->nama_mapel ?? '-' }}</p>
                </div>
                <div class="card-body py-4">
                    <div class="nilai-besar text-{{ $warna }}">{{ $hasil->nilai }}</div>
                    <span class="badge bg-{{ $warna }} fs-4 px-4 py-2 mb-4">Predikat {{ $pred }}</span>

                    <div class="row g-3 mt-2">
                        <div class="col-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                <div class="fs-1 fw-bold text-success">{{ $hasil->jumlah_benar }}</div>
                                <small class="text-muted">Jawaban Benar</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-danger bg-opacity-10 rounded-3">
                                <div class="fs-1 fw-bold text-danger">{{ $hasil->jumlah_salah }}</div>
                                <small class="text-muted">Jawaban Salah</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-grid gap-2">
                        <a href="{{ route('murid.dashboard') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-house"></i> Kembali ke Dashboard
                        </a>
                        <a href="{{ route('murid.pilih-mapel') }}" class="btn btn-outline-success btn-lg">
                            <i class="bi bi-arrow-repeat"></i> Ujian Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>