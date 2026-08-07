@extends('layouts.app')
@section('title', 'Catat Pengeluaran')

@section('content')
<h5 class="page-title mb-3">Catat Pengeluaran</h5>

<div class="card">
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kendaraan</label>
                    <select name="vehicle_id" class="form-select" required>
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}" @selected(request('vehicle_id') == $v->id)>{{ $v->plat_nomor }} - {{ $v->jenis_kendaraan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Pengeluaran</label>
                    <select name="jenis_pengeluaran" class="form-select" required>
                        <option value="BBM">BBM</option>
                        <option value="Tol">Tol</option>
                        <option value="Bengkel">Bengkel</option>
                        <option value="Parkir">Parkir</option>
                        <option value="Pajak">Pajak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Biaya (Rp)</label>
                    <input type="number" step="0.01" name="jumlah_biaya" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Isi bensin full tank">
                </div>
            </div>

            <div class="mt-4 form-actions-mobile">
                <button class="btn btn-brand">Simpan</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection