@extends('layouts.app')

@section('title', 'Data Kendaraan & Servis')

@section('content')
<style>
    .page-header-title {
        font-weight: 700;
        color: var(--text-main);
    }
    .aesthetic-card {
        border: 1px solid var(--border-color) !important;
        border-radius: 12px !important;
        background: #ffffff;
        box-shadow: none !important;
        margin-bottom: 24px;
    }
    .stat-card {
        border: 1px solid var(--border-color) !important;
        border-radius: 12px !important;
        background: #ffffff;
        box-shadow: none !important;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
    }
    
    .card-grad-1 { background: #0f172a !important; color: #ffffff !important; border: none !important; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15) !important; }
    .card-grad-2 { background: #0f2742 !important; color: #ffffff !important; border: none !important; box-shadow: 0 4px 12px rgba(15, 39, 66, 0.15) !important; }
    .card-grad-3 { background: #0e7490 !important; color: #ffffff !important; border: none !important; box-shadow: 0 4px 12px rgba(14, 116, 144, 0.15) !important; }
    .card-grad-4 { background: #0891b2 !important; color: #ffffff !important; border: none !important; box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15) !important; }
    
    .stat-card.card-grad-1:hover, .stat-card.card-grad-2:hover, .stat-card.card-grad-3:hover, .stat-card.card-grad-4:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }

    /* Glassmorphic Gradient Spheres disabled for flat design */
    .stat-card::after, .stat-card::before {
        display: none !important;
    }
    
    .stat-card.card-grad-1 h3, .stat-card.card-grad-2 h3, .stat-card.card-grad-3 h3, .stat-card.card-grad-4 h3 {
        color: #ffffff !important;
    }
    .stat-card.card-grad-1 span, .stat-card.card-grad-2 span, .stat-card.card-grad-3 span, .stat-card.card-grad-4 span {
        color: #ffffff !important;
    }
    .stat-card.card-grad-1 .text-white-50, .stat-card.card-grad-2 .text-white-50, .stat-card.card-grad-3 .text-white-50, .stat-card.card-grad-4 .text-white-50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    .stat-card.card-grad-1 .progress, .stat-card.card-grad-2 .progress, .stat-card.card-grad-3 .progress, .stat-card.card-grad-4 .progress {
        background: rgba(255, 255, 255, 0.25) !important;
    }
    .stat-card.card-grad-1 .progress-bar, .stat-card.card-grad-2 .progress-bar, .stat-card.card-grad-3 .progress-bar, .stat-card.card-grad-4 .progress-bar {
        background-color: #ffffff !important;
    }

    .stat-icon-grad {
        background: rgba(255, 255, 255, 0.18) !important;
        border-radius: 12px;
        width: 52px; height: 52px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: #fff !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        position: relative; z-index: 2;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.25rem;
    }
    .custom-input-group {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background-color: #ffffff !important;
    }
    .custom-input-group input {
        box-shadow: none !important;
        color: #0f172a !important;
    }
    .custom-input-group input::placeholder {
        color: #64748b !important;
        opacity: 1 !important;
    }
    .custom-input-group .input-group-text {
        color: #64748b !important;
    }
    #customStatusFilter {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 8px;
    }

    /* Pendukung tema gelap (dark theme) */
    body.dark-theme .custom-input-group {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    body.dark-theme .custom-input-group input {
        color: #f8fafc !important;
    }
    body.dark-theme .custom-input-group input::placeholder {
        color: #94a3b8 !important;
    }
    body.dark-theme .custom-input-group .input-group-text {
        color: #94a3b8 !important;
    }
    body.dark-theme #customStatusFilter,
    body.dark-theme #customTempoFilter {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }
    body.dark-theme .vehicle-image-wrapper {
        background-color: #0f172a !important;
    }
    
    .badge-soft-success {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .badge-soft-warning {
        background-color: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .badge-soft-danger {
        background-color: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.15s ease;
        border: 1px solid var(--border-color);
        background-color: #ffffff;
        color: var(--text-muted);
        text-decoration: none;
    }
    .btn-action-icon:hover {
        background-color: #f3f4f6;
        color: var(--text-main) !important;
    }
    
    /* DataTables Minimalist UI */
    .dataTables_filter, .dataTables_length { display: none !important; }
    .dataTables_info {
        font-size: 0.85rem;
        color: var(--text-muted);
        padding-top: 1rem !important;
    }
    .dataTables_paginate { padding-top: 1rem !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link {
        background-color: var(--text-main);
        border-color: var(--text-main);
        color: white !important;
        border-radius: 6px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button .page-link {
        border-radius: 6px;
        color: var(--text-main);
        border: 1px solid var(--border-color);
        margin: 0 2px;
    }

    .plate-badge {
        cursor: pointer;
        background-color: #f9fafb !important;
        border: 1px solid var(--border-color) !important;
        transition: all 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.08) !important;
    }
    .hover-card:hover .transition-img {
        transform: scale(1.06);
    }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1 page-header-title">Data Kendaraan & Servis</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Kelola armada perusahaan, pantau odometer real-time, dan jadwalkan pemeliharaan terpadu.</p>
    </div>
    @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin']))
        <div>
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2">
                <i class="bi bi-plus-circle"></i> Tambah Kendaraan Baru
            </a>
        </div>
    @endif
</div>

@php
    $totalKendaraan = $vehicles->count();
    $siapPakai = $vehicles->where('status', 'Siap Pakai')->count() + $vehicles->where('status', 'Selesai')->count();
    $sedangDiservis = $vehicles->where('status', 'Sedang Diservis')->count();
    
    $butuhServis = $vehicles->filter(function($v) {
        $tglServis = isset($v->tanggal_servis_berikutnya) && $v->tanggal_servis_berikutnya ? \Carbon\Carbon::parse($v->tanggal_servis_berikutnya) : null;
        $kmTarget = $v->km_servis_berikutnya ?? 0;
        $kmSekarang = $v->odometer_terkini ?? 0;

        $terlambatTgl = $tglServis && $tglServis->isPast();
        $mendekatiTgl = $tglServis && !$terlambatTgl && now()->diffInDays($tglServis, false) <= 7;
        $terlambatKm = $kmTarget > 0 && $kmSekarang >= $kmTarget;

        return $terlambatTgl || $mendekatiTgl || $terlambatKm;
    })->count();
@endphp

@php
    $pctSiap = $totalKendaraan > 0 ? round(($siapPakai / $totalKendaraan) * 100) : 0;
    $pctServis = $totalKendaraan > 0 ? round(($sedangDiservis / $totalKendaraan) * 100) : 0;
    $pctButuh = $totalKendaraan > 0 ? round(($butuhServis / $totalKendaraan) * 100) : 0;
@endphp

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('vehicles.index') }}" class="stat-card card-grad-1 p-4 d-flex flex-column justify-content-between h-100">
            <div class="d-flex align-items-center gap-4">
                <div class="stat-icon-grad">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; color: #fff;">
                        <path d="M 2.5,5.5 H 12.5 V 7.5 H 15.5 L 18.5,10.5 V 13.5 H 2.5 Z" fill="#ffffff" stroke="#1e1b4b" stroke-width="1.2" stroke-linejoin="round" />
                        <path d="M 13.5,8.5 H 15.2 L 16.8,10.5 H 13.5 Z" fill="#1e1b4b" />
                        <circle cx="6" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                        <circle cx="15" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                    </svg>
                </div>
                <div>
                    <span class="text-white d-block mb-1" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">Total Armada</span>
                    <h3 class="fw-bold text-white mb-0" style="font-size: 1.8rem;">{{ $totalKendaraan }}</h3>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                    <span class="text-white-50">Kondisi Aktif</span>
                    <span class="fw-bold text-white">100%</span>
                </div>
                <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                    <div class="progress-bar bg-white" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('vehicles.index', ['status' => 'Siap Pakai']) }}" class="stat-card card-grad-2 p-4 d-flex flex-column justify-content-between h-100">
            <div class="d-flex align-items-center gap-4">
                <div class="stat-icon-grad">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <span class="text-white d-block mb-1" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">Siap Pakai</span>
                    <h3 class="fw-bold text-white mb-0" style="font-size: 1.8rem;">{{ $siapPakai }}</h3>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                    <span class="text-white-50">Rasio Siap Jalan</span>
                    <span class="fw-bold text-white">{{ $pctSiap }}%</span>
                </div>
                <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                    <div class="progress-bar bg-white" role="progressbar" style="width: {{ $pctSiap }}%;" aria-valuenow="{{ $pctSiap }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('vehicles.index', ['status' => 'Sedang Diservis']) }}" class="stat-card card-grad-3 p-4 d-flex flex-column justify-content-between h-100">
            <div class="d-flex align-items-center gap-4">
                <div class="stat-icon-grad">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <span class="text-white d-block mb-1" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">Sedang Diservis</span>
                    <h3 class="fw-bold text-white mb-0" style="font-size: 1.8rem;">{{ $sedangDiservis }}</h3>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                    <span class="text-white-50">Rasio Perbaikan</span>
                    <span class="fw-bold text-white">{{ $pctServis }}%</span>
                </div>
                <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                    <div class="progress-bar bg-white" role="progressbar" style="width: {{ $pctServis }}%;" aria-valuenow="{{ $pctServis }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card card-grad-4 p-4 d-flex flex-column justify-content-between h-100">
            <div class="d-flex align-items-center gap-4">
                <div class="stat-icon-grad">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <span class="text-white d-block mb-1" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">Peringatan Servis</span>
                    <h3 class="fw-bold text-white mb-0" style="font-size: 1.8rem;">{{ $butuhServis }}</h3>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                    <span class="text-white-50">Rasio Butuh Servis</span>
                    <span class="fw-bold text-white">{{ $pctButuh }}%</span>
                </div>
                <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                    <div class="progress-bar bg-white" role="progressbar" style="width: {{ $pctButuh }}%;" aria-valuenow="{{ $pctButuh }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="d-flex align-items-center gap-3 justify-content-start">
        <div class="input-group custom-input-group" style="width: 410px !important; flex-shrink: 0;">
            <span class="input-group-text bg-transparent border-0 text-muted" style="padding-right: 0;"><i class="bi bi-search"></i></span>
            <input type="text" id="customLiveSearch" class="form-control bg-transparent border-0 shadow-none" placeholder="Cari berdasarkan Plat, Driver, Merk, Tipe, atau Pool..." style="font-size: 0.88rem; padding-left: 8px;">
        </div>
        <select id="customStatusFilter" class="form-select border shadow-none" style="width: 240px !important; min-width: 240px !important; font-size: 0.88rem; flex-shrink: 0;">
            <option value="">Semua Status Operasional</option>
            <option value="Siap Pakai" @selected(request('status') === 'Siap Pakai')>Siap Pakai</option>
            <option value="Sedang Diservis" @selected(request('status') === 'Sedang Diservis')>Sedang Diservis</option>
            <option value="Selesai" @selected(request('status') === 'Selesai')>Selesai</option>
        </select>
        <select id="customTempoFilter" class="form-select border shadow-none" style="width: 285px !important; min-width: 285px !important; font-size: 0.88rem; flex-shrink: 0;">
            <option value="">Semua Status Dokumen/Servis</option>
            <option value="aman">Aman & Valid</option>
            <option value="mendekati">Mendekati</option>
            <option value="lewat">Lewat Tempo</option>
        </select>
    </div>
</div>

<div class="row g-4" id="vehiclesGrid">
    @forelse ($vehicles as $v)
        @php
            $tglServis = isset($v->tanggal_servis_berikutnya) && $v->tanggal_servis_berikutnya ? \Carbon\Carbon::parse($v->tanggal_servis_berikutnya) : null;
            $kmTarget = $v->km_servis_berikutnya ?? 0;
            $kmSekarang = $v->odometer_terkini ?? 0;

            $isTerlambatTgl = $tglServis && $tglServis->isPast();
            $isMendekatiTgl = $tglServis && !$isTerlambatTgl && now()->diffInDays($tglServis, false) <= 7;
            $isTerlambatKm = $kmTarget > 0 && $kmSekarang >= $kmTarget;

            $diffDays = null;
            if ($v->jatuh_tempo_kir) {
                $diffDays = now()->startOfDay()->diffInDays($v->jatuh_tempo_kir->startOfDay(), false);
            }

            $isLewatTempo = ($v->status_kir === 'merah') || $isTerlambatTgl || $isTerlambatKm;
            $isMendekati = !$isLewatTempo && (($v->status_kir === 'kuning') || $isMendekatiTgl);
            $tempoStatus = 'aman';
            if ($isLewatTempo) {
                $tempoStatus = 'lewat';
            } elseif ($isMendekati) {
                $tempoStatus = 'mendekati';
            }
        @endphp
        <div class="col-md-4 vehicle-card-col" data-status="{{ $v->status }}" data-tempo="{{ $tempoStatus }}" data-search="{{ strtolower($v->plat_nomor . ' ' . $v->merek . ' ' . $v->tipe . ' ' . $v->jenis_kendaraan . ' ' . $v->lokasi_pool . ' ' . $v->supir_utama) }}">
            <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs hover-card transition-all p-3" style="transition: all 0.25s ease; border-radius: 16px;">
                <!-- Plate & Status Header (Above Image) -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Plate Badge -->
                    <span class="badge bg-dark text-white font-monospace px-3 py-2 fs-6 border border-secondary shadow-sm" onclick="navigator.clipboard.writeText('{{ $v->plat_nomor }}'); alert('Plat nomor {{ $v->plat_nomor }} berhasil disalin!');" style="cursor: pointer; border-radius: 10px; letter-spacing: 0.8px;">
                        {{ $v->plat_nomor }} <i class="bi bi-clipboard ms-1 text-white-50" style="font-size: 0.75rem;"></i>
                    </span>
                    <!-- Status Badge -->
                    <div>
                        @if ($v->status === 'Siap Pakai')
                            <span class="badge bg-success px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 10px;">
                                Siap Pakai
                            </span>
                        @elseif ($v->status === 'Sedang Diservis')
                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 10px;">
                                Sedang Diservis
                            </span>
                        @else
                            <span class="badge bg-secondary text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">{{ $v->status ?? 'Non-Aktif' }}</span>
                        @endif
                    </div>
                </div>

                <!-- Vehicle Image -->
                <div class="position-relative vehicle-image-wrapper rounded-3" style="height: 230px; overflow: hidden; background: #f8fafc;">
                    <img src="{{ $v->foto_url }}" alt="{{ $v->plat_nomor }}" class="w-100 h-100 object-fit-cover transition-img" style="transition: transform 0.3s ease;">
                </div>
                
                <!-- Card Body -->
                <div class="card-body px-0 pt-3 pb-0">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-uppercase text-primary fw-bold font-monospace" style="font-size: 0.72rem; letter-spacing: 0.8px;">{{ $v->jenis_kendaraan }}</span>
                            <h5 class="card-title fw-extrabold text-dark mb-0 mt-1">{{ $v->merek }}</h5>
                            <small class="text-muted">{{ $v->tipe }} ({{ $v->tahun ?? 2024 }})</small>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="row g-2 my-3 py-2 border-top border-bottom" style="font-size: 0.82rem;">
                        <div class="col-6">
                            <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Jatuh Tempo KIR</span>
                            <span class="fw-semibold text-dark"><i class="bi bi-shield-check text-secondary me-1"></i>{{ $v->jatuh_tempo_kir ? $v->jatuh_tempo_kir->format('d M Y') : 'Belum diset' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Jadwal Servis</span>
                            <span class="fw-semibold text-dark"><i class="bi bi-calendar3 text-secondary me-1"></i>{{ $v->tanggal_servis_berikutnya ? \Carbon\Carbon::parse($v->tanggal_servis_berikutnya)->format('d M Y') : 'Belum diset' }}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Supir Utama</span>
                            <span class="fw-semibold text-dark"><i class="bi bi-person-fill text-secondary me-1"></i>{{ $v->supir_utama ?? '-' }}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Odometer</span>
                            <span class="fw-semibold text-dark font-monospace"><i class="bi bi-speedometer2 text-secondary me-1"></i>{{ number_format($v->odometer_terkini ?? 0, 0, ',', '.') }} km</span>
                        </div>
                    </div>

                    <!-- Warning Statuses -->
                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- Status KIR -->
                        @if ($v->status_kir === 'merah')
                            <div class="alert alert-danger py-1.5 px-3 mb-0 d-flex align-items-center gap-2" style="font-size: 0.75rem; border-radius: 8px; border: 1px solid #fecaca; background: #fef2f2;">
                                <i class="bi bi-x-circle-fill text-danger fs-6"></i>
                                <span class="fw-bold text-danger">KIR Lewat Tempo! (Terlewat {{ $diffDays !== null ? abs($diffDays) : 0 }} hari)</span>
                            </div>
                        @elseif ($v->status_kir === 'kuning')
                            <div class="alert alert-warning py-1.5 px-3 mb-0 d-flex align-items-center gap-2 text-dark" style="font-size: 0.75rem; border-radius: 8px; border: 1px solid #fde68a; background: #fffbeb;">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-6"></i>
                                <span class="fw-bold" style="color: #92400e;">KIR Mendekati Tempo (Sisa {{ $diffDays }} hari)</span>
                            </div>
                        @else
                            <div class="alert alert-success py-1.5 px-3 mb-0 d-flex align-items-center gap-2" style="font-size: 0.75rem; border-radius: 8px; border: 1px solid #a7f3d0; background: #ecfdf5;">
                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                <span class="fw-bold text-success">Dokumen KIR Valid</span>
                            </div>
                        @endif

                        <!-- Status Servis -->
                        @if($isTerlambatTgl || $isTerlambatKm)
                            <div class="alert alert-danger py-1.5 px-3 mb-0 d-flex align-items-center gap-2" style="font-size: 0.75rem; border-radius: 8px; border: 1px solid #fecaca; background: #fef2f2;">
                                <i class="bi bi-exclamation-triangle-fill text-danger fs-6"></i>
                                <span class="fw-bold text-danger">Terlambat Servis!</span>
                            </div>
                        @elseif($isMendekatiTgl)
                            <div class="alert alert-warning py-1.5 px-3 mb-0 d-flex align-items-center gap-2 text-dark" style="font-size: 0.75rem; border-radius: 8px; border: 1px solid #fde68a; background: #fffbeb;">
                                <i class="bi bi-clock-history text-warning fs-6"></i>
                                <span class="fw-bold" style="color: #92400e;">Servis &lt; 7 Hari</span>
                            </div>
                        @else
                            <div class="alert alert-success py-1.5 px-3 mb-0 d-flex align-items-center gap-2" style="font-size: 0.75rem; border-radius: 8px; border: 1px solid #a7f3d0; background: #ecfdf5;">
                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                <span class="fw-bold text-success">Jadwal Servis Aman</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Controls -->
                    <div class="d-flex flex-column gap-3 border-top" style="margin-top: 24px !important; padding-top: 20px !important;">
                        <!-- Quick status updater for Admin & Technician -->
                        @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted small fw-semibold">Ubah Status:</span>
                                <form action="{{ route('vehicles.updateStatus', $v) }}" method="POST" class="d-inline-block m-0">
                                    @csrf 
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm shadow-none border" style="font-size: 0.78rem; font-weight: 600; border-radius: 8px; background-color: #f8fafc; padding: 6px 12px; width: 135px;" onchange="this.form.submit()">
                                        <option value="Siap Pakai" @selected($v->status === 'Siap Pakai')>Siap Pakai</option>
                                        <option value="Sedang Diservis" @selected($v->status === 'Sedang Diservis')>Sedang Diservis</option>
                                        <option value="Selesai" @selected($v->status === 'Selesai')>Selesai</option>
                                    </select>
                                </form>
                            </div>
                        @endif
 
                        <!-- General Actions -->
                        <div class="d-flex align-items-center justify-content-end gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary px-3 py-1.5 d-inline-flex align-items-center gap-1 btn-detail-vehicle" 
                                    data-plat="{{ $v->plat_nomor }}"
                                    data-merek="{{ $v->merek }}"
                                    data-tipe="{{ $v->tipe }}"
                                    data-tahun="{{ $v->tahun ?? 2024 }}"
                                    data-km="{{ number_format($v->odometer_terkini ?? 0, 0, ',', '.') }} km"
                                    data-driver="{{ $v->supir_utama ?? 'Belum diset' }}"
                                    data-pool="{{ $v->lokasi_pool ?? '-' }}"
                                    data-status="{{ $v->status }}"
                                    data-foto="{{ $v->foto_url }}"
                                    data-show-url="{{ route('vehicles.show', $v) }}"
                                    style="border-radius: 8px; font-size: 0.78rem; font-weight: 600; margin-right: 8px !important;">
                                <i class="bi bi-eye-fill"></i> Detail
                            </button>
                            @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin']))
                                <a href="{{ route('vehicles.edit', $v) }}" class="btn btn-sm btn-outline-warning d-inline-flex align-items-center justify-content-center" style="border-radius: 8px; width: 32px; height: 32px; padding: 0; margin-right: 8px !important;" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('vehicles.destroy', $v) }}" method="POST" class="d-inline m-0 form-confirm-delete" data-text="Data kendaraan ini beserta seluruh riwayat terkait akan dihapus secara permanen dari sistem!">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" style="border-radius: 8px; width: 32px; height: 32px; padding: 0;" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <div class="mb-3">
                <div class="bg-light rounded-circle d-inline-flex p-4 text-secondary opacity-75 shadow-xs" style="background: rgba(2, 132, 199, 0.08) !important;">
                    <i class="bi bi-car-front fs-1 text-primary" style="color: #0284c7 !important;"></i>
                </div>
            </div>
            <h5 class="fw-bold text-dark mb-1">Tidak ada data kendaraan</h5>
            <p class="text-secondary mb-0" style="font-size: 0.9rem;">Belum ada data armada kendaraan yang terdaftar di dalam sistem.</p>
        </div>
    @endforelse
</div>

<!-- Modal Detail Kendaraan & QR Code -->
<div class="modal fade" id="detailVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark mb-0"><i class="bi bi-car-front-fill text-primary me-2"></i> Informasi Detail Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Vehicle Card Preview inside Modal -->
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-4">
                    <img id="modalVehicleFoto" src="" alt="Armada" class="rounded-3 object-fit-cover" style="width: 80px; height: 80px; background: #fff;">
                    <div>
                        <span class="badge bg-dark text-white font-monospace px-2 py-1 mb-1" id="modalVehiclePlat" style="letter-spacing: 0.5px; border-radius: 6px;"></span>
                        <h6 class="fw-bold mb-0 text-dark" id="modalVehicleMerekTipe"></h6>
                        <span class="badge mt-1" id="modalVehicleStatus" style="font-size: 0.72rem;"></span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-2.5 border rounded-3 bg-light bg-opacity-50">
                            <span class="text-muted d-block mb-0.5" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Polisi</span>
                            <strong class="text-dark fs-6" id="modalVehiclePlatDetail"></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 border rounded-3 bg-light bg-opacity-50">
                            <span class="text-muted d-block mb-0.5" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Tahun Pembuatan</span>
                            <strong class="text-dark fs-6" id="modalVehicleTahun"></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 border rounded-3 bg-light bg-opacity-50">
                            <span class="text-muted d-block mb-0.5" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Odometer Terkini</span>
                            <strong class="text-dark fs-6" id="modalVehicleKM"></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 border rounded-3 bg-light bg-opacity-50">
                            <span class="text-muted d-block mb-0.5" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Driver / Supir</span>
                            <strong class="text-dark fs-6" id="modalVehicleDriver"></strong>
                        </div>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="mt-4 pt-3 border-top text-center">
                    <span class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">QR Code Kendaraan</span>
                    <div class="d-inline-block border rounded-4 p-2 bg-white shadow-sm mb-2">
                        <img id="modalVehicleQR" src="" alt="QR Code" style="width: 140px; height: 140px;">
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.75rem;">Tempel QR di kendaraan untuk scan &amp; melacak riwayat servis langsung.</p>
                </div>
            </div>
            <div class="modal-footer border-top p-2 bg-light d-flex gap-2">
                <a id="modalVehicleLink" href="" class="btn btn-primary flex-fill fw-bold py-2 d-flex align-items-center justify-content-center gap-1" style="border-radius: 10px;"><i class="bi bi-clock-history"></i> Buka Riwayat Lengkap</a>
                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button (FAB) for Admin to Add Vehicle -->
@if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin']))
<div class="fab-container">
    <a href="{{ route('vehicles.create') }}" class="fab-btn" title="Tambah Kendaraan Baru">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>
@endif

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        function filterVehicles() {
            var searchVal = $('#customLiveSearch').val().toLowerCase().trim();
            var filterStatus = $('#customStatusFilter').val();
            var filterTempo = $('#customTempoFilter').val();

            $('.vehicle-card-col').each(function () {
                var cardSearch = $(this).data('search');
                var cardStatus = $(this).data('status');
                var cardTempo = $(this).data('tempo');

                var matchesSearch = cardSearch.indexOf(searchVal) > -1;
                var matchesStatus = !filterStatus || cardStatus === filterStatus;
                var matchesTempo = !filterTempo || cardTempo === filterTempo;

                if (matchesSearch && matchesStatus && matchesTempo) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        var searchTimeout;
        $('#customLiveSearch').on('keyup', function () {
            clearTimeout(searchTimeout);
            var searchVal = $(this).val().toLowerCase().trim();

            // Render skeleton loader
            var skeletonHtml = '';
            for (var i = 0; i < 3; i++) {
                skeletonHtml += `
                    <div class="col-md-4 vehicle-skeleton-col">
                        <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs" style="border-radius: 16px;">
                            <div class="skeleton" style="height: 180px; width: 100%;"></div>
                            <div class="card-body p-4">
                                <div class="skeleton mb-2" style="height: 15px; width: 45%;"></div>
                                <div class="skeleton mb-3" style="height: 24px; width: 70%;"></div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6"><div class="skeleton" style="height: 14px; width: 80%;"></div></div>
                                    <div class="col-6"><div class="skeleton" style="height: 14px; width: 80%;"></div></div>
                                </div>
                                <div class="skeleton" style="height: 35px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            $('.vehicle-skeleton-col').remove();
            if (searchVal.length > 0) {
                $('.vehicle-card-col').hide();
                $('#vehiclesGrid').prepend(skeletonHtml);
            }

            searchTimeout = setTimeout(function () {
                $('.vehicle-skeleton-col').remove();
                filterVehicles();
            }, 250);
        });

        $('#customStatusFilter').on('change', filterVehicles);
        $('#customTempoFilter').on('change', filterVehicles);

        // Vehicle Detail Modal population handler
        $('.btn-detail-vehicle').on('click', function () {
            var plat = $(this).data('plat');
            var merek = $(this).data('merek');
            var tipe = $(this).data('tipe');
            var tahun = $(this).data('tahun');
            var km = $(this).data('km');
            var driver = $(this).data('driver');
            var pool = $(this).data('pool');
            var status = $(this).data('status');
            var foto = $(this).data('foto');
            var showUrl = $(this).data('show-url');

            // Populate text
            $('#modalVehiclePlat').text(plat);
            $('#modalVehiclePlatDetail').text(plat);
            $('#modalVehicleMerekTipe').text(merek + ' ' + tipe);
            $('#modalVehicleTahun').text(tahun);
            $('#modalVehicleKM').text(km);
            $('#modalVehicleDriver').text(driver);
            
            // Set image
            $('#modalVehicleFoto').attr('src', foto);
            
            // Generate QR Code URL using free public QR code generator API
            var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(showUrl);
            $('#modalVehicleQR').attr('src', qrUrl);
            
            // Set link
            $('#modalVehicleLink').attr('href', showUrl);

            // Set status badge style
            var $badge = $('#modalVehicleStatus').text(status).removeClass('bg-success bg-warning bg-secondary text-white text-dark');
            if (status === 'Siap Pakai') {
                $badge.addClass('bg-success text-white');
            } else if (status === 'Sedang Diservis') {
                $badge.addClass('bg-warning text-dark');
            } else {
                $badge.addClass('bg-secondary text-white');
            }

            // Open Modal
            var modal = new bootstrap.Modal(document.getElementById('detailVehicleModal'));
            modal.show();
        });
    });
</script>
@endpush
@endsection