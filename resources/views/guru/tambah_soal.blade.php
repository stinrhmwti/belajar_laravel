<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Soal</title>
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
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-danger btn-sm">Logout</button></form>
</nav>
<div class="container mt-4 mb-5">
    <a href="{{ route('guru.soal') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
    <div class="card border-0 shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Soal Baru</h5>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)<p class="mb-0">{{ $error }}</p>@endforeach
            </div>
            @endif
            <form method="POST" action="{{ route('guru.simpan-soal') }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Mata Pelajaran</label>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori ?? 'Kategori '.$kat->id }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Pertanyaan</label>
                    <textarea name="pertanyaan" class="form-control" rows="3" required>{{ old('pertanyaan') }}</textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Pilihan A</label>
                        <input type="text" name="pilihan_a" class="form-control" value="{{ old('pilihan_a') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Pilihan B</label>
                        <input type="text" name="pilihan_b" class="form-control" value="{{ old('pilihan_b') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Pilihan C</label>
                        <input type="text" name="pilihan_c" class="form-control" value="{{ old('pilihan_c') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Pilihan D</label>
                        <input type="text" name="pilihan_d" class="form-control" value="{{ old('pilihan_d') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Kunci Jawaban</label>
                    <select name="kunci_jawaban" class="form-select" required>
                        <option value="">-- Pilih Jawaban Benar --</option>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-save"></i> Simpan Soal
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>