@extends('layouts.app')
@section('title', 'Dashboard Monitoring')

@section('content')

@push('styles')
<style>
    /* CSS for Service Calendar */
    .calendar-container {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        text-align: center;
    }
    .calendar-day-header {
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        color: #64748b;
        padding: 6px 0;
    }
    .calendar-cell {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        min-height: 52px;
        padding: 4px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    body.dark-theme .calendar-cell {
        background: #1e293b;
        border-color: #334155;
    }
    .calendar-cell:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .calendar-cell.today {
        border: 2px solid #4f46e5 !important;
        background: rgba(79, 70, 229, 0.05) !important;
    }
    .calendar-date-number {
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-align: right;
    }
    body.dark-theme .calendar-date-number {
        color: #94a3b8;
    }
    .calendar-event-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    
    /* CSS for Leaderboard */
    .leaderboard-row {
        transition: all 0.2s ease;
    }
    .leaderboard-row:hover {
        background: rgba(79, 70, 229, 0.04) !important;
    }
    body.dark-theme .leaderboard-row:hover {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    .card-grad-1 {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
        color: #ffffff !important; border: none !important;
        box-shadow: 0 8px 20px -6px rgba(59, 130, 246, 0.3) !important;
        position: relative; overflow: hidden;
    }
    .card-grad-2 {
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%) !important;
        color: #ffffff !important; border: none !important;
        box-shadow: 0 8px 20px -6px rgba(239, 68, 68, 0.3) !important;
        position: relative; overflow: hidden;
    }
    .card-grad-3 {
        background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%) !important;
        color: #ffffff !important; border: none !important;
        box-shadow: 0 8px 20px -6px rgba(245, 158, 11, 0.3) !important;
        position: relative; overflow: hidden;
    }
    .card-grad-4 {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important; border: none !important;
        box-shadow: 0 8px 20px -6px rgba(16, 185, 129, 0.3) !important;
        position: relative; overflow: hidden;
    }

    /* Glassmorphic Gradient Spheres */
    .card-grad-1::after, .card-grad-2::after, .card-grad-3::after, .card-grad-4::after {
        content: ''; position: absolute; right: -15px; bottom: -15px;
        width: 120px; height: 120px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%); pointer-events: none;
        filter: blur(5px);
    }
    .card-grad-1::before, .card-grad-2::before, .card-grad-3::before, .card-grad-4::before {
        content: ''; position: absolute; left: -20px; top: -20px;
        width: 90px; height: 90px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); pointer-events: none;
        filter: blur(5px);
    }

    .card-grad-icon {
        background: rgba(255, 255, 255, 0.18) !important; backdrop-filter: blur(8px); border-radius: 12px;
        width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #ffffff !important;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .card-grad-title { color: rgba(255, 255, 255, 0.85) !important; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; }
    
    .dashboard-card { border-radius: 16px !important; border: 1px solid rgba(0,0,0,0.05) !important; box-shadow: 0 4px 15px rgba(0,0,0,0.02) !important; transition: box-shadow 0.2s ease; }
    .dashboard-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.05) !important; }
    .dashboard-card .card-header { background-color: #fff !important; border-bottom: 1px solid rgba(0,0,0,0.04) !important; padding: 1.1rem 1.4rem; font-weight: 700; color: #0f172a; border-radius: 16px 16px 0 0 !important; }
    
    body.dark-theme .dashboard-card {
        background-color: #111827 !important;
        border-color: #1e293b !important;
    }
    body.dark-theme .dashboard-card .card-header {
        background-color: #1e293b !important;
        border-bottom: 1px solid #334155 !important;
        color: #f1f5f9 !important;
    }
    
    .transition-hover { transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08) !important; z-index: 5; }

    .role-badge {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
        padding: 4px 12px; border-radius: 20px;
    }

    /* Pulse Dot Animations for Live Dashboard feel */
    .pulse-dot-green {
        display: inline-block; width: 8px; height: 8px; background-color: #4ade80; border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7); animation: pulse-green 2s infinite;
    }
    .pulse-dot-red {
        display: inline-block; width: 8px; height: 8px; background-color: #f87171; border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(248, 113, 113, 0.7); animation: pulse-red 2s infinite;
    }
    .pulse-dot-yellow {
        display: inline-block; width: 8px; height: 8px; background-color: #fbbf24; border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7); animation: pulse-yellow 2s infinite;
    }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(74, 222, 128, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
    }
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(248, 113, 113, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(248, 113, 113, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(248, 113, 113, 0); }
    }
    @keyframes pulse-yellow {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(251, 191, 36, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }
    .welcome-banner-card {
        background-color: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.06) !important;
    }
    body.dark-theme .welcome-banner-card {
        background-color: #111827 !important;
        border-color: #1e293b !important;
    }
    
    /* Soft badge colors */
    .badge-soft-success {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #10b981 !important;
        border: 1px solid rgba(16, 185, 129, 0.25) !important;
    }
    .badge-soft-warning {
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #d97706 !important;
        border: 1px solid rgba(245, 158, 11, 0.25) !important;
    }
    .badge-soft-danger {
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #ef4444 !important;
        border: 1px solid rgba(239, 68, 68, 0.25) !important;
    }
    .badge-soft-secondary {
        background-color: rgba(100, 116, 139, 0.12) !important;
        color: #64748b !important;
        border: 1px solid rgba(100, 116, 139, 0.25) !important;
    }
    .hover-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-color: rgba(99, 102, 241, 0.35) !important;
    }
    .hover-card:hover .transition-img {
        transform: scale(1.06);
    }
    
    /* Theme adaptive print button */
    .btn-cetak-laporan {
        background-color: transparent !important;
        color: #6366f1 !important;
        border: 1px solid #6366f1 !important;
        transition: all 0.25s ease;
        font-weight: 600;
    }
    .btn-cetak-laporan:hover {
        background-color: #6366f1 !important;
        color: #ffffff !important;
    }
    body.dark-theme .btn-cetak-laporan {
        color: #818cf8 !important;
        border-color: #818cf8 !important;
    }
    body.dark-theme .btn-cetak-laporan:hover {
        background-color: #818cf8 !important;
        color: #0f172a !important;
    }
    
    /* SPA-style filtering animations */
    .vehicle-card-wrapper {
        transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        transform: scale(1);
        opacity: 1;
    }
    
    /* Sparkline containers on metric cards */
    .sparkline-container {
        position: relative;
        width: 100%;
        height: 40px;
        margin-top: 15px;
        overflow: hidden;
    }
    .sparkline-canvas {
        width: 100% !important;
        height: 100% !important;
    }
    
    @media print {
        /* General layout overrides */
        header, .sidebar, .sidebar-left, .navbar, .btn, .input-group, #statusFilterButtons, .welcome-banner-card .d-flex.gap-2, .card-grad-1::after, .card-grad-2::after, .card-grad-3::after, .card-grad-4::after, .card-grad-1::before, .card-grad-2::before, .card-grad-3::before, .card-grad-4::before {
            display: none !important;
        }
        body, .content, .main-content {
            background: #ffffff !important;
            color: #0f172a !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        
        /* Set columns to be side-by-side instead of stacked full page in print */
        .row {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
        }
        .col-md-3 {
            flex: 0 0 25% !important;
            max-width: 25% !important;
        }
        .col-md-4 {
            flex: 0 0 33.333% !important;
            max-width: 33.333% !important;
        }
        .col-md-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .col-lg-4 {
            flex: 0 0 33.333% !important;
            max-width: 33.333% !important;
        }
        .col-lg-8 {
            flex: 0 0 66.666% !important;
            max-width: 66.666% !important;
        }

        /* Card styles overrides for print */
        .card {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            break-inside: avoid !important;
            background: #ffffff !important;
            color: #0f172a !important;
        }
        
        /* Override gradient cards to high-contrast monochrome with colored indicator borders */
        .card-grad-1, .card-grad-2, .card-grad-3, .card-grad-4 {
            background: #ffffff !important;
            color: #0f172a !important;
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
        }
        
        .card-grad-1 { border-top: 4px solid #4f46e5 !important; }
        .card-grad-2 { border-top: 4px solid #e11d48 !important; }
        .card-grad-3 { border-top: 4px solid #d97706 !important; }
        .card-grad-4 { border-top: 4px solid #0f766e !important; }
        
        /* Make all text black/dark gray inside gradient cards */
        .card-grad-title {
            color: #475569 !important;
        }
        .card-grad-1 h2, .card-grad-2 h2, .card-grad-3 h2, .card-grad-4 h2 {
            color: #0f172a !important;
        }
        .card-grad-1 .text-white-50, .card-grad-2 .text-white-50, .card-grad-3 .text-white-50, .card-grad-4 .text-white-50 {
            color: #64748b !important;
        }
        .card-grad-1 .fw-bold.text-white, .card-grad-2 .fw-bold.text-white, .card-grad-3 .fw-bold.text-white, .card-grad-4 .fw-bold.text-white {
            color: #0f172a !important;
        }
        .card-grad-1 .badge, .card-grad-2 .badge, .card-grad-3 .badge, .card-grad-4 .badge {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
        }
        
        /* Icon styles inside print cards */
        .card-grad-icon {
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
        }
        
        /* Progress bars in print */
        .progress {
            background-color: #e2e8f0 !important;
            border: 1px solid #cbd5e1 !important;
        }
        .progress-bar {
            background-color: #475569 !important;
        }
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .custom-vehicle-marker-dashboard {
        position: relative;
    }
    .custom-vehicle-marker-dashboard .marker-pin {
        width: 30px;
        height: 30px;
        border-radius: 50% 50% 50% 0;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%;
        top: 50%;
        margin: -15px 0 0 -15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }
    .custom-vehicle-marker-dashboard .marker-pin i {
        transform: rotate(45deg);
        color: #ffffff;
        font-size: 0.95rem;
    }
    
    .custom-vehicle-marker-dashboard.marker-ready .marker-pin {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .custom-vehicle-marker-dashboard.marker-servis .marker-pin {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    .custom-vehicle-marker-dashboard.marker-warning .marker-pin {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .custom-vehicle-marker-dashboard .marker-pulse {
        border-radius: 50%;
        height: 30px;
        width: 30px;
        position: absolute;
        left: 50%;
        top: 50%;
        margin: -15px 0 0 -15px;
        transform: rotateX(55deg);
        z-index: -2;
        animation: marker-pulsate 1.6s ease-out infinite;
        opacity: 0;
    }
    
    .custom-vehicle-marker-dashboard.marker-ready .marker-pulse {
        background: rgba(16, 185, 129, 0.4);
    }
    .custom-vehicle-marker-dashboard.marker-servis .marker-pulse {
        background: rgba(245, 158, 11, 0.4);
    }
    .custom-vehicle-marker-dashboard.marker-warning .marker-pulse {
        background: rgba(239, 68, 68, 0.4);
    }
    
    @keyframes marker-pulsate {
        0% { transform: scale(0.1, 0.1); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: scale(1.8, 1.8); opacity: 0; }
    }
</style>
@endpush

{{-- HEADER WELCOME --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 p-3 rounded-4 shadow-sm welcome-banner-card">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:1.2rem;">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-extrabold mb-0 text-dark">Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
                @if(auth()->user()->role === 'superadmin')
                    <span class="role-badge bg-dark text-white">Super Admin</span>
                @elseif(auth()->user()->role === 'admin')
                    <span class="role-badge bg-primary text-white">Admin Fleet</span>
                @elseif(auth()->user()->role === 'teknisi')
                    <span class="role-badge bg-info text-white">Mekanik / Teknisi</span>
                @elseif(auth()->user()->role === 'pimpinan')
                    <span class="role-badge bg-warning text-dark">Pimpinan</span>
                @else
                    <span class="role-badge bg-success text-white">Driver / Pengemudi</span>
                @endif
            </div>
            <p class="text-muted mb-0" style="font-size:0.875rem;">Berikut ringkasan status operasional &amp; pemeliharaan armada Anda hari ini.</p>
            <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                <span class="badge bg-success-subtle text-success border border-success-subtle d-flex align-items-center gap-1.5 py-1 px-2" style="border-radius: 6px; font-size: 0.72rem;">
                    <span class="pulse-dot-green" style="width: 6px; height: 6px; box-shadow: none; animation: pulse-green 2s infinite;"></span>
                    Sistem Normal
                </span>
                <span id="liveClock" class="text-muted fw-semibold font-monospace" style="font-size: 0.75rem;"></span>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center">
        @if(auth()->user()->role === 'superadmin')
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Tambah Armada Kendaraan Baru">
                <i class="bi bi-plus-circle-fill"></i> Tambah Kendaraan
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-dark px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Kelola Akun & Hak Akses Pengguna">
                <i class="bi bi-people-fill"></i> Kelola User
            </a>
        @elseif(auth()->user()->role === 'admin')
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Tambah Armada Kendaraan Baru">
                <i class="bi bi-plus-circle-fill"></i> Tambah Kendaraan
            </a>
            <a href="{{ route('checklist.create') }}" class="btn btn-outline-primary px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Input Checklist Fisik Kendaraan">
                <i class="bi bi-clipboard-plus-fill"></i> Input Checklist
            </a>
        @elseif(auth()->user()->role === 'teknisi')
            <a href="{{ route('checklist.create') }}" class="btn btn-primary px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Input Checklist Fisik Kendaraan">
                <i class="bi bi-clipboard-plus-fill"></i> Input Checklist
            </a>
            <a href="{{ route('expenses.create') }}" class="btn btn-outline-secondary px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Catat Pengeluaran Operasional / BBM">
                <i class="bi bi-wallet2"></i> Catat Biaya
            </a>
        @elseif(auth()->user()->role === 'pimpinan')
            <a href="{{ route('expenses.index') }}" class="btn btn-warning text-dark px-3 py-2 d-flex align-items-center gap-1.5 fw-bold" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Buka Panel Persetujuan Anggaran">
                <i class="bi bi-shield-check"></i> Persetujuan Biaya
            </a>
        @else
            <a href="{{ route('complaints.create') }}" class="btn btn-primary px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Laporkan Masalah / Kerusakan Kendaraan">
                <i class="bi bi-megaphone-fill"></i> Lapor Keluhan
            </a>
        @endif
        <button onclick="window.print()" class="btn btn-cetak-laporan px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius: 10px;" data-bs-toggle="tooltip" data-bs-title="Cetak Halaman ini Sebagai Laporan Bisnis">
            <i class="bi bi-printer-fill"></i> Cetak Laporan
        </button>
    </div>
</div>

{{-- PUSAT PENGUMUMAN & SYSTEM ALERTS PANEL --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm welcome-banner-card overflow-hidden" style="border-left: 5px solid #4f46e5 !important;">
            <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-indigo-subtle text-indigo d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                        <i class="bi bi-megaphone-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark" style="font-size:0.875rem;">Pusat Pemberitahuan Sistem</h6>
                        <span class="text-muted" style="font-size:0.75rem;">
                            @php
                                $totalKuning = $vehicles->filter(fn($v) => $v->status_kir === 'kuning')->count();
                                $totalMerah = $vehicles->filter(fn($v) => $v->status_kir === 'merah')->count();
                            @endphp
                            • KIR Kendaraan: Terdapat <strong>{{ $totalMerah }}</strong> unit lewat tempo (Merah), dan <strong>{{ $totalKuning }}</strong> unit mendekati jatuh tempo (Kuning). Mohon segera jadwalkan pengurusan dokumen.
                        </span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1" onclick="this.closest('.row').remove()" style="font-size: 0.7rem; border-radius: 6px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PERINGATAN SERVIS TERLATE LEWAT TEMPO --}}
@if(isset($perluServis) && $perluServis->count() > 0)
<div class="card mb-4 border-0 shadow-sm overflow-hidden" style="background-color: #fff5f5; border-left: 5px solid #ef4444 !important;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-circle bg-danger text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-danger mb-0 fs-6">Peringatan: {{ $perluServis->count() }} Kendaraan Melewati Jadwal Servis Berkala</h6>
                    <small class="text-muted">Armada berikut memerlukan tindak lanjut teknisi segera demi keselamatan operasional.</small>
                </div>
            </div>
            <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-danger px-3 py-2 rounded-3 fw-bold">Kelola Semua Kendaraan</a>
        </div>
        <div class="row g-3">
            @foreach($perluServis->take(3) as $v)
            @php
                $alasan = [];
                
                // 1. Cek servis rutin
                $lastService = $v->expenses()
                    ->where('jenis_pengeluaran', 'like', '%Servis%')
                    ->latest('tanggal')->first();
                if ($lastService) {
                    $nextServiceDate = \Carbon\Carbon::parse($lastService->tanggal)->addMonths(3);
                    if (now()->greaterThan($nextServiceDate)) {
                        $alasan[] = 'Servis Terlewat';
                    }
                }
                
                // 2. Cek keluhan terlambat
                $activeComplaints = \App\Models\Complaint::where('vehicle_id', $v->id)
                    ->where('status', '!=', 'Selesai')->get();
                foreach ($activeComplaints as $c) {
                    $targetDate = \Carbon\Carbon::parse($c->tanggal)->addDays(2);
                    if (now()->startOfDay()->greaterThan($targetDate->startOfDay())) {
                        $alasan[] = 'Keluhan: ' . \Illuminate\Support\Str::limit($c->keluhan, 20);
                        break;
                    }
                }
                
                $textAlasan = count($alasan) > 0 ? implode(', ', $alasan) : 'Perlu Pengecekan';
            @endphp
            <div class="col-md-4">
                <div class="p-3 rounded-3 bg-white border border-danger-subtle d-flex justify-content-between align-items-center shadow-xs h-100">
                    <div>
                        <span class="fw-bold font-monospace text-dark d-block"><i class="bi bi-car-front-fill me-1 text-danger"></i>{{ $v->plat_nomor }}</span>
                        <small class="text-muted d-block" style="font-size:0.75rem;">{{ $v->merek }} {{ $v->tipe }}</small>
                        <span class="badge bg-danger-subtle text-danger px-2 py-1 mt-2 d-inline-block fw-bold" style="font-size:0.68rem; border-radius:6px;">{{ $textAlasan }}</span>
                    </div>
                    <a href="{{ route('vehicles.show', $v->id) }}" class="btn btn-xs btn-outline-danger px-2.5 py-1 align-self-start" style="font-size:0.75rem; border-radius: 6px;">Detail</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
{{-- STATISTIK KARTU UTAMA (Multi-Role & Quick Stats Support) --}}
@if (in_array(auth()->user()->role, ['superadmin', 'admin', 'pimpinan']))
<div class="row g-3 mb-4">
    <!-- Card 1: Total Kendaraan -->
    <div class="col-md-3">
        <div class="card dashboard-card card-grad-1 h-100 transition-hover">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="card-grad-title d-flex align-items-center gap-2">
                            <span class="pulse-dot-green"></span>
                            <span>Total Kendaraan</span>
                        </div>
                        <div class="card-grad-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $totalKendaraan }}</h2>
                        <span class="badge font-normal text-white" style="background: rgba(255, 255, 255, 0.22) !important; font-size:0.75rem;">Unit Aktif</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">Rasio Siap Operasional</span>
                        <span class="fw-bold text-white">{{ $totalKendaraan > 0 ? round(($selesaiServis / $totalKendaraan) * 100) : 100 }}%</span>
                    </div>
                    <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $totalKendaraan > 0 ? ($selesaiServis / $totalKendaraan) * 100 : 100 }}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="sparkline-container">
                        <canvas id="sparklineTotal" class="sparkline-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Card 2: Keluhan Hari Ini -->
    <div class="col-md-3">
        <div class="card dashboard-card card-grad-2 h-100 transition-hover">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="card-grad-title d-flex align-items-center gap-2">
                            <span class="{{ $keluhanHariIni > 0 ? 'pulse-dot-red' : 'pulse-dot-green' }}"></span>
                            <span>Keluhan Hari Ini</span>
                        </div>
                        <div class="card-grad-icon"><i class="bi bi-exclamation-octagon-fill"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $keluhanHariIni }}</h2>
                        <span class="badge bg-danger text-white border border-danger-subtle fw-bold" style="font-size:0.68rem; letter-spacing: 0.5px; text-transform: uppercase; padding: 3px 8px; border-radius: 12px; background: rgba(220,38,38,0.7) !important;">Baru</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">Tindak Lanjut Segera</span>
                        <span class="fw-bold text-white">Butuh Respon</span>
                    </div>
                    <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="sparkline-container">
                        <canvas id="sparklineKeluhan" class="sparkline-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Sedang Servis -->
    <div class="col-md-3">
        <div class="card dashboard-card card-grad-3 h-100 transition-hover">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="card-grad-title d-flex align-items-center gap-2">
                            <span class="{{ $sedangServis > 0 ? 'pulse-dot-yellow' : 'pulse-dot-green' }}"></span>
                            <span>Sedang Servis</span>
                        </div>
                        <div class="card-grad-icon"><i class="bi bi-wrench-adjustable-caps"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $sedangServis }}</h2>
                        <span class="badge bg-warning text-dark border border-warning-subtle fw-bold" style="font-size:0.68rem; letter-spacing: 0.5px; text-transform: uppercase; padding: 3px 8px; border-radius: 12px; background: rgba(245,158,11,0.7) !important;">Bengkel</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">Proses Perbaikan</span>
                        <span class="fw-bold text-white">{{ $totalKendaraan > 0 ? round(($sedangServis / $totalKendaraan) * 100) : 0 }}%</span>
                    </div>
                    <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $totalKendaraan > 0 ? round(($sedangServis / $totalKendaraan) * 100) : 0 }}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="sparkline-container">
                        <canvas id="sparklineServis" class="sparkline-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Card 4: Selesai Servis/Siap Pakai -->
    <div class="col-md-3">
        <div class="card dashboard-card card-grad-4 h-100 transition-hover">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="card-grad-title d-flex align-items-center gap-2">
                            <span class="pulse-dot-green"></span>
                            <span>Selesai / Siap Pakai</span>
                        </div>
                        <div class="card-grad-icon"><i class="bi bi-check2-circle"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $selesaiServis }}</h2>
                        <span class="badge font-normal text-white" style="background: rgba(255, 255, 255, 0.22) !important; font-size:0.75rem;">Siap Jalan</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">Rasio Kesiapan Armada</span>
                        <span class="fw-bold text-white">{{ $totalKendaraan > 0 ? round(($selesaiServis / $totalKendaraan) * 100) : 100 }}%</span>
                    </div>
                    <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                        <div class="progress-bar bg-white" role="progressbar" style="width: {{ $totalKendaraan > 0 ? round(($selesaiServis / $totalKendaraan) * 100) : 100 }}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="sparkline-container">
                        <canvas id="sparklineSiap" class="sparkline-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Keuangan & Kepatuhan Dokumen (Horizontal Minimalis) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid rgba(0,0,0,0.05) !important;">
        <div class="card-body py-3 px-4">
            <div class="row align-items-center text-center text-md-start g-3">
                
                <!-- Col 1: Biaya Bulan Ini -->
                <div class="col-md-3 border-end">
                    <div class="d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-size: 1.2rem;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Biaya Bulan Ini</small>
                            <span class="fw-extrabold text-dark" style="font-size: 1.1rem;">Rp {{ number_format($totalBiayaBulanIni, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Col 2: KIR Lewat Tempo -->
                <div class="col-md-3 border-end">
                    <div class="d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                        <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-size: 1.2rem;">
                            <i class="bi bi-calendar-x-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">KIR Lewat Tempo</small>
                            <span class="fw-extrabold text-danger" style="font-size: 1.1rem;">{{ $kendaraanBermasalah }} Unit</span>
                        </div>
                    </div>
                </div>

                <!-- Col 3: Mendekati Tempo -->
                <div class="col-md-3 border-end">
                    <div class="d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                        <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-size: 1.2rem;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Mendekati Tempo</small>
                            <span class="fw-extrabold text-warning" style="font-size: 1.1rem;">{{ $kendaraanMendekatiJatuhTempo }} Unit</span>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Peringatan Servis -->
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                        <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-size: 1.2rem;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Servis Lewat Jadwal</small>
                            <span class="fw-extrabold text-info" style="font-size: 1.1rem;">{{ $perluServis->count() }} Unit</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@else
    @php
        $pctBermasalah = $totalKendaraan > 0 ? round(($kendaraanBermasalah / $totalKendaraan) * 100) : 0;
        $pctMendekati = $totalKendaraan > 0 ? round(($kendaraanMendekatiJatuhTempo / $totalKendaraan) * 100) : 0;
        $pctAman = 100 - $pctBermasalah - $pctMendekati;
        
        $myReadyPct = 100;
        $myComplaintResolvedPct = 100;
        if (auth()->user()->role === 'user') {
            $myReadyCount = $kendaraanSaya->whereIn('status', ['Siap Pakai', 'Selesai'])->count();
            $myReadyPct = $kendaraanSaya->count() > 0 ? round(($myReadyCount / $kendaraanSaya->count()) * 100) : 100;
            $myComplaintResolvedPct = $totalKeluhanSaya > 0 ? round(($totalKeluhanSelesaiSaya / $totalKeluhanSaya) * 100) : 100;
        }
    @endphp
    <div class="row g-3 mb-4">
        <!-- Card 1 -->
        <div class="col-md-3">
            <div class="card dashboard-card card-grad-1 h-100 transition-hover">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (auth()->user()->role === 'user')
                                <div class="card-grad-title">Armada Penugasan</div>
                                <div class="card-grad-icon"><i class="bi bi-car-front-fill"></i></div>
                            @else
                                <div class="card-grad-title">Total Armada</div>
                                <div class="card-grad-icon">
                                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                                        <path d="M 2.5,5.5 H 12.5 V 7.5 H 15.5 L 18.5,10.5 V 13.5 H 2.5 Z" fill="#ffffff" stroke="#1e1b4b" stroke-width="1.2" stroke-linejoin="round" />
                                        <path d="M 13.5,8.5 H 15.2 L 16.8,10.5 H 13.5 Z" fill="#1e1b4b" />
                                        <circle cx="6" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                                        <circle cx="15" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            @if (auth()->user()->role === 'user')
                                <h2 class="mb-0 fw-extrabold display-6 text-white">{{ count($kendaraanSaya) }}</h2>
                                <span class="badge font-normal text-white" style="background: rgba(255,255,255,0.22) !important; font-size:0.75rem;">Unit Anda</span>
                            @else
                                <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $totalKendaraan }}</h2>
                                <span class="badge font-normal text-white" style="background: rgba(255,255,255,0.22) !important; font-size:0.75rem;">Unit Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                            <span class="text-white-50">Kondisi Siap Jalan</span>
                            <span class="fw-bold text-white">{{ auth()->user()->role === 'user' ? $myReadyPct : ($totalKendaraan > 0 ? round(($selesaiServis / $totalKendaraan) * 100) : 100) }}%</span>
                        </div>
                        <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ auth()->user()->role === 'user' ? $myReadyPct : ($totalKendaraan > 0 ? round(($selesaiServis / $totalKendaraan) * 100) : 100) }}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-3">
            <div class="card dashboard-card card-grad-2 h-100 transition-hover">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (auth()->user()->role === 'user')
                                <div class="card-grad-title">Laporan Keluhan</div>
                                <div class="card-grad-icon"><i class="bi bi-chat-left-text-fill"></i></div>
                            @else
                                <div class="card-grad-title">KIR Lewat Tempo</div>
                                <div class="card-grad-icon"><i class="bi bi-exclamation-octagon-fill"></i></div>
                            @endif
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            @if (auth()->user()->role === 'user')
                                <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $totalKeluhanSaya ?? 0 }}</h2>
                                <span class="badge font-normal text-white" style="background: rgba(255,255,255,0.22) !important; font-size:0.75rem;">Total Laporan</span>
                            @else
                                <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $kendaraanBermasalah }}</h2>
                                <span class="badge bg-danger text-white border border-danger-subtle fw-bold" style="font-size:0.68rem; letter-spacing: 0.5px; text-transform: uppercase; padding: 3px 8px; border-radius: 12px; background: rgba(220,38,38,0.7) !important;">Urgent</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                            <span class="text-white-50">{{ auth()->user()->role === 'user' ? 'Penyelesaian Keluhan' : 'Rasio Keterlambatan' }}</span>
                            <span class="fw-bold text-white">{{ auth()->user()->role === 'user' ? $myComplaintResolvedPct : $pctBermasalah }}%</span>
                        </div>
                        <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ auth()->user()->role === 'user' ? $myComplaintResolvedPct : $pctBermasalah }}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-3">
            <div class="card dashboard-card card-grad-3 h-100 transition-hover">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (auth()->user()->role === 'user')
                                <div class="card-grad-title">Keluhan Selesai</div>
                                <div class="card-grad-icon"><i class="bi bi-shield-fill-check"></i></div>
                            @else
                                <div class="card-grad-title">Mendekati Tempo</div>
                                <div class="card-grad-icon"><i class="bi bi-hourglass-split"></i></div>
                            @endif
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between">
                            @if (auth()->user()->role === 'user')
                                <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $totalKeluhanSelesaiSaya ?? 0 }}</h2>
                                <span class="badge font-normal text-white" style="background: rgba(255,255,255,0.22) !important; font-size:0.75rem;">Selesai Servis</span>
                            @else
                                <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $kendaraanMendekatiJatuhTempo }}</h2>
                                <span class="badge bg-warning text-dark border border-warning-subtle fw-bold" style="font-size:0.68rem; letter-spacing: 0.5px; text-transform: uppercase; padding: 3px 8px; border-radius: 12px; background: rgba(245,158,11,0.7) !important;">Caution</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                            <span class="text-white-50">{{ auth()->user()->role === 'user' ? 'Tingkat Kelancaran' : 'Rasio Mendekati Jatuh Tempo' }}</span>
                            <span class="fw-bold text-white">{{ auth()->user()->role === 'user' ? 100 : $pctMendekati }}%</span>
                        </div>
                        <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ auth()->user()->role === 'user' ? 100 : $pctMendekati }}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-3">
            <div class="card dashboard-card card-grad-4 h-100 transition-hover">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (auth()->user()->role === 'user')
                                <div class="card-grad-title">Status KIR Utama</div>
                                <div class="card-grad-icon"><i class="bi bi-file-earmark-check-fill"></i></div>
                            @else
                                <div class="card-grad-title">Biaya Bulan Ini</div>
                                <div class="card-grad-icon"><i class="bi bi-cash-stack"></i></div>
                            @endif
                        </div>
                        <div class="mt-2">
                            @if (auth()->user()->role === 'user')
                                @php
                                    $statusKirSaya = 'Aman';
                                    if ($kendaraanSaya->count() > 0) {
                                        $vSaya = $kendaraanSaya->first();
                                        if ($vSaya->status_kir === 'merah') {
                                            $statusKirSaya = 'Lewat Tempo';
                                        } elseif ($vSaya->status_kir === 'kuning') {
                                            $statusKirSaya = 'Mendekati';
                                        }
                                    }
                                @endphp
                                <h4 class="mb-0 fw-extrabold text-white">{{ $statusKirSaya }}</h4>
                                <span class="text-white-50" style="font-size:0.75rem;">Dokumen KIR Mobil Anda</span>
                            @else
                                <h4 class="mb-0 fw-extrabold text-white">Rp {{ number_format($totalBiayaBulanIni, 0, ',', '.') }}</h4>
                                <span class="text-white-50" style="font-size:0.75rem;">Rekap {{ now()->translatedFormat('F Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        @if (auth()->user()->role === 'user')
                            @php
                                $valText = 'Valid';
                                $valPct = 100;
                                if ($statusKirSaya === 'Lewat Tempo') {
                                    $valText = 'Tidak Valid';
                                    $valPct = 0;
                                } elseif ($statusKirSaya === 'Mendekati') {
                                    $valText = 'Mendekati Expired';
                                    $valPct = 50;
                                }
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                                <span class="text-white-50">Kelayakan KIR</span>
                                <span class="fw-bold text-white">{{ $valText }}</span>
                            </div>
                            <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                                <div class="progress-bar bg-white" role="progressbar" style="width: {{ $valPct }}%;" aria-valuenow="{{ $valPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                                <span class="text-white-50">Status Anggaran</span>
                                <span class="fw-bold text-white">Aman</span>
                            </div>
                            <div class="progress" style="height: 4px; border-radius: 10px; background: rgba(255, 255, 255, 0.22);">
                                <div class="progress-bar bg-white" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif   </div>

{{-- WIDGET PETA PEMANTAUAN ARMADA REAL-TIME --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary-subtle text-primary rounded p-1.5"><i class="bi bi-map-fill fs-5"></i></div>
                    <div>
                        <span class="fw-bold d-block">Peta Pemantauan Posisi &amp; Status Armada</span>
                        <small class="text-muted" style="font-size:0.75rem;">Lokasi real-time dan indikator kondisi armada kendaraan</small>
                    </div>
                </div>
                <span class="badge bg-primary-subtle text-primary border fw-bold d-inline-flex align-items-center gap-1.5 py-1 px-2.5" style="border-radius: 8px;">
                    <span class="pulse-dot-green" style="width:6px; height:6px; box-shadow:none; animation: pulse-green 2s infinite;"></span>
                    Live GPS
                </span>
            </div>
            <div class="card-body p-0" style="position: relative; overflow: hidden; border-radius: 0 0 16px 16px;">
                <div id="fleetMap" style="height: 380px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

{{-- WIDGET AGENDA KALENDER & EXPORTER LAPORAN --}}
<div class="row g-4 mb-4">
    <!-- Col 1: Agenda Kalender Servis & KIR -->
    <div class="col-lg-8">
        <div class="card dashboard-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary-subtle text-primary rounded p-1.5"><i class="bi bi-calendar3 fs-5"></i></div>
                    <div>
                        <span class="fw-bold d-block">Agenda Pemeliharaan &amp; KIR</span>
                        <small class="text-muted" style="font-size:0.75rem;">Jadwal jatuh tempo dokumen &amp; rencana servis bulan ini</small>
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-xs btn-outline-secondary px-2.5 py-1" id="btnPrevMonth" style="font-size:0.72rem; border-radius:6px;"><i class="bi bi-chevron-left"></i></button>
                    <span class="fw-bold px-2 align-self-center text-dark" id="calendarMonthTitle" style="font-size:0.82rem;">Agustus 2026</span>
                    <button class="btn btn-xs btn-outline-secondary px-2.5 py-1" id="btnNextMonth" style="font-size:0.72rem; border-radius:6px;"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="calendar-container" id="calendarContainer">
                    <!-- Day Headers -->
                    <div class="calendar-day-header">Min</div>
                    <div class="calendar-day-header">Sen</div>
                    <div class="calendar-day-header">Sel</div>
                    <div class="calendar-day-header">Rab</div>
                    <div class="calendar-day-header">Kam</div>
                    <div class="calendar-day-header">Jum</div>
                    <div class="calendar-day-header">Sab</div>
                    <!-- Cells will be generated dynamically by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Col 2: Exporter Laporan Cepat -->
    <div class="col-lg-4">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-indigo-subtle text-indigo rounded p-1.5" style="background:#e0e7ff; color:#4f46e5;"><i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i></div>
                    <div>
                        <span class="fw-bold d-block">Pusat Ekspor Laporan</span>
                        <small class="text-muted" style="font-size:0.75rem;">Download ringkasan data format CSV</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <label class="form-label fw-bold text-dark mb-1" style="font-size:0.8rem;">Pilih Kategori Laporan</label>
                    <select class="form-select mb-3 py-2" id="exporterSelectType" style="font-size: 0.85rem; border-radius: 8px;">
                        <option value="expenses">Laporan Biaya &amp; Pengeluaran</option>
                        <option value="complaints">Laporan Keluhan Kendaraan</option>
                    </select>
                    <p class="text-muted mb-3" style="font-size:0.78rem;">Ekspor data ini langsung menghasilkan berkas spreadsheet (.csv) siap pakai tanpa menunggu proses server.</p>
                </div>
                <button type="button" class="btn btn-primary w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" id="btnExportCSV" style="border-radius:10px;">
                    <i class="bi bi-download"></i> Unduh File CSV
                </button>
            </div>
        </div>
    </div>
</div>

{{-- BAGIAN GRAFIK & ANALITIK --}}
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card dashboard-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary-subtle text-primary rounded p-1.5"><i class="bi bi-pie-chart-fill fs-5"></i></div>
                    <span class="fw-bold">Distribusi Status Armada</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-4">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>
    </div>
    @if (isset($trenBiayaChart))
    <div class="col-lg-8">
        <div class="card dashboard-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-indigo-subtle text-indigo rounded p-1.5" style="background:#e0e7ff; color:#4f46e5;"><i class="bi bi-graph-up-arrow fs-5"></i></div>
                    <span class="fw-bold">Tren Pengeluaran Biaya (6 Bulan Terakhir)</span>
                </div>
                <span class="badge bg-light text-secondary border">Agregat Bulanan</span>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="chartTren"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if (isset($borosChart) && count($borosChart['labels']) > 0)
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="bg-warning-subtle text-warning-emphasis rounded p-1.5"><i class="bi bi-bar-chart-line-fill fs-5"></i></div>
                <span class="fw-bold">Top 5 Kendaraan Dengan Biaya Perawatan/BBM Tertinggi</span>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="chartBoros"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- MODUL KHUSUS ADMIN & PIMPINAN (Persetujuan & Keluhan Baru) --}}
@if (in_array(auth()->user()->role, ['superadmin', 'admin', 'pimpinan']))
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning-subtle text-warning-emphasis rounded p-1.5"><i class="bi bi-clock-history fs-5"></i></div>
                    <span class="fw-bold">Pengajuan Anggaran Perlu Persetujuan</span>
                </div>
                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold">{{ $menungguPersetujuan->count() }} Menunggu</span>
            </div>
            <div class="card-body p-4">
                @forelse ($menungguPersetujuan as $e)
                <div class="p-3 rounded-3 mb-2 bg-light border d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary font-monospace">{{ $e->vehicle->plat_nomor ?? 'N/A' }}</span>
                            <span class="fw-bold text-dark" style="font-size:0.875rem;">{{ $e->jenis_pengeluaran }}</span>
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size:0.8rem;">{{ $e->keterangan ?? 'Tanpa keterangan' }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-extrabold text-primary fs-6 mb-1">Rp {{ number_format($e->jumlah_biaya, 0, ',', '.') }}</div>
                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="d-flex gap-1 justify-content-end">
                            @csrf @method('PUT')
                            <button type="submit" name="status_approval" value="Disetujui" class="btn btn-sm btn-success px-2.5 py-1" style="font-size:0.75rem; border-radius:6px;">Setujui</button>
                            <button type="submit" name="status_approval" value="Ditolak" class="btn btn-sm btn-outline-danger px-2.5 py-1" style="font-size:0.75rem; border-radius:6px;">Tolak</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:52px; height:52px;">
                        <i class="bi bi-check-all fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Semua Anggaran Disetujui</h6>
                    <small class="text-muted">Tidak ada klaim biaya yang tertunda.</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-danger-subtle text-danger rounded p-1.5"><i class="bi bi-exclamation-square-fill fs-5"></i></div>
                    <span class="fw-bold">Laporan Keluhan Baru Dari Pengemudi</span>
                </div>
                <a href="{{ route('complaints.index') }}" class="btn btn-xs btn-outline-primary px-2.5 py-1" style="font-size:0.78rem;">Lihat Semua</a>
            </div>
            <div class="card-body p-4">
                @forelse ($keluhanBaru as $k)
                <div class="p-3 rounded-3 mb-2 bg-light border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-danger-subtle text-danger font-monospace">{{ $k->vehicle->plat_nomor ?? 'N/A' }}</span>
                        <small class="text-muted" style="font-size:0.75rem;"><i class="bi bi-person me-1"></i>{{ $k->user->name }}</small>
                    </div>
                    <div class="text-dark" style="font-size:0.85rem; line-height:1.4;">{{ Str::limit($k->keluhan, 90) }}</div>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:52px; height:52px;">
                        <i class="bi bi-shield-check fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Armada Bebas Masalah</h6>
                    <small class="text-muted">Belum ada keluhan baru dari pengemudi hari ini.</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endif

{{-- MODUL KHUSUS TEKNISI --}}
@if (auth()->user()->role === 'teknisi')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card dashboard-card h-100">
            <div class="card-body text-center py-4 p-4">
                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width:68px; height:68px;">
                    <i class="bi bi-clipboard-check-fill fs-2"></i>
                </div>
                <h2 class="fw-extrabold text-dark display-5 mb-0">{{ $checklistHariIni }}</h2>
                <p class="text-muted mb-3" style="font-size:0.875rem;">Checklist selesai diisi hari ini</p>
                <a href="{{ route('checklist.create') }}" class="btn btn-primary w-100 py-2.5 fw-bold" style="border-radius: 10px;">
                    <i class="bi bi-plus-circle-fill me-1"></i> Input Checklist Baru
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card dashboard-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning-subtle text-warning-emphasis rounded p-1.5"><i class="bi bi-tools fs-5"></i></div>
                    <span class="fw-bold">Keluhan Perlu Tindakan Perbaikan</span>
                </div>
                <a href="{{ route('complaints.index') }}" class="btn btn-xs btn-outline-primary px-2 py-1" style="font-size:0.78rem;">Kelola Semua</a>
            </div>
            <div class="card-body p-4">
                @forelse ($keluhanPerluDitangani as $k)
                <div class="p-3 rounded-3 mb-2 bg-light border d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-subtle text-primary font-monospace">{{ $k->vehicle->plat_nomor }}</span>
                            <span class="badge {{ $k->status === 'Baru' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $k->status }}</span>
                        </div>
                        <small class="text-dark d-block">{{ Str::limit($k->keluhan, 80) }}</small>
                    </div>
                    <a href="{{ route('complaints.index') }}" class="btn btn-sm btn-outline-primary px-3 py-1" style="border-radius:6px; font-size:0.78rem;">Proses</a>
                </div>
                @empty
                <div class="text-center py-4">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:48px; height:48px;">
                        <i class="bi bi-check-lg fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Semua Keluhan Ditangani</h6>
                    <small class="text-muted">Tidak ada pekerjaan servis tertunda.</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endif

{{-- MODUL KHUSUS USER / DRIVER --}}
@if (auth()->user()->role === 'user')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-car-front-fill text-primary me-2"></i>Armada Utama Penugasan Anda</h6>
            <a href="{{ route('complaints.create') }}" class="btn btn-sm btn-outline-primary px-3 py-1" style="border-radius:8px;"><i class="bi bi-megaphone me-1"></i>Lapor Kendala</a>
        </div>
    </div>
    @forelse ($kendaraanSaya as $v)
    <div class="col-md-6">
        <div class="p-3 rounded-4 bg-white border shadow-xs d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $v->foto_url }}" alt="{{ $v->plat_nomor }}" class="rounded-3 border object-fit-cover shadow-xs" style="width: 64px; height: 48px;">
                <div>
                    <span class="fw-bold font-monospace fs-5 text-dark d-block">{{ $v->plat_nomor }}</span>
                    <small class="text-muted">{{ $v->jenis_kendaraan }} - {{ $v->merek }}</small>
                </div>
            </div>
            <div>
                @if ($v->status_kir === 'merah')
                    <span class="badge bg-danger">Lewat Tempo</span>
                @elseif ($v->status_kir === 'kuning')
                    <span class="badge bg-warning text-dark">Mendekati</span>
                @else
                    <span class="badge bg-success">Aman</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="p-4 text-center rounded-4 bg-white border text-muted">
            <i class="bi bi-inbox fs-2 d-block mb-1 opacity-50"></i>
            <span>Belum ada armada spesifik yang didaftarkan atas nama Anda.</span>
        </div>
    </div>
    @endforelse
</div>
@endif

{{-- TABEL STATUS KENDARAAN (KIR & DOKUMEN) --}}
<div class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 bg-primary-subtle text-primary rounded-3"><i class="bi bi-file-earmark-text-fill fs-5"></i></div>
            <div>
                <h5 class="fw-extrabold text-dark mb-0">Status Dokumen &amp; Uji KIR Seluruh Armada</h5>
                <small class="text-muted">Monitoring masa aktif dokumen KIR kendaraan real-time</small>
            </div>
        </div>
        <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-outline-primary px-3 py-2 fw-semibold" style="border-radius:10px;">
            <i class="bi bi-gear-fill me-1"></i> Kelola Data Master
        </a>
    </div>

    <!-- Search & Filter Controls -->
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md-6 col-lg-4">
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-end-0 text-muted" style="border-color: rgba(0,0,0,0.08);"><i class="bi bi-search"></i></span>
                <input type="text" id="searchVehicleInput" class="form-control border-start-0 ps-0 py-2.5" placeholder="Cari Plat Nomor / Merk / Tipe..." style="box-shadow: none; border-color: rgba(0,0,0,0.08); font-size: 0.9rem;">
            </div>
        </div>
        <div class="col-md-6 col-lg-8">
            <div class="d-flex gap-2 justify-content-md-end flex-wrap" id="statusFilterButtons">
                <button class="btn btn-sm btn-dark rounded-3 px-3 py-2.5 active font-semibold" data-status="all">Semua</button>
                <button class="btn btn-sm btn-outline-success rounded-3 px-3 py-2.5 font-semibold" data-status="hijau"><i class="bi bi-check-circle-fill me-1"></i>Aman &amp; Valid</button>
                <button class="btn btn-sm btn-outline-warning rounded-3 px-3 py-2.5 font-semibold text-dark" data-status="kuning"><i class="bi bi-exclamation-triangle-fill me-1"></i>Mendekati</button>
                <button class="btn btn-sm btn-outline-danger rounded-3 px-3 py-2.5 font-semibold" data-status="merah"><i class="bi bi-x-circle-fill me-1"></i>Lewat Tempo</button>
            </div>
        </div>
    </div>

    <div class="row g-4" id="kirStatusGrid">
        @forelse ($vehicles as $v)
        @php
            $diffDays = null;
            $progressPct = 0;
            $progressBarColor = 'bg-secondary';
            if ($v->jatuh_tempo_kir) {
                $diffDays = now()->startOfDay()->diffInDays($v->jatuh_tempo_kir->startOfDay(), false);
                $progressPct = max(0, min(100, round(($diffDays / 180) * 100)));
                if ($v->status_kir === 'hijau') {
                    $progressBarColor = 'bg-success';
                } elseif ($v->status_kir === 'kuning') {
                    $progressBarColor = 'bg-warning';
                } else {
                    $progressBarColor = 'bg-danger';
                }
            }

            // Servis status calculations
            $tglServis = isset($v->tanggal_servis_berikutnya) && $v->tanggal_servis_berikutnya ? \Carbon\Carbon::parse($v->tanggal_servis_berikutnya) : null;
            $kmTarget = $v->km_servis_berikutnya ?? 0;
            $kmSekarang = $v->odometer_terkini ?? 0;

            $isTerlambatTgl = $tglServis && $tglServis->isPast();
            $isMendekatiTgl = $tglServis && !$isTerlambatTgl && now()->diffInDays($tglServis, false) <= 7;
            $isTerlambatKm = $kmTarget > 0 && $kmSekarang >= $kmTarget;
        @endphp
        <div class="col-md-6 col-lg-4 vehicle-card-wrapper" data-status-kir="{{ $v->status_kir }}" data-search="{{ $v->plat_nomor }} {{ $v->merek }} {{ $v->tipe }} {{ $v->jenis_kendaraan }}">
            <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs hover-card transition-all" style="transition: all 0.25s ease; border-radius: 16px; background-color: #ffffff;">
                <!-- Vehicle Image -->
                <div class="position-relative" style="height: 180px; overflow: hidden; background: #f8fafc;">
                    <img src="{{ $v->foto_url }}" alt="{{ $v->plat_nomor }}" class="w-100 h-100 object-fit-cover transition-img" style="transition: transform 0.3s ease;">
                    <!-- Floating Plate Badge -->
                    <span class="position-absolute badge bg-dark text-white font-monospace px-3 py-2 fs-6 border border-secondary shadow-sm" style="border-radius: 10px; letter-spacing: 0.8px; top: 12px; left: 12px; z-index: 5;">
                        {{ $v->plat_nomor }}
                    </span>
                    <!-- Floating Status Badge -->
                    <div class="position-absolute" style="top: 12px; right: 12px; z-index: 5;">
                        @if ($v->status === 'Siap Pakai')
                            <span class="badge bg-success px-3 py-2 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 10px;">
                                <span class="bg-white rounded-circle" style="width: 6px; height: 6px; display: inline-block;"></span> Siap Pakai
                            </span>
                        @elseif ($v->status === 'Sedang Diservis')
                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 10px;">
                                <span class="bg-dark rounded-circle" style="width: 6px; height: 6px; display: inline-block;"></span> Sedang Diservis
                            </span>
                        @else
                            <span class="badge bg-secondary text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">{{ $v->status ?? 'Non-Aktif' }}</span>
                        @endif
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-4">
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
                                <span class="fw-bold text-danger">KIR Lewat Tempo! (Terlewat {{ abs($diffDays) }} hari)</span>
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
                    <div class="d-flex justify-content-between align-items-center gap-2 mt-3 pt-2 border-top">
                        <!-- Quick status updater for Admin & Technician -->
                        @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                            <form action="{{ route('vehicles.updateStatus', $v) }}" method="POST" class="d-inline-block">
                                @csrf 
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm shadow-none border" style="font-size: 0.78rem; font-weight: 600; border-radius: 8px; background-color: #f8fafc; padding: 6px 12px; width: 130px;" onchange="this.form.submit()">
                                    <option value="Siap Pakai" @selected($v->status === 'Siap Pakai')>Siap Pakai</option>
                                    <option value="Sedang Diservis" @selected($v->status === 'Sedang Diservis')>Sedang Diservis</option>
                                    <option value="Selesai" @selected($v->status === 'Selesai')>Selesai</option>
                                </select>
                            </form>
                        @else
                            <div></div>
                        @endif

                        <!-- General Actions -->
                        <div class="d-flex align-items-center gap-1.5">
                            <a href="{{ route('vehicles.show', $v) }}" class="btn btn-sm btn-outline-primary px-3 py-1.5 d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                                <i class="bi bi-eye-fill"></i> Detail
                            </a>
                            @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin']))
                                <a href="{{ route('vehicles.edit', $v) }}" class="btn btn-sm btn-outline-warning p-1.5 d-inline-flex align-items-center" style="border-radius: 8px;" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">Belum ada data kendaraan.</div>
        @endforelse
    </div>
</div>

<script type="application/json" id="dashboard-chart-data">
{
    "vehicles": {!! json_encode($vehicles->map(fn($v) => [
        'id' => $v->id,
        'plat_nomor' => $v->plat_nomor,
        'merek' => $v->merek,
        'tipe' => $v->tipe,
        'jenis_kendaraan' => $v->jenis_kendaraan,
        'status' => $v->status,
        'supir_utama' => $v->supir_utama,
        'odometer' => $v->odometer_terkini,
        'latitude' => $v->latitude,
        'longitude' => $v->longitude,
        'status_kir' => $v->status_kir,
        'status_servis' => $v->status_servis_berkala,
        'foto_url' => $v->foto_url
    ])) !!},
    "status": @json($statusChart),
    "tren": {!! isset($trenBiayaChart) ? json_encode($trenBiayaChart) : 'null' !!},
    "boros": {!! isset($borosChart) && count($borosChart['labels']) > 0 ? json_encode($borosChart) : 'null' !!},
    "sparkline": {!! isset($sparklineData) ? json_encode($sparklineData) : 'null' !!},
    "calendarEvents": {!! json_encode($calendarEvents) !!},
    "rawExpenses": {!! json_encode(\App\Models\Expense::with('vehicle')->latest('tanggal')->get()->map(fn($e) => [
        'Tanggal' => $e->tanggal->toDateString(),
        'Kendaraan' => $e->vehicle->plat_nomor ?? 'N/A',
        'Jenis' => $e->jenis_pengeluaran,
        'Biaya' => $e->jumlah_biaya,
        'Status' => $e->status_approval,
        'Keterangan' => $e->keterangan
    ])) !!},
    "rawComplaints": {!! json_encode(\App\Models\Complaint::with(['vehicle', 'user'])->latest('tanggal')->get()->map(fn($c) => [
        'Tanggal' => $c->tanggal->toDateString(),
        'Kendaraan' => $c->vehicle->plat_nomor ?? 'N/A',
        'Pelapor' => $c->user->name ?? 'N/A',
        'Keluhan' => $c->keluhan,
        'Status' => $c->status,
        'Progress' => $c->progress_perbaikan . '%'
    ])) !!}
}
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    var chartPayload = JSON.parse(document.getElementById('dashboard-chart-data').textContent);
    var chartInstances = [];

    // --- 🗺️ REAL-TIME FLEET MAP INITIALIZATION ---
    (function() {
        const mapContainer = document.getElementById('fleetMap');
        if (!mapContainer) return;

        // Central coordinates for Jakarta
        const defaultLat = -6.2088;
        const defaultLng = 106.8456;

        // Initialize Map
        const map = L.map('fleetMap', {
            center: [defaultLat, defaultLng],
            zoom: 12,
            scrollWheelZoom: false, // Disable scroll wheel zoom for general dashboard comfort
            dragging: true,
            tap: true
        });

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const vehicles = chartPayload.vehicles || [];
        const markers = [];

        vehicles.forEach(function(v, index) {
            let lat = parseFloat(v.latitude);
            let lng = parseFloat(v.longitude);

            // Fallback & Slight randomization if coordinates are missing/zero for demo visibility
            if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) {
                // Distribute around default coordinates based on vehicle ID/index
                const angle = (index / vehicles.length) * 2 * Math.PI;
                const radius = 0.04 + (index * 0.005); // Radius offset
                lat = defaultLat + Math.sin(angle) * radius;
                lng = defaultLng + Math.cos(angle) * radius;
            }

            // Determine Status Group Class for styling
            let markerType = 'marker-ready'; // Default green
            if (v.status === 'Sedang Diservis') {
                markerType = 'marker-servis'; // Yellow
            } else if (v.status_kir === 'merah' || v.status_servis === 'merah') {
                markerType = 'marker-warning'; // Red
            } else if (v.status_kir === 'kuning' || v.status_servis === 'kuning') {
                markerType = 'marker-servis'; // Yellow
            }

            // Map Bootstrap icon name based on vehicle type
            let iconClass = 'bi-car-front-fill';
            const jenis = (v.jenis_kendaraan || '').toLowerCase();
            if (jenis.includes('boks')) {
                iconClass = 'bi-truck';
            } else if (jenis.includes('pick up') || jenis.includes('pickup')) {
                iconClass = 'bi-truck-flatbed';
            } else if (jenis.includes('motor')) {
                iconClass = 'bi-bicycle';
            }

            // Custom Leaflet DivIcon
            const vehicleIcon = L.divIcon({
                className: 'custom-vehicle-marker-dashboard ' + markerType,
                html: '<div class="marker-pin"><i class="bi ' + iconClass + '"></i></div><div class="marker-pulse"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -28]
            });

            // Create Popup Content
            const popupHtml = `
                <div style="font-family: 'Inter', sans-serif; font-size: 0.85rem; width: 220px; line-height: 1.45;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="${v.foto_url}" class="rounded object-fit-cover" style="width: 50px; height: 50px; background: #f8fafc;" />
                        <div>
                            <strong class="font-monospace text-dark d-block" style="font-size: 0.95rem; letter-spacing: 0.5px;">${v.plat_nomor}</strong>
                            <small class="text-muted">${v.merek} ${v.tipe}</small>
                        </div>
                    </div>
                    <div style="font-size: 0.78rem;" class="mb-2">
                        <span class="text-muted d-block">Supir Utama: <b>${v.supir_utama || '-'}</b></span>
                        <span class="text-muted d-block">Odometer: <b>${v.odometer.toLocaleString('id-ID')} km</b></span>
                        <span class="text-muted d-block">Status KIR: <span class="badge ${v.status_kir === 'merah' ? 'bg-danger' : (v.status_kir === 'kuning' ? 'bg-warning text-dark' : 'bg-success')} text-white py-0.5 px-1.5">${v.status_kir.toUpperCase()}</span></span>
                    </div>
                    <a href="/vehicles/${v.id}" class="btn btn-xs btn-primary text-white w-100 text-center py-1 fw-bold" style="border-radius: 6px; font-size: 0.72rem; text-decoration: none; display: block;">Buka Detail Kendaraan</a>
                </div>
            `;

            // Place Marker
            const marker = L.marker([lat, lng], { icon: vehicleIcon }).addTo(map);
            marker.bindPopup(popupHtml);
            markers.push(marker);
        });

        // Fit map bounds to contain all markers if there are markers
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            setTimeout(function() {
                map.invalidateSize();
                map.fitBounds(group.getBounds().pad(0.1));
            }, 300);
        }

        // Adjust map styles on theme change
        window.addEventListener('themeChanged', function() {
            setTimeout(function() { map.invalidateSize(); }, 100);
        });
    })();

    // Helper to get tick/label colors based on theme
    function getThemeColors() {
        const isDark = document.body.classList.contains('dark-theme');
        return {
            text: isDark ? '#94a3b8' : '#64748b',
            textBold: isDark ? '#cbd5e1' : '#475569',
            grid: isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.04)',
            border: isDark ? '#111827' : '#fff'
        };
    }

    const currentColors = getThemeColors();

    const chartStatus = new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: chartPayload.status.labels,
            datasets: [{
                data: chartPayload.status.data,
                backgroundColor: ['#10b981', '#f59e0b', '#64748b'],
                borderWidth: 3,
                borderColor: currentColors.border
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { 
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        usePointStyle: true, 
                        padding: 18, 
                        color: currentColors.text,
                        font: { size: 12, family: 'Inter', weight: '600' } 
                    } 
                } 
            }
        }
    });
    chartInstances.push(chartStatus);

    if (chartPayload.tren) {
        const ctxTren = document.getElementById('chartTren').getContext('2d');
        const gradientTren = ctxTren.createLinearGradient(0, 0, 0, 300);
        gradientTren.addColorStop(0, 'rgba(79, 70, 229, 0.45)');
        gradientTren.addColorStop(1, 'rgba(79, 70, 229, 0.01)');

        const chartTren = new Chart(ctxTren, {
            type: 'line',
            data: {
                labels: chartPayload.tren.labels,
                datasets: [{
                    label: 'Biaya (Rp)',
                    data: chartPayload.tren.data,
                    borderColor: '#4f46e5',
                    backgroundColor: gradientTren,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 3,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { padding: 12, cornerRadius: 8, titleFont: { size: 14 }, bodyFont: { size: 14 } } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: currentColors.grid, drawBorder: false },
                        ticks: { callback: function (v) { return 'Rp ' + v.toLocaleString('id-ID'); }, font: { family: 'Inter', size: 11 }, color: currentColors.text },
                        border: { display: false }
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { family: 'Inter', size: 11 }, color: currentColors.text },
                        border: { display: false }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
        chartInstances.push(chartTren);
    }

    if (chartPayload.boros) {
        const ctxBoros = document.getElementById('chartBoros').getContext('2d');
        const gradientBoros = ctxBoros.createLinearGradient(0, 0, 400, 0);
        gradientBoros.addColorStop(0, '#f97316');
        gradientBoros.addColorStop(1, '#ea580c');

        const chartBoros = new Chart(ctxBoros, {
            type: 'bar',
            data: {
                labels: chartPayload.boros.labels,
                datasets: [{
                    label: 'Total Biaya (Rp)',
                    data: chartPayload.boros.data,
                    backgroundColor: gradientBoros,
                    borderRadius: 8,
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false }, tooltip: { padding: 12, cornerRadius: 8, titleFont: { size: 14 }, bodyFont: { size: 14 } } },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: currentColors.grid, drawBorder: false },
                        ticks: { callback: function (v) { return 'Rp ' + v.toLocaleString('id-ID'); }, color: currentColors.text },
                        border: { display: false }
                    },
                    y: { 
                        grid: { display: false }, 
                        ticks: { color: currentColors.textBold, font: { weight: '600' } }, 
                        border: { display: false } 
                    }
                }
            }
        });
        chartInstances.push(chartBoros);
    }

    // Dynamic Theme Swapping for Chart Labels
    window.addEventListener('themeChanged', function() {
        const newColors = getThemeColors();

        chartInstances.forEach(function(chart) {
            if (chart.config.type === 'doughnut') {
                chart.options.plugins.legend.labels.color = newColors.text;
                chart.data.datasets[0].borderColor = newColors.border;
            } else {
                if (chart.options.scales.x) {
                    chart.options.scales.x.ticks.color = newColors.text;
                    chart.options.scales.x.grid.color = newColors.grid;
                }
                if (chart.options.scales.y) {
                    chart.options.scales.y.ticks.color = (chart.config.type === 'bar') ? newColors.textBold : newColors.text;
                    chart.options.scales.y.grid.color = newColors.grid;
                }
            }
            chart.update();
        });
    });
    // Client-side search and status filter for KIR Grid
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchVehicleInput');
        const filterButtons = document.querySelectorAll('#statusFilterButtons button');
        const vehicleCards = document.querySelectorAll('#kirStatusGrid .vehicle-card-wrapper');

        // Initialize Bootstrap tooltips with theme check
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }

        // Initialize Sparkline Trends
        function createSparkline(canvasId, dataPoints, strokeColor) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 40);
            gradient.addColorStop(0, strokeColor.replace('1)', '0.35)'));
            gradient.addColorStop(1, strokeColor.replace('1)', '0.01)'));
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array(dataPoints.length).fill(''),
                    datasets: [{
                        data: dataPoints,
                        borderColor: strokeColor,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: {
                        x: { display: false },
                        y: { display: false, min: Math.min(...dataPoints) - 1 }
                    }
                }
            });
        }
        
        // Using real historical trends from database
        const spTotal = (chartPayload.sparkline && chartPayload.sparkline.total) ? chartPayload.sparkline.total : [8, 8, 8, 8, 8, 8, 8];
        const spKeluhan = (chartPayload.sparkline && chartPayload.sparkline.keluhan) ? chartPayload.sparkline.keluhan : [0, 0, 0, 0, 0, 0, 0];
        const spServis = (chartPayload.sparkline && chartPayload.sparkline.servis) ? chartPayload.sparkline.servis : [0, 0, 0, 0, 0, 0, 0];
        const spSiap = (chartPayload.sparkline && chartPayload.sparkline.siap) ? chartPayload.sparkline.siap : [0, 0, 0, 0, 0, 0, 0];

        createSparkline('sparklineTotal', spTotal, 'rgba(255, 255, 255, 0.85)');
        createSparkline('sparklineKeluhan', spKeluhan, 'rgba(255, 255, 255, 0.85)');
        createSparkline('sparklineServis', spServis, 'rgba(255, 255, 255, 0.85)');
        createSparkline('sparklineSiap', spSiap, 'rgba(255, 255, 255, 0.85)');

        function filterVehicles() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const activeBtn = document.querySelector('#statusFilterButtons button.active');
            const selectedStatus = activeBtn ? activeBtn.getAttribute('data-status') : 'all';

            vehicleCards.forEach(card => {
                const searchData = card.getAttribute('data-search').toLowerCase();
                const cardStatus = card.getAttribute('data-status-kir');

                const matchesSearch = searchData.includes(searchTerm);
                const matchesStatus = (selectedStatus === 'all' || cardStatus === selectedStatus);

                if (matchesSearch && matchesStatus) {
                    card.style.setProperty('display', 'block', 'important');
                    // Trigger reflow for transition effect
                    card.offsetHeight;
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        if (card.style.opacity === '0') {
                            card.style.setProperty('display', 'none', 'important');
                        }
                    }, 350);
                }
            });

            // Handle empty state visual feedback
            setTimeout(() => {
                const visibleCards = Array.from(vehicleCards).filter(card => card.style.display !== 'none');
                const gridContainer = document.getElementById('kirStatusGrid');
                let emptyState = document.getElementById('emptySearchState');

                if (visibleCards.length === 0) {
                    if (!emptyState) {
                        const noDataHTML = `
                            <div class="col-12 text-center py-5 text-muted" id="emptySearchState" style="animation: fadeIn 0.4s ease;">
                                <i class="bi bi-search fs-2 d-block mb-2 opacity-50"></i>
                                <span>Tidak ditemukan kendaraan yang cocok dengan pencarian / filter Anda.</span>
                            </div>
                        `;
                        gridContainer.insertAdjacentHTML('beforeend', noDataHTML);
                    }
                } else {
                    if (emptyState) {
                        emptyState.remove();
                    }
                }
            }, 360);
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterVehicles);
        }

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active classes
                filterButtons.forEach(b => {
                    b.classList.remove('active', 'btn-dark', 'btn-success', 'btn-warning', 'btn-danger');
                    const s = b.getAttribute('data-status');
                    if (s === 'all') b.classList.add('btn-outline-dark');
                    else if (s === 'hijau') b.classList.add('btn-outline-success');
                    else if (s === 'kuning') b.classList.add('btn-outline-warning');
                    else if (s === 'merah') b.classList.add('btn-outline-danger');
                });
                
                // Add active state to clicked button
                this.classList.remove('btn-outline-dark', 'btn-outline-success', 'btn-outline-warning', 'btn-outline-danger');
                this.classList.add('active');
                
                const status = this.getAttribute('data-status');
                if (status === 'all') this.classList.add('btn-dark');
                else if (status === 'hijau') this.classList.add('btn-success');
                else if (status === 'kuning') this.classList.add('btn-warning');
                else if (status === 'merah') this.classList.add('btn-danger');

                filterVehicles();
            });
        });

        // --- 📅 SERVICE CALENDAR BUILDER ---
        var currentDate = new Date();
        var currentMonth = currentDate.getMonth();
        var currentYear = currentDate.getFullYear();
        var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        function renderCalendar(month, year) {
            var firstDay = new Date(year, month, 1).getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var calendarContainer = document.getElementById('calendarContainer');
            var monthTitle = document.getElementById('calendarMonthTitle');
            
            if (!calendarContainer || !monthTitle) return;

            // Clear old dates (keep day headers)
            var headersHtml = `
                <div class="calendar-day-header">Min</div>
                <div class="calendar-day-header">Sen</div>
                <div class="calendar-day-header">Sel</div>
                <div class="calendar-day-header">Rab</div>
                <div class="calendar-day-header">Kam</div>
                <div class="calendar-day-header">Jum</div>
                <div class="calendar-day-header">Sab</div>
            `;
            calendarContainer.innerHTML = headersHtml;
            monthTitle.textContent = monthNames[month] + " " + year;

            // Render empty cells for offset
            for (let i = 0; i < firstDay; i++) {
                var emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-cell opacity-25 bg-transparent border-0';
                calendarContainer.appendChild(emptyCell);
            }

            // Render days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                var cell = document.createElement('div');
                cell.className = 'calendar-cell';
                
                var dateStr = year + "-" + String(month + 1).padStart(2, '0') + "-" + String(day).padStart(2, '0');
                
                // Highlight today
                var isToday = (day === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear());
                if (isToday) {
                    cell.classList.add('today');
                }

                var cellHeader = document.createElement('div');
                cellHeader.className = 'calendar-date-number';
                cellHeader.textContent = day;
                cell.appendChild(cellHeader);

                // Add events/dots for this date
                if (chartPayload.calendarEvents) {
                    var dayEvents = chartPayload.calendarEvents.filter(function(e) {
                        return e.date === dateStr;
                    });

                    if (dayEvents.length > 0) {
                        var dotsContainer = document.createElement('div');
                        dotsContainer.className = 'd-flex gap-1 justify-content-start flex-wrap mt-1';
                        
                        dayEvents.forEach(function(ev) {
                            var dot = document.createElement('span');
                            dot.className = 'calendar-event-dot';
                            dot.style.backgroundColor = ev.color;
                            dot.setAttribute('data-bs-toggle', 'tooltip');
                            dot.setAttribute('data-bs-title', ev.title);
                            dotsContainer.appendChild(dot);
                        });
                        cell.appendChild(dotsContainer);
                    }
                }

                calendarContainer.appendChild(cell);
            }

            // Re-initialize Bootstrap tooltips for new dots
            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = document.querySelectorAll('.calendar-event-dot');
                [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            }
        }

        renderCalendar(currentMonth, currentYear);

        document.getElementById('btnPrevMonth').addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        document.getElementById('btnNextMonth').addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });


        // --- 📊 INSTANT CSV EXPORTER ---
        document.getElementById('btnExportCSV').addEventListener('click', function() {
            var exportType = document.getElementById('exporterSelectType').value;
            var dataToExport = [];
            var filename = "";

            if (exportType === 'expenses') {
                dataToExport = chartPayload.rawExpenses || [];
                filename = "rekap_biaya_operasional.csv";
            } else {
                dataToExport = chartPayload.rawComplaints || [];
                filename = "rekap_keluhan_kendaraan.csv";
            }

            if (dataToExport.length === 0) {
                alert("Tidak ada data untuk diekspor!");
                return;
            }

            // Build CSV Content
            var csvRows = [];
            var headers = Object.keys(dataToExport[0]);
            csvRows.push(headers.join(','));

            dataToExport.forEach(function(row) {
                var values = headers.map(function(header) {
                    var val = row[header] !== null ? row[header] : '';
                    // Escape double quotes and wrap in quotes if contains comma
                    var escaped = ('' + val).replace(/"/g, '""');
                    if (escaped.includes(',') || escaped.includes('\n') || escaped.includes('"')) {
                        escaped = '"' + escaped + '"';
                    }
                    return escaped;
                });
                csvRows.push(values.join(','));
            });

            var csvContent = "\uFEFF" + csvRows.join('\n'); // Add UTF-8 BOM for Excel compliance
            var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            var url = URL.createObjectURL(blob);
            var link = document.createElement("a");
            
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });

    // Real-time Indonesian clock for live dashboard feel
    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        const clockEl = document.getElementById('liveClock');
        if (clockEl) {
            clockEl.textContent = '• ' + now.toLocaleDateString('id-ID', options) + ' WIB';
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection