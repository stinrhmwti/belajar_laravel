@extends('layouts.app')
@section('title', 'Dashboard Monitoring')

@section('content')

@php
    $role = auth()->user()->role;
    $menuItems = [];
    
    if ($role === 'superadmin' || $role === 'admin') {
        $menuItems = [
            [
                'label' => 'Armada Master',
                'icon'  => 'bi-car-front-fill',
                'url'   => route('vehicles.index'),
                'active'=> false
            ],
            [
                'label' => 'Servis Berkala',
                'icon'  => 'bi-calendar3-event',
                'url'   => '#calendarSection',
                'active'=> true
            ],
            [
                'label' => 'Persetujuan Biaya',
                'icon'  => 'bi-wallet2',
                'url'   => route('expenses.index'),
                'active'=> false
            ],
            [
                'label' => 'Kelola Akun',
                'icon'  => 'bi-people-fill',
                'url'   => route('users.index'),
                'active'=> false
            ]
        ];
    } elseif ($role === 'teknisi') {
        $menuItems = [
            [
                'label' => 'Input Checklist',
                'icon'  => 'bi-clipboard2-check-fill',
                'url'   => route('checklist.create'),
                'active'=> true
            ],
            [
                'label' => 'Jadwal Servis',
                'icon'  => 'bi-calendar3-event',
                'url'   => '#calendarSection',
                'active'=> false
            ],
            [
                'label' => 'Catat Biaya',
                'icon'  => 'bi-cash-coin',
                'url'   => route('expenses.create'),
                'active'=> false
            ],
            [
                'label' => 'Daftar Keluhan',
                'icon'  => 'bi-exclamation-octagon-fill',
                'url'   => route('complaints.index'),
                'active'=> false
            ]
        ];
    } elseif ($role === 'pimpinan') {
         $menuItems = [
            [
                'label' => 'Armada Master',
                'icon'  => 'bi-car-front-fill',
                'url'   => route('vehicles.index'),
                'active'=> false
            ],
            [
                'label' => 'Servis Berkala',
                'icon'  => 'bi-calendar3-event',
                'url'   => '#calendarSection',
                'active'=> true
            ],
            [
                'label' => 'Rekap Biaya',
                'icon'  => 'bi-wallet2',
                'url'   => route('expenses.index'),
                'active'=> false
            ],
            [
                'label' => 'Kontak Admin',
                'icon'  => 'bi-whatsapp',
                'url'   => 'https://wa.me/6287738565383?text=Halo%20Admin,%20saya%20Pimpinan%20butuh%20bantuan',
                'active'=> false,
                'target'=> '_blank'
            ]
        ];
    } else { // user/driver
        $menuItems = [
            [
                'label' => 'Armada Saya',
                'icon'  => 'bi-car-front-fill',
                'url'   => route('vehicles.index'),
                'active'=> false
            ],
            [
                'label' => 'Lapor Keluhan',
                'icon'  => 'bi-megaphone-fill',
                'url'   => route('complaints.create'),
                'active'=> true
            ],
            [
                'label' => 'Histori Checklist',
                'icon'  => 'bi-clipboard-data-fill',
                'url'   => route('checklist.index'),
                'active'=> false
            ],
            [
                'label' => 'Kontak Admin',
                'icon'  => 'bi-whatsapp',
                'url'   => 'https://wa.me/6287738565383?text=Halo%20Admin,%20saya%20Driver%20butuh%20bantuan%20terkait%20armada',
                'active'=> false,
                'target'=> '_blank'
            ]
        ];
    }
@endphp


