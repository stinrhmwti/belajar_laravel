<!DOCTYPE html>
<html lang="id">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Daftar Armada</title>
</head>
<body class="bg-light p-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h2>Daftar Armada Kendaraan</h2>
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary mb-3">Tambah Kendaraan</a>
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Nopol</th>
                    <th>Merek</th>
                    <th>Tipe</th>
                    <th>Odometer</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $v)
                <tr>
                    <td>{{ $v->nopol }}</td>
                    <td>{{ $v->merek }}</td>
                    <td>{{ $v->tipe }}</td>
                    <td>{{ $v->odometer_terakhir }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>