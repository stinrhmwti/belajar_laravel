@extends('layouts.app')
@section('title', 'Edit Jadwal / Pengeluaran Servis')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 fw-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i> Edit Data Servis & Pengeluaran Kendaraan
                </div>
                <div class="card-body">
                    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Kendaraan (Plat Nomor)</label>
                            <select name="vehicle_id" class="form-select" required>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}" {{ $expense->vehicle_id == $v->id ? 'selected' : '' }}>
                                        {{ $v->plat_nomor }} - {{ $v->jenis_kendaraan }} ({{ $v->merek }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Servis Terakhir</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $expense->tanggal }}" required>
                            <small class="text-muted">Tanggal ini digunakan sistem untuk menghitung otomatis jatuh tempo servis berikutnya (+3 bulan).</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Pengeluaran</label>
                            <select name="jenis_pengeluaran" class="form-select" required>
                                <option value="Bengkel" {{ $expense->jenis_pengeluaran == 'Bengkel' ? 'selected' : '' }}>Bengkel (Servis Berkala)</option>
                                <option value="Sparepart" {{ $expense->jenis_pengeluaran == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
                                <option value="Lainnya" {{ $expense->jenis_pengeluaran == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Biaya (Rp)</label>
                            <input type="number" name="jumlah_biaya" class="form-control" value="{{ $expense->jumlah_biaya }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan / Deskripsi Servis</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ $expense->deskripsi }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection