@extends('layouts.app')
@section('title', 'Edit Kendaraan')

@section('content')
<style>
    .edit-card {
        border: 1px solid var(--border-color) !important;
        border-radius: 16px !important;
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .edit-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
    }
    .edit-card-header {
        background: #ffffff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem 1.5rem;
    }
    .accent-primary {
        border-left: 4px solid #4f46e5 !important;
    }
    .accent-success {
        border-left: 4px solid #10b981 !important;
    }
    .accent-warning {
        border-left: 4px solid #f59e0b !important;
    }
    .form-label-custom {
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-control-custom {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }
    .form-control-custom:focus {
        background-color: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        outline: none;
    }
    .form-select-custom {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }
    .form-select-custom:focus {
        background-color: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        outline: none;
    }
    .input-group-custom {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }
    .input-group-custom-icon {
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.85rem;
        font-size: 0.95rem;
    }
    .input-group-custom .form-control-custom {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        flex: 1 1 auto;
        width: 1%;
    }
    .image-preview-container {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        background-color: #f8fafc;
        overflow: hidden;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 200px;
    }
    .image-preview-container:hover {
        border-color: #6366f1;
        background-color: #f1f5f9;
    }
    .btn-action {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.6rem 1.75rem;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    .btn-action-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        border: none;
        color: white;
    }
    .btn-action-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    .btn-action-secondary {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
    }
    .btn-action-secondary:hover {
        background-color: #f8fafc;
        color: #1e293b;
        border-color: #94a3b8;
    }
    .badge-soft-success {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-soft-warning {
        background-color: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-soft-danger {
        background-color: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="container-fluid px-0">
    <!-- Breadcrumb / Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="p-2.5 bg-primary-subtle text-primary rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
            <i class="bi bi-pencil-square fs-4"></i>
        </div>
        <div>
            <h4 class="fw-extrabold text-dark mb-0" style="letter-spacing: -0.5px;">Edit Kendaraan</h4>
            <small class="text-secondary">Perbarui spesifikasi dan dokumen kendaraan <span class="badge bg-dark font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; padding: 3px 8px; border-radius: 6px;">{{ $vehicle->plat_nomor }}</span></small>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert" style="border-left: 5px solid #dc3545 !important; background-color: #fdf2f2;">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger mt-0.5"></i>
                <div>
                    <h6 class="fw-bold mb-1 text-danger" style="font-size: 0.95rem;">Gagal Menyimpan Perubahan:</h6>
                    <ul class="mb-0 ps-3 text-danger-subtitle" style="font-size: 0.825rem; color: #7f1d1d;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')
        
        <div class="row g-4">
            <!-- Left Column: Form Fields grouped in sections -->
            <div class="col-lg-8">
                <!-- Group 1: Spesifikasi Utama -->
                <div class="edit-card accent-primary mb-4">
                    <div class="edit-card-header py-3 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                        <span class="fw-bold text-dark fs-6">Spesifikasi Utama Kendaraan</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-truck text-secondary"></i> Jenis Kendaraan</label>
                                <input type="text" name="jenis_kendaraan" class="form-control form-control-custom" value="{{ old('jenis_kendaraan', $vehicle->jenis_kendaraan) }}" placeholder="Contoh: Truk Box / Mobil Pick Up" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-tag text-secondary"></i> Merek</label>
                                <input type="text" name="merek" class="form-control form-control-custom" value="{{ old('merek', $vehicle->merek) }}" placeholder="Contoh: Mitsubishi / Toyota" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-grid-3x3-gap text-secondary"></i> Tipe Kendaraan</label>
                                <input type="text" name="tipe" class="form-control form-control-custom" value="{{ old('tipe', $vehicle->tipe) }}" placeholder="Contoh: Canter FE 74" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-calendar3 text-secondary"></i> Tahun Pembuatan</label>
                                <input type="number" name="tahun" class="form-control form-control-custom" value="{{ old('tahun', $vehicle->tahun) }}" placeholder="Contoh: 2022" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label-custom"><i class="bi bi-credit-card-2-front text-secondary"></i> Plat Nomor</label>
                                <input type="text" name="plat_nomor" class="form-control form-control-custom font-monospace fw-bold text-uppercase" style="letter-spacing:1px;" value="{{ old('plat_nomor', $vehicle->plat_nomor) }}" placeholder="Contoh: B 1234 ABC" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group 2: Operasional & Penugasan -->
                <div class="edit-card accent-success mb-4">
                    <div class="edit-card-header py-3 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                        <span class="fw-bold text-dark fs-6">Operasional &amp; Penugasan</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-person text-secondary"></i> Supir Utama</label>
                                <input type="text" name="supir_utama" class="form-control form-control-custom" value="{{ old('supir_utama', $vehicle->supir_utama) }}" placeholder="Nama Supir">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-building text-secondary"></i> Lokasi Pool</label>
                                <input type="text" name="lokasi_pool" class="form-control form-control-custom" value="{{ old('lokasi_pool', $vehicle->lokasi_pool) }}" placeholder="Lokasi Pool Cabang">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-speedometer2 text-secondary"></i> Odometer Awal (km)</label>
                                <input type="number" name="odometer_awal" class="form-control form-control-custom" value="{{ old('odometer_awal', $vehicle->odometer_awal) }}" placeholder="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-toggle-on text-secondary"></i> Status Operasional</label>
                                <select name="status" class="form-select form-select-custom">
                                    @foreach (['Siap Pakai', 'Sedang Diservis', 'Selesai'] as $s)
                                        <option value="{{ $s }}" @selected(old('status', $vehicle->status) === $s)>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group 3: Administrasi & Pajak -->
                <div class="edit-card accent-warning mb-4">
                    <div class="edit-card-header py-3 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text-fill text-warning fs-5"></i>
                        <span class="fw-bold text-dark fs-6">Administrasi &amp; Dokumen</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-cash-stack text-secondary"></i> Pajak Tahunan (Rp)</label>
                                <div class="input-group-custom">
                                    <span class="input-group-custom-icon">Rp</span>
                                    <input type="number" step="0.01" name="pajak_tahunan" class="form-control form-control-custom" value="{{ old('pajak_tahunan', $vehicle->pajak_tahunan) }}" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="bi bi-cash-stack text-secondary"></i> Pajak 5 Tahunan (Rp)</label>
                                <div class="input-group-custom">
                                    <span class="input-group-custom-icon">Rp</span>
                                    <input type="number" step="0.01" name="pajak_5_tahunan" class="form-control form-control-custom" value="{{ old('pajak_5_tahunan', $vehicle->pajak_5_tahunan) }}" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label-custom"><i class="bi bi-calendar-event text-secondary"></i> Jatuh Tempo Uji KIR</label>
                                <input type="date" name="jatuh_tempo_kir" class="form-control form-control-custom" value="{{ old('jatuh_tempo_kir', $vehicle->jatuh_tempo_kir?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label-custom"><i class="bi bi-calendar-check text-secondary"></i> Jadwal Servis Berikutnya (Manual Override)</label>
                                <input type="date" name="tanggal_servis_manual" class="form-control form-control-custom" value="{{ old('tanggal_servis_manual', $vehicle->tanggal_servis_manual?->format('Y-m-d')) }}">
                                <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1 text-primary"></i>Isi kolom ini jika Anda ingin menetapkan jadwal servis berikutnya secara manual secara langsung.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Vehicle Preview & Photo upload -->
            <div class="col-lg-4">
                <div class="edit-card mb-4 position-sticky" style="top: 24px;">
                    <div class="edit-card-header py-3 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-image-fill text-primary fs-5"></i>
                        <span class="fw-bold text-dark fs-6">Foto Kendaraan</span>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="image-preview-container mb-3 shadow-sm border">
                            <img id="vehicle-preview" src="{{ $vehicle->foto_url }}" alt="Foto {{ $vehicle->plat_nomor }}" class="w-100 h-100 object-fit-cover">
                        </div>
                        <div class="text-start mb-4">
                            <label class="form-label-custom" for="foto-input"><i class="bi bi-upload text-secondary"></i> Unggah Foto Baru</label>
                            <input type="file" id="foto-input" name="foto" class="form-control form-control-custom" style="font-size: 0.85rem;" accept="image/*">
                            <small class="text-muted d-block mt-1.5" style="font-size: 0.75rem; line-height: 1.4;"><i class="bi bi-info-circle me-1"></i>Format: JPEG, PNG, JPG, GIF (Max. 2MB)</small>
                        </div>
                        
                        <div class="border-top pt-3 text-start">
                            <div class="d-flex justify-content-between align-items-center mb-2.5">
                                <span class="text-secondary fw-semibold" style="font-size:0.825rem;">Status KIR:</span>
                                @if ($vehicle->status_kir === 'merah')
                                    <span class="badge-soft-danger"><i class="bi bi-exclamation-octagon"></i> Lewat Tempo</span>
                                @elseif ($vehicle->status_kir === 'kuning')
                                    <span class="badge-soft-warning"><i class="bi bi-exclamation-triangle"></i> Mendekati</span>
                                @else
                                    <span class="badge-soft-success"><i class="bi bi-check-circle"></i> Aman &amp; Valid</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary fw-semibold" style="font-size:0.825rem;">Supir Utama:</span>
                                <span class="fw-bold text-dark font-monospace" style="font-size:0.825rem;">{{ $vehicle->supir_utama ?? 'Belum Ditunjuk' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions bottom bar -->
        <div class="mt-4 p-3 bg-white rounded-4 shadow-sm d-flex gap-3 justify-content-end border" style="border-color: rgba(0,0,0,0.05) !important;">
            <a href="{{ route('vehicles.index') }}" class="btn btn-action btn-action-secondary">Batal</a>
            <button type="submit" class="btn btn-action btn-action-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('foto-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('vehicle-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection