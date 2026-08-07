<!DOCTYPE html>
<html lang="id">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Kendaraan</title>
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="card p-4 shadow">
            <h3>Tambah Kendaraan Baru</h3>
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="mb-3"><input type="text" name="nopol" class="form-control" placeholder="Nopol" required></div>
                <div class="mb-3"><input type="text" name="merek" class="form-control" placeholder="Merek" required></div>
                <div class="mb-3"><input type="text" name="tipe" class="form-control" placeholder="Tipe" required></div>
                <div class="mb-3"><input type="text" name="pool" class="form-control" placeholder="Pool" required></div>
                <div class="mb-3"><input type="date" name="jatuh_tempo_pajak_tahunan" class="form-control" required></div>
                <div class="mb-3"><input type="number" name="odometer_terakhir" class="form-control" placeholder="Odometer" required></div>
                <button type="submit" class="btn btn-success">Simpan Data</button>
            </form>
        </div>
    </div>
</body>
</html>