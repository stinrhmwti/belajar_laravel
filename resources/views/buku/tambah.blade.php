<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Tambah Buku Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-primary mb-4 text-center">Formulir Tambah Buku Baru</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/buku/simpan" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Penulis</label>
                <input type="text" name="penulis" class="form-control" placeholder="Misal: Rahma" value="{{ old('penulis') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Judul Buku</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul lengkap buku..." value="{{ old('judul') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Unggah Foto Sampul</label>
                <input type="file" name="sampul" class="form-control">
                <small class="text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 10MB.</small>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="/buku" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data Buku</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>