@push('styles')
<style>
    /* ===== PREMIUM WIDGETS ANIMS & EFFECTS ===== */
    .premium-pulse-success {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: #d1fae5;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        animation: premium-pulsate 2s infinite;
    }
    body.dark-theme .premium-pulse-success {
        background-color: rgba(16, 185, 129, 0.15) !important;
    }
    @keyframes premium-pulsate {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.06) !important;
        border-color: rgba(0,0,0,0.08) !important;
    }
    body.dark-theme .hover-lift:hover {
        border-color: rgba(255,255,255,0.08) !important;
    }
    body.dark-theme .bg-white.hover-lift {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }

    /* Welcome Greetings & Quick Action Enhancements */
    .bg-light-soft {
        background: rgba(248, 250, 252, 0.65) !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }
    body.dark-theme .bg-light-soft {
        background: rgba(30, 41, 59, 0.4) !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
    }
    .dark-text-white {
        color: #0f172a !important;
    }
    body.dark-theme .dark-text-white {
        color: #f8fafc !important;
    }
    .welcome-banner-card {
        background-color: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
    }
    body.dark-theme .welcome-banner-card {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    
    .bg-soft-primary {
        background-color: rgba(79, 70, 229, 0.12) !important;
        color: #4f46e5 !important;
    }
    .bg-soft-info {
        background-color: rgba(8, 145, 178, 0.12) !important;
        color: #0891b2 !important;
    }
    .bg-soft-success {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #10b981 !important;
    }
    .bg-soft-warning {
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #d97706 !important;
    }
    .bg-soft-danger {
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #ef4444 !important;
    }
    .badge-soft-primary {
        background-color: rgba(79, 70, 229, 0.12) !important;
        color: #4f46e5 !important;
        border: 1px solid rgba(79, 70, 229, 0.25) !important;
    }

    /* CSS for Service Calendar */
    .calendar-container {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        text-align: center;
    }
    .calendar-day-header {
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 8px 0;
    }
    .calendar-card-custom {
        position: relative;
        border-radius: 16px !important;
        background-color: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04) !important;
        transition: box-shadow 0.25s ease, transform 0.25s ease;
        overflow: hidden !important;
    }
    .calendar-card-custom::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 5px;
        background: linear-gradient(to bottom, #0891b2, #0e3054) !important;
        z-index: 10;
    }
    .calendar-card-custom:hover {
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.07) !important;
    }
    body.dark-theme .calendar-card-custom {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    .calendar-cell {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px;
        min-height: 76px;
        padding: 8px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    body.dark-theme .calendar-cell {
        background: #0f172a !important;
        border-color: #1e293b !important;
    }
    .calendar-cell-empty {
        background: rgba(248, 250, 252, 0.5) !important;
        border: 1.5px dashed #e2e8f0 !important;
        border-radius: 12px;
        min-height: 76px;
        opacity: 0.6;
    }
    body.dark-theme .calendar-cell-empty {
        background: rgba(15, 23, 42, 0.3) !important;
        border-color: #1e293b !important;
    }
    .calendar-cell:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(8, 145, 178, 0.08) !important;
        border-color: #0891b2 !important;
    }
    .calendar-cell.today {
        border: 2px solid #0891b2 !important;
        background: rgba(8, 145, 178, 0.04) !important;
    }
    .calendar-cell.selected-date {
        border: 2px solid #0e3054 !important;
        background: rgba(14, 48, 84, 0.03) !important;
        box-shadow: 0 0 0 3px rgba(14, 48, 84, 0.15) !important;
    }
    .calendar-date-number {
        font-size: 0.8rem;
        font-weight: 800;
        color: #334155;
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
    .calendar-event-badge {
        font-size: 0.62rem;
        font-weight: 700;
        padding: 2px 4px;
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 3px;
        text-decoration: none;
    }
    .agenda-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .agenda-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .agenda-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(8, 145, 178, 0.2);
        border-radius: 10px;
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
        background: #0f172a !important;
        color: #ffffff !important;
        border: none !important;
        border-left: 5px solid rgba(255, 255, 255, 0.85) !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15) !important;
        position: relative; overflow: hidden;
    }
    .card-grad-2 {
        background: #0f2742 !important;
        color: #ffffff !important;
        border: none !important;
        border-left: 5px solid rgba(255, 255, 255, 0.85) !important;
        box-shadow: 0 4px 12px rgba(15, 39, 66, 0.15) !important;
        position: relative; overflow: hidden;
    }
    .card-grad-3 {
        background: #0e7490 !important;
        color: #ffffff !important;
        border: none !important;
        border-left: 5px solid rgba(255, 255, 255, 0.85) !important;
        box-shadow: 0 4px 12px rgba(14, 116, 144, 0.15) !important;
        position: relative; overflow: hidden;
    }
    .card-grad-4 {
        background: #0891b2 !important;
        color: #ffffff !important;
        border: none !important;
        border-left: 5px solid rgba(255, 255, 255, 0.85) !important;
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15) !important;
        position: relative; overflow: hidden;
    }

    /* Glassmorphic Gradient Spheres disabled for flat design */
    .card-grad-1::after, .card-grad-2::after, .card-grad-3::after, .card-grad-4::after {
        display: none;
    }
    .card-grad-1::before, .card-grad-2::before, .card-grad-3::before, .card-grad-4::before {
        display: none;
    }

    .card-grad-1 h2, .card-grad-2 h2, .card-grad-3 h2, .card-grad-4 h2 {
        color: #ffffff !important;
    }
    .card-grad-title {
        color: rgba(255, 255, 255, 0.85) !important;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    .card-grad-1 .text-white-50, .card-grad-2 .text-white-50, .card-grad-3 .text-white-50, .card-grad-4 .text-white-50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    .card-grad-1 .fw-bold.text-white, .card-grad-2 .fw-bold.text-white, .card-grad-3 .fw-bold.text-white, .card-grad-4 .fw-bold.text-white {
        color: #ffffff !important;
    }
    .card-grad-1 .progress, .card-grad-2 .progress, .card-grad-3 .progress, .card-grad-4 .progress {
        background: rgba(255, 255, 255, 0.25) !important;
    }
    .card-grad-1 .progress-bar, .card-grad-2 .progress-bar, .card-grad-3 .progress-bar, .card-grad-4 .progress-bar {
        background-color: #ffffff !important;
    }

    .card-grad-icon {
        background: rgba(255, 255, 255, 0.18) !important;
        color: #ffffff !important;
        border-radius: 12px;
        width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
    }

    
    .dashboard-card {
        position: relative;
        border-radius: 16px !important;
        border: 1px solid #f5c2c7 !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02) !important;
        transition: box-shadow 0.2s ease;
        overflow: hidden !important;
    }
    .dashboard-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 5px;
        background: linear-gradient(to bottom, #ef4444, #dc2626) !important;
        z-index: 10;
    }
    .card-border-grad-orange {
        border: 1px solid #ffe69c !important;
    }
    .card-border-grad-orange::before {
        background: linear-gradient(to bottom, #f59e0b, #d97706) !important;
    }
    .card-border-grad-red {
        border: 1px solid #f5c2c7 !important;
    }
    .card-border-grad-red::before {
        background: linear-gradient(to bottom, #ef4444, #b91c1c) !important;
    }
    .card-border-grad-green {
        border: 1px solid #badbcc !important;
    }
    .card-border-grad-green::before {
        background: linear-gradient(to bottom, #10b981, #059669) !important;
    }
    .dashboard-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.05) !important; }
    .dashboard-card .card-header { background-color: #fff !important; border-bottom: 1px solid rgba(0,0,0,0.04) !important; padding: 1.1rem 1.4rem; font-weight: 700; color: #0f172a; border-radius: 16px 16px 0 0 !important; }
    
    body.dark-theme .dashboard-card {
        background-color: #111827 !important;
        border-color: #842029 !important;
    }
    body.dark-theme .card-border-grad-orange {
        border-color: #664d03 !important;
    }
    body.dark-theme .card-border-grad-red {
        border-color: #842029 !important;
    }
    body.dark-theme .card-border-grad-green {
        border-color: #0f5132 !important;
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

{{-- HEADER WELCOME (Daya Motor Style Layout - Full Width) --}}
<div class="mb-4">
    <!-- Breadcrumb Header -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 0.85rem; font-weight: 600;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none" style="color: #64748b;">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active" style="color: #0891b2;" aria-current="page">{{ __('Pemeliharaan Armada') }}</li>
            </ol>
        </nav>
        <span id="liveClock" class="text-muted fw-semibold font-monospace" style="font-size: 0.78rem;"></span>
    </div>

    <!-- Banner Image Card -->
    <div class="card border-0 shadow-lg overflow-hidden mb-4 rounded-4 position-relative" 
         style="background: url('{{ asset('images/clean_car_lineup.jpg') }}') no-repeat center 85%; 
                background-size: cover; 
                min-height: 480px; 
                border-radius: 20px !important; 
                border: 1px solid rgba(0,0,0,0.05) !important;
                box-shadow: 0 15px 35px rgba(15,23,42,0.1) !important;
                transition: transform 0.3s ease, box-shadow 0.3s ease;">
        
        <!-- Subtle transparent overlay on the left to ensure high text contrast while keeping background fully visible -->
        <div style="position: absolute; inset: 0; background: linear-gradient(95deg, rgba(255, 255, 255, 0.85) 0%, rgba(255, 255, 255, 0.55) 45%, rgba(255, 255, 255, 0.1) 75%, rgba(255, 255, 255, 0) 100%); pointer-events: none; z-index: 1;"></div>
        
        <!-- Card Content -->
        <div class="card-body p-4 p-md-5 d-flex flex-column align-items-start justify-content-start position-relative" style="z-index: 2; min-height: 480px; padding-bottom: 100px !important;">
            <div class="col-12 col-md-9 col-lg-7 text-start py-2">
                <h1 class="fw-extrabold mb-1" style="color: #0e3054; font-size: clamp(1.8rem, 4vw, 2.5rem); letter-spacing: -1.5px; line-height: 1.15;">
                    {{ __('SISTEM MANAJEMEN ARMADA') }}
                </h1>
                <h5 class="fw-bold mb-3" style="color: #0891b2; letter-spacing: -0.5px;">
                    {{ __('Kelola Armada, Optimalkan Performa') }}
                </h5>
                <p class="mb-4 d-none d-sm-block text-secondary" style="font-size: 0.95rem; line-height: 1.6; max-width: 580px; font-weight: 500; color: #475569 !important;">
                    {{ __('Solusi lengkap untuk manajemen kendaraan perusahaan mulai dari pemantauan, perawatan, hingga pelaporan dalam satu sistem terintegrasi.') }}
                </p>
                
                <!-- Quick Icon Features Section -->
                <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 15px 0;">
                    <!-- Icon 1: Data Armada -->
                    <div class="text-center px-3" style="min-width: 80px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 46px; height: 46px; background-color: #0e3054; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="bi bi-car-front text-white fs-5"></i>
                        </div>
                        <span class="d-block mt-2 fw-semibold text-secondary" style="font-size: 0.75rem; color: #475569 !important;">{{ __('Data Armada') }}</span>
                    </div>

                    <div class="d-none d-sm-block" style="height: 35px; width: 1px; background-color: rgba(71, 85, 105, 0.25);"></div>
                    
                    <!-- Icon 2: Perawatan -->
                    <div class="text-center px-3" style="min-width: 80px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 46px; height: 46px; background-color: #0e3054; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="bi bi-tools text-white fs-5"></i>
                        </div>
                        <span class="d-block mt-2 fw-semibold text-secondary" style="font-size: 0.75rem; color: #475569 !important;">{{ __('Perawatan') }}</span>
                    </div>

                    <div class="d-none d-sm-block" style="height: 35px; width: 1px; background-color: rgba(71, 85, 105, 0.25);"></div>

                    <!-- Icon 3: Bahan Bakar -->
                    <div class="text-center px-3" style="min-width: 80px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 46px; height: 46px; background-color: #0e3054; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="bi bi-fuel-pump text-white fs-5"></i>
                        </div>
                        <span class="d-block mt-2 fw-semibold text-secondary" style="font-size: 0.75rem; color: #475569 !important;">{{ __('Bahan Bakar') }}</span>
                    </div>

                    <div class="d-none d-sm-block" style="height: 35px; width: 1px; background-color: rgba(71, 85, 105, 0.25);"></div>

                    <!-- Icon 4: Laporan -->
                    <div class="text-center px-3" style="min-width: 80px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 46px; height: 46px; background-color: #0e3054; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="bi bi-bar-chart-line text-white fs-5"></i>
                        </div>
                        <span class="d-block mt-2 fw-semibold text-secondary" style="font-size: 0.75rem; color: #475569 !important;">{{ __('Laporan') }}</span>
                    </div>

                    <div class="d-none d-sm-block" style="height: 35px; width: 1px; background-color: rgba(71, 85, 105, 0.25);"></div>

                    <!-- Icon 5: Pengingat -->
                    <div class="text-center px-3" style="min-width: 80px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 46px; height: 46px; background-color: #0e3054; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="bi bi-bell text-white fs-5"></i>
                        </div>
                        <span class="d-block mt-2 fw-semibold text-secondary" style="font-size: 0.75rem; color: #475569 !important;">{{ __('Pengingat') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Right Badge: Armada Terpantau -->
        <div class="position-absolute d-none d-lg-flex align-items-center gap-3 p-3 shadow" 
             style="top: 30px; right: 30px; background: rgba(224, 242, 254, 0.85); backdrop-filter: blur(8px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.4); max-width: 290px; z-index: 3;">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 44px; height: 44px; background-color: #0e3054; flex-shrink: 0;">
                <i class="bi bi-shield-check fs-4"></i>
            </div>
            <div class="text-start">
                <h6 class="fw-extrabold mb-0.5" style="color: #0e3054; font-size: 0.85rem;">{{ __('Armada Terpantau') }}</h6>
                <p class="mb-0 text-secondary" style="font-size: 0.72rem; line-height: 1.3; font-weight: 500;">{{ __('Semua kendaraan dalam kondisi siap dan terkontrol penuh.') }}</p>
            </div>
        </div>

        <!-- Bottom Semi-Transparent Full-Width Status Bar -->
        <div class="w-100 position-absolute bottom-0 start-0 px-4 py-3 d-none d-md-block" 
             style="background: rgba(14, 48, 84, 0.6); backdrop-filter: blur(10px); border-top: 1px solid rgba(255,255,255,0.08); z-index: 3;">
            <div class="row text-white g-0 text-start align-items-center">
                <!-- Col 1 -->
                <div class="col-md-3 d-flex align-items-center gap-3 pe-4" style="border-right: 1px solid rgba(255, 255, 255, 0.15);">
                    <i class="bi bi-display fs-4 text-white"></i>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.78rem; letter-spacing: 0.2px;">{{ __('Monitoring Real-Time') }}</h6>
                        <span class="text-white-50" style="font-size: 0.65rem; line-height: 1.2; display: block;">{{ __('Pantau lokasi dan status kendaraan secara real-time.') }}</span>
                    </div>
                </div>
                <!-- Col 2 -->
                <div class="col-md-3 d-flex align-items-center gap-3 px-4" style="border-right: 1px solid rgba(255, 255, 255, 0.15);">
                    <i class="bi bi-calendar3 fs-4 text-white"></i>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.78rem; letter-spacing: 0.2px;">{{ __('Jadwal Perawatan') }}</h6>
                        <span class="text-white-50" style="font-size: 0.65rem; line-height: 1.2; display: block;">{{ __('Kelola jadwal servis dan perawatan kendaraan dengan mudah.') }}</span>
                    </div>
                </div>
                <!-- Col 3 -->
                <div class="col-md-3 d-flex align-items-center gap-3 px-4" style="border-right: 1px solid rgba(255, 255, 255, 0.15);">
                    <i class="bi bi-file-earmark-text fs-4 text-white"></i>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.78rem; letter-spacing: 0.2px;">{{ __('Laporan Lengkap') }}</h6>
                        <span class="text-white-50" style="font-size: 0.65rem; line-height: 1.2; display: block;">{{ __('Dapatkan laporan penggunaan dan biaya operasional.') }}</span>
                    </div>
                </div>
                <!-- Col 4 -->
                <div class="col-md-3 d-flex align-items-center gap-3 ps-4">
                    <i class="bi bi-shield-check fs-4 text-white"></i>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.78rem; letter-spacing: 0.2px;">{{ __('Keamanan Data') }}</h6>
                        <span class="text-white-50" style="font-size: 0.65rem; line-height: 1.2; display: block;">{{ __('Data armada aman dengan sistem yang terintegrasi.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Selamat Datang Greetings Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 welcome-banner-card" style="border-radius: 16px;">
        <!-- Top accent line -->
        <div style="height: 6px; background: linear-gradient(to right, #0891b2, #0e7490, #4f46e5);"></div>
        <div class="card-body p-4 p-md-5">
            <div class="row mb-4">
                <div class="col-12 text-start">
                    <span class="badge badge-soft-success mb-2 py-1.5 px-3 fw-bold" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.8px; border-radius: 5px;">
                        <i class="bi bi-shield-check me-1"></i> {{ __('Panel') }} {{ ucfirst($role) }}
                    </span>
                    <h3 class="fw-extrabold text-dark mb-2 dark-text-white" style="font-size: clamp(1.4rem, 4vw, 1.8rem); letter-spacing: -0.5px;">{{ __('Selamat Datang di Bengkel Resmi FleetMaintenance') }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.6; max-width: 900px;">
                        {{ __('Untuk menjamin keamanan dan kenyamanan dalam berkendara, pastikan armada Anda mendapatkan servis terbaik dari mekanik ahli, dilengkapi dengan prosedur standar dan pencatatan riwayat terpadu.') }}
                    </p>
                </div>
            </div>

            <!-- Quick Action Cards Grid based on Role -->
            <div class="row g-3">
                @if ($role === 'superadmin' || $role === 'admin')
                    <!-- Action 1 -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(8, 145, 178, 0.04); border: 1px solid rgba(8, 145, 178, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-info text-info rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-car-front-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Kelola Armada') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Pantau status kesiapan, detail spesifikasi, dan jadwal servis unit armada.') }}</p>
                            <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-info text-white fw-bold w-100 py-2 rounded-3 mt-auto">
                                {{ __('Buka Master Armada') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Action 2 -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(79, 70, 229, 0.04); border: 1px solid rgba(79, 70, 229, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-primary text-primary rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Kelola Pengguna') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Daftarkan dan atur hak akses untuk supir, mekanik, dan pimpinan.') }}</p>
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary fw-bold w-100 py-2 rounded-3 mt-auto">
                                {{ __('Buka Manajemen User') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Action 3 -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-success text-success rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-printer-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Cetak Laporan') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Cetak rangkuman aktivitas perawatan armada ke kertas atau PDF.') }}</p>
                            <button onclick="window.print()" class="btn btn-sm btn-success fw-bold w-100 py-2 rounded-3 mt-auto text-white">
                                {{ __('Cetak Laporan Sekarang') }} <i class="bi bi-printer ms-1"></i>
                            </button>
                        </div>
                    </div>

                @elseif ($role === 'teknisi')
                    <!-- Action 1 -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(8, 145, 178, 0.04); border: 1px solid rgba(8, 145, 178, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-info text-info rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-clipboard2-check-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Input Checklist') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Catat kondisi kelayakan fisik & fungsional kendaraan hari ini.') }}</p>
                            <a href="{{ route('checklist.create') }}" class="btn btn-sm btn-info text-white fw-bold w-100 py-2 rounded-3 mt-auto">
                                {{ __('Mulai Checklist Baru') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Action 2 -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(245, 158, 11, 0.04); border: 1px solid rgba(245, 158, 11, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-warning text-warning rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Catat Biaya Bengkel') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Input pengeluaran suku cadang, jasa servis, dan perbaikan unit.') }}</p>
                            <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-warning text-white fw-bold w-100 py-2 rounded-3 mt-auto">
                                {{ __('Input Biaya Servis') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Action 3 -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-success text-success rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-printer-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Cetak Laporan') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Cetak rangkuman aktivitas perawatan armada ke kertas atau PDF.') }}</p>
                            <button onclick="window.print()" class="btn btn-sm btn-success fw-bold w-100 py-2 rounded-3 text-white mt-auto">
                                {{ __('Cetak Laporan Sekarang') }} <i class="bi bi-printer ms-1"></i>
                            </button>
                        </div>
                    </div>

                @elseif ($role === 'pimpinan')
                    <!-- Action 1 -->
                    <div class="col-md-6">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(245, 158, 11, 0.04); border: 1px solid rgba(245, 158, 11, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-warning text-warning rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Rekap Biaya Operasional') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Tinjau, setujui, dan pantau seluruh transaksi pengeluaran perawatan armada.') }}</p>
                            <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-warning text-white fw-bold w-100 py-2 rounded-3 mt-auto">
                                {{ __('Buka Rekap Biaya') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Action 2 -->
                    <div class="col-md-6">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-success text-success rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-printer-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Cetak Laporan') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Cetak rangkuman aktivitas keuangan dan perbaikan armada.') }}</p>
                            <button onclick="window.print()" class="btn btn-sm btn-success fw-bold w-100 py-2 rounded-3 text-white mt-auto">
                                {{ __('Cetak Laporan Sekarang') }} <i class="bi bi-printer ms-1"></i>
                            </button>
                        </div>
                    </div>

                @else
                    <!-- Action 1: Input Checklist -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(8, 145, 178, 0.04); border: 1px solid rgba(8, 145, 178, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-info text-info rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-clipboard2-check-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Input Checklist') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Catat kondisi kelayakan fisik & fungsional kendaraan Anda hari ini.') }}</p>
                            <a href="{{ route('checklist.create') }}" class="btn btn-sm btn-info text-white fw-bold w-100 py-2 rounded-3 mt-auto">
                                {{ __('Mulai Checklist Baru') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Action 2: Lapor Keluhan -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(239, 68, 68, 0.04); border: 1px solid rgba(239, 68, 68, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-danger text-danger rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-megaphone-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Lapor Keluhan') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Laporkan kendala atau keluhan kerusakan kendaraan Anda ke tim bengkel.') }}</p>
                            <a href="{{ route('complaints.create') }}" class="btn btn-sm btn-danger fw-bold w-100 py-2 rounded-3 mt-auto">
                                {{ __('Buat Laporan Keluhan') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Action 3: Cetak Laporan -->
                    <div class="col-md-4">
                        <div class="card h-100 hover-lift border-0 shadow-xs p-3 text-start bg-light-soft" style="border-radius: 12px; background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.08) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-soft-success text-success rounded-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="bi bi-printer-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark dark-text-white">{{ __('Cetak Laporan') }}</h6>
                            </div>
                            <p class="text-secondary small mb-3">{{ __('Cetak rangkuman data laporan atau halaman dashboard ini.') }}</p>
                            <button onclick="window.print()" class="btn btn-sm btn-success fw-bold w-100 py-2 rounded-3 text-white mt-auto">
                                {{ __('Cetak Halaman Ini') }} <i class="bi bi-printer ms-1"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


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
                            <span>{{ __('Total Kendaraan') }}</span>
                        </div>
                        <div class="card-grad-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $totalKendaraan }}</h2>
                        <span class="badge font-normal text-white" style="background: rgba(255, 255, 255, 0.22) !important; font-size:0.75rem;">{{ __('Unit Aktif') }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">{{ __('Rasio Siap Operasional') }}</span>
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
                            <span>{{ __('Keluhan Hari Ini') }}</span>
                        </div>
                        <div class="card-grad-icon"><i class="bi bi-exclamation-octagon-fill"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $keluhanHariIni }}</h2>
                        <span class="badge bg-danger text-white border border-danger-subtle fw-bold" style="font-size:0.68rem; letter-spacing: 0.5px; text-transform: uppercase; padding: 3px 8px; border-radius: 12px; background: rgba(220,38,38,0.7) !important;">{{ __('Baru') }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">{{ __('Tindak Lanjut Segera') }}</span>
                        <span class="fw-bold text-white">{{ __('Butuh Respon') }}</span>
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
                            <span>{{ __('Sedang Servis') }}</span>
                        </div>
                        <div class="card-grad-icon"><i class="bi bi-wrench-adjustable-caps"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $sedangServis }}</h2>
                        <span class="badge bg-warning text-dark border border-warning-subtle fw-bold" style="font-size:0.68rem; letter-spacing: 0.5px; text-transform: uppercase; padding: 3px 8px; border-radius: 12px; background: rgba(245,158,11,0.7) !important;">{{ __('Bengkel') }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">{{ __('Proses Perbaikan') }}</span>
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
                            <span>{{ __('Selesai / Siap Pakai') }}</span>
                        </div>
                        <div class="card-grad-icon"><i class="bi bi-check2-circle"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h2 class="mb-0 fw-extrabold display-6 text-white">{{ $selesaiServis }}</h2>
                        <span class="badge font-normal text-white" style="background: rgba(255, 255, 255, 0.22) !important; font-size:0.75rem;">{{ __('Siap Jalan') }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                        <span class="text-white-50">{{ __('Rasio Kesiapan Armada') }}</span>
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
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">{{ __('Biaya Bulan Ini') }}</small>
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
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">{{ __('KIR Lewat Tempo') }}</small>
                            <span class="fw-extrabold text-danger" style="font-size: 1.1rem;">{{ $kendaraanBermasalah }} {{ __('Unit') }}</span>
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
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">{{ __('Mendekati Tempo') }}</small>
                            <span class="fw-extrabold text-warning" style="font-size: 1.1rem;">{{ $kendaraanMendekatiJatuhTempo }} {{ __('Unit') }}</span>
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
                            <small class="text-muted d-block" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">{{ __('Servis Lewat Jadwal') }}</small>
                            <span class="fw-extrabold text-info" style="font-size: 1.1rem;">{{ $perluServis->count() }} {{ __('Unit') }}</span>
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
                                <div class="card-grad-title">{{ __('KIR Lewat Tempo') }}</div>
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
    </div>
@endif

{{-- MODUL KHUSUS ADMIN & PIMPINAN (Persetujuan & Keluhan Baru) --}}
@if (in_array(auth()->user()->role, ['superadmin', 'admin', 'pimpinan']))
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card dashboard-card card-border-grad-orange h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning-subtle text-warning-emphasis rounded p-1.5"><i class="bi bi-clock-history fs-5"></i></div>
                    <span class="fw-bold">{{ __('Pengajuan Anggaran Perlu Persetujuan') }}</span>
                </div>
                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold">{{ $menungguPersetujuan->count() }} {{ __('Menunggu') }}</span>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-start" style="min-height: 280px;">
                @forelse ($menungguPersetujuan as $e)
                <div class="p-3 rounded-4 mb-3 bg-white border border-slate-100 shadow-xs hover-lift transition-all d-flex justify-content-between align-items-center" style="border-left: 4px solid #f59e0b !important;">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-dark text-white font-monospace" style="letter-spacing: 0.5px; border-radius: 6px; font-size: 0.72rem; padding: 4px 8px;">{{ $e->vehicle->plat_nomor ?? 'N/A' }}</span>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size:0.7rem; padding: 4px 8px; border-radius: 6px;">{{ __($e->jenis_pengeluaran) }}</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size:0.875rem;">{{ $e->keterangan ?? __('Tanpa keterangan') }}</h6>
                        <small class="text-secondary" style="font-size:0.75rem;"><i class="bi bi-calendar3 me-1"></i> {{ $e->tanggal->format('d M Y') }}</small>
                    </div>
                    <div class="text-end ms-3">
                        <div class="fw-extrabold text-dark fs-5 mb-2 font-monospace">Rp {{ number_format($e->jumlah_biaya, 0, ',', '.') }}</div>
                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="d-flex gap-2 justify-content-end">
                            @csrf @method('PUT')
                            <button type="submit" name="status_approval" value="Disetujui" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 shadow-sm px-3" style="font-size:0.75rem; border-radius:8px; font-weight: 600;">
                                <i class="bi bi-check-lg"></i> {{ __('Setuju') }}
                            </button>
                            <button type="submit" name="status_approval" value="Ditolak" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3" style="font-size:0.75rem; border-radius:8px; font-weight: 600;">
                                <i class="bi bi-x-lg"></i> {{ __('Tolak') }}
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 my-auto">
                    <div class="premium-pulse-success mb-3 mx-auto">
                        <i class="bi bi-check-lg text-success" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="fw-extrabold text-dark mb-1">{{ __('Semua Anggaran Disetujui') }}</h6>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">{{ __('Tidak ada klaim biaya yang tertunda.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card dashboard-card card-border-grad-red h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-danger-subtle text-danger rounded p-1.5"><i class="bi bi-exclamation-square-fill fs-5"></i></div>
                    <span class="fw-bold">{{ __('Laporan Keluhan Baru Dari Pengemudi') }}</span>
                </div>
                <a href="{{ route('complaints.index') }}" class="btn btn-xs btn-outline-primary px-2.5 py-1" style="font-size:0.78rem;">{{ __('Lihat Semua') }}</a>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-start" style="min-height: 280px;">
                @forelse ($keluhanBaru as $k)
                <div class="p-3 rounded-4 mb-3 bg-white border border-slate-100 shadow-xs hover-lift transition-all" style="border-left: 4px solid #ef4444 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-dark text-white font-monospace" style="letter-spacing: 0.5px; border-radius: 6px; font-size: 0.72rem; padding: 4px 8px;">{{ $k->vehicle->plat_nomor ?? 'N/A' }}</span>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size:0.68rem; padding: 3px 6px; border-radius: 5px;">{{ __('Baru') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5 text-muted" style="font-size:0.75rem;">
                            <div class="rounded-circle overflow-hidden bg-slate-100 d-flex align-items-center justify-content-center border" style="width: 22px; height: 22px; font-size: 0.65rem; font-weight: 700;">
                                {{ strtoupper(substr($k->user->name ?? 'D', 0, 2)) }}
                            </div>
                            <span class="fw-semibold text-dark" style="font-size: 0.78rem;">{{ $k->user->name ?? 'Driver' }}</span>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-3 bg-light text-dark mb-2" style="font-size:0.825rem; line-height:1.4;">
                        "{{ Str::limit($k->keluhan, 120) }}"
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-secondary" style="font-size:0.72rem;"><i class="bi bi-clock me-1"></i>{{ $k->created_at ? $k->created_at->diffForHumans() : '-' }}</small>
                        <a href="{{ route('complaints.index') }}" class="btn btn-xs btn-outline-primary px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; border-radius: 6px; font-weight: 600;">
                            {{ __('Proses') }} <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 my-auto">
                    <div class="premium-pulse-success mb-3 mx-auto">
                        <i class="bi bi-shield-check text-success" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="fw-extrabold text-dark mb-1">{{ __('Armada Bebas Masalah') }}</h6>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">{{ __('Belum ada keluhan baru hari ini.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endif

{{-- WIDGET AGENDA KALENDER & EXPORTER LAPORAN --}}
<div class="row g-4 mb-4" id="calendarSection">
    <!-- Col 1: Agenda Kalender Servis & KIR -->
    <div class="col-lg-8 col-12">
        <div class="card calendar-card-custom h-100 overflow-hidden">
            <div class="card-header d-flex align-items-center justify-content-between" style="background-color: transparent !important; border-bottom: 1px solid rgba(14, 48, 84, 0.08) !important;">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-info-subtle text-info rounded p-1.5" style="background-color: rgba(8, 145, 178, 0.08) !important; color: #0891b2 !important;"><i class="bi bi-calendar3 fs-5"></i></div>
                    <div>
                        <span class="fw-bold d-block text-dark dark-text-white">{{ __('Agenda Pemeliharaan & KIR') }}</span>
                        <small class="text-muted" style="font-size:0.75rem;">{{ __('Jadwal jatuh tempo dokumen & rencana servis bulan ini') }}</small>
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-xs btn-outline-secondary px-2.5 py-1" id="btnPrevMonth" style="font-size:0.72rem; border-radius:6px;"><i class="bi bi-chevron-left"></i></button>
                    <span class="fw-bold px-2 align-self-center text-dark dark-text-white" id="calendarMonthTitle" style="font-size:0.82rem;">Agustus 2026</span>
                    <button class="btn btn-xs btn-outline-secondary px-2.5 py-1" id="btnNextMonth" style="font-size:0.72rem; border-radius:6px;"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="calendar-container" id="calendarContainer">
                    <!-- Day Headers -->
                    <div class="calendar-day-header">{{ __('Min') }}</div>
                    <div class="calendar-day-header">{{ __('Sen') }}</div>
                    <div class="calendar-day-header">{{ __('Sel') }}</div>
                    <div class="calendar-day-header">{{ __('Rab') }}</div>
                    <div class="calendar-day-header">{{ __('Kam') }}</div>
                    <div class="calendar-day-header">{{ __('Jum') }}</div>
                    <div class="calendar-day-header">{{ __('Sab') }}</div>
                    <!-- Cells will be generated dynamically by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Col 2: Detail Agenda Harian -->
    <div class="col-lg-4 col-12">
        <div class="card calendar-card-custom h-100 overflow-hidden" id="agendaCard">
            <div class="card-header d-flex align-items-center justify-content-between" style="background-color: transparent !important; border-bottom: 1px solid rgba(14, 48, 84, 0.08) !important;">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary-subtle text-primary rounded p-1.5" style="background-color: rgba(14, 48, 84, 0.08) !important; color: #0e3054 !important;"><i class="bi bi-clock-history fs-5"></i></div>
                    <div>
                        <span class="fw-bold d-block text-dark dark-text-white">{{ __('Agenda Harian') }}</span>
                        <small class="text-muted" style="font-size:0.75rem;">{{ __('Jadwal operasional & rencana aksi') }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 text-start">
                <div class="mb-3">
                    <span class="text-secondary fw-bold small text-uppercase d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Tanggal Terpilih') }}</span>
                    <h6 class="fw-extrabold text-dark dark-text-white mb-0" id="agendaSelectedDate">{{ __('Memuat agenda...') }}</h6>
                </div>
                <div class="agenda-scroll" id="agendaListContainer" style="max-height: 420px; overflow-y: auto; padding-right: 5px;">
                    <!-- Agenda items will load dynamically here -->
                </div>
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
                    <span class="fw-bold">{{ __('Distribusi Status Armada') }}</span>
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
                    <span class="fw-bold">{{ __('Tren Pengeluaran Biaya (6 Bulan Terakhir)') }}</span>
                </div>
                <span class="badge bg-light text-secondary border">{{ __('Agregat Bulanan') }}</span>
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
                <span class="fw-bold">{{ __('Top 5 Kendaraan Dengan Biaya Perawatan/BBM Tertinggi') }}</span>
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
        <div class="card dashboard-card card-border-grad-orange h-100">
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
    @php
        $gradClass = 'card-border-grad-green';
        if ($v->status_kir === 'merah') {
            $gradClass = 'card-border-grad-red';
        } elseif ($v->status_kir === 'kuning') {
            $gradClass = 'card-border-grad-orange';
        }
    @endphp
    <div class="col-md-6">
        <div class="p-3 rounded-4 bg-white border shadow-xs d-flex align-items-center justify-content-between position-relative overflow-hidden {{ $gradClass }}">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $v->foto_url }}" alt="{{ $v->plat_nomor }}" class="rounded-3 border object-fit-cover shadow-xs" style="width: 64px; height: 48px;">
                <div>
                    <span class="fw-bold font-monospace fs-5 text-dark d-block">{{ $v->plat_nomor }}</span>
                    <small class="text-muted d-block mb-1">{{ $v->jenis_kendaraan }} - {{ $v->merek }}</small>
                    <a href="{{ route('checklist.create', ['vehicle_id' => $v->id]) }}" class="btn btn-xs btn-outline-info py-0.5 px-2 fw-semibold" style="font-size: 0.72rem; border-radius: 6px;">
                        <i class="bi bi-clipboard2-check"></i> {{ __('Mulai Pengecekan') }}
                    </a>
                </div>
            </div>
            <div class="text-end">
                @if ($v->status_kir === 'merah')
                    <span class="badge bg-danger mb-1 d-inline-block">KIR: Lewat Tempo</span>
                @elseif ($v->status_kir === 'kuning')
                    <span class="badge bg-warning text-dark mb-1 d-inline-block">KIR: Mendekati</span>
                @else
                    <span class="badge bg-success mb-1 d-inline-block">KIR: Aman</span>
                @endif
                <small class="text-muted d-block" style="font-size:0.75rem;">Odo: {{ number_format($v->odometer_terkini, 0, ',', '.') }} km</small>
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

    // 1. Status Chart (Doughnut) with premium gradients
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    
    // Emerald Green to Mint Green for "Siap Pakai"
    const gradSiap = ctxStatus.createLinearGradient(0, 0, 0, 200);
    gradSiap.addColorStop(0, '#10b981');
    gradSiap.addColorStop(1, '#34d399');

    // Amber to Soft Orange for "Sedang Diservis"
    const gradServis = ctxStatus.createLinearGradient(0, 0, 0, 200);
    gradServis.addColorStop(0, '#f97316');
    gradServis.addColorStop(1, '#fbbf24');

    // Indigo to Electric Blue for "Selesai" (or general resolved state)
    const gradSelesai = ctxStatus.createLinearGradient(0, 0, 0, 200);
    gradSelesai.addColorStop(0, '#6366f1');
    gradSelesai.addColorStop(1, '#818cf8');

    const translatedStatusLabels = (chartPayload.status.labels || []).map(label => {
        if (label === 'Siap Pakai') return "{{ __('Siap Pakai') }}";
        if (label === 'Sedang Diservis') return "{{ __('Sedang Diservis') }}";
        if (label === 'Selesai') return "{{ __('Selesai') }}";
        return label;
    });

    const chartStatus = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: translatedStatusLabels,
            datasets: [{
                data: chartPayload.status.data,
                backgroundColor: [gradSiap, gradServis, gradSelesai],
                borderWidth: 3,
                borderColor: currentColors.border,
                hoverOffset: 6
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

    // 2. Tren Biaya Chart (Line) with glowing X-axis gradient and smooth filling
    if (chartPayload.tren) {
        const ctxTren = document.getElementById('chartTren').getContext('2d');
        
        // Multi-color path gradient (Blue -> Indigo -> Violet)
        const lineGradient = ctxTren.createLinearGradient(0, 0, ctxTren.canvas.clientWidth || 600, 0);
        lineGradient.addColorStop(0, '#3b82f6');
        lineGradient.addColorStop(0.5, '#6366f1');
        lineGradient.addColorStop(1, '#a855f7');
 
        // Smooth fading area fill
        const gradientTren = ctxTren.createLinearGradient(0, 0, 0, 300);
        gradientTren.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
        gradientTren.addColorStop(0.5, 'rgba(168, 85, 247, 0.1)');
        gradientTren.addColorStop(1, 'rgba(168, 85, 247, 0.0)');
 
        const chartTren = new Chart(ctxTren, {
            type: 'line',
            data: {
                labels: chartPayload.tren.labels,
                datasets: [{
                    label: "{{ __('Biaya (Rp)') }}",
                    data: chartPayload.tren.data,
                    borderColor: lineGradient,
                    backgroundColor: gradientTren,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 3,
                    borderWidth: 3.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }, 
                    tooltip: { 
                        padding: 12, 
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 13 },
                        cornerRadius: 8,
                        displayColors: false
                    } 
                },
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
 
    // 3. Top 5 Expense Vehicles Chart (Bar) with modern warning gradient (Purple -> Rose -> Coral)
    if (chartPayload.boros) {
        const ctxBoros = document.getElementById('chartBoros').getContext('2d');
        
        const gradientBoros = ctxBoros.createLinearGradient(0, 0, 500, 0);
        gradientBoros.addColorStop(0, '#8b5cf6'); // Violet
        gradientBoros.addColorStop(0.5, '#ec4899'); // Rose Pink
        gradientBoros.addColorStop(1, '#f97316'); // Coral Orange
 
        const chartBoros = new Chart(ctxBoros, {
            type: 'bar',
            data: {
                labels: chartPayload.boros.labels,
                datasets: [{
                    label: "{{ __('Total Biaya (Rp)') }}",
                    data: chartPayload.boros.data,
                    backgroundColor: gradientBoros,
                    borderRadius: 8,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { 
                    legend: { display: false }, 
                    tooltip: { 
                        padding: 12, 
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 13 },
                        cornerRadius: 8,
                        displayColors: false
                    } 
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: currentColors.grid, drawBorder: false },
                        ticks: { callback: function (v) { return 'Rp ' + v.toLocaleString('id-ID'); }, color: currentColors.text, font: { family: 'Inter', size: 11 } },
                        border: { display: false }
                    },
                    y: { 
                        grid: { display: false }, 
                        ticks: { color: currentColors.textBold, font: { family: 'Inter', weight: '600', size: 12 } }, 
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
    // Initialize Bootstrap tooltips with theme check
    document.addEventListener('DOMContentLoaded', function() {
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



        // --- 📅 SERVICE CALENDAR BUILDER ---
        var currentDate = new Date();
        var currentMonth = currentDate.getMonth();
        var currentYear = currentDate.getFullYear();
        var monthNames = [
            "{{ __('Januari') }}", "{{ __('Februari') }}", "{{ __('Maret') }}", "{{ __('April') }}",
            "{{ __('Mei') }}", "{{ __('Juni') }}", "{{ __('Juli') }}", "{{ __('Agustus') }}",
            "{{ __('September') }}", "{{ __('Oktober') }}", "{{ __('November') }}", "{{ __('Desember') }}"
        ];
        const currentLocale = '{{ App::getLocale() === 'en' ? 'en-US' : 'id-ID' }}';

        function showAgendaForDate(dateStr) {
            const parsedDate = new Date(dateStr);
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = parsedDate.toLocaleDateString(currentLocale, options);
            
            const dateTitle = document.getElementById('agendaSelectedDate');
            if (dateTitle) dateTitle.textContent = formattedDate;
            
            const container = document.getElementById('agendaListContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            const events = (chartPayload.calendarEvents || []).filter(e => e.date === dateStr);
            
            if (events.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 my-3">
                        <div class="mb-3 mx-auto text-muted d-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background-color: rgba(8, 145, 178, 0.08) !important;">
                            <i class="bi bi-calendar-check text-info fs-4" style="color: #0891b2 !important;"></i>
                        </div>
                        <h6 class="fw-bold text-dark dark-text-white mb-1">${"{{ __('Bebas Operasional') }}"}</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.78rem;">${"{{ __('Tidak ada jadwal servis atau KIR untuk armada pada tanggal ini.') }}"}</p>
                    </div>
                `;
                return;
            }
            
            events.forEach(ev => {
                const isKir = ev.type === 'kir';
                const icon = isKir ? 'bi-shield-check' : 'bi-wrench-adjustable';
                const bgClass = isKir ? 'bg-soft-info text-info' : 'bg-soft-primary text-primary';
                const label = isKir ? "{{ __('Jatuh Tempo KIR') }}" : "{{ __('Rencana Servis') }}";
                
                const vehicle = (chartPayload.vehicles || []).find(v => v.plat_nomor === ev.plat_nomor);
                const url = vehicle ? `/vehicles/${vehicle.id}` : '#';
                
                const itemHtml = `
                    <div class="p-3 mb-3 rounded-3 border d-flex align-items-center justify-content-between hover-lift" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.08) !important; transition: transform 0.2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box d-flex align-items-center justify-content-center ${bgClass} rounded-3" style="width: 42px; height: 42px; font-size: 1.2rem; flex-shrink: 0; background-color: ${isKir ? 'rgba(8, 145, 178, 0.1)' : 'rgba(79, 70, 229, 0.1)'}; color: ${isKir ? '#0891b2' : '#4f46e5'};">
                                <i class="bi ${icon}"></i>
                            </div>
                            <div class="text-start">
                                <span class="badge mb-1" style="font-size: 0.65rem; font-weight: 700; background-color: ${ev.color}20; color: ${ev.color}; border: 1px solid ${ev.color}40;">
                                    ${label}
                                </span>
                                <h6 class="fw-extrabold text-dark dark-text-white mb-0.5 font-monospace" style="font-size: 0.9rem; letter-spacing: -0.3px;">${ev.plat_nomor}</h6>
                                <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.2;">${ev.merek || "{{ __('Detail Unit') }}"}</small>
                            </div>
                        </div>
                        <div>
                            ${vehicle ? `
                            <a href="${url}" class="btn btn-sm px-3 py-1.5 text-white fw-bold rounded-pill" style="font-size: 0.72rem; background: linear-gradient(to right, #0891b2, #0e3054); border: none;">
                                ${"{{ __('Detail') }}"} <i class="bi bi-chevron-right ms-0.5" style="font-size: 0.65rem;"></i>
                            </a>
                            ` : ''}
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
            });
        }

        function renderCalendar(month, year) {
            var firstDay = new Date(year, month, 1).getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var calendarContainer = document.getElementById('calendarContainer');
            var monthTitle = document.getElementById('calendarMonthTitle');
            
            if (!calendarContainer || !monthTitle) return;

            var headersHtml = `
                <div class="calendar-day-header">${"{{ __('Min') }}"}</div>
                <div class="calendar-day-header">${"{{ __('Sen') }}"}</div>
                <div class="calendar-day-header">${"{{ __('Sel') }}"}</div>
                <div class="calendar-day-header">${"{{ __('Rab') }}"}</div>
                <div class="calendar-day-header">${"{{ __('Kam') }}"}</div>
                <div class="calendar-day-header">${"{{ __('Jum') }}"}</div>
                <div class="calendar-day-header">${"{{ __('Sab') }}"}</div>
            `;
            calendarContainer.innerHTML = headersHtml;
            monthTitle.textContent = monthNames[month] + " " + year;

            for (let i = 0; i < firstDay; i++) {
                var emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-cell-empty';
                calendarContainer.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                var cell = document.createElement('div');
                cell.className = 'calendar-cell';
                
                var dateStr = year + "-" + String(month + 1).padStart(2, '0') + "-" + String(day).padStart(2, '0');
                cell.setAttribute('data-date', dateStr);
                
                var isToday = (day === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear());
                if (isToday) {
                    cell.classList.add('today');
                }

                var cellHeader = document.createElement('div');
                cellHeader.className = 'calendar-date-number';
                cellHeader.textContent = day;
                cell.appendChild(cellHeader);

                if (chartPayload.calendarEvents) {
                    var dayEvents = chartPayload.calendarEvents.filter(function(e) {
                        return e.date === dateStr;
                    });

                    if (dayEvents.length > 0) {
                        var eventsContainer = document.createElement('div');
                        eventsContainer.className = 'd-flex flex-column gap-1 w-100 mt-1';
                        
                        dayEvents.forEach(function(ev) {
                            var isKir = ev.type === 'kir';
                            var badge = document.createElement('div');
                            badge.className = 'calendar-event-badge d-none d-md-flex align-items-center gap-1';
                            badge.style.backgroundColor = isKir ? 'rgba(8, 145, 178, 0.1)' : 'rgba(79, 70, 229, 0.1)';
                            badge.style.color = isKir ? '#0891b2' : '#4f46e5';
                            badge.style.border = '1px solid ' + (isKir ? 'rgba(8, 145, 178, 0.2)' : 'rgba(79, 70, 229, 0.2)');
                            badge.innerHTML = `<i class="bi ${isKir ? 'bi-shield-check' : 'bi-wrench-adjustable'}" style="font-size:0.6rem;"></i> <span class="text-truncate" style="max-width: 100%;">${ev.plat_nomor}</span>`;
                            
                            badge.setAttribute('data-bs-toggle', 'tooltip');
                            badge.setAttribute('data-bs-title', ev.title);
                            eventsContainer.appendChild(badge);
                        });
                        
                        var dotsContainer = document.createElement('div');
                        dotsContainer.className = 'd-flex d-md-none gap-1 justify-content-start flex-wrap mt-0.5';
                        dayEvents.forEach(function(ev) {
                            var dot = document.createElement('span');
                            dot.className = 'calendar-event-dot';
                            dot.style.backgroundColor = ev.color;
                            dotsContainer.appendChild(dot);
                        });
                        eventsContainer.appendChild(dotsContainer);
                        
                        cell.appendChild(eventsContainer);
                    }
                }

                cell.addEventListener('click', function() {
                    document.querySelectorAll('.calendar-cell').forEach(c => c.classList.remove('selected-date'));
                    this.classList.add('selected-date');
                    showAgendaForDate(this.getAttribute('data-date'));
                });

                calendarContainer.appendChild(cell);
            }

            var totalCells = firstDay + daysInMonth;
            var nextMultipleOf7 = Math.ceil(totalCells / 7) * 7;
            var emptyCellsAtEnd = nextMultipleOf7 - totalCells;
            for (let i = 0; i < emptyCellsAtEnd; i++) {
                var emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-cell-empty';
                calendarContainer.appendChild(emptyCell);
            }

            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = document.querySelectorAll('.calendar-event-badge');
                [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            }

            var todayCell = calendarContainer.querySelector('.calendar-cell.today');
            if (todayCell) {
                todayCell.classList.add('selected-date');
                showAgendaForDate(todayCell.getAttribute('data-date'));
            } else {
                var firstCell = calendarContainer.querySelector('.calendar-cell');
                if (firstCell) {
                    firstCell.classList.add('selected-date');
                    showAgendaForDate(firstCell.getAttribute('data-date'));
                }
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



        // Smooth scroll for any link pointing to # with header offset
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 90
                }, 400);
            }
        });
    });

    // Real-time clock with dynamic locale support
    function updateClock() {
        const now = new Date();
        const locale = '{{ app()->getLocale() === "en" ? "en-US" : "id-ID" }}';
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
            const timeZoneSuffix = locale === 'en-US' ? ' (WIB)' : ' WIB';
            clockEl.textContent = '• ' + now.toLocaleDateString(locale, options) + timeZoneSuffix;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection