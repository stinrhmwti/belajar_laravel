@extends('layouts.app')
@section('title', __('Ubah Riwayat Servis'))

@section('content')
<div class="mb-4">
    <a href="{{ route('vehicle-histories.index') }}" class="btn btn-sm btn-outline-secondary rounded-3" style="border-radius: 8px;">
        <i class="bi bi-arrow-left"></i> {{ __('Kembali ke Daftar') }}
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white" style="border-radius: 16px;">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square text-primary me-1"></i> {{ __('Ubah Riwayat Servis') }}</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('vehicle-histories.update', $vehicleHistory) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">{{ __('Kendaraan') }} <span class="text-danger">*</span></label>
                    <select name="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required style="border-radius: 8px; font-size: 0.88rem; height: 38px;">
                        <option value="">-- {{ __('Pilih Kendaraan') }} --</option>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}" @selected(old('vehicle_id', $vehicleHistory->vehicle_id) == $v->id)>
                                {{ $v->plat_nomor }} - {{ $v->merek }} {{ $v->tipe }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">{{ __('Tanggal Servis') }} <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $vehicleHistory->tanggal->format('Y-m-d')) }}" required style="border-radius: 8px; font-size: 0.88rem; height: 38px;">
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">{{ __('Teknisi Penanggung Jawab') }}</label>
                    <select name="teknisi_id" class="form-select @error('teknisi_id') is-invalid @enderror" style="border-radius: 8px; font-size: 0.88rem; height: 38px;">
                        <option value="">-- {{ __('Pilih Teknisi') }} ({{ __('Opsional') }}) --</option>
                        @foreach ($technicians as $t)
                            <option value="{{ $t->id }}" @selected(old('teknisi_id', $vehicleHistory->teknisi_id) == $t->id)>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teknisi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">{{ __('Jenis Pekerjaan') }} <span class="text-danger">*</span></label>
                    <input type="text" name="jenis_pekerjaan" class="form-control @error('jenis_pekerjaan') is-invalid @enderror" value="{{ old('jenis_pekerjaan', $vehicleHistory->jenis_pekerjaan) }}" placeholder="Contoh: Ganti Oli Mesin, Servis Rem, Tune Up" required style="border-radius: 8px; font-size: 0.88rem; height: 38px;">
                    @error('jenis_pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">{{ __('Biaya Perbaikan') }} (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm" style="height: 38px;">
                        <span class="input-group-text border-slate-200 text-secondary bg-light" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; font-weight: 600; font-size: 0.88rem;">Rp</span>
                        <input type="number" name="biaya" class="form-control @error('biaya') is-invalid @enderror" value="{{ old('biaya', (int) $vehicleHistory->biaya) }}" placeholder="Contoh: 350000" required style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; font-size: 0.88rem; height: 100%;">
                    </div>
                    @error('biaya')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">{{ __('Sparepart / Suku Cadang yang Digunakan') }}</label>
                    <input type="text" name="sparepart_digunakan" class="form-control @error('sparepart_digunakan') is-invalid @enderror" value="{{ old('sparepart_digunakan', $vehicleHistory->sparepart_digunakan) }}" placeholder="Contoh: Oli Shell Helix 4L, Kampas Rem Depan" style="border-radius: 8px; font-size: 0.88rem; height: 38px;">
                    @error('sparepart_digunakan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">{{ __('Keterangan Tambahan / Detail Perbaikan') }}</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Tulis detail catatan tambahan mengenai servis..." style="border-radius: 8px; font-size: 0.88rem;">{{ old('keterangan', $vehicleHistory->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 8px; font-weight: 600; font-size: 0.88rem;">
                    <i class="bi bi-save"></i> {{ __('Simpan Perubahan') }}
                </button>
                <a href="{{ route('vehicle-histories.index') }}" class="btn btn-secondary px-4 py-2" style="border-radius: 8px; font-weight: 600; font-size: 0.88rem;">
                    {{ __('Batal') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
