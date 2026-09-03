@extends('layouts.app')
@section('title', __('Detail Kendaraan'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h5 class="page-title mb-0 fw-bold text-dark">{{ __('Detail Kendaraan') }} - {{ $vehicle->plat_nomor }}</h5>
        <small class="text-muted">{{ $vehicle->merek }} {{ $vehicle->tipe }} ({{ $vehicle->jenis_kendaraan ?? 'Operasional' }})</small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @php
            $pesanWa = "🔔 *Pemberitahuan Servis & Pemeliharaan Armada*\n\n"
                . "Kepada Yth: " . ($vehicle->supir_utama ?: "Pengemudi/Petugas") . "\n"
                . "Kendaraan: " . $vehicle->merek . " " . $vehicle->tipe . " (" . $vehicle->plat_nomor . ")\n"
                . "Lokasi Pool: " . ($vehicle->lokasi_pool ?: "Pool Pusat") . "\n"
                . "Odometer Terkini: " . number_format($vehicle->odometer_terkini, 0, ',', '.') . " km\n"
                . "Jadwal Servis Berikutnya: " . ($nextServiceDate ? $nextServiceDate->translatedFormat('d F Y') : 'Segera') . "\n"
                . "Jatuh Tempo KIR: " . ($vehicle->jatuh_tempo_kir ? $vehicle->jatuh_tempo_kir->translatedFormat('d F Y') : '-') . "\n\n"
                . "Mohon lakukan koordinasi dan perawatan berkala di bengkel. Terima kasih.\n- Sistem FleetMaintenance";
        @endphp
        <a href="https://wa.me/?text={{ urlencode($pesanWa) }}" target="_blank" class="btn btn-sm btn-success d-flex align-items-center gap-1 text-white shadow-xs" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;" title="{{ __('Kirim Pengingat Servis via WhatsApp') }}">
            <i class="bi bi-whatsapp"></i> {{ __('Kirim Pengingat WA') }}
        </a>

        <button type="button" class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1 text-dark shadow-xs" data-bs-toggle="modal" data-bs-target="#fuelCalculatorModal" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;" title="{{ __('Hitung Efisiensi BBM (KM/L)') }}">
            <i class="bi bi-fuel-pump-fill text-warning"></i> {{ __('Kalkulator BBM') }}
        </button>

        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 shadow-xs" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;" title="{{ __('Cetak Rapor Kesehatan Kendaraan / PDF') }}">
            <i class="bi bi-printer-fill"></i> {{ __('Cetak Rapor Unit') }}
        </button>

        @if (auth()->check())
            @if (in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                <a href="{{ route('checklist.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 shadow-xs" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;">
                    <i class="bi bi-clipboard-check"></i> {{ __('Catat Checklist') }}
                </a>
                <a href="{{ route('expenses.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1 shadow-xs" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;">
                    <i class="bi bi-cash-stack"></i> {{ __('Catat Biaya') }}
                </a>
            @endif
            <a href="{{ route('complaints.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 shadow-xs" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;">
                <i class="bi bi-megaphone"></i> {{ __('Lapor Keluhan') }}
            </a>
        @endif
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">
            <!-- Left Info Panel -->
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="badge bg-dark text-white font-monospace px-3 py-2 fs-5" style="border-radius: 10px; letter-spacing: 0.8px;">
                        {{ $vehicle->plat_nomor }}
                    </span>
                    @if ($vehicle->status === 'Siap Pakai')
                        <span class="badge bg-success text-white px-3 py-2 fw-bold d-inline-flex align-items-center gap-2" style="border-radius: 10px;">
                            <span class="bg-white rounded-circle" style="width: 6px; height: 6px; display: inline-block;"></span> {{ __('Siap Pakai') }}
                        </span>
                    @elseif ($vehicle->status === 'Sedang Diservis')
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold d-inline-flex align-items-center gap-2" style="border-radius: 10px;">
                            <span class="bg-dark rounded-circle" style="width: 6px; height: 6px; display: inline-block;"></span> {{ __('Sedang Diservis') }}
                        </span>
                    @else
                        <span class="badge bg-secondary text-white px-3 py-2 fw-bold" style="border-radius: 10px;">{{ __($vehicle->status) }}</span>
                    @endif
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.72rem;">{{ __('Tipe & Merk') }}</p>
                        <h6 class="fw-bold text-dark">{{ $vehicle->merek }} {{ $vehicle->tipe }} ({{ $vehicle->jenis_kendaraan }})</h6>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.72rem;">{{ __('Tahun Pembuatan') }}</p>
                        <h6 class="fw-bold text-dark">{{ $vehicle->tahun ?? 2024 }}</h6>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.72rem;">{{ __('Lokasi Pool') }}</p>
                        <h6 class="fw-bold text-dark">{{ $vehicle->lokasi_pool ?? '-' }}</h6>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.72rem;">{{ __('Supir Utama') }}</p>
                        <h6 class="fw-bold text-dark">{{ $vehicle->supir_utama ?? '-' }}</h6>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.72rem;">{{ __('Odometer Terkini') }}</p>
                        <h6 class="fw-bold text-dark font-monospace">{{ number_format($vehicle->odometer_terkini, 0, ',', '.') }} km</h6>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.72rem;">{{ __('Jatuh Tempo KIR') }}</p>
                        <h6 class="fw-bold text-dark">{{ $vehicle->jatuh_tempo_kir?->format('d M Y') ?? '-' }}</h6>
                    </div>
                </div>
            </div>

            <!-- Right QR Code & Barcode Panel -->
            <div class="col-md-4 text-center border-start border-slate-100 d-flex flex-column align-items-center justify-content-center">
                <!-- QR Code Section -->
                <div class="mb-4">
                    <div class="d-inline-block p-2.5 bg-white border rounded-4 shadow-sm mb-2">
                        <!-- Generate QR pointing to this page -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(request()->url()) }}" alt="QR Code" style="width: 120px; height: 120px;">
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">{{ __('QR Code Unit') }}</h6>
                    <p class="text-muted mb-0" style="font-size: 0.72rem; max-width: 200px; margin: 0 auto;">{{ __('Scan menggunakan HP untuk melihat riwayat unit ini.') }}</p>
                </div>

                <!-- Barcode Section -->
                <div class="mb-3">
                    <div class="d-inline-block p-3 bg-white border rounded-4 shadow-sm mb-2">
                        <!-- Generate 1D Barcode representing Plate Number using bwip-js API -->
                        <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ urlencode(str_replace(' ', '', $vehicle->plat_nomor)) }}&scale=2&rotate=N&includetext&height=12" alt="Barcode" style="max-width: 170px; height: auto;">
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">{{ __('Barcode Plat Nomor') }}</h6>
                    <p class="text-muted mb-0" style="font-size: 0.72rem; max-width: 200px; margin: 0 auto;">{{ __('Barcode plat nomor untuk scanner fisik / inventaris.') }}</p>
                </div>

                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-3 mt-2" style="font-size: 0.78rem;">
                    <i class="bi bi-printer"></i> {{ __('Cetak Label') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #gpsMap {
            height: 400px;
            width: 100%;
        }
        .custom-vehicle-marker {
            position: relative;
        }
        .custom-vehicle-marker .marker-pin {
            width: 36px;
            height: 36px;
            border-radius: 50% 50% 50% 0;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            position: absolute;
            transform: rotate(-45deg);
            left: 50%;
            top: 50%;
            margin: -18px 0 0 -18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
            border: 2px solid #ffffff;
        }
        .custom-vehicle-marker .marker-pin i {
            transform: rotate(45deg);
            color: #ffffff;
            font-size: 1.1rem;
        }
        .custom-vehicle-marker .marker-pulse {
            background: rgba(79, 70, 229, 0.3);
            border-radius: 50%;
            height: 36px;
            width: 36px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin: -18px 0 0 -18px;
            transform: rotateX(55deg);
            z-index: -2;
            animation: marker-pulsate 1.6s ease-out infinite;
            opacity: 0;
        }
        @keyframes marker-pulsate {
            0% {
                transform: scale(0.1, 0.1);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: scale(1.6, 1.6);
                opacity: 0;
            }
        }
        .timeline-v {
            position: relative;
            padding-left: 5px;
        }
        .timeline-v-item {
            position: relative;
        }
        .timeline-v-item::before {
            content: '';
            position: absolute;
            top: 36px;
            left: 17px;
            bottom: -32px;
            width: 2px;
            background-color: var(--border-color, #e4e4e7);
            z-index: 1;
        }
        .timeline-v-item:last-child::before {
            display: none;
        }
    </style>
@endpush

{{-- Live GPS Location Map --}}
@php
    $iconClass = 'bi-car-front-fill';
    if (str_contains(strtolower($vehicle->jenis_kendaraan), 'motor')) {
        $iconClass = 'bi-motorcycle';
    } elseif (str_contains(strtolower($vehicle->jenis_kendaraan), 'boks') || str_contains(strtolower($vehicle->jenis_kendaraan), 'box') || str_contains(strtolower($vehicle->jenis_kendaraan), 'pick up') || str_contains(strtolower($vehicle->jenis_kendaraan), 'pickup')) {
        $iconClass = 'bi-truck';
    }
@endphp

<div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-geo-alt-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">{{ __('Lokasi Live GPS Kendaraan') }}</h6>
                <small class="text-muted" style="font-size: 0.78rem;">{{ __('Status lokasi terkini armada berdasarkan koordinat aktif') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tracking.index', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;">
                <i class="bi bi-geo-alt-fill"></i> {{ __('Buka di Peta Pelacakan Lengkap') }}
            </a>
            @if ($vehicle->latitude && $vehicle->longitude)
                <a href="https://www.google.com/maps/search/?api=1&query={{ $vehicle->latitude }},{{ $vehicle->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2" style="border-radius: 8px; font-size: 0.8rem; padding: 6px 12px;">
                    <i class="bi bi-map-fill"></i> {{ __('Buka Google Maps') }}
                </a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if ($vehicle->latitude && $vehicle->longitude)
            <!-- Map Container -->
            <div id="gpsMap"></div>
            
            @push('scripts')
            <!-- Leaflet Map JS -->
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
            
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var lat = {{ $vehicle->latitude }};
                    var lng = {{ $vehicle->longitude }};
                    var plat = "{{ $vehicle->plat_nomor }}";
                    var merek = "{{ $vehicle->merek }} {{ $vehicle->tipe }}";
                    var status = "{{ $vehicle->status }}";
                    var iconClass = "{{ $iconClass }}";
                    
                    // Initialize Map pointing to vehicle coords with enabled zoom/drag controls
                    var map = L.map('gpsMap', {
                        center: [lat, lng],
                        zoom: 16,
                        scrollWheelZoom: true, // Enable zooming comfortably
                        dragging: true,
                        tap: true
                    });
                    
                    // Load CartoDB Voyager premium tiles (clean, Google Maps-like styling)
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
                    }).addTo(map);
                    
                    // Custom premium vehicle marker icon based on vehicle type
                    var vehicleIcon = L.divIcon({
                        className: 'custom-vehicle-marker',
                        html: '<div class="marker-pin"><i class="bi ' + iconClass + '"></i></div><div class="marker-pulse"></div>',
                        iconSize: [36, 36],
                        iconAnchor: [18, 36],
                        popupAnchor: [0, -32]
                    });
                    
                    // Create marker with popup
                    var marker = L.marker([lat, lng], { icon: vehicleIcon }).addTo(map);
                    marker.bindPopup("<div style='font-family: sans-serif; font-size: 0.85rem;'><strong style='font-size: 0.95rem; color: #4f46e5;'>" + plat + "</strong><br>" + merek + "<br><span class='badge bg-dark mt-1 text-white'>" + status + "</span><br><a href='https://www.google.com/maps/search/?api=1&query=" + lat + "," + lng + "' target='_blank' class='btn btn-xs btn-primary text-white mt-2 d-inline-block py-1 px-2 fs-6' style='font-size: 0.72rem; border-radius: 4px; text-decoration: none;'>Navigasi ke Sini</a></div>").openPopup();
 
                    // Force size recalculation to ensure no gray areas and correct rendering bounds
                    setTimeout(function() {
                        map.invalidateSize();
                        map.setView([lat, lng], 16);
                    }, 250);
                });
            </script>
            @endpush
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-geo-alt fs-1 d-block mb-2 text-secondary opacity-50"></i>
                <span>{{ __('Data koordinat GPS belum tersedia untuk armada ini.') }}</span>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ __('Timeline Riwayat Servis & Perbaikan') }}</h6>
                        <small class="text-muted" style="font-size: 0.78rem;">{{ __('Catatan perawatan unit secara kronologis') }}</small>
                    </div>
                </div>
                @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                    <a href="{{ route('vehicle-histories.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-xs btn-primary py-1.5 px-3 d-inline-flex align-items-center gap-1" style="border-radius: 8px; font-size: 0.75rem; font-weight: 600;">
                        <i class="bi bi-plus-circle"></i> {{ __('Catat Servis') }}
                    </a>
                @endif
            </div>
            <div class="card-body p-4" style="max-height: 480px; overflow-y: auto;">
                @if ($vehicle->histories->count() > 0)
                    <div class="timeline-v">
                        @foreach ($vehicle->histories as $history)
                            <div class="timeline-v-item d-flex gap-3 mb-4 position-relative">
                                <div class="timeline-v-badge bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 50%; z-index: 2; flex-shrink: 0; box-shadow: 0 0 0 4px #ffffff; border: 2px solid #ffffff;">
                                    <i class="bi bi-tools" style="font-size: 0.85rem;"></i>
                                </div>
                                <div class="timeline-v-content bg-light p-3 w-100 border border-slate-100" style="border-radius: 12px;">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1.5">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">{{ $history->jenis_pekerjaan }}</h6>
                                            <small class="text-muted d-block mt-0.5" style="font-size: 0.75rem;">
                                                <i class="bi bi-calendar3 me-1"></i>{{ $history->tanggal->translatedFormat('d M Y') }}
                                                @if ($history->teknisi)
                                                    <span class="mx-1.5 text-secondary opacity-50">|</span>
                                                    <i class="bi bi-person me-1"></i>{{ __('Teknisi') }}: {{ $history->teknisi->name }}
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fw-bold" style="font-size: 0.78rem; border-radius: 8px;">
                                            Rp {{ number_format($history->biaya, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    @if ($history->sparepart_digunakan)
                                        <div class="mt-2 pt-2 border-top border-slate-200">
                                            <small class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ __('Sparepart / Suku Cadang') }}:</small>
                                            <span class="text-dark bg-white border border-slate-200 px-2 py-0.5 rounded d-inline-block fw-medium" style="font-size: 0.8rem; border-radius: 6px;">{{ $history->sparepart_digunakan }}</span>
                                        </div>
                                    @endif
 
                                    @if ($history->keterangan)
                                        <div class="mt-2">
                                            <small class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ __('Keterangan') }}:</small>
                                            <p class="text-secondary mb-0" style="font-size: 0.82rem; line-height: 1.45;">{{ $history->keterangan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-clock-history fs-2 d-block mb-2 text-secondary opacity-50"></i>
                        <span>{{ __('Belum ada riwayat perawatan/servis untuk unit ini.') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <div class="p-2 rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-calendar-event fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ __('Estimasi Servis') }}</h6>
                    <small class="text-muted" style="font-size: 0.78rem;">{{ __('Prediksi jadwal servis berikutnya') }}</small>
                </div>
            </div>
            <div class="card-body p-4 text-center">
                @if($vehicle->tanggal_servis_manual)
                    <h4 class="text-primary fw-extrabold mb-1" style="font-size: 1.25rem;">{{ $vehicle->tanggal_servis_manual->translatedFormat('d F Y') }}</h4>
                    <span class="badge bg-info text-white px-2.5 py-1.5 mb-2" style="font-size: 0.72rem; border-radius: 6px;">{{ __('Jadwal Manual') }}</span>
                    <p class="text-muted small mb-0">{{ __('Ditetapkan secara manual oleh Admin/Teknisi.') }}</p>
                @elseif($nextServiceDate)
                    <h4 class="text-primary fw-extrabold mb-1" style="font-size: 1.25rem;">{{ $nextServiceDate->translatedFormat('d F Y') }}</h4>
                    <p class="text-muted small mb-0">{{ __('Estimasi berdasarkan interval 3 bulan dari servis terakhir.') }}</p>
                @else
                    <p class="text-muted small mb-0">{{ __('Data servis belum tersedia untuk estimasi.') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-white fw-bold">{{ __('10 Checklist Harian Terakhir') }}</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('Tanggal') }}</th><th>{{ __('Teknisi') }}</th><th>{{ __('Odometer') }}</th><th>{{ __('Catatan') }}</th></tr></thead>
            <tbody>
                @forelse ($vehicle->checklists as $c)
                <tr>
                    <td>{{ $c->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $c->nama_teknisi }}</td>
                    <td>{{ $c->odometer }}</td>
                    <td>{{ $c->catatan_tambahan }} @if($c->ada_masalah)<span class="badge bg-danger">{{ __('Ada Masalah') }}</span>@endif</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-3">{{ __('Belum ada checklist.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white fw-bold">{{ __('10 Pengeluaran Terakhir') }}</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('Tanggal') }}</th><th>{{ __('Jenis') }}</th><th>{{ __('Jumlah') }}</th><th>{{ __('Keterangan') }}</th></tr></thead>
            <tbody>
                @forelse ($vehicle->expenses as $e)
                <tr>
                    <td>{{ $e->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $e->jenis_pengeluaran }}</td>
                    <td>Rp {{ number_format($e->jumlah_biaya, 0, ',', '.') }}</td>
                    <td>{{ $e->keterangan }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-3">{{ __('Belum ada data pengeluaran.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('vehicles.index') }}" class="btn btn-secondary mt-3">{{ __('Kembali') }}</a>

<!-- Modal Kalkulator Efisiensi BBM -->
<div class="modal fade" id="fuelCalculatorModal" tabindex="-1" aria-labelledby="fuelCalculatorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-warning text-dark py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h6 class="modal-title fw-bold mb-0" id="fuelCalculatorModalLabel">
                    <i class="bi bi-fuel-pump-fill me-1"></i> {{ __('Kalkulator Efisiensi Bahan Bakar (BBM)') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning py-2 px-3 d-flex align-items-center gap-2 mb-3 text-dark" style="border-radius: 10px; font-size: 0.82rem;">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <div>{{ __('Hitung konsumsi rata-rata bahan bakar untuk armada') }} <strong>{{ $vehicle->plat_nomor }}</strong></div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Odometer Sebelumnya (KM)') }}</label>
                        <input type="number" id="calcOdoPrev" class="form-control" placeholder="Contoh: 12000" value="{{ max(0, $vehicle->odometer_terkini - 350) }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Odometer Saat Ini (KM)') }}</label>
                        <input type="number" id="calcOdoCurr" class="form-control" placeholder="Contoh: 12350" value="{{ $vehicle->odometer_terkini }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Jumlah Liter BBM (L)') }}</label>
                        <input type="number" step="0.1" id="calcLiters" class="form-control" placeholder="Contoh: 30" value="30">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Total Biaya BBM (Rp)') }}</label>
                        <input type="number" id="calcCost" class="form-control" placeholder="Contoh: 300000" value="300000">
                    </div>
                </div>

                <!-- Hasil Kalkulasi Box -->
                <div class="p-3 bg-light rounded-4 border text-center">
                    <span class="text-muted d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Hasil Analisis Efisiensi BBM') }}</span>
                    <div class="d-flex justify-content-around align-items-center my-3">
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('Jarak Tempuh') }}</small>
                            <h5 class="fw-bold text-dark mb-0 font-monospace" id="calcResDistance">0 km</h5>
                        </div>
                        <div class="border-start border-end px-3">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('Konsumsi BBM') }}</small>
                            <h4 class="fw-bold text-primary mb-0 font-monospace" id="calcResKmL">0.0 km/L</h4>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('Biaya per KM') }}</small>
                            <h5 class="fw-bold text-dark mb-0 font-monospace" id="calcResCostKm">Rp 0 /km</h5>
                        </div>
                    </div>
                    <div id="calcBadgeStatus" class="mt-2">
                        <span class="badge bg-success px-3 py-1.5 fw-bold">{{ __('Sangat Irit') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 border-top">
                <button type="button" class="btn btn-secondary w-100 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">{{ __('Tutup') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function calculateFuel() {
        const odoPrev = parseFloat(document.getElementById('calcOdoPrev').value) || 0;
        const odoCurr = parseFloat(document.getElementById('calcOdoCurr').value) || 0;
        const liters = parseFloat(document.getElementById('calcLiters').value) || 0;
        const cost = parseFloat(document.getElementById('calcCost').value) || 0;

        const distance = Math.max(0, odoCurr - odoPrev);
        const kmL = liters > 0 ? (distance / liters) : 0;
        const costPerKm = distance > 0 ? (cost / distance) : 0;

        document.getElementById('calcResDistance').innerText = distance.toLocaleString('id-ID') + ' km';
        document.getElementById('calcResKmL').innerText = kmL.toFixed(1) + ' km/L';
        document.getElementById('calcResCostKm').innerText = 'Rp ' + Math.round(costPerKm).toLocaleString('id-ID') + ' /km';

        const badgeContainer = document.getElementById('calcBadgeStatus');
        if (kmL >= 12) {
            badgeContainer.innerHTML = '<span class="badge bg-success px-3 py-1.5 fw-bold"><i class="bi bi-patch-check-fill me-1"></i> Efisiensi Sangat Baik (Sangat Irit)</span>';
        } else if (kmL >= 8) {
            badgeContainer.innerHTML = '<span class="badge bg-warning text-dark px-3 py-1.5 fw-bold"><i class="bi bi-check-circle me-1"></i> Efisiensi Standar / Normal</span>';
        } else {
            badgeContainer.innerHTML = '<span class="badge bg-danger px-3 py-1.5 fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Konsumsi Boros (Perlu Cek Filter / Tune-up)</span>';
        }
    }

    ['calcOdoPrev', 'calcOdoCurr', 'calcLiters', 'calcCost'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', calculateFuel);
        }
    });

    calculateFuel();
});
</script>
@endpush
@endsection