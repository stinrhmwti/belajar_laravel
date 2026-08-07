@extends('layouts.app')
@section('title', 'Detail Kendaraan')

@section('content')
<h5 class="page-title mb-3">Detail Kendaraan - {{ $vehicle->plat_nomor }}</h5>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>Jenis:</strong> {{ $vehicle->jenis_kendaraan }} ({{ $vehicle->merek }} {{ $vehicle->tipe }})</p>
        <p><strong>Lokasi Pool:</strong> {{ $vehicle->lokasi_pool }}</p>
        <p><strong>Supir Utama:</strong> {{ $vehicle->supir_utama }}</p>
        <p><strong>Odometer Terkini:</strong> {{ number_format($vehicle->odometer_terkini, 0, ',', '.') }} km</p>
        <p><strong>Jatuh Tempo KIR:</strong> {{ $vehicle->jatuh_tempo_kir?->format('d/m/Y') ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $vehicle->status }}</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-white fw-bold">10 Checklist Harian Terakhir</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Teknisi</th><th>Odometer</th><th>Catatan</th></tr></thead>
            <tbody>
                @forelse ($vehicle->checklists as $c)
                <tr>
                    <td>{{ $c->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $c->nama_teknisi }}</td>
                    <td>{{ $c->odometer }}</td>
                    <td>{{ $c->catatan_tambahan }} @if($c->ada_masalah)<span class="badge bg-danger">Ada Masalah</span>@endif</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-3">Belum ada checklist.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white fw-bold">10 Pengeluaran Terakhir</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Jenis</th><th>Jumlah</th><th>Keterangan</th></tr></thead>
            <tbody>
                @forelse ($vehicle->expenses as $e)
                <tr>
                    <td>{{ $e->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $e->jenis_pengeluaran }}</td>
                    <td>Rp {{ number_format($e->jumlah_biaya, 0, ',', '.') }}</td>
                    <td>{{ $e->keterangan }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-3">Belum ada data pengeluaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('vehicles.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection