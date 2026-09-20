@extends('layouts.app')
@section('title', 'Pusat Notifikasi WhatsApp Gateway')

@section('content')
<style>
    /* =========================================================
       CYBER-FLEET WHATSAPP GATEWAY - HIGH-TECH DESIGN SYSTEM
       ========================================================= */
    :root {
        --wa-brand: #25D366;
        --wa-brand-hover: #1ebd5a;
        --wa-brand-dark: #128C7E;
        --wa-brand-deep: #075E54;
        --wa-chat-bg: #efeae2;
        --wa-bubble-out: #d9fdd3;
        --wa-bubble-dark-out: #005c4b;
    }

    /* Ambient Clean Cards */
    .aesthetic-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease;
        position: relative;
    }
    .aesthetic-card:hover {
        box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.08);
    }
    body.dark-theme .aesthetic-card {
        background: #111827;
        border-color: #1e293b;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.4);
    }

    /* Stat Cards */
    .stat-card-tech {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.4rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card-tech:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -4px rgba(0, 0, 0, 0.06);
    }
    .stat-card-tech::after {
        content: '';
        position: absolute;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        top: -30px;
        right: -30px;
        opacity: 0.12;
        pointer-events: none;
    }
    .stat-card-tech.stat-success::after { background: radial-gradient(circle, #22c55e, transparent); }
    .stat-card-tech.stat-danger::after { background: radial-gradient(circle, #ef4444, transparent); }
    .stat-card-tech.stat-warning::after { background: radial-gradient(circle, #f59e0b, transparent); }
    .stat-card-tech.stat-info::after { background: radial-gradient(circle, #06b6d4, transparent); }

    body.dark-theme .stat-card-tech {
        background: #111827;
        border-color: #1e293b;
    }

    .icon-box-tech {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    /* Pulsing Status Dot */
    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }
    .pulse-dot::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0; left: 0;
        border-radius: 50%;
        animation: pulse-ring 1.8s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1.6); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    .pulse-dot.pulse-success { background: #22c55e; }
    .pulse-dot.pulse-success::after { background: rgba(34, 197, 94, 0.4); }

    /* Soft Glow Badges */
    .badge-soft-success { background: rgba(34, 197, 94, 0.12); color: #16a34a; border: 1px solid rgba(34, 197, 94, 0.25); }
    .badge-soft-danger { background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); }
    .badge-soft-warning { background: rgba(245, 158, 11, 0.12); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.25); }
    .badge-soft-info { background: rgba(6, 182, 212, 0.12); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.25); }
    .badge-soft-primary { background: rgba(59, 130, 246, 0.12); color: #2563eb; border: 1px solid rgba(59, 130, 246, 0.25); }

    body.dark-theme .badge-soft-success { color: #4ade80; border-color: rgba(74, 222, 128, 0.3); }
    body.dark-theme .badge-soft-danger { color: #f87171; border-color: rgba(248, 113, 113, 0.3); }
    body.dark-theme .badge-soft-warning { color: #fbbf24; border-color: rgba(251, 191, 36, 0.3); }
    body.dark-theme .badge-soft-info { color: #38bdf8; border-color: rgba(56, 189, 248, 0.3); }
    body.dark-theme .badge-soft-primary { color: #60a5fa; border-color: rgba(96, 165, 250, 0.3); }

    /* Metallic License Plate Badge */
    .plate-badge-tech {
        background: #0f172a;
        color: #ffffff;
        font-family: 'Inter', monospace;
        font-weight: 700;
        font-size: 0.74rem;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #334155;
        letter-spacing: 0.8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .plate-badge-tech i { color: #f59e0b; font-size: 0.72rem; }

    /* Template Filter Pills */
    .tmpl-filter-pill {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        user-select: none;
    }
    .tmpl-filter-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .tmpl-filter-pill.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
    }
    body.dark-theme .tmpl-filter-pill {
        background: #1e293b;
        border-color: #334155;
        color: #94a3b8;
    }
    body.dark-theme .tmpl-filter-pill:hover {
        background: #334155;
        color: #ffffff;
    }
    body.dark-theme .tmpl-filter-pill.active {
        background: #3b82f6;
        color: #ffffff;
        border-color: #3b82f6;
    }

    /* Realistic Smartphone Mockup */
    .phone-mockup-wrapper {
        background: #0b141a;
        border-radius: 36px;
        padding: 12px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25), inset 0 0 0 2px #334155;
        max-width: 380px;
        margin: 0 auto;
        position: relative;
    }
    .phone-notch {
        width: 100px;
        height: 16px;
        background: #0b141a;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        position: absolute;
        top: 12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .phone-notch .camera-lens {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #1e293b;
    }
    .phone-screen {
        background: var(--wa-chat-bg);
        border-radius: 26px;
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: 480px;
        background-image: radial-gradient(rgba(0,0,0,0.05) 1px, transparent 0);
        background-size: 16px 16px;
    }
    body.dark-theme .phone-screen {
        background-color: #0b141a;
        background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 0);
    }
    .phone-header {
        background: var(--wa-brand-deep);
        color: #ffffff;
        padding: 14px 14px 12px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 4px rgba(0,0,0,0.12);
        z-index: 5;
    }
    .phone-chat-body {
        padding: 14px;
        flex-grow: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .chat-bubble-wa {
        background: var(--wa-bubble-out);
        color: #111827;
        border-radius: 12px 12px 2px 12px;
        padding: 10px 14px;
        max-width: 90%;
        align-self: flex-end;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.12);
        font-size: 0.83rem;
        line-height: 1.5;
        word-break: break-word;
        white-space: pre-wrap;
    }
    body.dark-theme .chat-bubble-wa {
        background: var(--wa-bubble-dark-out);
        color: #e9edef;
    }
    .chat-time-meta {
        font-size: 0.65rem;
        color: #667781;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 3px;
        margin-top: 4px;
    }
    body.dark-theme .chat-time-meta { color: #8696a0; }
    .chat-time-meta i { color: #53bdeb; font-size: 0.75rem; }

    .security-notice-badge {
        background: #ffeecd;
        color: #54656f;
        font-size: 0.68rem;
        padding: 6px 10px;
        border-radius: 6px;
        text-align: center;
        margin: 4px auto 6px auto;
        max-width: 92%;
        line-height: 1.35;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    body.dark-theme .security-notice-badge {
        background: #182229;
        color: #ffd279;
    }

    /* Formatting Toolbar */
    .format-btn {
        padding: 3px 9px;
        font-size: 0.74rem;
        font-weight: 600;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        transition: all 0.15s ease;
    }
    .format-btn:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }
    body.dark-theme .format-btn {
        background: #1e293b;
        border-color: #334155;
        color: #94a3b8;
    }

    /* Custom Navigation Tabs */
    .nav-tabs-clean {
        border-bottom: 1px solid #e2e8f0;
        gap: 8px;
    }
    body.dark-theme .nav-tabs-clean { border-color: #1e293b; }
    .nav-tabs-clean .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.86rem;
        padding: 12px 18px;
        border-radius: 8px 8px 0 0;
        position: relative;
        background: transparent;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .nav-tabs-clean .nav-link:hover {
        color: #0f172a;
    }
    body.dark-theme .nav-tabs-clean .nav-link:hover { color: #f1f5f9; }
    .nav-tabs-clean .nav-link.active {
        color: #16a34a;
        background: #ffffff;
        border-bottom: 2px solid #16a34a;
    }
    body.dark-theme .nav-tabs-clean .nav-link.active {
        background: #111827;
        color: #4ade80;
        border-bottom-color: #4ade80;
    }

    /* Contact Card Miniature */
    .contact-card-mini {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }
    body.dark-theme .contact-card-mini {
        background: #1e293b;
        border-color: #334155;
    }

    /* Primary Dispatch Glow Button */
    .btn-dispatch-wa {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35);
        transition: all 0.2s ease;
    }
    .btn-dispatch-wa:hover {
        background: linear-gradient(135deg, #1ebd5a 0%, #0d6e63 100%);
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45);
        color: #ffffff;
        transform: translateY(-1px);
    }
    .btn-dispatch-wa:active {
        transform: translateY(0);
    }
</style>

{{-- =========================================================
     HEADER SECTION & TELEMETRY STATUS
     ========================================================= --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <div class="d-flex align-items-center gap-2.5 mb-1">
            <div class="p-2 bg-success text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-whatsapp fs-5"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <span>{{ __('Pusat Notifikasi WhatsApp Gateway') }}</span>
                </h3>
            </div>
            <span class="badge badge-soft-success px-2.5 py-1 rounded-pill d-inline-flex align-items-center gap-1.5 ms-2" style="font-size: 0.75rem;">
                <span class="pulse-dot pulse-success"></span>
                <span>Ervelia REST Gateway Online</span>
            </span>
        </div>
        <p class="text-muted mb-0" style="font-size: 0.88rem;">
            {{ __('Otomasi notifikasi kerusakan armada, pengingat servis terintegrasi, dan telemetri API real-time.') }}
        </p>
    </div>

    {{-- Header Quick Utilities --}}
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="https://web.whatsapp.com" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 8px; font-weight: 500; padding: 7px 14px;">
            <i class="bi bi-box-arrow-up-right"></i>
            <span>{{ __('Buka WhatsApp Web') }}</span>
        </a>
        <button type="button" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#templateGuideModal" style="border-radius: 8px; font-weight: 500; padding: 7px 14px;">
            <i class="bi bi-collection-play text-primary"></i>
            <span>{{ __('Katalog Template (7)') }}</span>
        </button>
    </div>
</div>

{{-- Flash Alerts --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2.5 border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: rgba(34, 197, 94, 0.12); color: #15803d; border-left: 4px solid #22c55e !important;">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div><strong>Sukses:</strong> {{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: rgba(239, 68, 68, 0.12); color: #b91c1c; border-left: 4px solid #ef4444 !important;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2.5">
                <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                <div><strong>Kendala Pengiriman:</strong> {{ session('error') }}</div>
            </div>
            @if(session('direct_wa_url'))
                <a href="{{ session('direct_wa_url') }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1.5 shadow-sm text-nowrap ms-auto" style="border-radius: 8px; font-weight: 600; font-size: 0.82rem; background: #25D366; border-color: #25D366;">
                    <i class="bi bi-whatsapp fs-6"></i>
                    <span>Kirim via WhatsApp Web (wa.me)</span>
                </a>
            @endif
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; border-left: 4px solid #ef4444 !important;">
        <div class="d-flex align-items-center gap-2 mb-1 fw-bold text-danger">
            <i class="bi bi-x-circle-fill fs-5"></i>
            <span>Terdapat kesalahan validasi input:</span>
        </div>
        <ul class="mb-0 ps-3 text-danger small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- =========================================================
     SUMMARY METRIC & TELEMETRY CARDS
     ========================================================= --}}
@php
    $successCount = $logs->whereIn('status', ['sent', 'success'])->count();
    $failedCount = $logs->where('status', 'failed')->count();
    $pendingCount = $logs->where('status', 'pending')->count();
    $totalCount = $logs->total();
    $deliveryRate = $totalCount > 0 ? round(($successCount / $totalCount) * 100, 1) : 100;
@endphp

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card-tech stat-success">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Pesan Terkirim') }}</span>
                <span class="badge badge-soft-success px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                    <i class="bi bi-arrow-up-right me-1"></i>{{ $deliveryRate }}% Rate
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box-tech bg-success text-white shadow-sm">
                    <i class="bi bi-check2-all"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0 font-monospace">{{ $successCount }}</h3>
                    <small class="text-muted" style="font-size: 0.76rem;">Pesan sukses via API</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card-tech stat-danger">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Gagal Terkirim') }}</span>
                <span class="badge badge-soft-danger px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                    Auto Resend Ready
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box-tech bg-danger text-white shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0 font-monospace">{{ $failedCount }}</h3>
                    <small class="text-muted" style="font-size: 0.76rem;">Pesan gagal / timeout</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card-tech stat-warning">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Antrean Gateway') }}</span>
                <span class="badge badge-soft-warning px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                    Queue Status
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box-tech bg-warning text-dark shadow-sm">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0 font-monospace">{{ $pendingCount }}</h3>
                    <small class="text-muted" style="font-size: 0.76rem;">Dalam proses antrean</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card-tech stat-info">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Total Log Aktivitas') }}</span>
                <span class="badge badge-soft-info px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                    REST API
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box-tech bg-info text-white shadow-sm">
                    <i class="bi bi-terminal-split"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0 font-monospace">{{ $totalCount }}</h3>
                    <small class="text-muted" style="font-size: 0.76rem;">Seluruh log tercatat</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     MAIN CONSOLE: 2-COLUMN MODERN WORKSPACE
     ========================================================= --}}
<div class="row g-4 mb-4">
    
    {{-- =====================================================
         KOLOM KIRI: COMPOSER & DISPATCHER KILAT
         ===================================================== --}}
    <div class="col-lg-5">
        <div class="aesthetic-card h-100">
            {{-- Header Card Composer --}}
            <div class="p-3.5 px-4 border-bottom d-flex align-items-center justify-content-between bg-light bg-opacity-40">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="p-2 bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-send-check-fill fs-6"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ __('Composer Pesan & Dispatch') }}</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Kirim teks manual atau template otomatis</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-success py-1 px-2.5 d-inline-flex align-items-center gap-1.5" id="btnUseAdminNumber" title="Isi otomatis dengan nomor Admin">
                    <i class="bi bi-shield-check"></i>
                    <span style="font-size: 0.75rem; font-weight: 500;">{{ __('Nomor Admin') }}</span>
                </button>
            </div>

            <div class="p-4">
                <form action="{{ route('whatsapp.send') }}" method="POST" id="waSendForm">
                    @csrf
                    
                    {{-- 1. PILIH PENERIMA DARI DAFTAR KONTAK --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark d-flex justify-content-between align-items-center mb-1.5" style="font-size: 0.85rem;">
                            <span class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-lines-fill text-primary"></i>
                                <span>{{ __('Kontak Penerima Cepat') }}</span>
                            </span>
                            <span class="badge bg-light text-muted fw-normal border">{{ __('Opsional') }}</span>
                        </label>
                        <select id="quickUserSelect" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="">-- Pilih Kontak Driver / Teknisi / Admin --</option>
                            @php
                                $driverUsers = $users->where('role', 'user');
                                $teknisiUsers = $users->where('role', 'teknisi');
                                $adminUsers = $users->where('role', 'admin');
                                $otherUsers = $users->whereNotIn('role', ['admin', 'teknisi', 'user']);
                            @endphp

                            @if($driverUsers->count() > 0)
                                <optgroup label="🚚 Pengemudi / Driver Armada">
                                    @foreach ($driverUsers as $user)
                                        @php
                                            $userPhone = $user->no_wa ?: $user->no_telepon;
                                            $assignedVehicle = $vehicles->first(function($v) use ($user) {
                                                return $v->driver_id === $user->id || strtolower($v->supir_utama) === strtolower($user->name);
                                            });
                                        @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="Pengemudi"
                                                data-vehicle-plat="{{ $assignedVehicle ? $assignedVehicle->plat_nomor : '' }}"
                                                data-vehicle-merk="{{ $assignedVehicle ? trim(($assignedVehicle->merek ?? '') . ' ' . ($assignedVehicle->tipe ?? '')) : '' }}">
                                            {{ $user->name }} {{ $assignedVehicle ? '— [' . $assignedVehicle->plat_nomor . ']' : '' }} ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if($teknisiUsers->count() > 0)
                                <optgroup label="🔧 Tim Teknisi Armada">
                                    @foreach ($teknisiUsers as $user)
                                        @php $userPhone = $user->no_wa ?: $user->no_telepon; @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="Teknisi">
                                            {{ $user->name }} ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if($adminUsers->count() > 0)
                                <optgroup label="👑 Admin & Manajemen">
                                    @foreach ($adminUsers as $user)
                                        @php $userPhone = $user->no_wa ?: $user->no_telepon; @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="Admin Fleet">
                                            {{ $user->name }} ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if($otherUsers->count() > 0)
                                <optgroup label="👥 Pengguna Lainnya">
                                    @foreach ($otherUsers as $user)
                                        @php $userPhone = $user->no_wa ?: $user->no_telepon; @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="{{ ucfirst($user->role) }}">
                                            {{ $user->name }} [{{ ucfirst($user->role) }}] ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>

                        {{-- Mini Contact Card Indicator --}}
                        <div id="contactMiniCard" class="contact-card-mini d-none">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle-sm bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; font-size: 0.78rem;">
                                    <span id="contactMiniInitial">U</span>
                                </div>
                                <div>
                                    <span id="contactMiniName" class="fw-bold text-dark d-block" style="font-size: 0.84rem;">Nama</span>
                                    <span id="contactMiniRole" class="badge badge-soft-primary px-1.5 py-0.5" style="font-size: 0.65rem;">Role</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span id="contactMiniPhone" class="font-monospace text-muted small d-block">628xxx</span>
                                <span id="contactMiniPlat" class="plate-badge-tech" style="font-size: 0.68rem; display: none;"></span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. INPUT NOMOR TELEPON TUJUAN --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-1.5" style="font-size: 0.85rem;">
                            <i class="bi bi-phone text-success"></i>
                            <span>{{ __('Nomor WhatsApp Tujuan') }} <span class="text-danger">*</span></span>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted border-end-0 px-2.5">
                                <i class="bi bi-whatsapp text-success fs-6"></i>
                            </span>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   class="form-control border-start-0 font-monospace fw-semibold py-2" 
                                   value="{{ old('phone') }}" 
                                   placeholder="Contoh: 08123456789 atau 628123456789" 
                                   required 
                                   style="border-radius: 0 8px 8px 0; font-size: 0.88rem;">
                        </div>
                        <input type="hidden" name="user_id" id="selectedUserId" value="{{ old('user_id') }}">
                    </div>

                    {{-- 3. FILTER & SELECTOR TEMPLATE SISTEM --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <label for="template_code" class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-0" style="font-size: 0.85rem;">
                                <i class="bi bi-file-earmark-code text-info"></i>
                                <span>{{ __('Template Sistem Armada') }}</span>
                            </label>
                            <small class="text-muted">{{ __('Pilih template otomatis') }}</small>
                        </div>

                        {{-- Quick Category Filter Pills --}}
                        <div class="d-flex flex-wrap gap-1.5 mb-2">
                            <button type="button" class="tmpl-filter-pill active" data-filter="all">Semua</button>
                            <button type="button" class="tmpl-filter-pill" data-filter="keluhan">🚨 Keluhan</button>
                            <button type="button" class="tmpl-filter-pill" data-filter="servis">🔧 Servis</button>
                            <button type="button" class="tmpl-filter-pill" data-filter="checklist">📋 Checklist</button>
                            <button type="button" class="tmpl-filter-pill" data-filter="biaya">💼 Approval</button>
                            <button type="button" class="tmpl-filter-pill" data-filter="tugas">📍 Rute</button>
                        </div>

                        <select name="template_code" id="template_code" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="" data-category="manual">-- Pesan Manual (Tulis Teks Bebas) --</option>
                            @foreach ($templates as $tmpl)
                                @php
                                    $cat = match(true) {
                                        str_contains($tmpl->code, 'keluhan') => 'keluhan',
                                        str_contains($tmpl->code, 'servis') || str_contains($tmpl->code, 'kir') => 'servis',
                                        str_contains($tmpl->code, 'checklist') => 'checklist',
                                        str_contains($tmpl->code, 'approval') || str_contains($tmpl->code, 'biaya') => 'biaya',
                                        str_contains($tmpl->code, 'tugas') || str_contains($tmpl->code, 'driver') => 'tugas',
                                        default => 'lainnya',
                                    };
                                @endphp
                                <option value="{{ $tmpl->code }}" 
                                        data-category="{{ $cat }}"
                                        data-content="{{ $tmpl->content }}"
                                        data-vars="{{ json_encode($tmpl->variables) }}"
                                        @selected(old('template_code') == $tmpl->code)>
                                    {{ $tmpl->name }} [{{ $tmpl->code }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. QUICK PICKER KENDARAAN (Untuk isi otomatis template) --}}
                    <div class="mb-3 d-none" id="vehiclePickerContainer">
                        <label class="form-label fw-semibold text-dark d-flex justify-content-between align-items-center mb-1" style="font-size: 0.83rem;">
                            <span class="d-flex align-items-center gap-1.5">
                                <i class="bi bi-truck text-primary"></i>
                                <span>{{ __('Sinkronisasi Data Armada') }}</span>
                            </span>
                            <span class="text-muted small fw-normal">{{ __('Auto isi plat & merk') }}</span>
                        </label>
                        <select id="quickVehicleSelect" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="">-- Pilih Armada Terdaftar --</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->plat_nomor }}" 
                                        data-plat="{{ $v->plat_nomor }}"
                                        data-merk="{{ trim(($v->merek ?? '') . ' ' . ($v->tipe ?? '')) }}"
                                        data-odo="{{ $v->odometer_awal ?? 0 }}"
                                        data-driver="{{ $v->supir_utama ?: ($v->driver?->name ?: 'Belum ada driver') }}">
                                    {{ $v->plat_nomor }} — {{ $v->merek }} {{ $v->tipe }} ({{ $v->supir_utama ?: 'Driver: —' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 5. DYNAMIC VARIABLES CONTAINER --}}
                    <div id="dynamicVariablesContainer" class="p-3 bg-light rounded-3 border mb-3 d-none">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark d-flex align-items-center gap-1.5" style="font-size: 0.82rem;">
                                <i class="bi bi-sliders2 text-primary"></i>
                                <span>{{ __('Variabel Parameter Template') }}</span>
                            </span>
                            <span class="badge badge-soft-primary px-2 py-0.5" style="font-size: 0.68rem;">Placeholder Bindings</span>
                        </div>
                        <div id="dynamicFieldsList" class="d-flex flex-column gap-2"></div>
                    </div>

                    {{-- 6. MANUAL MESSAGE TEXTAREA & FORMATTING TOOLBAR --}}
                    <div class="mb-3" id="manualMessageContainer">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <label for="message" class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-0" style="font-size: 0.85rem;">
                                <i class="bi bi-chat-left-text text-primary"></i>
                                <span>{{ __('Isi Teks Pesan') }} <span id="msgRequiredAsterisk" class="text-danger">*</span></span>
                            </label>
                            
                            {{-- Formatting Shortcode Toolbar --}}
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="format-btn" data-format="bold" title="Teks Tebal (*teks*)"><b>B</b></button>
                                <button type="button" class="format-btn" data-format="italic" title="Teks Miring (_teks_)"><i>I</i></button>
                                <button type="button" class="format-btn" data-format="strike" title="Teks Coret (~teks~)"><s>S</s></button>
                                <button type="button" class="format-btn" data-format="bullet" title="Poin Bullet (•)">•</button>
                            </div>
                        </div>

                        <textarea name="message" 
                                  id="message" 
                                  rows="5" 
                                  class="form-control py-2.5" 
                                  placeholder="Tuliskan pesan teks yang ingin dikirimkan via WhatsApp Gateway..." 
                                  style="border-radius: 8px; font-size: 0.88rem; font-family: inherit;">{{ old('message') }}</textarea>
                        
                        <div class="d-flex justify-content-between align-items-center mt-1.5 text-muted" style="font-size: 0.76rem;">
                            <span>Maksimal 2.000 karakter via REST API</span>
                            <span id="charCounter" class="font-monospace fw-semibold">0 karakter</span>
                        </div>
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <button type="submit" id="btnSubmitWa" class="btn btn-dispatch-wa w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
                        <i class="bi bi-send-fill fs-6"></i>
                        <span>{{ __('Kirim Sekarang via Gateway') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- =====================================================
         KOLOM KANAN: SMARTPHONE MOCKUP & LOG MONITOR TABLE
         ===================================================== --}}
    <div class="col-lg-7">
        <div class="aesthetic-card h-100 d-flex flex-column">
            
            {{-- Clean Nav Tabs Switcher --}}
            <div class="p-0 px-3 pt-2 border-bottom bg-light bg-opacity-40">
                <ul class="nav nav-tabs-clean" id="waHubTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="table-tab" data-bs-toggle="tab" data-bs-target="#tab-table" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-clock-history text-primary"></i>
                            <span>{{ __('Riwayat & Logbook') }}</span>
                            <span class="badge bg-secondary rounded-pill px-1.5 py-0.5 ms-1" style="font-size: 0.68rem;">{{ $logs->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="simulator-tab" data-bs-toggle="tab" data-bs-target="#tab-simulator" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-phone text-success"></i>
                            <span>{{ __('Simulator Chat Live') }}</span>
                            <span class="badge badge-soft-success rounded-pill px-1.5 py-0.5 ms-1" style="font-size: 0.68rem;">Interactive</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="catalogue-tab" data-bs-toggle="tab" data-bs-target="#tab-catalogue" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-grid-fill text-info"></i>
                            <span>{{ __('Galeri Template') }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content flex-grow-1 p-0" id="waHubTabContent">
                
                {{-- TAB 1: TABEL LOG RIWAYAT PENGIRIMAN --}}
                <div class="tab-pane fade show active h-100 d-flex flex-column" id="tab-table" role="tabpanel" aria-labelledby="table-tab">
                    
                    {{-- Filter & Search Toolbar --}}
                    <div class="p-3 px-4 border-bottom bg-light bg-opacity-30 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark small">{{ __('Filter Status:') }}</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('whatsapp.index') }}" class="btn {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}" style="font-size: 0.74rem;">Semua</a>
                                <a href="{{ route('whatsapp.index', ['status' => 'sent']) }}" class="btn {{ request('status') === 'sent' || request('status') === 'success' ? 'btn-success' : 'btn-outline-success' }}" style="font-size: 0.74rem;">Terkirim</a>
                                <a href="{{ route('whatsapp.index', ['status' => 'pending']) }}" class="btn {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}" style="font-size: 0.74rem;">Antrean</a>
                                <a href="{{ route('whatsapp.index', ['status' => 'failed']) }}" class="btn {{ request('status') === 'failed' ? 'btn-danger' : 'btn-outline-danger' }}" style="font-size: 0.74rem;">Gagal</a>
                            </div>
                        </div>

                        {{-- Search Input --}}
                        <form action="{{ route('whatsapp.index') }}" method="GET" class="d-flex align-items-center gap-1.5 ms-auto">
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            <div class="input-group input-group-sm" style="width: 220px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari pelapor / plat..." value="{{ request('search') }}" style="border-radius: 6px 0 0 6px;">
                                <button class="btn btn-outline-secondary" type="submit" style="border-radius: 0 6px 6px 0;">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            @if(request()->filled('search') || request()->filled('status'))
                                <a href="{{ route('whatsapp.index') }}" class="btn btn-sm btn-light border" title="Reset Filter">
                                    <i class="bi bi-x-lg text-muted" style="font-size: 0.7rem;"></i>
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- Table Content --}}
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted" style="width: 35px;">#</th>
                                    <th style="width: 110px;">{{ __('Waktu') }}</th>
                                    <th>{{ __('Pelapor & No. WA') }}</th>
                                    <th>{{ __('Armada') }}</th>
                                    <th>{{ __('Kategori / Pesan') }}</th>
                                    <th style="width: 85px;">{{ __('Status') }}</th>
                                    <th class="pe-3 text-end" style="width: 95px;">{{ __('Aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                <tr>
                                    <td class="ps-3 text-muted font-monospace" style="font-size: 0.78rem;">
                                        {{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block" style="font-size: 0.8rem;">
                                            {{ $log->created_at ? $log->created_at->format('d/m/Y') : '—' }}
                                        </span>
                                        <span class="text-muted font-monospace" style="font-size: 0.72rem;">
                                            {{ $log->created_at ? $log->created_at->format('H:i:s') : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.72rem;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <span class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $log->pelapor }}</span>
                                        </div>
                                        <div class="text-muted font-monospace d-flex align-items-center gap-1.5" style="font-size: 0.75rem;">
                                            <i class="bi bi-whatsapp text-success"></i>
                                            <span>{{ $log->phone }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($log->kendaraan)
                                            <span class="plate-badge-tech">
                                                <i class="bi bi-truck"></i>
                                                <span>{{ $log->kendaraan }}</span>
                                            </span>
                                        @else
                                            <span class="text-muted font-monospace" style="font-size: 0.78rem;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if ($log->template)
                                                @php
                                                    $tmplClass = match($log->template->code) {
                                                        'keluhan_baru' => 'badge-soft-danger',
                                                        'keluhan_status' => 'badge-soft-info',
                                                        'servis_reminder' => 'badge-soft-warning',
                                                        'checklist_peringatan' => 'badge-soft-warning',
                                                        'approval_biaya' => 'badge-soft-primary',
                                                        default => 'badge-soft-success',
                                                    };
                                                @endphp
                                                <span class="badge {{ $tmplClass }} align-self-start px-2 py-0.5" style="font-size: 0.68rem;">
                                                    {{ $log->template->name }}
                                                </span>
                                            @else
                                                <span class="badge badge-soft-info align-self-start px-2 py-0.5" style="font-size: 0.68rem;">
                                                    {{ __('Pesan Manual') }}
                                                </span>
                                            @endif
                                            <span class="text-dark fw-medium d-inline-block text-truncate" style="max-width: 210px; font-size: 0.8rem;" title="{{ $log->message }}">
                                                {{ $log->kendala }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($log->isSuccess())
                                            <span class="badge badge-soft-success px-2 py-0.5 d-inline-flex align-items-center gap-1 text-uppercase" style="font-size: 0.68rem;">
                                                <i class="bi bi-check2-all"></i>Terkirim
                                            </span>
                                        @elseif($log->isFailed())
                                            <span class="badge badge-soft-danger px-2 py-0.5 d-inline-flex align-items-center gap-1 text-uppercase" style="font-size: 0.68rem;">
                                                <i class="bi bi-x-circle"></i>Gagal
                                            </span>
                                        @else
                                            <span class="badge badge-soft-warning px-2 py-0.5 d-inline-flex align-items-center gap-1 text-uppercase" style="font-size: 0.68rem;" title="Percobaan: {{ $log->attempts }}/5">
                                                <i class="bi bi-hourglass-split"></i>Antrean @if($log->attempts > 0)({{ $log->attempts }}/5)@endif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-inline-flex gap-1 align-items-center">
                                            {{-- Tombol Buka di WhatsApp Web / App (wa.me) --}}
                                            <a href="{{ $log->wa_url }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer" 
                                               class="btn btn-sm btn-outline-success px-1.5 py-0.5" 
                                               style="border-radius: 6px; font-size: 0.72rem;" 
                                               title="Buka Chat di WhatsApp Web (wa.me)">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>

                                            {{-- Tombol Kirim Ulang jika Status Gagal --}}
                                            @if ($log->status === 'failed')
                                                <form action="{{ route('whatsapp.resend', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim ulang pesan WhatsApp ini ke {{ $log->phone }}?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-1.5 py-0.5" style="border-radius: 6px; font-size: 0.72rem;" title="Kirim Ulang via Gateway">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Tombol Detail Log --}}
                                            <button type="button" 
                                                    class="btn btn-sm btn-light border px-2 py-0.5 btn-detail-log" 
                                                    style="border-radius: 6px; font-size: 0.74rem;" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailLogModal"
                                                    data-log="{{ json_encode(array_merge($log->load(['template', 'user'])->toArray(), [
                                                        'parsed_pelapor' => $log->pelapor,
                                                        'parsed_kendaraan' => $log->kendaraan,
                                                        'parsed_kendala' => $log->kendala,
                                                        'wa_url' => $log->wa_url,
                                                    ])) }}"
                                                    title="Lihat Detail Telemetri API">
                                                <i class="bi bi-eye"></i> Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                            <i class="bi bi-chat-square-dots text-secondary fs-4"></i>
                                        </div>
                                        <span class="fw-bold d-block text-dark">Belum ada riwayat pesan WhatsApp</span>
                                        <small class="text-muted">Kirim pesan pertama Anda menggunakan panel Composer di sebelah kiri.</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Footer --}}
                    @if ($logs->hasPages())
                        <div class="p-3 px-4 border-top d-flex justify-content-between align-items-center bg-body-tertiary">
                            <span class="text-muted small">
                                Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} log
                            </span>
                            <div>
                                {{ $logs->links() }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- TAB 2: SMARTPHONE WHATSAPP SIMULATOR LIVE --}}
                <div class="tab-pane fade p-4" id="tab-simulator" role="tabpanel" aria-labelledby="simulator-tab">
                    <div class="text-center mb-3">
                        <span class="badge badge-soft-success px-3 py-1 rounded-pill mb-1">
                            <i class="bi bi-phone me-1"></i>Live Smartphone Mockup Simulator
                        </span>
                        <p class="text-muted small mb-0">Tampilan pesan terformat di smartphone pengemudi / staf secara real-time.</p>
                    </div>

                    {{-- Realistic Smartphone Body --}}
                    <div class="phone-mockup-wrapper">
                        <div class="phone-notch">
                            <div class="camera-lens"></div>
                        </div>
                        <div class="phone-screen">
                            {{-- Phone WhatsApp Top Header --}}
                            <div class="phone-header">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-arrow-left fs-6"></i>
                                    <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                        <i class="bi bi-person-fill text-secondary"></i>
                                    </div>
                                    <div>
                                        <span id="phoneRecipientName" class="fw-bold d-block" style="font-size: 0.8rem; line-height: 1.2;">Fleet Control Center</span>
                                        <small class="text-white-50" style="font-size: 0.65rem;">online</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2.5 fs-6 text-white-50">
                                    <i class="bi bi-camera-video"></i>
                                    <i class="bi bi-telephone"></i>
                                    <i class="bi bi-three-dots-vertical"></i>
                                </div>
                            </div>

                            {{-- Phone WhatsApp Chat Screen --}}
                            <div class="phone-chat-body">
                                <div class="security-notice-badge">
                                    <i class="bi bi-lock-fill me-1"></i>Pesan ini dienkripsi secara end-to-end melalui Ervelia WhatsApp Gateway API.
                                </div>

                                {{-- Live Chat Bubble --}}
                                <div class="chat-bubble-wa" id="phoneLiveBubble">
                                    Pilih template atau tulis teks pesan pada panel composer untuk melihat pratinjau live di sini.
                                    <div class="chat-time-meta">
                                        <span id="phoneLiveTime">12:00</span>
                                        <i class="bi bi-check2-all"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: KATALOG 7 TEMPLATE SISTEM --}}
                <div class="tab-pane fade p-4" id="tab-catalogue" role="tabpanel" aria-labelledby="catalogue-tab">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Katalog Template Resmi Sistem Armada</h6>
                            <small class="text-muted">Template bawaan yang digunakan untuk notifikasi event-driven.</small>
                        </div>
                        <span class="badge bg-primary rounded-pill px-2.5 py-1">7 Template Aktif</span>
                    </div>

                    <div class="row g-3">
                        @foreach ($templates as $tmpl)
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                                            <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $tmpl->name }}</span>
                                            <span class="badge bg-dark font-monospace" style="font-size: 0.65rem;">{{ $tmpl->code }}</span>
                                        </div>
                                        <p class="text-muted small mb-2" style="font-size: 0.76rem;">{{ $tmpl->description }}</p>
                                        <div class="p-2.5 bg-white rounded border small font-monospace text-muted mb-2" style="font-size: 0.72rem; max-height: 85px; overflow-y: auto; white-space: pre-wrap;">{{ $tmpl->content }}</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 btn-apply-tmpl" data-code="{{ $tmpl->code }}" style="font-size: 0.75rem; border-radius: 6px;">
                                        <i class="bi bi-pencil-square me-1"></i>Gunakan di Composer
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     MODAL DETAIL TELEMETRI & LOG INSPECTOR
     ========================================================= --}}
<div class="modal fade" id="detailLogModal" tabindex="-1" aria-labelledby="detailLogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3 px-4 bg-body-tertiary">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-1.5 bg-primary-subtle text-primary rounded-2">
                        <i class="bi bi-cpu-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="detailLogModalLabel">
                            {{ __('Telemetri Pesan & Diagnostik Gateway') }}
                        </h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Detail payload REST API Ervelia dan status pengiriman</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Info Ringkas Kartu --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">Pelapor / Pengemudi</span>
                            <div id="modalPelapor" class="fw-bold text-dark fs-6 mt-1"></div>
                            <div id="modalPhone" class="text-muted font-monospace small"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">Armada Terkait</span>
                            <div id="modalKendaraan" class="fw-bold text-primary fs-6 mt-1"></div>
                            <div id="modalTemplateName" class="text-muted small"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">Status Gateway</span>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span id="modalStatusBadge" class="badge"></span>
                                <span id="modalSentAt" class="text-dark small font-monospace"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kendala yang dilaporkan --}}
                <div class="mb-3">
                    <label class="fw-bold text-dark small d-block mb-1">
                        <i class="bi bi-wrench-adjustable text-danger me-1"></i>Bagian Kerusakan / Kendala Dilaporkan
                    </label>
                    <div id="modalKendala" class="p-2.5 bg-danger-subtle text-danger rounded-3 border border-danger-subtle fw-semibold" style="font-size: 0.85rem;"></div>
                </div>

                {{-- Isi Pesan Lengkap --}}
                <div class="mb-3">
                    <label class="fw-bold text-dark small d-block mb-1">
                        <i class="bi bi-whatsapp text-success me-1"></i>Format Isi Pesan Teks
                    </label>
                    <div id="modalMessage" class="p-3 bg-light rounded-3 border text-dark font-monospace" style="font-size: 0.82rem; white-space: pre-wrap; word-break: break-word;"></div>
                </div>

                {{-- Error Banner jika ada --}}
                <div id="modalErrorSection" class="mb-3 d-none">
                    <label class="fw-bold text-danger small d-block mb-1">Pesan Kesalahan Provider Gateway</label>
                    <div id="modalError" class="p-2.5 bg-danger-subtle text-danger rounded-3 border border-danger-subtle small font-monospace"></div>
                </div>

                {{-- Raw JSON Telemetry --}}
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="fw-bold text-muted small mb-0">Payload JSON Response (Ervelia Gateway)</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="btnCopyJson" style="font-size: 0.7rem;">
                            <i class="bi bi-clipboard me-1"></i>Salin JSON
                        </button>
                    </div>
                    <pre id="modalResponse" class="p-3 bg-dark text-light rounded-3 small mb-0 font-monospace" style="max-height: 140px; overflow-y: auto; font-size: 0.75rem;"></pre>
                </div>
            </div>
            <div class="modal-footer border-top py-2.5 px-4 bg-body-tertiary d-flex justify-content-between align-items-center">
                <a href="#" id="modalBtnDirectWa" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm px-3 d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 8px;">
                    <i class="bi bi-whatsapp"></i>
                    <span>Kirim via WhatsApp Web</span>
                </a>
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     MODAL KATALOG 7 TEMPLATE SISTEM ARMADA
     ========================================================= --}}
<div class="modal fade" id="templateGuideModal" tabindex="-1" aria-labelledby="templateGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3 px-4 bg-body-tertiary">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-1.5 bg-success-subtle text-success rounded-2">
                        <i class="bi bi-collection-play-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="templateGuideModalLabel">
                            {{ __('Katalog 7 Template Otomasi WhatsApp') }}
                        </h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Panduan pemicu otomatis dan placeholder variabel</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                <div class="row g-3">
                    @foreach ($templates as $tmpl)
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $tmpl->name }}</span>
                                    <span class="badge bg-dark font-monospace">{{ $tmpl->code }}</span>
                                </div>
                                <p class="text-muted small mb-2">{{ $tmpl->description }}</p>
                                <div class="p-2.5 bg-white rounded border small font-monospace text-dark mb-2" style="white-space: pre-wrap; font-size: 0.78rem;">{{ $tmpl->content }}</div>
                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                    <span class="text-muted small fw-semibold me-1" style="font-size: 0.72rem;">Variabel:</span>
                                    @if($tmpl->variables)
                                        @foreach($tmpl->variables as $v)
                                            <span class="badge badge-soft-primary px-2 py-0.5" style="font-size: 0.68rem;">@{{ {{ $v }} }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-top py-2.5 px-4 bg-body-tertiary">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     INTERACTIVE JAVASCRIPT ENGINE
     ========================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');
        const selectedUserId = document.getElementById('selectedUserId');
        const quickUserSelect = document.getElementById('quickUserSelect');
        const quickVehicleSelect = document.getElementById('quickVehicleSelect');
        const vehiclePickerContainer = document.getElementById('vehiclePickerContainer');
        const templateSelect = document.getElementById('template_code');
        const messageTextarea = document.getElementById('message');
        const manualMessageContainer = document.getElementById('manualMessageContainer');
        const dynamicVariablesContainer = document.getElementById('dynamicVariablesContainer');
        const dynamicFieldsList = document.getElementById('dynamicFieldsList');
        const charCounter = document.getElementById('charCounter');
        const msgAsterisk = document.getElementById('msgRequiredAsterisk');
        const btnUseAdminNumber = document.getElementById('btnUseAdminNumber');
        const btnSubmitWa = document.getElementById('btnSubmitWa');

        // Phone Simulator Elements
        const phoneLiveBubble = document.getElementById('phoneLiveBubble');
        const phoneLiveTime = document.getElementById('phoneLiveTime');
        const phoneRecipientName = document.getElementById('phoneRecipientName');

        // Contact Mini Card
        const contactMiniCard = document.getElementById('contactMiniCard');
        const contactMiniName = document.getElementById('contactMiniName');
        const contactMiniRole = document.getElementById('contactMiniRole');
        const contactMiniPhone = document.getElementById('contactMiniPhone');
        const contactMiniPlat = document.getElementById('contactMiniPlat');
        const contactMiniInitial = document.getElementById('contactMiniInitial');

        const adminNumber = @json(config('services.whatsapp.admin_number', ''));

        // Update Phone Live Clock
        function updateLiveClock() {
            const now = new Date();
            const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            if (phoneLiveTime) phoneLiveTime.textContent = timeStr;
        }
        updateLiveClock();

        // 1. Tombol Cepat Isi Nomor Admin
        if (btnUseAdminNumber) {
            btnUseAdminNumber.addEventListener('click', function () {
                if (adminNumber) {
                    phoneInput.value = adminNumber;
                    if (selectedUserId) selectedUserId.value = '';
                    if (phoneRecipientName) phoneRecipientName.textContent = 'Admin Fleet Control';
                    if (contactMiniCard) contactMiniCard.classList.add('d-none');
                } else {
                    alert('Nomor admin belum dikonfigurasi di file .env (WHATSAPP_ADMIN_NUMBER)');
                }
            });
        }

        // 2. Quick User Picker Handler
        if (quickUserSelect) {
            quickUserSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const phone = this.value;
                const userId = selected.getAttribute('data-user-id');
                const userName = selected.getAttribute('data-user-name');
                const userRole = selected.getAttribute('data-user-role') || 'Staf';
                const vehiclePlat = selected.getAttribute('data-vehicle-plat');
                const vehicleMerk = selected.getAttribute('data-vehicle-merk');

                if (phone) {
                    phoneInput.value = phone;
                }
                if (selectedUserId) {
                    selectedUserId.value = userId || '';
                }

                if (userName && phone) {
                    contactMiniCard.classList.remove('d-none');
                    contactMiniName.textContent = userName;
                    contactMiniRole.textContent = userRole;
                    contactMiniPhone.textContent = phone;
                    contactMiniInitial.textContent = userName.charAt(0).toUpperCase();
                    if (phoneRecipientName) phoneRecipientName.textContent = userName;

                    if (vehiclePlat) {
                        contactMiniPlat.style.display = 'inline-flex';
                        contactMiniPlat.innerHTML = `<i class="bi bi-truck me-1"></i> ${vehiclePlat}`;
                    } else {
                        contactMiniPlat.style.display = 'none';
                    }
                } else {
                    contactMiniCard.classList.add('d-none');
                    if (phoneRecipientName) phoneRecipientName.textContent = 'Fleet Control Center';
                }

                // Auto-fill template variables
                if (userName) {
                    const driverInputs = document.querySelectorAll('input[name="data[nama_driver]"], input[name="data[driver]"], input[name="data[nama_pemeriksa]"]');
                    driverInputs.forEach(input => {
                        input.value = userName;
                    });
                }

                if (vehiclePlat) {
                    const platInput = document.querySelector('input[name="data[plat_nomor]"]');
                    const merkInput = document.querySelector('input[name="data[merk_tipe]"]');
                    if (platInput && !platInput.value) platInput.value = vehiclePlat;
                    if (merkInput && !merkInput.value && vehicleMerk) merkInput.value = vehicleMerk;

                    if (quickVehicleSelect) {
                        for (let i = 0; i < quickVehicleSelect.options.length; i++) {
                            if (quickVehicleSelect.options[i].value === vehiclePlat) {
                                quickVehicleSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                }

                updateLivePreview();
            });
        }

        // 3. Quick Vehicle Picker Handler
        if (quickVehicleSelect) {
            quickVehicleSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const plat = selected.getAttribute('data-plat');
                const merk = selected.getAttribute('data-merk');
                const odo = selected.getAttribute('data-odo');
                const driver = selected.getAttribute('data-driver');

                if (plat) {
                    const platInput = document.querySelector('input[name="data[plat_nomor]"]');
                    const merkInput = document.querySelector('input[name="data[merk_tipe]"]');
                    const odoInput = document.querySelector('input[name="data[odometer]"]');
                    const driverInput = document.querySelector('input[name="data[driver]"]') || document.querySelector('input[name="data[nama_driver]"]');

                    if (platInput) platInput.value = plat;
                    if (merkInput) merkInput.value = merk || '';
                    if (odoInput) odoInput.value = odo || '0';
                    if (driverInput && driver && driver !== 'Belum ada driver') driverInput.value = driver;

                    updateLivePreview();
                }
            });
        }

        // 4. Character Counter for Manual Message
        function updateCounter() {
            if (messageTextarea && charCounter) {
                charCounter.textContent = `${messageTextarea.value.length} karakter`;
            }
        }
        if (messageTextarea) {
            messageTextarea.addEventListener('input', function () {
                updateCounter();
                updateLivePreview();
            });
            updateCounter();
        }

        // 5. WhatsApp Text Formatter (WhatsApp bold *text*, italics _text_, strikethrough ~text~)
        function formatWhatsappText(raw) {
            if (!raw) return 'Tulis teks pesan untuk melihat pratinjau live.';
            let formatted = raw
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\*(.*?)\*/g, '<strong>$1</strong>')
                .replace(/_(.*?)_/g, '<em>$1</em>')
                .replace(/~(.*?)~/g, '<del>$1</del>')
                .replace(/\n/g, '<br>');
            return formatted;
        }

        // 6. Live Preview Update Function
        function updateLivePreview() {
            const selectedOpt = templateSelect.options[templateSelect.selectedIndex];
            let content = selectedOpt.getAttribute('data-content');

            if (!content) {
                const manualText = messageTextarea.value;
                const formattedHtml = formatWhatsappText(manualText);
                if (phoneLiveBubble) {
                    phoneLiveBubble.innerHTML = `${formattedHtml} <div class="chat-time-meta"><span>${phoneLiveTime ? phoneLiveTime.textContent : '12:00'}</span><i class="bi bi-check2-all"></i></div>`;
                }
                return;
            }

            const varInputs = dynamicFieldsList.querySelectorAll('input, textarea');
            varInputs.forEach(input => {
                const varName = input.getAttribute('data-var');
                const val = input.value || `{${varName}}`;
                const regex = new RegExp(`\\{\\{\\s*${varName}\\s*\\}\\}`, 'g');
                content = content.replace(regex, val);
            });

            const formattedHtml = formatWhatsappText(content);
            if (phoneLiveBubble) {
                phoneLiveBubble.innerHTML = `${formattedHtml} <div class="chat-time-meta"><span>${phoneLiveTime ? phoneLiveTime.textContent : '12:00'}</span><i class="bi bi-check2-all"></i></div>`;
            }
        }

        // 7. Template Category Filter Pills
        const filterPills = document.querySelectorAll('.tmpl-filter-pill');
        filterPills.forEach(pill => {
            pill.addEventListener('click', function () {
                filterPills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');
                for (let i = 0; i < templateSelect.options.length; i++) {
                    const opt = templateSelect.options[i];
                    const cat = opt.getAttribute('data-category');
                    if (filter === 'all' || cat === filter || opt.value === '') {
                        opt.style.display = 'block';
                    } else {
                        opt.style.display = 'none';
                    }
                }
            });
        });

        // 8. Template Select Handler
        if (templateSelect) {
            templateSelect.addEventListener('change', function () {
                const selectedOpt = this.options[this.selectedIndex];
                const content = selectedOpt.getAttribute('data-content');
                const rawVars = selectedOpt.getAttribute('data-vars');

                dynamicFieldsList.innerHTML = '';

                if (content) {
                    manualMessageContainer.classList.add('d-none');
                    dynamicVariablesContainer.classList.remove('d-none');
                    vehiclePickerContainer.classList.remove('d-none');
                    if (msgAsterisk) msgAsterisk.classList.add('d-none');

                    let vars = [];
                    try {
                        vars = JSON.parse(rawVars) || [];
                    } catch (e) {
                        vars = [];
                    }

                    vars.forEach(varName => {
                        const formGroup = document.createElement('div');
                        formGroup.className = 'mb-2';

                        const label = document.createElement('label');
                        label.className = 'form-label text-muted small fw-semibold mb-1 text-capitalize d-flex align-items-center gap-1';
                        label.innerHTML = `<i class="bi bi-dot text-primary"></i> <span>${varName.replace(/_/g, ' ')}</span>`;

                        let inputField;
                        if (varName.includes('deskripsi') || varName.includes('catatan') || varName.includes('komponen')) {
                            inputField = document.createElement('textarea');
                            inputField.rows = 2;
                        } else {
                            inputField = document.createElement('input');
                            inputField.type = 'text';
                        }

                        inputField.name = `data[${varName}]`;
                        inputField.className = 'form-control form-control-sm';
                        inputField.setAttribute('data-var', varName);
                        inputField.placeholder = `Isi nilai untuk ${varName.replace(/_/g, ' ')}...`;
                        inputField.style.borderRadius = '6px';

                        if (varName === 'tanggal') {
                            const now = new Date();
                            inputField.value = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()}`;
                        } else if (varName === 'waktu') {
                            const now = new Date();
                            inputField.value = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
                        }

                        inputField.addEventListener('input', updateLivePreview);

                        formGroup.appendChild(label);
                        formGroup.appendChild(inputField);
                        dynamicFieldsList.appendChild(formGroup);
                    });

                    updateLivePreview();
                } else {
                    manualMessageContainer.classList.remove('d-none');
                    dynamicVariablesContainer.classList.add('d-none');
                    vehiclePickerContainer.classList.add('d-none');
                    if (msgAsterisk) msgAsterisk.classList.remove('d-none');
                    updateCounter();
                    updateLivePreview();
                }
            });
        }

        // 9. Apply Template from Catalogue Tab
        const applyButtons = document.querySelectorAll('.btn-apply-tmpl');
        applyButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const code = this.getAttribute('data-code');
                templateSelect.value = code;
                templateSelect.dispatchEvent(new Event('change'));

                // Switch to simulator tab to see preview
                const simTab = document.getElementById('simulator-tab');
                if (simTab) {
                    const tabTrigger = new bootstrap.Tab(simTab);
                    tabTrigger.show();
                }
            });
        });

        // 10. Manual Formatting Toolbar Handlers
        const formatBtns = document.querySelectorAll('.format-btn');
        formatBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const format = this.getAttribute('data-format');
                if (!messageTextarea) return;

                const start = messageTextarea.selectionStart;
                const end = messageTextarea.selectionEnd;
                const text = messageTextarea.value;
                const selectedText = text.substring(start, end);

                let replacement = '';
                if (format === 'bold') replacement = `*${selectedText || 'teks'}*`;
                else if (format === 'italic') replacement = `_${selectedText || 'teks'}_`;
                else if (format === 'strike') replacement = `~${selectedText || 'teks'}~`;
                else if (format === 'bullet') replacement = `• ${selectedText || 'poin'}`;

                messageTextarea.value = text.substring(0, start) + replacement + text.substring(end);
                messageTextarea.focus();
                messageTextarea.setSelectionRange(start + replacement.length, start + replacement.length);
                updateCounter();
                updateLivePreview();
            });
        });

        // 11. Modal Detail Log Inspector Handler
        const detailButtons = document.querySelectorAll('.btn-detail-log');
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                try {
                    const logData = JSON.parse(this.getAttribute('data-log'));
                    
                    document.getElementById('modalPelapor').textContent = logData.parsed_pelapor || (logData.user ? logData.user.name : 'Pengemudi / Staff');
                    document.getElementById('modalPhone').textContent = logData.phone ? `No. WA: ${logData.phone}` : '—';
                    document.getElementById('modalKendaraan').textContent = logData.parsed_kendaraan || 'Armada Umum / Belum Ditentukan';
                    document.getElementById('modalTemplateName').textContent = logData.template ? logData.template.name : 'Pesan Manual';
                    document.getElementById('modalKendala').textContent = logData.parsed_kendala || 'Tidak ada kendala tercatat.';

                    const statusBadge = document.getElementById('modalStatusBadge');
                    statusBadge.textContent = (logData.status || 'PENDING').toUpperCase();
                    statusBadge.className = `badge badge-soft-${logData.status === 'success' ? 'success' : (logData.status === 'failed' ? 'danger' : 'warning')}`;

                    document.getElementById('modalSentAt').textContent = logData.sent_at || logData.created_at || '—';
                    document.getElementById('modalMessage').textContent = logData.message || '—';

                    const errorSection = document.getElementById('modalErrorSection');
                    const modalError = document.getElementById('modalError');
                    if (logData.error_message) {
                        errorSection.classList.remove('d-none');
                        modalError.textContent = logData.error_message;
                    } else {
                        errorSection.classList.add('d-none');
                    }

                    const formattedJson = logData.response ? JSON.stringify(logData.response, null, 2) : '{\n  "status": "pending",\n  "message": "Menunggu respons provider"\n}';
                    document.getElementById('modalResponse').textContent = formattedJson;

                    const modalBtnDirectWa = document.getElementById('modalBtnDirectWa');
                    if (modalBtnDirectWa) {
                        modalBtnDirectWa.href = logData.wa_url || '#';
                    }
                } catch (e) {
                    console.error('Error parsing log data', e);
                }
            });
        });

        // 12. Copy JSON Helper
        const btnCopyJson = document.getElementById('btnCopyJson');
        if (btnCopyJson) {
            btnCopyJson.addEventListener('click', function () {
                const text = document.getElementById('modalResponse').textContent;
                navigator.clipboard.writeText(text).then(() => {
                    this.innerHTML = '<i class="bi bi-check2 text-success me-1"></i>Tersalin!';
                    setTimeout(() => {
                        this.innerHTML = '<i class="bi bi-clipboard me-1"></i>Salin JSON';
                    }, 2000);
                });
            });
        }

        // 13. Form Submit Loading Spinner
        const waSendForm = document.getElementById('waSendForm');
        if (waSendForm && btnSubmitWa) {
            waSendForm.addEventListener('submit', function () {
                btnSubmitWa.disabled = true;
                btnSubmitWa.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim via Gateway...';
            });
        }

        // Trigger initial preview
        updateLivePreview();
    });
</script>
@endsection
