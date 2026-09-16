@extends('layouts.app')
@section('title', 'Edit Pengeluaran Kendaraan')

@section('content')
<div class="container-fluid px-0 py-2">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-primary"></i>
                        <span>Edit Data Pengeluaran / Servis</span>
                    </h5>
                    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Pilih Kendaraan (Plat Nomor) <span class="text-danger">*</span></label>
                            <select name="vehicle_id" class="form-select" required style="border-radius: 8px;">
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}" {{ $expense->vehicle_id == $v->id ? 'selected' : '' }}>
                                        {{ $v->plat_nomor }} - {{ $v->jenis_kendaraan }} ({{ $v->merek }} {{ $v->tipe }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $expense->tanggal ? $expense->tanggal->format('Y-m-d') : '') }}" required style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Jenis Pengeluaran <span class="text-danger">*</span></label>
                                <select name="jenis_pengeluaran" id="editJenisPengeluaran" class="form-select" required style="border-radius: 8px;">
                                    <option value="BBM" {{ $expense->jenis_pengeluaran == 'BBM' ? 'selected' : '' }}>BBM (Bahan Bakar)</option>
                                    <option value="Tol" {{ $expense->jenis_pengeluaran == 'Tol' ? 'selected' : '' }}>Tol</option>
                                    <option value="Bengkel" {{ $expense->jenis_pengeluaran == 'Bengkel' ? 'selected' : '' }}>Bengkel (Servis Berkala / Perbaikan)</option>
                                    <option value="Sparepart" {{ $expense->jenis_pengeluaran == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
                                    <option value="Parkir" {{ $expense->jenis_pengeluaran == 'Parkir' ? 'selected' : '' }}>Parkir</option>
                                    <option value="Pajak" {{ $expense->jenis_pengeluaran == 'Pajak' ? 'selected' : '' }}>Pajak & STNK</option>
                                    <option value="Lainnya" {{ $expense->jenis_pengeluaran == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Jumlah Biaya (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" step="0.01" name="jumlah_biaya" class="form-control" value="{{ old('jumlah_biaya', $expense->jumlah_biaya) }}" required style="border-radius: 0 8px 8px 0;">
                            </div>
                        </div>

                        <!-- Opsi Tambahan untuk BBM (Efisiensi Bahan Bakar) -->
                        <div class="row g-3 mb-3 p-3 bg-light rounded-3 border" id="bbmFieldsContainer">
                            <div class="col-12 mb-1">
                                <span class="fw-bold text-secondary" style="font-size: 0.85rem;"><i class="bi bi-fuel-pump text-warning me-1"></i> Data Efisiensi BBM (Opsional)</span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted" style="font-size: 0.82rem;">Jumlah Liter BBM</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="liter_bbm" class="form-control" value="{{ old('liter_bbm', $expense->liter_bbm) }}" placeholder="Contoh: 35.5">
                                    <span class="input-group-text">Liter</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted" style="font-size: 0.82rem;">Odometer Saat Pengisian</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="odometer_pengisian" class="form-control" value="{{ old('odometer_pengisian', $expense->odometer_pengisian) }}" placeholder="Contoh: 45200">
                                    <span class="input-group-text">KM</span>
                                </div>
                            </div>
                        </div>

                        <!-- Fitur Persetujuan / Approval Biaya oleh Admin & Pimpinan -->
                        @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'pimpinan']))
                        <div class="mb-3 p-3 rounded-3 border" style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
                            <label class="form-label fw-bold text-dark d-flex align-items-center gap-2 mb-1" style="font-size: 0.88rem;">
                                <i class="bi bi-shield-check text-primary fs-5"></i>
                                <span>Persetujuan / Status Approval Admin <span class="text-danger">*</span></span>
                            </label>
                            <p class="text-muted small mb-3">Sebagai Administrator / Pimpinan, Anda dapat mengubah status persetujuan pengeluaran ini.</p>

                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="card h-100 p-2.5 border rounded-3 cursor-pointer" style="cursor: pointer; background: #ffffff;">
                                        <div class="d-flex align-items-start gap-2">
                                            <input type="radio" name="status_approval" value="Disetujui" class="form-check-input mt-1" {{ old('status_approval', $expense->status_approval) === 'Disetujui' ? 'checked' : '' }}>
                                            <div>
                                                <span class="badge bg-success text-white fw-bold mb-1 d-inline-block" style="font-size: 0.72rem;">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                                </span>
                                                <div class="text-muted" style="font-size: 0.75rem; line-height: 1.3;">Biaya disetujui & valid dalam anggaran.</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="card h-100 p-2.5 border rounded-3 cursor-pointer" style="cursor: pointer; background: #ffffff;">
                                        <div class="d-flex align-items-start gap-2">
                                            <input type="radio" name="status_approval" value="Menunggu Persetujuan" class="form-check-input mt-1" {{ old('status_approval', $expense->status_approval) === 'Menunggu Persetujuan' ? 'checked' : '' }}>
                                            <div>
                                                <span class="badge bg-warning text-dark fw-bold mb-1 d-inline-block" style="font-size: 0.72rem;">
                                                    <i class="bi bi-clock-history me-1"></i> Menunggu
                                                </span>
                                                <div class="text-muted" style="font-size: 0.75rem; line-height: 1.3;">Perlu verifikasi & review lanjutan.</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="card h-100 p-2.5 border rounded-3 cursor-pointer" style="cursor: pointer; background: #ffffff;">
                                        <div class="d-flex align-items-start gap-2">
                                            <input type="radio" name="status_approval" value="Ditolak" class="form-check-input mt-1" {{ old('status_approval', $expense->status_approval) === 'Ditolak' ? 'checked' : '' }}>
                                            <div>
                                                <span class="badge bg-danger text-white fw-bold mb-1 d-inline-block" style="font-size: 0.72rem;">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                                </span>
                                                <div class="text-muted" style="font-size: 0.75rem; line-height: 1.3;">Biaya ditolak / tidak diizinkan.</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="form-label fw-semibold text-secondary" style="font-size: 0.8rem;">Catatan Persetujuan Admin (Opsional)</label>
                                <input type="text" name="catatan_admin" class="form-control form-control-sm" value="{{ old('catatan_admin', $expense->catatan_admin) }}" placeholder="Contoh: Disetujui sesuai SPK Servis No. 042 / ACC perbaikan rutin">
                            </div>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Keterangan / Catatan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan pengeluaran atau rincian perbaikan..." style="border-radius: 8px;">{{ old('keterangan', $expense->keterangan) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('expenses.index') }}" class="btn btn-light px-4" style="border-radius: 8px;">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection