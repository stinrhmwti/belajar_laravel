<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Daftar Soal Ujian</h2>
    <form action="/nilai-ujian" method="POST">
        @csrf
     @foreach($data_soal as $soal)
    <p>{{ $soal->pertanyaan }}</p>
    <input type="radio" name="jawaban[{{ $soal->id }}]" value="a"> A. {{ $soal->pilihan_a }}<br>
    <input type="radio" name="jawaban[{{ $soal->id }}]" value="b"> B. {{ $soal->pilihan_b }}<br>
    <input type="radio" name="jawaban[{{ $soal->id }}]" value="c"> C. {{ $soal->pilihan_c }}<br>
    <input type="radio" name="jawaban[{{ $soal->id }}]" value="d"> D. {{ $soal->pilihan_d }}<br>
@endforeach
        <button type="submit" class="btn btn-primary">Kumpulkan</button>
    </form>
</body>
</html>