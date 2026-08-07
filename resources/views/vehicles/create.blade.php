@extends('layouts.app')
@section('title', 'Tambah Kendaraan')

@section('content')
<h5 class="page-title mb-3">Tambah Kendaraan Baru</h5>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert" style="border-left: 5px solid #dc3545 !important;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <div>
                <h6 class="fw-bold mb-1 text-danger">Gagal Menyimpan Kendaraan:</h6>
                <ul class="mb-0 ps-3" style="font-size: 0.825rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('vehicles.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jenis Kendaraan</label>
                    <input type="text" name="jenis_kendaraan" class="form-control" value="{{ old('jenis_kendaraan') }}" placeholder="Mobil Boks / Mobil Pick Up" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Merek</label>
                    <input type="text" name="merek" class="form-control" value="{{ old('merek') }}" placeholder="Mitsubishi" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe</label>
                    <input type="text" name="tipe" class="form-control" value="{{ old('tipe') }}" placeholder="Canter FE 74" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tahun Pembuatan</label>
                    <input type="number" name="tahun" class="form-control" value="{{ old('tahun', 2024) }}" placeholder="2024" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" name="plat_nomor" class="form-control" value="{{ old('plat_nomor') }}" placeholder="B 1234 KTR" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi Pool</label>
                    <input type="text" name="lokasi_pool" class="form-control" value="{{ old('lokasi_pool') }}" placeholder="Jakarta">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supir Utama</label>
                    <input type="text" name="supir_utama" class="form-control" value="{{ old('supir_utama') }}" placeholder="Budi Santoso">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Odometer Awal (km)</label>
                    <input type="number" name="odometer_awal" class="form-control" value="{{ old('odometer_awal', 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pajak Tahunan (Rp)</label>
                    <input type="number" step="0.01" name="pajak_tahunan" class="form-control" value="{{ old('pajak_tahunan') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pajak 5 Tahunan (Rp)</label>
                    <input type="number" step="0.01" name="pajak_5_tahunan" class="form-control" value="{{ old('pajak_5_tahunan') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jatuh Tempo KIR</label>
                    <input type="date" name="jatuh_tempo_kir" class="form-control" value="{{ old('jatuh_tempo_kir') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jadwal Servis Berikutnya (Manual)</label>
                    <input type="date" name="tanggal_servis_manual" class="form-control" value="{{ old('tanggal_servis_manual') }}">
                </div>
            </div>

            <div class="mt-4 form-actions-mobile">
                <button class="btn btn-brand">Simpan</button>
                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection