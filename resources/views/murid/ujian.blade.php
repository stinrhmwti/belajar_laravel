<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ujian {{ $mapel->nama_mapel }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #11998e, #38ef7d); min-height: 100vh; }
        .soal-card { border: none; border-radius: 12px; margin-bottom: 15px; }
        .form-check-input:checked { background-color: #11998e; border-color: #11998e; }
        .timer { font-size: 1.5rem; font-weight: bold; color: #dc3545; }
        .form-check-label { cursor: pointer; }
        .form-check { padding: 8px 12px; border-radius: 8px; transition: background 0.2s; }
        .form-check:hover { background: #f0faf5; }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <a class="navbar-brand fw-bold text-success"><i class="bi bi-mortarboard-fill"></i> Ujian Online</a>
    <div class="timer" id="timer">⏱ 30:00</div>
</nav>
<div class="container mt-4 mb-5">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="text-success">📚 {{ $mapel->nama_mapel }}</h5>
            <p class="text-muted mb-0">Kategori: {{ $kategori->nama_kategori }} | Total soal: {{ $soals->count() }} soal</p>
        </div>
    </div>
    <form method="POST" action="{{ route('murid.kumpulkan', [$mapel->id, $kategori->id]) }}" id="formUjian">
        @csrf
        @foreach($soals as $i => $soal)
        <div class="card soal-card shadow-sm">
            <div class="card-body p-4">
                <p class="fw-bold mb-3">{{ $i+1 }}. {{ $soal->pertanyaan }}</p>
                @foreach(['A'=>$soal->pilihan_a,'B'=>$soal->pilihan_b,'C'=>$soal->pilihan_c,'D'=>$soal->pilihan_d] as $huruf=>$pilihan)
                <div class="form-check mb-1">
                    <input class="form-check-input" 
                           type="radio" 
                           name="jawaban_{{ $soal->id }}" 
                           value="{{ $huruf }}" 
                           id="soal{{ $soal->id }}_{{ $huruf }}" 
                           required>
                    <label class="form-check-label w-100" for="soal{{ $soal->id }}_{{ $huruf }}">
                        <strong>{{ $huruf }}.</strong> {{ $pilihan }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        <button type="submit" class="btn btn-success btn-lg w-100 mt-3">
            <i class="bi bi-check-circle"></i> Kumpulkan Ujian
        </button>
    </form>
</div>
<script>
let waktu = 30 * 60;
const timer = document.getElementById('timer');
const interval = setInterval(() => {
    let m = Math.floor(waktu / 60);
    let s = waktu % 60;
    timer.textContent = '⏱ ' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    if (waktu <= 0) { clearInterval(interval); document.getElementById('formUjian').submit(); }
    if (waktu <= 300) timer.style.color = '#dc3545';
    waktu--;
}, 1000);
</script>
</body>
</html>