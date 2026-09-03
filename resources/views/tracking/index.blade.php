@extends('layouts.app')

@section('title', __('Pelacakan Kendaraan (Live Fleet GPS Tracking)'))

@push('styles')
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <style>
        .tracking-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Layout Grid */
        .tracking-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 20px;
            min-height: calc(100vh - 180px);
        }

        @media (max-width: 991px) {
            .tracking-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Sidebar Panel */
        .tracking-sidebar {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            height: calc(100vh - 200px);
            position: sticky;
            top: 80px;
        }

        .tracking-sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            background: #fafafa;
        }

        .tracking-sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Map Container */
        .tracking-map-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            height: calc(100vh - 200px);
            position: relative;
        }

        .tracking-map-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            z-index: 10;
        }

        #liveFleetMap {
            flex: 1;
            width: 100%;
            height: 100%;
            min-height: 450px;
            background: #e5e7eb;
            z-index: 1;
        }

        /* Vehicle List Card */
        .vehicle-track-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 14px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .vehicle-track-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.1);
        }

        .vehicle-track-card.active {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        }

        /* Custom Marker CSS with Pulse Effects */
        .custom-vehicle-marker {
            position: relative;
        }

        .marker-pin {
            width: 38px;
            height: 38px;
            border-radius: 50% 50% 50% 0;
            background: #0ea5e9;
            position: absolute;
            transform: rotate(-45deg);
            left: 50%;
            top: 50%;
            margin: -24px 0 0 -19px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.35);
            border: 2px solid #ffffff;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .marker-pin i {
            transform: rotate(45deg);
            color: #ffffff;
            font-size: 1.1rem;
            margin-top: -2px;
            margin-left: -2px;
        }

        .marker-pulse {
            background: rgba(14, 165, 233, 0.3);
            border-radius: 50%;
            height: 20px;
            width: 20px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin: -10px 0 0 -10px;
            animation: pulse-ring 1.8s ease-out infinite;
            z-index: -1;
        }

        /* Color variations for marker types */
        .marker-ready .marker-pin {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        .marker-ready .marker-pulse {
            background: rgba(16, 185, 129, 0.4);
        }

        .marker-servis .marker-pin {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        .marker-servis .marker-pulse {
            background: rgba(245, 158, 11, 0.4);
        }

        .marker-warning .marker-pin {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        .marker-warning .marker-pulse {
            background: rgba(239, 68, 68, 0.4);
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.6); opacity: 1; }
            100% { transform: scale(2.8); opacity: 0; }
        }

        /* Map Controls Overlay */
        .map-floating-controls {
            position: absolute;
            top: 75px;
            right: 20px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .map-floating-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            font-size: 1.15rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            cursor: pointer;
            transition: all 0.2s;
        }

        .map-floating-btn:hover {
            background: #f8fafc;
            color: #2563eb;
            transform: scale(1.06);
        }

        /* Map Legend Overlay */
        .map-legend-overlay {
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 999;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.08);
            font-size: 0.78rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        /* Dark Theme Support */
        body.dark-theme .tracking-sidebar,
        body.dark-theme .tracking-map-card,
        body.dark-theme .tracking-sidebar-header,
        body.dark-theme .tracking-map-header {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-theme .vehicle-track-card {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        body.dark-theme .vehicle-track-card.active {
            border-color: #60a5fa !important;
            background: #1e3a8a !important;
        }

        body.dark-theme .map-floating-btn {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        body.dark-theme .map-legend-overlay {
            background: rgba(30, 41, 59, 0.92) !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        body.dark-theme .custom-search-input {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
    </style>
@endpush

@section('content')
<div class="tracking-container">
    <!-- Header Title & Quick Stats -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt-fill text-primary"></i> {{ __('Pelacakan Kendaraan & Live GPS Armada') }}
            </h3>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">
                {{ __('Pantau posisi geografis, rute perjalanan, status jalan, dan kondisi seluruh armada secara real-time.') }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-primary d-flex align-items-center gap-2" id="btnRefreshTracking" style="border-radius: 10px; font-size: 0.88rem;">
                <i class="bi bi-arrow-clockwise" id="refreshIcon"></i> {{ __('Segarkan Peta') }}
            </button>
            <a href="{{ route('vehicles.index') }}" class="btn btn-light border d-flex align-items-center gap-2" style="border-radius: 10px; font-size: 0.88rem;">
                <i class="bi bi-car-front"></i> {{ __('Daftar Armada') }}
            </a>
        </div>
    </div>

    <!-- 4 Stat Metric Cards -->
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3" style="border-radius: 14px; background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background: rgba(255,255,255,0.12); font-size: 1.3rem;">
                    <i class="bi bi-truck-front-fill text-info"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Total Terlacak') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['total'] }} <span style="font-size: 0.8rem; font-weight: normal;">Unit</span></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3" style="border-radius: 14px; background: linear-gradient(135deg, #065f46, #047857); color: #fff;">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background: rgba(255,255,255,0.12); font-size: 1.3rem;">
                    <i class="bi bi-check-circle-fill text-success-emphasis" style="color: #a7f3d0 !important;"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Siap Jalan') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['siap_pakai'] }} <span style="font-size: 0.8rem; font-weight: normal;">Unit</span></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3" style="border-radius: 14px; background: linear-gradient(135deg, #b45309, #d97706); color: #fff;">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background: rgba(255,255,255,0.12); font-size: 1.3rem;">
                    <i class="bi bi-wrench-adjustable-circle text-warning-emphasis" style="color: #fde68a !important;"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Dalam Servis') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['sedang_diservis'] }} <span style="font-size: 0.8rem; font-weight: normal;">Unit</span></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3" style="border-radius: 14px; background: linear-gradient(135deg, #991b1b, #dc2626); color: #fff;">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background: rgba(255,255,255,0.12); font-size: 1.3rem;">
                    <i class="bi bi-exclamation-octagon-fill text-danger-emphasis" style="color: #fecaca !important;"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Perlu Perhatian') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['peringatan'] }} <span style="font-size: 0.8rem; font-weight: normal;">Unit</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Sidebar + Interactive Map -->
    <div class="tracking-grid">
        <!-- Sidebar Panel -->
        <div class="tracking-sidebar">
            <div class="tracking-sidebar-header">
                <!-- Search Box -->
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="trackingSearch" class="form-control border-start-0 custom-search-input" placeholder="{{ __('Cari plat nomor, supir, pool...') }}">
                </div>

                <!-- Status Filter Pills -->
                <div class="d-flex align-items-center gap-1 overflow-x-auto pb-1" id="filterPills">
                    <button class="btn btn-xs btn-primary rounded-pill px-2.5 py-1 filter-pill active" data-filter="all">{{ __('Semua') }}</button>
                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 filter-pill" data-filter="Siap Pakai">{{ __('Siap') }}</button>
                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 filter-pill" data-filter="Sedang Diservis">{{ __('Servis') }}</button>
                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 filter-pill" data-filter="warning">{{ __('Peringatan') }}</button>
                </div>
            </div>

            <!-- Vehicle Cards List -->
            <div class="tracking-sidebar-content" id="vehicleCardsList">
                @forelse($mapVehicles as $v)
                    <div class="vehicle-track-card {{ (isset($selectedVehicleId) && $selectedVehicleId == $v['id']) ? 'active' : '' }}" 
                         data-id="{{ $v['id'] }}"
                         data-plat="{{ strtolower($v['plat_nomor']) }}"
                         data-merek="{{ strtolower($v['merek'] . ' ' . $v['tipe']) }}"
                         data-supir="{{ strtolower($v['supir_utama']) }}"
                         data-pool="{{ strtolower($v['lokasi_pool']) }}"
                         data-status="{{ $v['status'] }}"
                         data-marker-type="{{ $v['marker_type'] }}"
                         data-lat="{{ $v['latitude'] }}"
                         data-lng="{{ $v['longitude'] }}">
                        
                        <div class="d-flex align-items-start gap-2.5">
                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 48px; height: 48px; background: #f1f5f9;">
                                <img src="{{ $v['foto_url'] }}" alt="{{ $v['plat_nomor'] }}" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-truncate font-monospace" style="font-size: 0.95rem; color: #1e40af;">
                                        {{ $v['plat_nomor'] }}
                                    </h6>
                                    @if($v['status'] === 'Siap Pakai')
                                        <span class="badge bg-success-subtle text-success py-0.5 px-1.5" style="font-size: 0.68rem;">Siap</span>
                                    @elseif($v['status'] === 'Sedang Diservis')
                                        <span class="badge bg-warning-subtle text-warning py-0.5 px-1.5" style="font-size: 0.68rem;">Servis</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary py-0.5 px-1.5" style="font-size: 0.68rem;">{{ $v['status'] }}</span>
                                    @endif
                                </div>
                                <div class="text-muted text-truncate" style="font-size: 0.78rem;">
                                    {{ $v['merek'] }} {{ $v['tipe'] }}
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1 pt-1 border-top" style="font-size: 0.72rem; color: #64748b;">
                                    <span><i class="bi bi-person me-1"></i>{{ $v['supir_utama'] }}</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>{{ $v['lokasi_pool'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions on Card -->
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-1 border-top gap-1">
                            <button class="btn btn-xs btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-1 btn-focus-marker" data-id="{{ $v['id'] }}" style="border-radius: 6px; font-size: 0.72rem; padding: 3px 6px;">
                                <i class="bi bi-crosshair"></i> {{ __('Fokus di Peta') }}
                            </button>
                            <button class="btn btn-xs btn-outline-success w-100 d-flex align-items-center justify-content-center gap-1 btn-sync-gps" data-id="{{ $v['id'] }}" data-plat="{{ $v['plat_nomor'] }}" style="border-radius: 6px; font-size: 0.72rem; padding: 3px 6px;" title="{{ __('Sinkronkan posisi GPS HP saat ini') }}">
                                <i class="bi bi-phone-vibrate"></i> {{ __('Update GPS HP') }}
                            </button>
                            <a href="{{ $v['detail_url'] }}" class="btn btn-xs btn-light border d-flex align-items-center justify-content-center" style="border-radius: 6px; font-size: 0.72rem; padding: 3px 8px;" title="{{ __('Lihat Detail') }}">
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-car-front fs-2 d-block mb-2"></i>
                        <span>{{ __('Tidak ada data kendaraan ditemukan.') }}</span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Interactive Map Card -->
        <div class="tracking-map-card">
            <!-- Map Card Top Bar -->
            <div class="tracking-map-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-2.5 py-1.5 rounded-pill text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <i class="bi bi-broadcast me-1"></i> LIVE GPS
                    </span>
                    <span class="text-muted" style="font-size: 0.82rem;" id="activeVehicleCount">
                        Menampilkan <b>{{ $mapVehicles->count() }}</b> kendaraan aktif
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Layer Switcher -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" style="border-radius: 8px; font-size: 0.8rem;">
                            <i class="bi bi-layers"></i> {{ __('Tipe Peta') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 10px; font-size: 0.85rem;">
                            <li><a class="dropdown-item py-2 active" href="#" id="layerVoyager"><i class="bi bi-map me-2 text-primary"></i> {{ __('Peta Default (Voyager)') }}</a></li>
                            <li><a class="dropdown-item py-2" href="#" id="layerOSM"><i class="bi bi-globe me-2 text-success"></i> {{ __('OpenStreetMap') }}</a></li>
                            <li><a class="dropdown-item py-2" href="#" id="layerSatellite"><i class="bi bi-bounding-box-circles me-2 text-info"></i> {{ __('Mode Satelit / Esri') }}</a></li>
                        </ul>
                    </div>

                    <!-- Simulation Route Button -->
                    <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" id="btnSimulateRoute" style="border-radius: 8px; font-size: 0.8rem;" title="{{ __('Jalankan simulasi rute pergerakan armada') }}">
                        <i class="bi bi-play-circle-fill"></i> {{ __('Simulasi Rute') }}
                    </button>

                    <!-- Fullscreen Toggle -->
                    <button class="btn btn-sm btn-light border" id="btnToggleFullscreen" style="border-radius: 8px;" title="{{ __('Layar Penuh') }}">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                </div>
            </div>

            <!-- Leaflet Map Container -->
            <div id="liveFleetMap"></div>

            <!-- Floating Control Buttons -->
            <div class="map-floating-controls">
                <button class="map-floating-btn" id="btnFitAll" title="{{ __('Lihat Semua Kendaraan') }}">
                    <i class="bi bi-aspect-ratio"></i>
                </button>
                <button class="map-floating-btn" id="btnCenterPool" title="{{ __('Pusat Pool Jakarta') }}">
                    <i class="bi bi-building"></i>
                </button>
                <button class="map-floating-btn" id="btnMyPosition" title="{{ __('Lokasi GPS Saya Saat Ini') }}">
                    <i class="bi bi-crosshair2"></i>
                </button>
            </div>

            <!-- Map Status Legend Overlay -->
            <div class="map-legend-overlay">
                <div class="legend-item">
                    <span class="legend-dot" style="background: #10b981;"></span>
                    <span>{{ __('Siap Jalan') }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background: #f59e0b;"></span>
                    <span>{{ __('Sedang Servis') }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background: #ef4444;"></span>
                    <span>{{ __('KIR/Servis Lewat Batas') }}</span>
                </div>
                <div class="legend-item text-muted">
                    <i class="bi bi-info-circle me-1"></i> {{ __('Klik marker untuk detail rute & navigasi') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update GPS Manual / Preset Pool -->
<div class="modal fade" id="modalUpdateGps" tabindex="-1" aria-labelledby="modalUpdateGpsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-primary text-white py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h6 class="modal-title fw-bold" id="modalUpdateGpsLabel">
                    <i class="bi bi-geo-alt-fill me-1"></i> {{ __('Perbarui Titik Lokasi GPS Kendaraan') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateGps" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 d-flex align-items-center gap-2 mb-3" style="border-radius: 10px; font-size: 0.82rem;">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>{{ __('Pilih koordinat pool atau klik tombol Sinkronkan GPS HP di bawah.') }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Kendaraan') }}</label>
                        <input type="text" id="modalPlatNomor" class="form-control fw-bold font-monospace bg-light" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Preset Lokasi Pool Armada') }}</label>
                        <select class="form-select" id="selectPresetPool">
                            <option value="">-- {{ __('Pilih Lokasi Pool Siap Pakai') }} --</option>
                            <option value="-6.208763,106.845599|Pool Jakarta Pusat">Pool Jakarta Pusat (-6.2087, 106.8455)</option>
                            <option value="-6.175392,106.827153|Pool Monas">Pool Monas (-6.1753, 106.8271)</option>
                            <option value="-6.244431,106.800635|Pool Blok M">Pool Blok M (-6.2444, 106.8006)</option>
                            <option value="-6.126588,106.905663|Pool Tanjung Priok">Pool Tanjung Priok (-6.1265, 106.9056)</option>
                            <option value="-6.300641,106.814095|Pool Ragunan">Pool Ragunan (-6.3006, 106.8140)</option>
                            <option value="-6.201720,106.782155|Pool Palmerah">Pool Palmerah (-6.2017, 106.7821)</option>
                            <option value="-6.258882,106.852443|Pool Cawang">Pool Cawang (-6.2588, 106.8524)</option>
                            <option value="-6.195301,106.822301|Pool Sudirman">Pool Sudirman (-6.1953, 106.8223)</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Latitude') }}</label>
                            <input type="number" step="any" name="latitude" id="modalLatitude" class="form-control" required placeholder="-6.2088">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Longitude') }}</label>
                            <input type="number" step="any" name="longitude" id="modalLongitude" class="form-control" required placeholder="106.8456">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Nama Lokasi / Pool') }}</label>
                        <input type="text" name="lokasi_pool" id="modalLokasiPool" class="form-control" placeholder="Contoh: Pool Blok M / Kantor Cabang">
                    </div>

                    <button type="button" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 py-2" id="btnAcquireDeviceGps" style="border-radius: 10px; font-size: 0.88rem;">
                        <i class="bi bi-crosshair fs-5"></i> {{ __('Ambil Koordinat dari GPS HP Sekarang') }}
                    </button>
                </div>
                <div class="modal-footer bg-light py-3 border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="btnSubmitUpdateGps" style="border-radius: 8px;">
                        <i class="bi bi-check-lg me-1"></i> {{ __('Simpan Lokasi GPS') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Leaflet Map JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Master data armada dari backend
    let vehiclesData = {!! json_encode($mapVehicles) !!};
    const selectedVehicleId = "{{ $selectedVehicleId }}";

    // Peta Default Jakarta
    const defaultCenter = [-6.208763, 106.845599];
    const defaultZoom = 12;

    // Tile Layers
    const voyagerLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>'
    });

    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    });

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    // Inisialisasi Map
    const map = L.map('liveFleetMap', {
        center: defaultCenter,
        zoom: defaultZoom,
        layers: [voyagerLayer],
        scrollWheelZoom: true,
        dragging: true,
        tap: true
    });

    // Layer Switcher Events
    document.getElementById('layerVoyager').addEventListener('click', function(e) {
        e.preventDefault();
        map.removeLayer(osmLayer);
        map.removeLayer(satelliteLayer);
        map.addLayer(voyagerLayer);
        updateActiveLayer(this);
    });

    document.getElementById('layerOSM').addEventListener('click', function(e) {
        e.preventDefault();
        map.removeLayer(voyagerLayer);
        map.removeLayer(satelliteLayer);
        map.addLayer(osmLayer);
        updateActiveLayer(this);
    });

    document.getElementById('layerSatellite').addEventListener('click', function(e) {
        e.preventDefault();
        map.removeLayer(voyagerLayer);
        map.removeLayer(osmLayer);
        map.addLayer(satelliteLayer);
        updateActiveLayer(this);
    });

    function updateActiveLayer(clickedEl) {
        document.querySelectorAll('#layerVoyager, #layerOSM, #layerSatellite').forEach(el => el.classList.remove('active'));
        clickedEl.classList.add('active');
    }

    // Marker Store
    let markersMap = {};
    let markerGroup = L.featureGroup();

    // Fungsi Render Marker
    function renderMarkers(vehicles) {
        // Hapus marker sebelumnya
        markerGroup.clearLayers();
        markersMap = {};

        vehicles.forEach(function (v) {
            const lat = parseFloat(v.latitude);
            const lng = parseFloat(v.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            // Custom DivIcon
            const vehicleIcon = L.divIcon({
                className: 'custom-vehicle-marker ' + v.marker_type,
                html: '<div class="marker-pin"><i class="bi ' + v.icon_class + '"></i></div><div class="marker-pulse"></div>',
                iconSize: [38, 38],
                iconAnchor: [19, 38],
                popupAnchor: [0, -34]
            });

            // Popup HTML
            const statusBadgeClass = v.status === 'Siap Pakai' ? 'bg-success' : (v.status === 'Sedang Diservis' ? 'bg-warning text-dark' : 'bg-secondary');
            const kirBadgeClass = v.status_kir === 'merah' ? 'bg-danger' : (v.status_kir === 'kuning' ? 'bg-warning text-dark' : 'bg-success');
            
            const popupHtml = `
                <div style="font-family: 'Inter', sans-serif; font-size: 0.85rem; width: 250px; line-height: 1.4;">
                    <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                        <img src="${v.foto_url}" class="rounded object-fit-cover" style="width: 52px; height: 52px; background: #f8fafc; border: 1px solid #e2e8f0;" />
                        <div>
                            <div class="fw-bold font-monospace text-primary" style="font-size: 1.05rem;">${v.plat_nomor}</div>
                            <small class="text-muted fw-semibold">${v.merek} ${v.tipe}</small>
                        </div>
                    </div>
                    <div style="font-size: 0.78rem;" class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Status Kendaraan:</span>
                            <span class="badge ${statusBadgeClass}">${v.status}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Supir Utama:</span>
                            <span class="fw-semibold text-dark">${v.supir_utama}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Lokasi Pool:</span>
                            <span class="fw-semibold text-dark">${v.lokasi_pool}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Odometer:</span>
                            <span class="fw-bold text-dark">${v.odometer.toLocaleString('id-ID')} km</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Status KIR:</span>
                            <span class="badge ${kirBadgeClass}">${v.status_kir.toUpperCase()}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Koordinat GPS:</span>
                            <span class="font-monospace text-muted" style="font-size: 0.72rem;">${lat.toFixed(5)}, ${lng.toFixed(5)}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-1 pt-1 border-top">
                        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" class="btn btn-xs btn-outline-primary w-100 text-center py-1 fw-semibold d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px; font-size: 0.72rem; text-decoration: none;">
                            <i class="bi bi-map"></i> Buka di Google Maps
                        </a>
                        <a href="${v.detail_url}" class="btn btn-xs btn-primary text-white w-100 text-center py-1 fw-bold d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px; font-size: 0.72rem; text-decoration: none;">
                            <i class="bi bi-card-checklist"></i> Lihat Profil Kendaraan
                        </a>
                    </div>
                </div>
            `;

            const marker = L.marker([lat, lng], { icon: vehicleIcon }).addTo(markerGroup);
            marker.bindPopup(popupHtml);

            // Click listener on marker to activate card
            marker.on('click', function () {
                highlightCard(v.id);
            });

            markersMap[v.id] = marker;
        });

        markerGroup.addTo(map);

        if (vehicles.length > 0) {
            setTimeout(function() {
                map.invalidateSize();
                if (!selectedVehicleId) {
                    map.fitBounds(markerGroup.getBounds().pad(0.12));
                }
            }, 250);
        }
    }

    // Initial render
    renderMarkers(vehiclesData);

    // Focus on specific vehicle if selectedVehicleId exists in URL
    if (selectedVehicleId && markersMap[selectedVehicleId]) {
        setTimeout(function() {
            focusVehicle(selectedVehicleId);
        }, 500);
    }

    // Highlight Card
    function highlightCard(vehicleId) {
        document.querySelectorAll('.vehicle-track-card').forEach(c => c.classList.remove('active'));
        const targetCard = document.querySelector(`.vehicle-track-card[data-id="${vehicleId}"]`);
        if (targetCard) {
            targetCard.classList.add('active');
            targetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // Focus Vehicle on Map
    function focusVehicle(vehicleId) {
        const marker = markersMap[vehicleId];
        if (marker) {
            const latLng = marker.getLatLng();
            map.flyTo(latLng, 16, { animate: true, duration: 1.2 });
            marker.openPopup();
            highlightCard(vehicleId);
        }
    }

    // Vehicle Card Click Handlers
    document.querySelectorAll('.vehicle-track-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.btn-sync-gps') || e.target.closest('a')) return;
            const id = this.getAttribute('data-id');
            focusVehicle(id);
        });
    });

    // Focus button on card
    document.querySelectorAll('.btn-focus-marker').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            focusVehicle(id);
        });
    });

    // Floating Button Controls
    document.getElementById('btnFitAll').addEventListener('click', function() {
        if (markerGroup.getLayers().length > 0) {
            map.fitBounds(markerGroup.getBounds().pad(0.15));
        }
    });

    document.getElementById('btnCenterPool').addEventListener('click', function() {
        map.flyTo(defaultCenter, 13, { animate: true });
    });

    // User GPS location
    document.getElementById('btnMyPosition').addEventListener('click', function() {
        if ("geolocation" in navigator) {
            const btn = this;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    btn.innerHTML = '<i class="bi bi-crosshair2"></i>';
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    map.flyTo([userLat, userLng], 16);

                    // Add user location marker
                    const userIcon = L.divIcon({
                        className: 'custom-user-marker',
                        html: '<div style="width:20px;height:20px;border-radius:50%;background:#2563eb;border:3px solid #fff;box-shadow:0 0 10px rgba(37,99,235,0.8);"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });
                    L.marker([userLat, userLng], { icon: userIcon }).addTo(map).bindPopup("<b>Posisi Anda Saat Ini</b>").openPopup();
                },
                function(error) {
                    btn.innerHTML = '<i class="bi bi-crosshair2"></i>';
                    alert("Gagal mengakses GPS: " + error.message);
                }
            );
        } else {
            alert("Browser tidak mendukung Geolocation.");
        }
    });

    // Fullscreen Toggle
    document.getElementById('btnToggleFullscreen').addEventListener('click', function() {
        const card = document.querySelector('.tracking-map-card');
        if (!document.fullscreenElement) {
            card.requestFullscreen().catch(err => {
                alert(`Error saat mengaktifkan layar penuh: ${err.message}`);
            });
            this.innerHTML = '<i class="bi bi-fullscreen-exit"></i>';
        } else {
            document.exitFullscreen();
            this.innerHTML = '<i class="bi bi-arrows-fullscreen"></i>';
        }
    });

    // Refresh Tracking Data via AJAX API
    const btnRefresh = document.getElementById('btnRefreshTracking');
    const refreshIcon = document.getElementById('refreshIcon');
    btnRefresh.addEventListener('click', function() {
        refreshIcon.classList.add('spin-animation');
        fetch("{{ route('tracking.api') }}")
            .then(res => res.json())
            .then(data => {
                refreshIcon.classList.remove('spin-animation');
                if (data.status === 'success') {
                    vehiclesData = data.vehicles;
                    renderMarkers(vehiclesData);
                    document.getElementById('activeVehicleCount').innerHTML = `Menampilkan <b>${vehiclesData.length}</b> kendaraan aktif (Diperbarui ${new Date().toLocaleTimeString()})`;
                }
            })
            .catch(err => {
                refreshIcon.classList.remove('spin-animation');
                console.error("Gagal menyegarkan data pelacakan:", err);
            });
    });

    // Search & Filter functionality
    const searchInput = document.getElementById('trackingSearch');
    const filterPills = document.querySelectorAll('.filter-pill');
    let currentFilter = 'all';

    function applyFilters() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        document.querySelectorAll('.vehicle-track-card').forEach(card => {
            const plat = card.getAttribute('data-plat');
            const merek = card.getAttribute('data-merek');
            const supir = card.getAttribute('data-supir');
            const pool = card.getAttribute('data-pool');
            const status = card.getAttribute('data-status');
            const markerType = card.getAttribute('data-marker-type');

            const matchesSearch = !query || plat.includes(query) || merek.includes(query) || supir.includes(query) || pool.includes(query);
            let matchesFilter = true;

            if (currentFilter === 'Siap Pakai') {
                matchesFilter = (status === 'Siap Pakai');
            } else if (currentFilter === 'Sedang Diservis') {
                matchesFilter = (status === 'Sedang Diservis');
            } else if (currentFilter === 'warning') {
                matchesFilter = (markerType === 'marker-warning');
            }

            if (matchesSearch && matchesFilter) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', applyFilters);

    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => {
                p.classList.remove('active', 'btn-primary');
                p.classList.add('btn-outline-secondary');
            });
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-outline-secondary');
            currentFilter = this.getAttribute('data-filter');
            applyFilters();
        });
    });

    // ==========================================
    // GPS SYNC MODAL & DEVICE GEOLOCATION
    // ==========================================
    let currentUpdatingVehicleId = null;
    const modalUpdateGps = new bootstrap.Modal(document.getElementById('modalUpdateGps'));

    document.querySelectorAll('.btn-sync-gps').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            currentUpdatingVehicleId = this.getAttribute('data-id');
            const plat = this.getAttribute('data-plat');
            
            const card = document.querySelector(`.vehicle-track-card[data-id="${currentUpdatingVehicleId}"]`);
            const currentLat = card.getAttribute('data-lat');
            const currentLng = card.getAttribute('data-lng');
            const currentPool = card.getAttribute('data-pool');

            document.getElementById('modalPlatNomor').value = plat;
            document.getElementById('modalLatitude').value = currentLat;
            document.getElementById('modalLongitude').value = currentLng;
            document.getElementById('modalLokasiPool').value = currentPool.toUpperCase();

            document.getElementById('formUpdateGps').action = `/tracking/${currentUpdatingVehicleId}/location`;
            modalUpdateGps.show();
        });
    });

    // Preset Pool selector change
    document.getElementById('selectPresetPool').addEventListener('change', function() {
        if (this.value) {
            const parts = this.value.split('|');
            const coords = parts[0].split(',');
            const poolName = parts[1];
            document.getElementById('modalLatitude').value = coords[0];
            document.getElementById('modalLongitude').value = coords[1];
            document.getElementById('modalLokasiPool').value = poolName;
        }
    });

    // Acquire Device GPS in Modal
    document.getElementById('btnAcquireDeviceGps').addEventListener('click', function() {
        const btn = this;
        if ("geolocation" in navigator) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengambil koordinat GPS...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    btn.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5 me-1"></i> Koordinat GPS Berhasil Didapat!';
                    btn.disabled = false;
                    document.getElementById('modalLatitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('modalLongitude').value = position.coords.longitude.toFixed(6);
                    document.getElementById('modalLokasiPool').value = "GPS Live Device (Akurat)";
                },
                function(error) {
                    btn.innerHTML = '<i class="bi bi-crosshair fs-5"></i> Ambil Koordinat dari GPS HP Sekarang';
                    btn.disabled = false;
                    alert("Gagal membaca GPS perangkat: " + error.message + ". Pastikan izin lokasi aktif.");
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert("Perangkat Anda tidak mendukung fitur Geolocation.");
        }
    });

    // Submit Update GPS via AJAX
    document.getElementById('formUpdateGps').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('btnSubmitUpdateGps');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
        submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan Lokasi GPS';
            submitBtn.disabled = false;

            if (data.status === 'success') {
                modalUpdateGps.hide();
                // Update local dataset & marker
                const targetCard = document.querySelector(`.vehicle-track-card[data-id="${currentUpdatingVehicleId}"]`);
                if (targetCard) {
                    targetCard.setAttribute('data-lat', data.latitude);
                    targetCard.setAttribute('data-lng', data.longitude);
                    if (data.lokasi_pool) {
                        targetCard.setAttribute('data-pool', data.lokasi_pool);
                    }
                }
                
                // Update marker on map
                if (markersMap[currentUpdatingVehicleId]) {
                    markersMap[currentUpdatingVehicleId].setLatLng([data.latitude, data.longitude]);
                    map.flyTo([data.latitude, data.longitude], 16);
                    markersMap[currentUpdatingVehicleId].openPopup();
                }

                alert(data.message);
            }
        })
        .catch(err => {
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan Lokasi GPS';
            submitBtn.disabled = false;
            console.error(err);
            alert("Terjadi kesalahan saat menyimpan lokasi GPS.");
        });
    });

    // ==========================================
    // SIMULATED ROUTE PLAYBACK
    // ==========================================
    let simulationPolyline = null;
    let simulationAnimMarker = null;
    let isSimulating = false;

    document.getElementById('btnSimulateRoute').addEventListener('click', function() {
        if (isSimulating) {
            // Stop simulation
            if (simulationPolyline) map.removeLayer(simulationPolyline);
            if (simulationAnimMarker) map.removeLayer(simulationAnimMarker);
            isSimulating = false;
            this.innerHTML = '<i class="bi bi-play-circle-fill"></i> Simulasi Rute';
            this.classList.remove('btn-danger');
            this.classList.add('btn-outline-primary');
            return;
        }

        // Jakarta Route Simulation Waypoints (Sudirman - Thamrin - Monas - Tj Priok)
        const routePoints = [
            [-6.225014, 106.809462], // Senayan
            [-6.208763, 106.822301], // Sudirman
            [-6.195301, 106.823101], // Bundaran HI
            [-6.182401, 106.824201], // Sarinah
            [-6.175392, 106.827153], // Monas
            [-6.155000, 106.835000], // Kemayoran
            [-6.136000, 106.865000], // Sunter
            [-6.126588, 106.905663], // Tj Priok Pool
        ];

        isSimulating = true;
        this.innerHTML = '<i class="bi bi-stop-circle-fill"></i> Hentikan Simulasi';
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-danger');

        // Draw animated route path
        if (simulationPolyline) map.removeLayer(simulationPolyline);
        simulationPolyline = L.polyline(routePoints, {
            color: '#2563eb',
            weight: 5,
            opacity: 0.85,
            dashArray: '8, 8',
            lineCap: 'round'
        }).addTo(map);

        map.fitBounds(simulationPolyline.getBounds().pad(0.2));

        // Animated Vehicle Marker
        const simIcon = L.divIcon({
            className: 'custom-vehicle-marker marker-ready',
            html: '<div class="marker-pin" style="background:#2563eb !important;"><i class="bi bi-truck"></i></div><div class="marker-pulse"></div>',
            iconSize: [38, 38],
            iconAnchor: [19, 38]
        });

        if (simulationAnimMarker) map.removeLayer(simulationAnimMarker);
        simulationAnimMarker = L.marker(routePoints[0], { icon: simIcon }).addTo(map);
        simulationAnimMarker.bindPopup("<b>Simulasi Pergerakan Armada</b><br>Rute: Senayan &rarr; Monas &rarr; Tj Priok<br><span class='badge bg-primary text-white mt-1'>Kecepatan: 42 km/jam</span>").openPopup();

        let step = 0;
        const totalSteps = routePoints.length;
        const simInterval = setInterval(() => {
            if (!isSimulating || step >= totalSteps) {
                clearInterval(simInterval);
                if (isSimulating) {
                    simulationAnimMarker.bindPopup("<b>Armada Telah Tiba di Pool Tujuan!</b><br>Pool Tanjung Priok.").openPopup();
                }
                return;
            }
            simulationAnimMarker.setLatLng(routePoints[step]);
            step++;
        }, 1500);
    });
});
</script>
<style>
.spin-animation {
    animation: spin 0.8s linear infinite;
}
@keyframes spin {
    100% { transform: rotate(360deg); }
}
</style>
@endpush
@endsection
