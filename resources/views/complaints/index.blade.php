@extends('layouts.app')
@section('title', 'Keluhan Kendaraan')

@section('content')
<style>
    .badge-soft-success { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-soft-warning { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .badge-soft-danger { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    
    .stat-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }
    
    /* ===== VERTICAL TIMELINE ===== */
    .timeline-container {
        position: relative;
        padding-left: 28px;
        border-left: 2px solid #e2e8f0;
        margin-left: 10px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 24px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-badge {
        position: absolute;
        left: -37px;
        top: 2px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: #cbd5e1;
        border: 4px solid #fff;
        box-shadow: 0 0 0 2px #e2e8f0;
    }
    .timeline-badge.completed {
        background-color: #10b981;
        box-shadow: 0 0 0 2px #d1fae5;
    }
    .timeline-badge.active {
        background-color: #3b82f6;
        box-shadow: 0 0 0 2px #dbeafe;
    }
    .timeline-title {
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 2px;
        color: #1e293b;
    }
    .timeline-date {
        font-size: 0.75rem;
        color: #64748b;
    }

    body.dark-theme .stat-card {
        background: #111827 !important;
        border-color: #1e293b !important;
    }
    body.dark-theme .stat-card .text-dark {
        color: #f1f5f9 !important;
    }
    body.dark-theme .stat-card .text-muted {
        color: #94a3b8 !important;
    }
    body.dark-theme .icon-box.bg-danger-subtle { background-color: #7f1d1d !important; color: #f87171 !important; }
    body.dark-theme .icon-box.bg-warning-subtle { background-color: #78350f !important; color: #fbbf24 !important; }
    body.dark-theme .icon-box.bg-success-subtle { background-color: #064e3b !important; color: #34d399 !important; }

    #complaintSearch {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }
    #complaintSearch::placeholder {
        color: #64748b !important;
        opacity: 1 !important;
    }
    body.dark-theme #complaintSearch {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }
    body.dark-theme #complaintSearch::placeholder {
        color: #94a3b8 !important;
    }

    /* ===== TEKNISI CONTROL PANEL STYLING ===== */
    .teknisi-panel {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
        transition: all 0.2s ease;
    }
    body.dark-theme .teknisi-panel {
        background-color: #1e293b;
        border-color: #334155;
    }
    .teknisi-panel-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    body.dark-theme .teknisi-panel-title {
        color: #cbd5e1;
    }
    .dark-adaptive-bg {
        background-color: #ffffff;
    }
    body.dark-theme .dark-adaptive-bg {
        background-color: #0f172a !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Daftar Keluhan &amp; Masalah Kendaraan</h3>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Monitoring dan manajemen laporan masalah teknis armada dari driver.</p>
    </div>
    <a href="{{ route('complaints.create') }}" class="btn btn-primary px-4 py-2 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-megaphone-fill fs-6"></i>
        <span>Lapor Keluhan Baru</span>
    </a>
</div>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-danger-subtle text-danger">
                <i class="bi bi-exclamation-octagon-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Keluhan Baru</span>
                <h4 class="fw-bold text-dark mb-0">{{ $complaints->where('status', 'Baru')->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-warning-subtle text-warning">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sedang Diproses</span>
                <h4 class="fw-bold text-dark mb-0">{{ $complaints->where('status', 'Diproses')->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-success-subtle text-success">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Selesai Ditangani</span>
                <h4 class="fw-bold text-dark mb-0">{{ $complaints->where('status', 'Selesai')->count() }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-grid-fill text-primary fs-5"></i>
        <span class="fw-bold text-dark fs-5">Data Riwayat Laporan</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <input type="text" id="complaintSearch" class="form-control form-control-sm" placeholder="🔍 Cari berdasarkan Plat, Driver, Merk, atau Keluhan..." style="width: 320px; border-radius: 8px; font-size: 0.85rem; box-shadow: none;">
    </div>
</div>

<div class="row g-4" id="complaintsGrid">
    @forelse ($complaints as $c)
    @php
        $targetDate = $c->tanggal->copy()->addDays(2);
        $diffDays = now()->startOfDay()->diffInDays($targetDate->startOfDay(), false);
    @endphp
    <div class="col-md-4 complaint-card-col" data-search="{{ strtolower($c->vehicle->plat_nomor . ' ' . ($c->user->name ?? '') . ' ' . $c->vehicle->merek . ' ' . $c->vehicle->tipe . ' ' . $c->keluhan . ' ' . $c->tanggal->format('d/m/Y')) }}">
        <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs hover-card transition-all p-3" style="transition: all 0.25s ease; border-radius: 16px;">
            <!-- Plate & Status Header (Above Image) -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Plate Badge -->
                <span class="badge bg-dark text-white font-monospace px-3 py-2 fs-6 border border-secondary shadow-sm" style="border-radius: 10px; letter-spacing: 0.8px;">
                    {{ $c->vehicle->plat_nomor }}
                </span>
                <!-- Status Badge -->
                <div>
                    @if ($c->status === 'Baru')
                        <span class="badge bg-danger text-white px-3 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2" style="border-radius: 10px;">
                            <span class="bg-white rounded-circle" style="width: 5px; height: 5px; display: inline-block;"></span> Baru
                        </span>
                    @elseif ($c->status === 'Diproses')
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 10px;">
                            <span class="bg-dark rounded-circle" style="width: 5px; height: 5px; display: inline-block;"></span> Diproses
                        </span>
                    @else
                        <span class="badge bg-success text-white px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 10px;">
                            <span class="bg-white rounded-circle" style="width: 5px; height: 5px; display: inline-block;"></span> Selesai
                        </span>
                    @endif
                </div>
            </div>

            <!-- Vehicle Image -->
            <div class="position-relative vehicle-image-wrapper rounded-3" style="height: 230px; overflow: hidden; background: #f8fafc;">
                <img src="{{ $c->vehicle->foto_url }}" alt="{{ $c->vehicle->plat_nomor }}" class="w-100 h-100 object-fit-cover transition-img" style="transition: transform 0.3s ease;">
            </div>

            <!-- Card Body -->
            <div class="card-body px-0 pt-3 pb-0">
                <!-- SLA (Target Waktu) Badge -->
                <div class="mb-2">
                    @if ($c->status === 'Selesai')
                        <span class="badge bg-success-subtle text-success border border-success-subtle py-1.5 px-2.5 w-100 text-start" style="font-size:0.75rem;">
                            <i class="bi bi-clock-fill me-1"></i> SLA: Selesai Tepat Waktu
                        </span>
                    @else
                        @if ($diffDays > 0)
                            <span class="badge bg-info-subtle text-info border border-info-subtle py-1.5 px-2.5 w-100 text-start" style="font-size:0.75rem;">
                                <i class="bi bi-clock-fill me-1"></i> Target selesai: 2 Hari (Sisa {{ $diffDays }} Hari)
                            </span>
                        @elseif ($diffDays === 0)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle py-1.5 px-2.5 w-100 text-start" style="font-size:0.75rem;">
                                <i class="bi bi-clock-fill me-1"></i> Target selesai hari ini!
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1.5 px-2.5 w-100 text-start" style="font-size:0.75rem;">
                                <i class="bi bi-clock-fill me-1"></i> Terlambat {{ abs($diffDays) }} Hari
                            </span>
                        @endif
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 mt-1">
                    <span class="tgl-cell text-muted fw-bold font-monospace" style="font-size: 0.82rem;">
                        <i class="bi bi-calendar3 me-1"></i> {{ $c->tanggal->format('d M Y') }}
                    </span>
                    <small class="text-muted" style="font-size: 0.75rem;">{{ $c->created_at ? $c->created_at->diffForHumans() : '-' }}</small>
                </div>

                <!-- Driver Reporter Avatar & Name -->
                <div class="d-flex align-items-center gap-2.5 mb-3">
                    <div class="rounded-circle overflow-hidden border shadow-xs d-flex align-items-center justify-content-center" style="width:30px; height:30px; background-color: #f1f5f9;">
                        @if($c->user && $c->user->kelas)
                            <img src="{{ asset($c->user->kelas) }}" alt="{{ $c->user->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <span class="fw-bold text-secondary" style="font-size:0.72rem;">{{ strtoupper(substr($c->user->name ?? 'U', 0, 2)) }}</span>
                        @endif
                    </div>
                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $c->user->name ?? 'Unknown' }}</span>
                </div>

                <!-- Complaint Detail -->
                <div class="p-3 rounded-3 bg-light text-dark border-0 mb-3" style="font-size: 0.85rem; line-height: 1.4; min-height: 80px;">
                    {{ $c->keluhan }}
                </div>

                <!-- Progress Perbaikan -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Progres Perbaikan</span>
                        <span class="fw-bold text-dark" style="font-size: 0.8rem;">{{ $c->progress_perbaikan }}%</span>
                    </div>
                    <div class="progress" style="height: 6px; border-radius: 3px; background-color: #e2e8f0;">
                        <div class="progress-bar {{ $c->status === 'Selesai' ? 'bg-success' : ($c->status === 'Diproses' ? 'bg-primary' : 'bg-danger') }}" role="progressbar" style="width: {{ $c->progress_perbaikan }}%" aria-valuenow="{{ $c->progress_perbaikan }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Catatan Penyelesaian (jika ada) -->
                @if ($c->catatan_penyelesaian)
                <div class="p-3 rounded-3 bg-success-subtle text-success border border-success-subtle mb-3" style="font-size: 0.82rem; line-height: 1.4;">
                    <strong class="d-block mb-1"><i class="bi bi-patch-check-fill me-1"></i> Solusi Perbaikan:</strong>
                    {{ $c->catatan_penyelesaian }}
                </div>
                @endif

                <!-- Detail & Timeline Modal Trigger -->
                <button type="button" class="btn btn-sm btn-outline-info w-100 mb-3 btn-detail-complaint d-flex align-items-center justify-content-center gap-1" style="border-radius: 8px; font-size: 0.78rem;"
                        data-plat="{{ $c->vehicle->plat_nomor }}"
                        data-merek="{{ $c->vehicle->merek }} {{ $c->vehicle->tipe }}"
                        data-keluhan="{{ $c->keluhan }}"
                        data-status="{{ $c->status }}"
                        data-progress="{{ $c->progress_perbaikan }}"
                        data-foto-url="{{ $c->foto_kerusakan ? asset($c->foto_kerusakan) : '' }}"
                        data-video-url="{{ $c->video_kerusakan ? asset($c->video_kerusakan) : '' }}"
                        data-melapor-at="{{ $c->created_at ? $c->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}"
                        data-diterima-at="{{ $c->diterima_at ? $c->diterima_at->translatedFormat('d M Y, H:i') . ' WIB' : '' }}"
                        data-diperbaiki-at="{{ $c->diperbaiki_at ? $c->diperbaiki_at->translatedFormat('d M Y, H:i') . ' WIB' : '' }}"
                        data-selesai-at="{{ $c->selesai_at ? $c->selesai_at->translatedFormat('d M Y, H:i') . ' WIB' : '' }}"
                        data-solusi="{{ $c->catatan_penyelesaian ?? '' }}">
                    <i class="bi bi-clock-history"></i> Detail &amp; Timeline
                </button>

                <!-- Action Form for Technician & Super Admin -->
                @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'teknisi']))
                <div class="teknisi-panel">
                    <h6 class="teknisi-panel-title">
                        <i class="bi bi-tools text-primary"></i> Teknisi Control Panel
                    </h6>
                    <form action="{{ route('complaints.updateStatus', $c) }}" method="POST" class="d-flex flex-column gap-2.5">
                        @csrf @method('PUT')
                        
                        <!-- Status Select & Update Button -->
                        <div class="row g-2">
                            <div class="col-8">
                                <select name="status" class="form-select form-select-sm shadow-none border-slate-200" style="border-radius: 8px; font-size: 0.78rem; height: 34px;"
                                        onchange="toggleComplaintFields(this, '{{ $c->id }}')">
                                    <option value="Baru" @selected($c->status === 'Baru')>Baru</option>
                                    <option value="Diproses" @selected($c->status === 'Diproses')>Diproses (Sedang Diperbaiki)</option>
                                    <option value="Selesai" @selected($c->status === 'Selesai')>Selesai</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-sm btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm" style="border-radius: 8px; font-size: 0.78rem; height: 34px; font-weight: 600;">
                                    <i class="bi bi-arrow-repeat"></i> Update
                                </button>
                            </div>
                        </div>

                        <!-- Progress Slider -->
                        <div class="bg-white dark-adaptive-bg p-2.5 rounded-3 border border-slate-100 shadow-sm" id="progressContainer_{{ $c->id }}" style="display: {{ $c->status === 'Diproses' ? 'block' : 'none' }} !important;">
                            <label class="form-label text-secondary fw-semibold mb-1 d-flex justify-content-between" style="font-size: 0.72rem;">
                                <span>Set Progres Perbaikan:</span>
                                <span class="text-primary font-monospace fw-bold"><span id="progressVal_{{ $c->id }}">{{ $c->progress_perbaikan }}</span>%</span>
                            </label>
                            <input type="range" name="progress_perbaikan" class="form-range" min="0" max="100" step="5" value="{{ $c->progress_perbaikan }}" oninput="document.getElementById('progressVal_{{ $c->id }}').innerText = this.value">
                        </div>

                        <!-- Collapse Button for Timeline Dates -->
                        <button class="btn btn-sm btn-link text-decoration-none text-start p-0 fw-semibold text-secondary d-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDates_{{ $c->id }}" aria-expanded="false" style="font-size: 0.72rem; outline: none; box-shadow: none;">
                            <i class="bi bi-calendar-date text-primary"></i> Atur Manual Tanggal &amp; Waktu
                        </button>
                        
                        <!-- Collapsible Date Inputs -->
                        <div class="collapse" id="collapseDates_{{ $c->id }}">
                            <div class="bg-white dark-adaptive-bg p-2.5 rounded-3 border border-slate-100 d-flex flex-column gap-2 shadow-sm" style="font-size: 0.75rem;">
                                <div>
                                    <label class="form-label text-secondary mb-1" style="font-size: 0.68rem; font-weight: 600;">1. Tanggal Diterima Admin/Teknisi</label>
                                    <input type="datetime-local" name="diterima_at" class="form-control form-control-sm border-slate-200" value="{{ $c->diterima_at ? $c->diterima_at->format('Y-m-d\TH:i') : '' }}" style="font-size: 0.75rem; border-radius: 6px;">
                                </div>
                                <div>
                                    <label class="form-label text-secondary mb-1" style="font-size: 0.68rem; font-weight: 600;">2. Tanggal Mulai Perbaikan</label>
                                    <input type="datetime-local" name="diperbaiki_at" class="form-control form-control-sm border-slate-200" value="{{ $c->diperbaiki_at ? $c->diperbaiki_at->format('Y-m-d\TH:i') : '' }}" style="font-size: 0.75rem; border-radius: 6px;">
                                </div>
                                <div id="dateSelesai_{{ $c->id }}" style="display: {{ $c->status === 'Selesai' ? 'block' : 'none' }} !important;">
                                    <label class="form-label text-secondary mb-1" style="font-size: 0.68rem; font-weight: 600;">3. Tanggal Selesai Perbaikan</label>
                                    <input type="datetime-local" name="selesai_at" class="form-control form-control-sm border-slate-200" value="{{ $c->selesai_at ? $c->selesai_at->format('Y-m-d\TH:i') : '' }}" style="font-size: 0.75rem; border-radius: 6px;">
                                </div>
                            </div>
                        </div>

                        <!-- Biaya Perbaikan Bengkel -->
                        <div id="biayaContainer_{{ $c->id }}" style="display: {{ $c->status === 'Selesai' ? 'block' : 'none' }} !important;">
                            <label class="form-label text-secondary mb-1 fw-semibold" style="font-size: 0.72rem;">Biaya Bengkel (Rp):</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text border-slate-200 text-secondary bg-slate-50" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; font-size: 0.75rem; font-weight: 600;">Rp</span>
                                <input type="number" name="jumlah_biaya" class="form-control form-control-sm border-slate-200 shadow-none" placeholder="Contoh: 150000" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; font-size: 0.78rem;">
                            </div>
                        </div>

                        <!-- Catatan Solusi / Penyelesaian -->
                        <div>
                            <label class="form-label text-secondary mb-1 fw-semibold" style="font-size: 0.72rem;">Catatan Solusi / Tindakan:</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text border-slate-200 text-secondary bg-slate-50" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;"><i class="bi bi-chat-left-dots"></i></span>
                                <input type="text" name="catatan_penyelesaian" class="form-control form-control-sm border-slate-200 shadow-none" placeholder="Tulis tindakan perbaikan..." value="{{ $c->catatan_penyelesaian }}" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; font-size: 0.78rem;">
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-shield-check text-success fs-1 d-block mb-2"></i>
        <span class="fw-bold text-dark d-block">Tidak Ada Keluhan Kendaraan</span>
        <small class="text-muted">Semua armada dalam kondisi normal dan baik-baik saja.</small>
    </div>
    @endforelse
</div>

<!-- Modal Detail & Timeline Keluhan -->
<div class="modal fade" id="detailComplaintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary me-2"></i> Lacak Status Keluhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Info Armada -->
                <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-4">
                    <div>
                        <span class="badge bg-dark text-white font-monospace px-2 py-1 mb-1" id="modalPlat" style="letter-spacing: 0.5px; border-radius: 6px;"></span>
                        <h6 class="fw-bold mb-0 text-dark" id="modalMerek"></h6>
                    </div>
                    <div id="modalStatusBadge"></div>
                </div>

                <!-- Detail Deskripsi Keluhan -->
                <div class="mb-4">
                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Keluhan Pengemudi</small>
                    <p class="p-3 bg-light border-0 text-dark rounded-3 font-monospace mb-0" id="modalKeluhan" style="font-size: 0.85rem; line-height: 1.4;"></p>
                </div>

                <!-- Progress Perbaikan -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Progres Perbaikan</small>
                        <span class="fw-bold text-dark" id="modalProgressVal" style="font-size: 0.85rem;">0%</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #e2e8f0;">
                        <div class="progress-bar bg-success" id="modalProgressBar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Foto & Video Media -->
                <div class="mb-4 d-none" id="modalMediaSection">
                    <small class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">Bukti Foto &amp; Video Kerusakan</small>
                    <div class="row g-2">
                        <div class="col-6 d-none" id="modalFotoContainer">
                            <div class="border rounded-3 p-1 text-center bg-light">
                                <a id="modalFotoLink" href="" target="_blank">
                                    <img id="modalFotoImg" src="" alt="Foto Kerusakan" class="img-fluid rounded-2 object-fit-cover" style="height: 110px; width: 100%;">
                                </a>
                                <small class="text-muted mt-1 d-block" style="font-size: 0.68rem;">Lihat Foto</small>
                            </div>
                        </div>
                        <div class="col-6 d-none" id="modalVideoContainer">
                            <div class="border rounded-3 p-1 bg-light">
                                <video id="modalVideoPlayer" controls class="w-100 rounded-2 object-fit-cover" style="height: 110px;"></video>
                                <small class="text-muted mt-1 d-block text-center" style="font-size: 0.68rem;">Putar Video/Suara</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Perbaikan -->
                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;"><i class="bi bi-clock"></i> Riwayat Proses Perbaikan:</h6>
                <div class="timeline-container mb-4">
                    <!-- Driver Melapor -->
                    <div class="timeline-item">
                        <div class="timeline-badge completed"></div>
                        <div class="timeline-title">Laporan Dibuat (Driver)</div>
                        <div class="timeline-date" id="timeMelapor"></div>
                    </div>
                    <!-- Diterima Admin -->
                    <div class="timeline-item">
                        <div class="timeline-badge" id="badgeDiterima"></div>
                        <div class="timeline-title">Diterima &amp; Diproses Admin</div>
                        <div class="timeline-date" id="timeDiterima">—</div>
                    </div>
                    <!-- Sedang Diperbaiki -->
                    <div class="timeline-item">
                        <div class="timeline-badge" id="badgeDiperbaiki"></div>
                        <div class="timeline-title">Mulai Proses Perbaikan Teknisi</div>
                        <div class="timeline-date" id="timeDiperbaiki">—</div>
                    </div>
                    <!-- Selesai -->
                    <div class="timeline-item">
                        <div class="timeline-badge" id="badgeSelesai"></div>
                        <div class="timeline-title">Selesai Diperbaiki &amp; Aman</div>
                        <div class="timeline-date" id="timeSelesai">—</div>
                    </div>
                </div>

                <!-- Catatan Solusi -->
                <div class="p-3 bg-success-subtle text-success border border-success-subtle rounded-4 d-none" id="modalSolusiContainer">
                    <small class="fw-bold d-block mb-1 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;"><i class="bi bi-patch-check-fill me-1"></i> Solusi Perbaikan</small>
                    <p class="mb-0 font-monospace" id="modalSolusiText" style="font-size: 0.85rem;"></p>
                </div>
            </div>
            <div class="modal-footer border-top p-2 bg-light">
                <button type="button" class="btn btn-secondary w-100 fw-bold py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button (FAB) to Report Complaint -->
<div class="fab-container">
    <a href="{{ route('complaints.create') }}" class="fab-btn" title="Lapor Keluhan Baru">
        <i class="bi bi-megaphone-fill" style="font-size: 1.25rem;"></i>
    </a>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    function toggleComplaintFields(select, complaintId) {
        var val = select.value;
        var prog = document.getElementById('progressContainer_' + complaintId);
        var dateSelesai = document.getElementById('dateSelesai_' + complaintId);
        var biaya = document.getElementById('biayaContainer_' + complaintId);

        if (val === 'Diproses') {
            if (prog) prog.style.setProperty('display', 'block', 'important');
            if (dateSelesai) dateSelesai.style.setProperty('display', 'none', 'important');
            if (biaya) biaya.style.setProperty('display', 'none', 'important');
        } else if (val === 'Selesai') {
            if (prog) prog.style.setProperty('display', 'none', 'important');
            if (dateSelesai) dateSelesai.style.setProperty('display', 'block', 'important');
            if (biaya) biaya.style.setProperty('display', 'block', 'important');
        } else {
            if (prog) prog.style.setProperty('display', 'none', 'important');
            if (dateSelesai) dateSelesai.style.setProperty('display', 'none', 'important');
            if (biaya) biaya.style.setProperty('display', 'none', 'important');
        }
    }

    $(document).ready(function () {
        // Search text handler with Loading Skeleton
        var complaintSearchTimeout;
        $('#complaintSearch').on('keyup', function () {
            clearTimeout(complaintSearchTimeout);
            var searchVal = this.value.toLowerCase().trim();

            // Render skeleton loader
            var skeletonHtml = '';
            for (var i = 0; i < 3; i++) {
                skeletonHtml += `
                    <div class="col-md-4 complaint-skeleton-col">
                        <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs" style="border-radius: 16px;">
                            <div class="skeleton" style="height: 160px; width: 100%;"></div>
                            <div class="card-body p-4">
                                <div class="skeleton mb-2" style="height: 15px; width: 50%;"></div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="skeleton" style="height: 30px; width: 30px; border-radius: 50%;"></div>
                                    <div class="skeleton" style="height: 15px; width: 35%;"></div>
                                </div>
                                <div class="skeleton mb-3" style="height: 60px; width: 100%;"></div>
                                <div class="skeleton mb-2" style="height: 15px; width: 70%;"></div>
                                <div class="skeleton" style="height: 6px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            $('.complaint-skeleton-col').remove();
            if (searchVal.length > 0) {
                $('.complaint-card-col').hide();
                $('#complaintsGrid').prepend(skeletonHtml);
            }

            complaintSearchTimeout = setTimeout(function () {
                $('.complaint-skeleton-col').remove();
                $('.complaint-card-col').each(function () {
                    var searchData = $(this).data('search');
                    if (searchData.indexOf(searchVal) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }, 250);
        });

        // Detail & Timeline modal population handler
        $('.btn-detail-complaint').on('click', function () {
            var plat = $(this).data('plat');
            var merek = $(this).data('merek');
            var keluhan = $(this).data('keluhan');
            var status = $(this).data('status');
            var progress = $(this).data('progress');
            var fotoUrl = $(this).data('foto-url');
            var videoUrl = $(this).data('video-url');
            var melaporAt = $(this).data('melapor-at');
            var diterimaAt = $(this).data('diterima-at');
            var diperbaikiAt = $(this).data('diperbaiki-at');
            var selesaiAt = $(this).data('selesai-at');
            var solusi = $(this).data('solusi');

            // Set basic info
            $('#modalPlat').text(plat);
            $('#modalMerek').text(merek);
            $('#modalKeluhan').text(keluhan);
            $('#modalProgressVal').text(progress + '%');
            $('#modalProgressBar').css('width', progress + '%');

            // Status Badge
            var badgeHtml = '';
            if (status === 'Baru') {
                badgeHtml = '<span class="badge bg-danger text-white px-3 py-2 fw-bold" style="border-radius:10px;">Baru</span>';
            } else if (status === 'Diproses') {
                badgeHtml = '<span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="border-radius:10px;">Diproses</span>';
            } else {
                badgeHtml = '<span class="badge bg-success text-white px-3 py-2 fw-bold" style="border-radius:10px;">Selesai</span>';
            }
            $('#modalStatusBadge').html(badgeHtml);

            // Media uploads section
            if (fotoUrl || videoUrl) {
                $('#modalMediaSection').removeClass('d-none');
                if (fotoUrl) {
                    $('#modalFotoContainer').removeClass('d-none');
                    $('#modalFotoLink').attr('href', fotoUrl);
                    $('#modalFotoImg').attr('src', fotoUrl);
                } else {
                    $('#modalFotoContainer').addClass('d-none');
                }

                if (videoUrl) {
                    $('#modalVideoContainer').removeClass('d-none');
                    $('#modalVideoPlayer').attr('src', videoUrl);
                } else {
                    $('#modalVideoContainer').addClass('d-none');
                    $('#modalVideoPlayer').attr('src', '');
                }
            } else {
                $('#modalMediaSection').addClass('d-none');
                $('#modalVideoPlayer').attr('src', '');
            }

            // Timeline states
            $('#timeMelapor').text(melaporAt);

            // Reset timeline badges to gray
            $('#badgeDiterima, #badgeDiperbaiki, #badgeSelesai').removeClass('completed active');

            if (diterimaAt) {
                $('#timeDiterima').text(diterimaAt);
                $('#badgeDiterima').addClass('completed');
            } else {
                $('#timeDiterima').text('Menunggu persetujuan admin...');
            }

            if (diperbaikiAt) {
                $('#timeDiperbaiki').text(diperbaikiAt);
                $('#badgeDiperbaiki').addClass('completed');
            } else {
                $('#timeDiperbaiki').text('—');
            }

            if (selesaiAt) {
                $('#timeSelesai').text(selesaiAt);
                $('#badgeSelesai').addClass('completed');
            } else {
                $('#timeSelesai').text('—');
                if (status === 'Diproses') {
                    $('#badgeDiperbaiki').addClass('active');
                } else if (status === 'Baru' && diterimaAt) {
                    $('#badgeDiterima').addClass('active');
                }
            }

            // Solusi Perbaikan
            if (solusi) {
                $('#modalSolusiContainer').removeClass('d-none');
                $('#modalSolusiText').text(solusi);
            } else {
                $('#modalSolusiContainer').addClass('d-none');
            }

            // Open Modal
            var modal = new bootstrap.Modal(document.getElementById('detailComplaintModal'));
            modal.show();

            // Clear video player source when modal is closed to stop audio/playback
            $('#detailComplaintModal').on('hidden.bs.modal', function () {
                $('#modalVideoPlayer').attr('src', '');
            });
        });
    });
</script>
@endsection