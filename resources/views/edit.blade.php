@extends('layouts.app')
@section('title', 'Edit Kendaraan')

@section('content')
<h5 class="page-title mb-3">Edit Kendaraan - {{ $vehicle->plat_nomor }}</h5>

<div class="card">
    <div class="card-body">
        <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jenis Kendaraan</label>
                    <input type="text" name="jenis_kendaraan" class="form-control" value="{{ old('jenis_kendaraan', $vehicle->jenis_kendaraan) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Merek</label>
                    <input type="text" name="merek" class="form-control" value="{{ old('merek', $vehicle->merek) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe</label>
                    <input type="text" name="tipe" class="form-control" value="{{ old('tipe', $vehicle->tipe) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" name="plat_nomor" class="form-control" value="{{ old('plat_nomor', $vehicle->plat_nomor) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi Pool</label>
                    <input type="text" name="lokasi_pool" class="form-control" value="{{ old('lokasi_pool', $vehicle->lokasi_pool) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supir Utama</label>
                    <input type="text" name="supir_utama" class="form-control" value="{{ old('supir_utama', $vehicle->supir_utama) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Odometer (km)</label>
                    <input type="number" name="odometer_awal" class="form-control" value="{{ old('odometer_awal', $vehicle->odometer_awal) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pajak Tahunan</label>
                    <input type="number" step="0.01" name="pajak_tahunan" class="form-control" value="{{ old('pajak_tahunan', $vehicle->pajak_tahunan) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pajak 5 Tahunan</label>
                    <input type="number" step="0.01" name="pajak_5_tahunan" class="form-control" value="{{ old('pajak_5_tahunan', $vehicle->pajak_5_tahunan) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach (['Siap Pakai', 'Sedang Diservis', 'Selesai'] as $s)
                            <option value="{{ $s }}" @selected(old('status', $vehicle->status) === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jatuh Tempo KIR</label>
                    <input type="date" name="jatuh_tempo_kir" class="form-control"
                           value="{{ old('jatuh_tempo_kir', $vehicle->jatuh_tempo_kir?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-brand">Update</button>
                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection