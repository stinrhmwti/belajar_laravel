@extends('layouts.app')

@section('title', __('Pelacakan Kendaraan & Live Route ETA Armada'))

@push('styles')
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <style>
        /* -------------------------------------------------------------
           TRACKING LAYOUT & CORE DESIGN SYSTEM
        ------------------------------------------------------------- */
        .tracking-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .tracking-grid {
            display: grid;
            grid-template-columns: 420px 1fr;
            gap: 20px;
            min-height: calc(100vh - 190px);
        }

        @media (max-width: 1080px) {
            .tracking-grid {
                grid-template-columns: 1fr;
            }
            .tracking-sidebar {
                height: 480px;
                position: relative;
                top: 0;
            }
            .tracking-map-card {
                height: 600px;
            }
        }

        @media (max-width: 768px) {
            .tracking-map-header {
                padding: 10px 14px;
                gap: 8px;
            }
            .tracking-map-subbar {
                padding: 6px 12px;
                overflow-x: auto;
            }
            .floating-trip-hud {
                width: calc(100% - 24px);
                bottom: 12px;
                right: 12px;
                max-height: 85%;
            }
        }

        /* Custom Modern Scrollbar */
        .custom-sidebar-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-sidebar-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .custom-sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* -------------------------------------------------------------
           STATISTIC METRIC CARDS
        ------------------------------------------------------------- */
        .stat-metric-card {
            border-radius: 16px;
            padding: 16px 18px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        .stat-metric-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }
        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            font-size: 1.35rem;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* -------------------------------------------------------------
           SIDEBAR VEHICLE LIST & FILTER PILLS
        ------------------------------------------------------------- */
        .tracking-sidebar {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-color, #e2e8f0);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            height: calc(100vh - 210px);
            position: sticky;
            top: 80px;
        }

        .tracking-sidebar-header {
            padding: 16px 18px 12px 18px;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
            background: #f8fafc;
        }

        .tracking-sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-pill {
            font-size: 0.74rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            white-space: nowrap;
            transition: all 0.18s ease;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            cursor: pointer;
        }
        .filter-pill:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }
        .filter-pill.active {
            background: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .city-pill {
            font-size: 0.72rem;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 16px;
            white-space: nowrap;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .city-pill:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .city-pill.active {
            background: #0f172a !important;
            color: #ffffff !important;
            border-color: #0f172a !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
        }

        /* Vehicle Track Card */
        .vehicle-track-card {
            border: 1px solid var(--border-color, #e2e8f0);
            border-radius: 14px;
            padding: 14px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border-left: 4px solid #cbd5e1;
        }
        .vehicle-track-card:hover {
            border-color: #3b82f6;
            border-left-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(59, 130, 246, 0.12);
        }
        .vehicle-track-card.active {
            border-color: #2563eb;
            border-left-color: #2563eb;
            background: #f8faff;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .vehicle-track-card.status-trip {
            border-left-color: #0284c7;
        }
        .vehicle-track-card.status-standby {
            border-left-color: #10b981;
        }
        .vehicle-track-card.status-servis {
            border-left-color: #f59e0b;
        }
        .vehicle-track-card.status-warning {
            border-left-color: #ef4444;
        }

        /* Indonesian Plate Badge Style */
        .plat-badge {
            background: #0f172a;
            color: #ffffff;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 2px 8px;
            border-radius: 6px;
            border: 1px solid #334155;
            letter-spacing: 0.5px;
            display: inline-block;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }

        /* Mini Route Manifest Timeline */
        .route-manifest-timeline {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 10px;
            margin: 8px 0;
            font-size: 0.76rem;
            position: relative;
        }
        .route-timeline-item {
            display: flex;
            align-items: center;
            gap: 7px;
            line-height: 1.35;
        }
        .route-timeline-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Card Action Bar & Button Styling */
        .card-action-bar {
            padding-top: 8px;
            margin-top: 8px;
            border-top: 1px solid #f1f5f9;
        }
        .btn-card-main {
            height: 32px;
            padding: 0 8px;
            font-size: 0.73rem;
            font-weight: 600;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            transition: all 0.18s ease;
        }
        .btn-card-main:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.12);
        }
        .btn-card-sub {
            height: 28px;
            padding: 0 4px;
            font-size: 0.69rem;
            font-weight: 500;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #475569;
            white-space: nowrap;
            text-decoration: none !important;
            transition: all 0.18s ease;
        }
        .btn-card-sub:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
        }
        body.dark-theme .card-action-bar {
            border-top-color: #334155;
        }
        body.dark-theme .btn-card-sub {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }
        body.dark-theme .btn-card-sub:hover {
            background: #334155;
            color: #ffffff;
        }

        /* -------------------------------------------------------------
           MAP CARD & TOOLBARS
        ------------------------------------------------------------- */
        .tracking-map-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-color, #e2e8f0);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            height: calc(100vh - 210px);
            position: relative;
        }

        .tracking-map-header {
            padding: 12px 18px;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            z-index: 10;
        }

        .tracking-map-subbar {
            padding: 8px 18px;
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            z-index: 9;
        }

        #liveFleetMap {
            flex: 1;
            width: 100%;
            height: 100%;
            min-height: 480px;
            background: #e2e8f0;
            z-index: 1;
        }

        /* Live Indicator Badge */
        .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: live-pulse 1.8s infinite;
        }
        @keyframes live-pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 7px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Legend Chips */
        .legend-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-size: 0.74rem;
            font-weight: 600;
            color: #475569;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            white-space: nowrap;
        }
        .legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        /* -------------------------------------------------------------
           CUSTOM LEAFLET MARKERS & OVERLAYS
        ------------------------------------------------------------- */
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
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
            border: 2px solid #ffffff;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .marker-pin i {
            transform: rotate(45deg);
            color: #ffffff;
            font-size: 1.15rem;
            margin-top: -2px;
            margin-left: -2px;
        }
        .marker-speed-tag {
            position: absolute;
            top: -34px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.92);
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 12px;
            white-space: nowrap;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .marker-pulse {
            background: rgba(14, 165, 233, 0.35);
            border-radius: 50%;
            height: 24px;
            width: 24px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin: -12px 0 0 -12px;
            animation: pulse-ring 1.8s ease-out infinite;
            z-index: -1;
        }

        .marker-ready .marker-pin {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        .marker-ready .marker-pulse {
            background: rgba(16, 185, 129, 0.45);
        }
        .marker-servis .marker-pin {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        .marker-servis .marker-pulse {
            background: rgba(245, 158, 11, 0.45);
        }
        .marker-warning .marker-pin {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        .marker-warning .marker-pulse {
            background: rgba(239, 68, 68, 0.45);
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.6); opacity: 1; }
            100% { transform: scale(2.8); opacity: 0; }
        }

        /* Destination Flag Pin */
        .custom-dest-marker {
            position: relative;
        }
        .dest-flag-pin {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border: 2px solid #ffffff;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1rem;
            position: absolute;
            left: 50%;
            top: 50%;
            margin: -16px 0 0 -16px;
            animation: dest-bounce 2s infinite ease-in-out;
        }
        @keyframes dest-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* Origin Pin */
        .custom-origin-marker {
            position: relative;
        }
        .origin-pool-pin {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: 2px solid #ffffff;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 0.85rem;
            position: absolute;
            left: 50%;
            top: 50%;
            margin: -14px 0 0 -14px;
        }

        /* Hub Strategic Pin (Clean & Unobtrusive) */
        .hub-landmark-marker {
            position: relative;
        }
        .hub-pin {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            border: 2px solid #ffffff;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 0.72rem;
            position: absolute;
            left: 50%;
            top: 50%;
            margin: -12px 0 0 -12px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .hub-pin:hover {
            transform: scale(1.2);
            z-index: 100;
        }

        /* -------------------------------------------------------------
           FLOATING TRIP TELEMETRY HUD PANEL
        ------------------------------------------------------------- */
        .floating-trip-hud {
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 18px;
            box-shadow: 0 16px 44px rgba(15, 23, 42, 0.2);
            width: 390px;
            max-width: calc(100% - 40px);
            max-height: calc(100% - 40px);
            overflow-y: auto;
            padding: 18px;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: none;
        }
        .floating-trip-hud.show {
            display: block;
            animation: slideInUp 0.35s ease-out;
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hud-route-timeline {
            position: relative;
            padding-left: 24px;
            margin: 12px 0;
        }
        .hud-route-timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 10px;
            bottom: 12px;
            width: 2px;
            background: repeating-linear-gradient(to bottom, #3b82f6 0, #3b82f6 4px, transparent 4px, transparent 8px);
        }
        .hud-timeline-node {
            position: relative;
            margin-bottom: 10px;
        }
        .hud-timeline-node:last-child {
            margin-bottom: 0;
        }
        .hud-timeline-bullet {
            position: absolute;
            left: -24px;
            top: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: #fff;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .route-progress-bar {
            height: 6px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
            position: relative;
        }
        .route-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #10b981);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* -------------------------------------------------------------
           LEAFLET CONTROLS & POPUPS
        ------------------------------------------------------------- */
        .leaflet-top.leaflet-left .leaflet-control-zoom {
            margin-top: 14px !important;
            margin-left: 14px !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.12) !important;
        }
        .leaflet-control-zoom a {
            background-color: #ffffff !important;
            color: #1e293b !important;
            width: 32px !important;
            height: 32px !important;
            line-height: 30px !important;
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            transition: all 0.15s ease !important;
        }
        .leaflet-control-zoom a:hover {
            background-color: #f1f5f9 !important;
            color: #2563eb !important;
        }

        .leaflet-control-scale-line {
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid #64748b !important;
            border-top: none !important;
            color: #0f172a !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            border-radius: 0 0 6px 6px;
            padding: 2px 8px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            letter-spacing: 0.3px;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 14px !important;
            box-shadow: 0 14px 34px -4px rgba(15, 23, 42, 0.2) !important;
            padding: 0 !important;
            overflow: hidden !important;
            border: 1px solid rgba(226, 232, 240, 0.95);
        }
        .leaflet-popup-content {
            margin: 14px 16px !important;
        }

        /* Floating City Focus Toast */
        .map-city-toast {
            position: absolute;
            top: 14px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #ffffff;
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0,0,0,0.35);
            border: 1px solid rgba(255,255,255,0.25);
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .map-city-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* Dropdown Styling */
        .tracking-map-header .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.18s ease;
        }
        .tracking-map-header .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }
        .tracking-map-header .dropdown-menu {
            box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.18), 0 4px 14px -2px rgba(15, 23, 42, 0.08) !important;
            border: 1px solid rgba(226, 232, 240, 0.95) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.98) !important;
        }
        .tracking-map-header .dropdown-item {
            transition: all 0.15s ease;
            border-radius: 8px;
            margin: 1px 4px;
            width: calc(100% - 8px);
        }
        .tracking-map-header .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #2563eb;
            transform: translateX(2px);
        }
        .tracking-map-header .dropdown-item.active {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        /* -------------------------------------------------------------
           DARK THEME SUPPORT
        ------------------------------------------------------------- */
        body.dark-theme .tracking-sidebar,
        body.dark-theme .tracking-map-card,
        body.dark-theme .tracking-sidebar-header,
        body.dark-theme .tracking-map-header,
        body.dark-theme .tracking-map-subbar {
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
        body.dark-theme .route-manifest-timeline {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-theme .filter-pill {
            background: #0f172a;
            border-color: #334155;
            color: #cbd5e1;
        }
        body.dark-theme .city-pill {
            background: #0f172a;
            border-color: #334155;
            color: #94a3b8;
        }
        body.dark-theme .floating-trip-hud {
            background: rgba(30, 41, 59, 0.96) !important;
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
    <!-- Header Title & Quick Actions Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-1">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="live-dot"></span>
                <h3 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-primary"></i> {{ __('Pelacakan Kendaraan & Live Route ETA') }}
                </h3>
            </div>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">
                {{ __('Pantau posisi geografis live, rute perjalanan aktif (Asal ➔ Tujuan), telemetri kecepatan, dan estimasi waktu tiba (ETA) seluruh armada.') }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-xs" id="btnRefreshTracking" style="border-radius: 10px; font-size: 0.85rem; padding: 7px 14px; font-weight: 600;">
                <i class="bi bi-arrow-clockwise" id="refreshIcon"></i> {{ __('Segarkan Data Peta') }}
            </button>
            <a href="{{ route('vehicles.index') }}" class="btn btn-light border d-flex align-items-center gap-2 shadow-xs" style="border-radius: 10px; font-size: 0.85rem; padding: 7px 14px; font-weight: 600;">
                <i class="bi bi-car-front text-secondary"></i> {{ __('Daftar Armada') }}
            </a>
        </div>
    </div>

    <!-- 4 Executive Stat Metric Cards (Aligned with Fleet Management Data) -->
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="stat-metric-card d-flex align-items-center gap-3 btn-stat-filter" data-filter="all" style="background: linear-gradient(135deg, #0f172a, #1e293b); cursor: pointer;" title="{{ __('Klik untuk lihat seluruh armada') }}">
                <div class="stat-icon-box">
                    <i class="bi bi-truck-front-fill text-info"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Total Terlacak') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['total'] }} <span style="font-size: 0.8rem; font-weight: 400; color: #94a3b8;">Unit</span></div>
                    <div class="text-white-50 mt-0.5" style="font-size: 0.68rem;">100% Terdaftar di Sistem</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-metric-card d-flex align-items-center gap-3 btn-stat-filter" data-filter="on_trip" style="background: linear-gradient(135deg, #0369a1, #0284c7); cursor: pointer;" title="{{ __('Klik untuk filter armada siap jalan') }}">
                <div class="stat-icon-box">
                    <i class="bi bi-check-circle-fill text-white"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Siap Pakai') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['siap_pakai'] }} <span style="font-size: 0.8rem; font-weight: 400; color: #bae6fd;">Unit</span></div>
                    <div class="text-white-50 mt-0.5" style="font-size: 0.68rem;">{{ $stats['on_trip'] }} Di Jalan &bull; {{ $stats['standby'] }} Standby</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-metric-card d-flex align-items-center gap-3 btn-stat-filter" data-filter="servis" style="background: linear-gradient(135deg, #b45309, #d97706); cursor: pointer;" title="{{ __('Klik untuk filter armada yang sedang diservis') }}">
                <div class="stat-icon-box">
                    <i class="bi bi-tools text-white"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Sedang Diservis') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['sedang_diservis'] }} <span style="font-size: 0.8rem; font-weight: 400; color: #fde68a;">Unit</span></div>
                    <div class="text-white-50 mt-0.5" style="font-size: 0.68rem;">Dalam Perawatan Bengkel</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-metric-card d-flex align-items-center gap-3 btn-stat-filter" data-filter="on_trip" style="background: linear-gradient(135deg, #047857, #10b981); cursor: pointer;" title="{{ __('Klik untuk pantau kecepatan armada aktif') }}">
                <div class="stat-icon-box">
                    <i class="bi bi-speedometer2 text-white"></i>
                </div>
                <div>
                    <div class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Rata-rata Kecepatan') }}</div>
                    <div class="fs-4 fw-bold">{{ $stats['avg_speed'] }} <span style="font-size: 0.8rem; font-weight: 400; color: #a7f3d0;">km/jam</span></div>
                    <div class="text-white-50 mt-0.5" style="font-size: 0.68rem;">{{ $stats['on_trip'] }} Armada Bergerak</div>
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
                <div class="input-group input-group-sm mb-2.5">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="trackingSearch" class="form-control border-start-0 custom-search-input" placeholder="{{ __('Cari plat, supir, rute, tujuan...') }}">
                </div>

                <!-- Trip Status Filter Pills -->
                <div class="d-flex align-items-center gap-1.5 overflow-x-auto pb-1 mb-2 hide-scrollbar" id="filterPills">
                    <button class="filter-pill active" data-filter="all">{{ __('Semua Status') }}</button>
                    <button class="filter-pill" data-filter="on_trip">{{ __('Di Jalan') }}</button>
                    <button class="filter-pill" data-filter="standby">{{ __('Standby') }}</button>
                    <button class="filter-pill" data-filter="servis">{{ __('Servis') }}</button>
                    <button class="filter-pill" data-filter="warning">{{ __('Peringatan') }}</button>
                </div>

                <!-- Regional Filter Pills (Aligned with Fleet Operational Areas) -->
                <div class="d-flex align-items-center gap-1.5 overflow-x-auto pb-0.5 hide-scrollbar" id="cityFilterPills">
                    <button class="city-pill active" data-city="all">{{ __('Semua Area') }}</button>
                    <button class="city-pill" data-city="jakarta">Jakarta</button>
                    <button class="city-pill" data-city="bekasi">Bekasi</button>
                    <button class="city-pill" data-city="cikarang">Cikarang</button>
                    <button class="city-pill" data-city="karawang">Karawang</button>
                    <button class="city-pill" data-city="bandung">Bandung</button>
                    <button class="city-pill" data-city="tangerang">Tangerang</button>
                    <button class="city-pill" data-city="bogor">Bogor</button>
                    <button class="city-pill" data-city="depok">Depok</button>
                    <button class="city-pill" data-city="merak">Banten/Merak</button>
                </div>
            </div>

            <!-- Vehicle Cards List -->
            <div class="tracking-sidebar-content custom-sidebar-scrollbar" id="vehicleCardsList">
                @forelse($mapVehicles as $v)
                    @php
                        $cardStatusClass = 'status-standby';
                        if ($v['status_perjalanan'] === 'Dalam Perjalanan ke Tujuan') {
                            $cardStatusClass = 'status-trip';
                        } elseif ($v['status'] === 'Sedang Diservis') {
                            $cardStatusClass = 'status-servis';
                        } elseif ($v['marker_type'] === 'marker-warning') {
                            $cardStatusClass = 'status-warning';
                        }
                    @endphp
                    <div class="vehicle-track-card {{ $cardStatusClass }} {{ (isset($selectedVehicleId) && $selectedVehicleId == $v['id']) ? 'active' : '' }}" 
                         data-id="{{ $v['id'] }}"
                         data-plat="{{ strtolower($v['plat_nomor']) }}"
                         data-merek="{{ strtolower($v['merek'] . ' ' . $v['tipe']) }}"
                         data-supir="{{ strtolower($v['supir_utama']) }}"
                         data-asal="{{ strtolower($v['lokasi_asal']) }}"
                         data-tujuan="{{ strtolower($v['lokasi_tujuan']) }}"
                         data-pool="{{ strtolower($v['lokasi_pool']) }}"
                         data-status="{{ $v['status'] }}"
                         data-status-perjalanan="{{ $v['status_perjalanan'] }}"
                         data-marker-type="{{ $v['marker_type'] }}"
                         data-lat="{{ $v['latitude'] }}"
                         data-lng="{{ $v['longitude'] }}"
                         data-origin-lat="{{ $v['origin_lat'] }}"
                         data-origin-lng="{{ $v['origin_lng'] }}"
                         data-dest-lat="{{ $v['dest_lat'] }}"
                         data-dest-lng="{{ $v['dest_lng'] }}">
                        
                        <!-- Card Header: Image + Plate + Badges -->
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 44px; height: 44px; background: #f1f5f9; border: 1px solid #e2e8f0;">
                                <img src="{{ $v['foto_url'] }}" alt="{{ $v['plat_nomor'] }}" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="plat-badge">
                                        {{ $v['plat_nomor'] }}
                                    </span>
                                    <div>
                                        @if($v['status_perjalanan'] === 'Dalam Perjalanan ke Tujuan')
                                            <span class="badge bg-primary-subtle text-primary py-0.5 px-2 font-monospace fw-bold" style="font-size: 0.68rem; border-radius: 6px;">
                                                <i class="bi bi-speedometer2 me-0.5"></i> {{ $v['kecepatan_kmh'] }} km/h
                                            </span>
                                        @elseif($v['status_perjalanan'] === 'Selesai Mengantar')
                                            <span class="badge bg-success-subtle text-success py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;">
                                                <i class="bi bi-check-circle-fill me-0.5"></i> Selesai
                                            </span>
                                        @elseif($v['status_perjalanan'] === 'Proses Bongkar Muat')
                                            <span class="badge bg-warning-subtle text-warning py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;">Bongkar</span>
                                        @elseif($v['status'] === 'Sedang Diservis')
                                            <span class="badge bg-danger-subtle text-danger py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;">Servis</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;">Standby</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between text-muted mt-1" style="font-size: 0.76rem;">
                                    <span class="text-truncate" style="max-width: 170px;">{{ $v['merek'] }} {{ $v['tipe'] }}</span>
                                    <span class="text-dark fw-semibold text-truncate" style="max-width: 130px;"><i class="bi bi-person me-0.5 text-secondary"></i>{{ $v['supir_utama'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Route Manifest Box -->
                        <div class="route-manifest-timeline">
                            <div class="route-timeline-item mb-1">
                                <span class="route-timeline-dot bg-primary"></span>
                                <span class="text-muted" style="font-size: 0.7rem;">Asal:</span>
                                <span class="fw-medium text-dark text-truncate">{{ $v['lokasi_asal'] }}</span>
                            </div>
                            <div class="route-timeline-item">
                                <span class="route-timeline-dot bg-danger"></span>
                                <span class="text-muted" style="font-size: 0.7rem;">Tujuan:</span>
                                <span class="fw-bold text-danger text-truncate">{{ $v['lokasi_tujuan'] }}</span>
                            </div>
                        </div>

                        <!-- ETA Badge & Distance Sisa -->
                        <div class="d-flex align-items-center justify-content-between mb-2" style="font-size: 0.74rem;">
                            <span class="badge {{ $v['eta_badge_class'] }} text-white py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="bi bi-clock-history"></i> {{ $v['eta_short'] }}
                            </span>
                            <span class="text-secondary font-monospace" style="font-size: 0.74rem;">
                                Sisa <b>{{ $v['jarak_sisa_km'] }} km</b>
                            </span>
                        </div>

                        <!-- Action Buttons: Clean 2-Tier Structured Layout -->
                        <div class="card-action-bar">
                            <div class="row g-1 mb-1">
                                <div class="col-7">
                                    <button class="btn btn-sm btn-primary text-white w-100 btn-card-main btn-draw-nav" data-id="{{ $v['id'] }}" title="{{ __('Fokuskan armada & gambarkan rute perjalanan lengkap') }}">
                                        <i class="bi bi-signpost-2-fill"></i> {{ __('Rute & ETA') }}
                                    </button>
                                </div>
                                <div class="col-5">
                                    @if($v['status'] === 'Sedang Diservis')
                                        <span class="btn btn-sm btn-light border text-muted w-100 btn-card-main" style="cursor: not-allowed;" title="Armada dalam perawatan bengkel">
                                            <i class="bi bi-tools text-warning"></i> Servis
                                        </span>
                                    @elseif($v['status_perjalanan'] === 'Selesai Mengantar')
                                        <button class="btn btn-sm btn-primary text-white w-100 btn-card-main btn-assign-trip" data-id="{{ $v['id'] }}" data-plat="{{ $v['plat_nomor'] }}" data-supir="{{ $v['supir_utama'] }}" title="{{ __('Tugaskan rute pengantaran baru ke alamat berikutnya') }}">
                                            <i class="bi bi-send-plus-fill"></i> Rute Baru
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success text-white w-100 btn-card-main btn-complete-trip" data-id="{{ $v['id'] }}" data-plat="{{ $v['plat_nomor'] }}" data-tujuan="{{ $v['lokasi_tujuan'] }}" title="{{ __('Tandai pengantaran telah selesai tiba di tujuan') }}">
                                            <i class="bi bi-check2-circle"></i> Selesai
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="row g-1">
                                <div class="col-4">
                                    <button class="btn btn-sm btn-card-sub w-100 btn-assign-trip" data-id="{{ $v['id'] }}" data-plat="{{ $v['plat_nomor'] }}" data-supir="{{ $v['supir_utama'] }}" title="{{ __('Ubah / Tugaskan tujuan pengantaran baru') }}">
                                        <i class="bi bi-geo-alt-fill text-primary"></i> Tujuan
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-sm btn-card-sub w-100 btn-sync-gps" data-id="{{ $v['id'] }}" data-plat="{{ $v['plat_nomor'] }}" title="{{ __('Sinkronkan koordinat GPS') }}">
                                        <i class="bi bi-broadcast text-info"></i> GPS
                                    </button>
                                </div>
                                <div class="col-4">
                                    <a href="{{ $v['detail_url'] }}" class="btn btn-sm btn-card-sub w-100" title="{{ __('Lihat profil & riwayat armada') }}">
                                        <i class="bi bi-box-arrow-up-right text-secondary"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-car-front fs-2 d-block mb-2 text-secondary"></i>
                        <span>{{ __('Tidak ada data kendaraan ditemukan.') }}</span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Interactive Map Card -->
        <div class="tracking-map-card">
            <!-- 1. Map Header Main Bar -->
            <div class="tracking-map-header">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-primary px-2.5 py-1.5 rounded-pill text-uppercase fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <span class="live-dot" style="background:#fff;"></span> LIVE GPS & ROUTE
                    </span>
                    <span class="text-muted" style="font-size: 0.82rem;" id="activeVehicleCount">
                        Menampilkan <b>{{ $mapVehicles->count() }}</b> armada aktif
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Area & GPS Focus Buttons Group -->
                    <div class="btn-group btn-group-sm shadow-xs" role="group" aria-label="Fokus Wilayah">
                        <button type="button" class="btn btn-light border" id="btnFitAll" title="{{ __('Lihat Seluruh Sebaran Armada') }}" style="font-size: 0.78rem;">
                            <i class="bi bi-aspect-ratio me-1 text-primary"></i> {{ __('Semua Armada') }}
                        </button>

                        <!-- Region Navigation Dropdown (Aligned with Fleet Operational Zones) -->
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-light border dropdown-toggle d-flex align-items-center gap-1" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.78rem;" id="btnRegionDropdown">
                                <i class="bi bi-geo-alt-fill text-danger me-0.5"></i> <span id="currentRegionLabel">{{ __('Fokus Wilayah') }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 14px; font-size: 0.84rem; max-height: 420px; overflow-y: auto; min-width: 250px;">
                                <li><h6 class="dropdown-header text-primary fw-bold py-1 text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;"><i class="bi bi-buildings me-1"></i> Jabodetabek & Banten</h6></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="jakarta"><i class="bi bi-geo-alt me-1.5 text-secondary"></i> Jakarta (Pusat / Gambir)</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="priok"><i class="bi bi-water me-1.5 text-info"></i> Tj. Priok (Pelabuhan)</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="soetta"><i class="bi bi-airplane me-1.5 text-primary"></i> Bandara Soekarno-Hatta</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="bekasi"><i class="bi bi-geo-alt me-1.5 text-secondary"></i> Kota & Kab. Bekasi</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="tangerang"><i class="bi bi-geo-alt me-1.5 text-secondary"></i> Tangerang & BSD City</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="depok"><i class="bi bi-geo-alt me-1.5 text-secondary"></i> Depok & Margonda</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="bogor"><i class="bi bi-geo-alt me-1.5 text-secondary"></i> Bogor & Sentul City</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="merak"><i class="bi bi-ship me-1.5 text-danger"></i> Pelabuhan Merak Banten</a></li>
                                
                                <li><hr class="dropdown-divider my-1"></li>
                                <li><h6 class="dropdown-header text-success fw-bold py-1 text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;"><i class="bi bi-building-gear me-1"></i> Kawasan Industri & Jabar</h6></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="cikarang"><i class="bi bi-truck me-1.5 text-primary"></i> Cikarang (MM2100 & Jababeka)</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="karawang"><i class="bi bi-truck me-1.5 text-success"></i> Karawang (KIIC & Suryacipta)</a></li>
                                <li><a class="dropdown-item py-1.5 btn-focus-city" href="#" data-city="bandung"><i class="bi bi-geo-alt me-1.5 text-secondary"></i> Bandung (Pasteur & Soetta)</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Layer & Overlay Switcher (Featuring Real Google Maps) -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1 shadow-xs" type="button" data-bs-toggle="dropdown" style="border-radius: 8px; font-size: 0.78rem;">
                            <i class="bi bi-layers-fill text-primary"></i> {{ __('Tipe Peta & Layer') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 14px; font-size: 0.84rem; min-width: 290px;">
                            <li><h6 class="dropdown-header text-muted fw-bold py-1 text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('PETA DASAR GOOGLE MAPS (RESMI)') }}</h6></li>
                            <li><a class="dropdown-item py-2 active d-flex align-items-center justify-content-between" href="#" id="layerGoogleStreets">
                                <span><i class="bi bi-map me-2 text-primary"></i> {{ __('Google Maps (Jalan Resmi ID)') }}</span>
                                <span class="badge bg-primary-subtle text-primary" style="font-size: 0.65rem;">Default</span>
                            </a></li>
                            <li><a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="#" id="layerGoogleHybrid">
                                <span><i class="bi bi-globe-asia-australia me-2 text-info"></i> {{ __('Google Satelit Hibrida (Foto+Jalan)') }}</span>
                                <span class="badge bg-success text-white" style="font-size: 0.65rem;">Jelas & Realistis</span>
                            </a></li>
                            <li><a class="dropdown-item py-2" href="#" id="layerGoogleTerrain">
                                <i class="bi bi-compass me-2 text-success"></i> {{ __('Google Terrain (Topografi & Kontur)') }}
                            </a></li>
                            <li><a class="dropdown-item py-2" href="#" id="layerGoogleSatellite">
                                <i class="bi bi-bounding-box-circles me-2 text-secondary"></i> {{ __('Google Satelit Murni (Foto Udara)') }}
                            </a></li>
                            <li><a class="dropdown-item py-2" href="#" id="layerVoyager">
                                <i class="bi bi-geo me-2 text-secondary"></i> {{ __('Carto Voyager (Minimalis)') }}
                            </a></li>
                            <li><a class="dropdown-item py-2" href="#" id="layerDark">
                                <i class="bi bi-moon-stars me-2 text-dark"></i> {{ __('Mode Gelap (Command Center Dark)') }}
                            </a></li>

                            <li><hr class="dropdown-divider my-1"></li>
                            <li><h6 class="dropdown-header text-muted fw-bold py-1 text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('LAPISAN TAMBAHAN (OVERLAY)') }}</h6></li>
                            <li class="px-3 py-1.5">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleHubMarkers">
                                    <label class="form-check-label fw-semibold" for="toggleHubMarkers" style="font-size: 0.8rem; cursor: pointer;">
                                        {{ __('Titik Pool & Hub Operasional') }}
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Simulation Route Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle d-flex align-items-center gap-1 shadow-xs" type="button" data-bs-toggle="dropdown" id="btnSimulateMenu" style="border-radius: 8px; font-size: 0.78rem;">
                            <i class="bi bi-play-circle-fill"></i> {{ __('Simulasi Live') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 14px; font-size: 0.85rem; min-width: 290px;">
                            <li><h6 class="dropdown-header text-primary fw-bold py-1 text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('PILIH JALUR SIMULASI REALTIME') }}</h6></li>
                            <li><a class="dropdown-item py-2 btn-run-sim" href="#" data-route="cikarang"><i class="bi bi-truck me-2 text-primary"></i> {{ __('Simulasi 1: Tol Jakarta ➔ Cikarang') }}</a></li>
                            <li><a class="dropdown-item py-2 btn-run-sim" href="#" data-route="bandung"><i class="bi bi-truck-flatbed me-2 text-success"></i> {{ __('Simulasi 2: Tol Cipularang ➔ Bandung') }}</a></li>
                            <li><a class="dropdown-item py-2 btn-run-sim" href="#" data-route="bogor"><i class="bi bi-car-front me-2 text-warning"></i> {{ __('Simulasi 3: Tol Jagorawi ➔ Sentul Bogor') }}</a></li>
                            <li><a class="dropdown-item py-2 btn-run-sim" href="#" data-route="bandara"><i class="bi bi-airplane me-2 text-info"></i> {{ __('Simulasi 4: Cargo Bandara Soetta') }}</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li><a class="dropdown-item py-2 text-danger fw-semibold" href="#" id="btnStopSim"><i class="bi bi-stop-circle me-2"></i> {{ __('Hentikan Semua Simulasi') }}</a></li>
                        </ul>
                    </div>

                    <!-- Fullscreen Toggle -->
                    <button class="btn btn-sm btn-light border shadow-xs" id="btnToggleFullscreen" style="border-radius: 8px;" title="{{ __('Layar Penuh') }}">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                </div>
            </div>

            <!-- 2. Sub-Toolbar: Static Map Legend & Navigation Info Bar -->
            <div class="tracking-map-subbar">
                <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.76rem;">
                    <div class="legend-chip">
                        <span class="legend-dot" style="background: #10b981;"></span>
                        <span>{{ __('Siap / Di Jalan') }}</span>
                    </div>
                    <div class="legend-chip">
                        <span class="legend-dot" style="background: #f59e0b;"></span>
                        <span>{{ __('Servis') }}</span>
                    </div>
                    <div class="legend-chip">
                        <span class="legend-dot" style="background: #ef4444;"></span>
                        <span>{{ __('Peringatan KIR') }}</span>
                    </div>
                    <div class="legend-chip">
                        <i class="bi bi-flag-fill text-danger"></i>
                        <span>{{ __('Titik Tujuan (ETA)') }}</span>
                    </div>
                    <div class="legend-chip text-primary fw-bold" id="navRouteInfoBadge" style="display: none; background: #eff6ff; border-color: #bfdbfe;">
                        <i class="bi bi-signpost-2-fill me-0.5"></i> <span id="navRouteInfoText">Rute Aktif</span>
                        <button type="button" class="btn-close btn-close-sm ms-1" id="btnClearNavRoute" style="font-size: 0.6rem;" title="Hapus garis rute"></button>
                    </div>
                </div>
                <div class="text-muted d-none d-lg-flex align-items-center gap-1.5" style="font-size: 0.74rem;">
                    <i class="bi bi-cursor-fill text-primary"></i> <span>{{ __('Klik armada untuk rute & telemetri lengkap • Scroll untuk zoom') }}</span>
                </div>
            </div>

            <!-- 3. Leaflet Map Canvas Area -->
            <div id="liveFleetMap" style="position: relative;">
                <div class="map-city-toast" id="mapCityToast"><i class="bi bi-geo-alt-fill text-warning"></i> <span id="mapCityToastText">Fokus Wilayah</span></div>
            </div>

            <!-- Floating Trip Telemetry HUD Panel -->
            <div class="floating-trip-hud shadow-lg" id="floatingTripHud">
                <!-- HUD Header: Plate & Status & Close Button -->
                <div class="d-flex align-items-center justify-content-between pb-2 mb-2.5 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="plat-badge" id="hudPlatNomor" style="font-size: 0.95rem;">B 1234 ABC</span>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1" id="hudStatusPerjalanan" style="font-size: 0.72rem; border-radius: 6px;">Dalam Perjalanan</span>
                    </div>
                    <button type="button" class="btn-close btn-close-sm" id="btnCloseHud" aria-label="Tutup"></button>
                </div>

                <!-- Driver & Vehicle Meta -->
                <div class="d-flex align-items-center gap-2.5 mb-2.5">
                    <img id="hudFoto" src="" class="rounded-3 object-fit-cover border flex-shrink-0" style="width: 48px; height: 48px; background: #f8fafc;">
                    <div class="overflow-hidden flex-grow-1">
                        <h6 class="mb-0 fw-bold text-dark text-truncate" id="hudMerekTipe" style="font-size: 0.92rem;">Mitsubishi Canter FE 74</h6>
                        <div class="text-muted small text-truncate mt-0.5" id="hudSupir" style="font-size: 0.76rem;"><i class="bi bi-person me-1"></i>Supir: <b>Driver Utama</b></div>
                        <div class="text-primary font-monospace small fw-bold" id="hudSpeed" style="font-size: 0.76rem;"><i class="bi bi-speedometer2 me-1"></i>Kecepatan: <b>72 km/jam</b></div>
                    </div>
                </div>

                <!-- Route Timeline Manifest -->
                <div class="p-2.5 bg-light rounded-3 border mb-2.5" style="font-size: 0.78rem;">
                    <div class="hud-route-timeline">
                        <div class="hud-timeline-node">
                            <span class="hud-timeline-bullet bg-primary"><i class="bi bi-geo-alt-fill" style="font-size:0.5rem;"></i></span>
                            <div class="lh-sm">
                                <span class="text-muted d-block" style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.4px;">Titik Asal</span>
                                <span class="fw-semibold text-dark text-break" id="hudAsal">Pool Pusat Gambir</span>
                            </div>
                        </div>
                        <div class="hud-timeline-node">
                            <span class="hud-timeline-bullet bg-danger"><i class="bi bi-flag-fill" style="font-size:0.5rem;"></i></span>
                            <div class="lh-sm">
                                <span class="text-muted d-block" style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.4px;">Titik Tujuan</span>
                                <span class="fw-bold text-danger text-break" id="hudTujuan">Kawasan Industri KIIC Karawang Barat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 0.72rem;">
                        <span>Progress Perjalanan</span>
                        <span class="fw-bold text-dark" id="hudProgressText">65% (Sisa 24 km)</span>
                    </div>
                    <div class="route-progress-bar">
                        <div class="route-progress-fill" id="hudProgressBar" style="width: 65%;"></div>
                    </div>
                </div>

                <!-- Highlight Live ETA Box -->
                <div class="p-2.5 mb-2.5 rounded-3 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe;">
                    <div>
                        <div class="text-primary text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">ESTIMASI TIBA (ETA)</div>
                        <div class="fw-bold text-dark fs-6 mt-0.5" id="hudEtaText">Tiba pkl 04:03 WIB (&plusmn; 56 mnt)</div>
                    </div>
                    <div class="rounded-circle bg-white shadow-xs p-2 d-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 38px; height: 38px; border: 1px solid #bfdbfe;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                </div>

                <!-- Catatan Muatan -->
                <div class="mb-2.5 text-muted px-0.5 d-flex align-items-start gap-1.5" style="font-size: 0.75rem;" id="hudCatatanContainer">
                    <i class="bi bi-box-seam text-secondary flex-shrink-0 mt-0.5"></i>
                    <span id="hudCatatan" class="text-break">Distribusi kontainer bahan baku ekspor</span>
                </div>

                <!-- Action Buttons: Clean 2-Row Grid -->
                <div class="d-flex flex-column gap-2">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <button type="button" class="btn btn-sm btn-success text-white py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-xs" id="hudBtnCompleteTrip" style="border-radius: 8px; font-size: 0.76rem;" title="{{ __('Tandai mobil telah tiba dan selesai mengantar ke alamat tujuan') }}">
                            <i class="bi bi-check2-circle fs-6"></i> {{ __('Selesai Antar') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-primary py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-xs" id="hudBtnAssignTrip" style="border-radius: 8px; font-size: 0.76rem;" title="{{ __('Tugaskan rute pengantaran baru ke alamat selanjutnya') }}">
                            <i class="bi bi-send-plus-fill"></i> {{ __('Rute Berikutnya') }}
                        </button>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <a href="#" target="_blank" class="btn btn-sm btn-outline-primary py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1" id="hudBtnGoogleMaps" style="border-radius: 8px; font-size: 0.76rem; text-decoration: none;">
                            <i class="bi bi-map"></i> Google Maps
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-secondary py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1" id="hudBtnDetail" style="border-radius: 8px; font-size: 0.76rem; text-decoration: none;">
                            <i class="bi bi-info-circle"></i> Detail Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL TRIP DISPATCHER (TUGASKAN / UBAH RUTE PERJALANAN)  -->
<!-- ======================================================== -->
<div class="modal fade" id="modalAssignTrip" tabindex="-1" aria-labelledby="modalAssignTripLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-primary text-white py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h6 class="modal-title fw-bold" id="modalAssignTripLabel">
                    <i class="bi bi-send-plus-fill me-1"></i> {{ __('Tugaskan Rute Pengantaran Baru / Selanjutnya') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAssignTrip" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-primary py-2 px-3 d-flex align-items-center gap-2 mb-3" style="border-radius: 10px; font-size: 0.82rem;">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>{{ __('Tentukan alamat tujuan pengantaran berikutnya atau pilih preset destinasi logistik. Sistem akan menghitung rute, jarak sisa, dan ETA baru secara otomatis.') }}</div>
                    </div>

                    <!-- Data Kendaraan -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Plat Nomor') }}</label>
                            <input type="text" id="assignPlatNomor" class="form-control fw-bold font-monospace bg-light" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Supir / Pengemudi') }}</label>
                            <input type="text" id="assignSupir" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Titik Asal Keberangkatan (Otomatis dari posisi saat ini) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase d-flex align-items-center justify-content-between" style="font-size: 0.72rem;">
                            <span>{{ __('Titik Asal / Lokasi Keberangkatan') }}</span>
                            <span class="badge bg-light text-secondary border font-normal" style="font-size: 0.65rem;">Posisi Saat Ini</span>
                        </label>
                        <input type="text" name="lokasi_asal" id="assignLokasiAsal" class="form-control fw-semibold" placeholder="Contoh: Kawasan Industri MM2100 Cikarang" required>
                    </div>

                    <!-- Preset Tujuan Populer -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Preset Destinasi Pengantaran Populer') }}</label>
                        <select class="form-select" id="selectPresetDestination">
                            <option value="">-- {{ __('Pilih Destinasi Tujuan Siap Pakai') }} --</option>
                            <optgroup label="Kawasan Industri Jabodetabek">
                                <option value="-6.301500,107.112000|Kawasan Industri MM2100 Cikarang|35">Cikarang - Kawasan Industri MM2100 (35 menit)</option>
                                <option value="-6.345000,107.280000|Kawasan Industri KIIC Karawang Barat|45">Karawang - Kawasan Industri KIIC Barat (45 menit)</option>
                                <option value="-6.365000,107.340000|Kawasan Industri Surya Cipta Karawang|50">Karawang - Kawasan Surya Cipta (50 menit)</option>
                                <option value="-6.284000,107.150000|Kawasan Industri Jababeka Cikarang|40">Cikarang - Kawasan Industri Jababeka (40 menit)</option>
                            </optgroup>
                            <optgroup label="Pelabuhan & Terminal Logistik">
                                <option value="-6.105000,106.885000|Terminal Petikemas Tanjung Priok|25">Jakarta - Terminal Petikemas Tanjung Priok (25 menit)</option>
                                <option value="-6.125000,106.655000|Terminal Kargo Bandara Soekarno-Hatta|30">Tangerang - Cargo Bandara Soekarno-Hatta (30 menit)</option>
                                <option value="-5.932000,105.998000|Pelabuhan Penyeberangan Merak Banten|90">Banten - Pelabuhan Penyeberangan Merak (90 menit)</option>
                            </optgroup>
                            <optgroup label="Jawa Barat - Bandung & Bogor">
                                <option value="-6.892000,107.578000|Pool Pasteur Bandung|60">Bandung - Pool Pasteur & Hub Distribusi (60 menit)</option>
                                <option value="-6.938500,107.625000|Gudang Logistik Soekarno-Hatta Bandung|65">Bandung - Gudang Soekarno-Hatta (65 menit)</option>
                                <option value="-6.536000,106.862000|Sentul City Convention & Warehouse Bogor|30">Bogor - Sentul City Warehouse (30 menit)</option>
                                <option value="-6.595000,106.806000|Pool Pajajaran Bogor|35">Bogor - Pool Pajajaran (35 menit)</option>
                            </optgroup>
                            <optgroup label="Tangerang & Depok">
                                <option value="-6.301000,106.652000|BSD Green Office Park & Warehouse Serpong|25">Tangerang - BSD City Green Office (25 menit)</option>
                                <option value="-6.372000,106.832000|Pusat Distribusi Margonda Depok|20">Depok - Pusat Distribusi Margonda (20 menit)</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Input Nama Tujuan & Koordinat -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Nama Lokasi / Alamat Tujuan Baru') }}</label>
                        <input type="text" name="lokasi_tujuan" id="assignLokasiTujuan" class="form-control fw-bold" placeholder="Contoh: Kawasan Industri KIIC Karawang Barat" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Latitude Tujuan') }}</label>
                            <input type="number" step="any" name="tujuan_latitude" id="assignTujuanLat" class="form-control" required placeholder="-6.3015">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Longitude Tujuan') }}</label>
                            <input type="number" step="any" name="tujuan_longitude" id="assignTujuanLng" class="form-control" required placeholder="107.1120">
                        </div>
                    </div>

                    <!-- Status Perjalanan & Kecepatan -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Status Perjalanan') }}</label>
                            <select name="status_perjalanan" id="assignStatusPerjalanan" class="form-select">
                                <option value="Dalam Perjalanan ke Tujuan">Dalam Perjalanan ke Tujuan</option>
                                <option value="Proses Bongkar Muat">Proses Bongkar Muat</option>
                                <option value="Perjalanan Kembali ke Pool">Perjalanan Kembali ke Pool</option>
                                <option value="Standby di Pool">Standby di Pool</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Kecepatan Rata-rata (KM/H)') }}</label>
                            <input type="number" name="kecepatan_kmh" id="assignKecepatan" class="form-control" value="60" min="0" max="160">
                        </div>
                    </div>

                    <!-- Estimasi Menit Manual (Opsional) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Estimasi Waktu Tempuh Tersisa (Menit)') }}</label>
                        <input type="number" name="estimasi_menit" id="assignEstimasiMenit" class="form-control" placeholder="Biarkan kosong untuk kalkulasi otomatis jarak/kecepatan">
                    </div>

                    <!-- Catatan Manifes -->
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Catatan Manifes / Muatan Barang') }}</label>
                        <textarea name="catatan_perjalanan" id="assignCatatan" rows="2" class="form-control" placeholder="Contoh: Pengiriman 150 karton komponen sparepart batch baru"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="btnSubmitAssignTrip" style="border-radius: 8px;">
                        <i class="bi bi-send-check me-1"></i> {{ __('Tugaskan Pengantaran & Mulai Rute') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL SELESAI PENGANTARAN & PROMPT RUTE BERIKUTNYA       -->
<!-- ======================================================== -->
<div class="modal fade" id="modalCompleteTripSuccess" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i> {{ __('Pengantaran Berhasil Selesai!') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle bg-success-subtle text-success mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-geo-alt-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Armada <span id="completeSuccessPlat" class="font-monospace text-primary">B 1234 ABC</span></h5>
                <p class="text-muted mb-3" style="font-size: 0.88rem;">
                    Telah tiba di titik tujuan pengantaran: <br>
                    <strong class="text-success fs-6" id="completeSuccessTujuan">Kawasan Industri MM2100 Cikarang</strong>
                </p>
                <div class="alert alert-light border py-2.5 px-3 text-start mb-2" style="border-radius: 12px; font-size: 0.82rem;">
                    <div class="d-flex align-items-center gap-2 text-muted mb-1.5">
                        <i class="bi bi-check2-all text-success fs-6"></i>
                        <span>Status Armada: <strong class="text-success">Selesai Mengantar (Standby di Lokasi)</strong></span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i class="bi bi-signpost-2 text-primary fs-6"></i>
                        <span>Titik Pengantaran Baru: <strong class="text-dark">Dapat ditugaskan dari alamat dropoff ini</strong></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light p-3 border-top d-flex gap-2">
                <button type="button" class="btn btn-secondary flex-grow-1" data-bs-dismiss="modal" style="border-radius: 10px;">
                    {{ __('Tetap Standby di Lokasi') }}
                </button>
                <button type="button" class="btn btn-primary fw-bold flex-grow-1 d-flex align-items-center justify-content-center gap-1.5" id="btnProceedNextTrip" style="border-radius: 10px;">
                    <i class="bi bi-send-plus-fill"></i> {{ __('Tugaskan Rute Berikutnya') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL UPDATE GPS POSISI HP / DEVICE MANUAL               -->
<!-- ======================================================== -->
<div class="modal fade" id="modalUpdateGps" tabindex="-1" aria-labelledby="modalUpdateGpsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-success text-white py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h6 class="modal-title fw-bold" id="modalUpdateGpsLabel">
                    <i class="bi bi-geo-alt-fill me-1"></i> {{ __('Perbarui Titik Koordinat GPS Kendaraan') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateGps" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 d-flex align-items-center gap-2 mb-3" style="border-radius: 10px; font-size: 0.82rem;">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>{{ __('Pilih titik pool kota atau sinkronkan langsung dengan GPS perangkat HP pengemudi.') }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Kendaraan') }}</label>
                        <input type="text" id="modalPlatNomor" class="form-control fw-bold font-monospace bg-light" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Preset Titik Lokasi / Pool') }}</label>
                        <select class="form-select" id="selectPresetPool">
                            <option value="">-- {{ __('Pilih Titik Lokasi Siap Pakai') }} --</option>
                            <optgroup label="DKI Jakarta">
                                <option value="-6.175392,106.827153|Pool Pusat Gambir">Jakarta - Pool Pusat Gambir (-6.1754, 106.8272)</option>
                                <option value="-6.126588,106.905663|Pool Tanjung Priok">Jakarta - Pool Tanjung Priok (-6.1266, 106.9057)</option>
                                <option value="-6.215000,106.817000|Pool Sudirman Senayan">Jakarta - Pool Sudirman Senayan (-6.2150, 106.8170)</option>
                                <option value="-6.155000,106.745000|Pool Daan Mogot">Jakarta - Pool Daan Mogot (-6.1550, 106.7450)</option>
                                <option value="-6.194000,106.888000|Pool Rawamangun">Jakarta - Pool Rawamangun (-6.1940, 106.8880)</option>
                            </optgroup>
                            <optgroup label="Jawa Barat - Bandung & Bekasi">
                                <option value="-6.938500,107.625000|Pool Soekarno-Hatta Bandung">Bandung - Pool Soekarno-Hatta (-6.9385, 107.6250)</option>
                                <option value="-6.892000,107.578000|Pool Pasteur Bandung">Bandung - Pool Pasteur (-6.8920, 107.5780)</option>
                                <option value="-6.238000,106.992000|Pool Summarecon Bekasi">Bekasi - Pool Summarecon (-6.2380, 106.9920)</option>
                                <option value="-6.284000,107.150000|Pool Kawasan Cikarang">Bekasi - Pool Industri Cikarang (-6.2840, 107.1500)</option>
                            </optgroup>
                            <optgroup label="Banten, Depok & Bogor">
                                <option value="-6.301000,106.652000|Pool BSD City Serpong">Tangerang - Pool BSD City (-6.3010, 106.6520)</option>
                                <option value="-6.372000,106.832000|Pool Margonda Depok">Depok - Pool Margonda (-6.3720, 106.8320)</option>
                                <option value="-6.595000,106.806000|Pool Pajajaran Bogor">Bogor - Pool Pajajaran (-6.5950, 106.8060)</option>
                                <option value="-6.536000,106.862000|Pool Sentul City">Bogor - Pool Sentul City (-6.5360, 106.8620)</option>
                            </optgroup>
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
                        <label class="form-label fw-semibold text-muted text-uppercase" style="font-size: 0.72rem;">{{ __('Nama Posisi / Pool') }}</label>
                        <input type="text" name="lokasi_pool" id="modalLokasiPool" class="form-control" placeholder="Contoh: Pool Cikarang / Tol Cikampek KM 14">
                    </div>

                    <button type="button" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 py-2" id="btnAcquireDeviceGps" style="border-radius: 10px; font-size: 0.88rem;">
                        <i class="bi bi-crosshair fs-5"></i> {{ __('Ambil Koordinat dari GPS HP Sekarang') }}
                    </button>
                </div>
                <div class="modal-footer bg-light py-3 border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-success fw-bold" id="btnSubmitUpdateGps" style="border-radius: 8px;">
                        <i class="bi bi-check-lg me-1"></i> {{ __('Simpan Posisi GPS') }}
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
    // Master data 14 armada dari database
    let vehiclesData = {!! json_encode($mapVehicles) !!};
    const selectedVehicleId = "{{ $selectedVehicleId }}";

    // Peta Default Fokus Wilayah Operasional Armada (Jabodetabek - Jawa Barat)
    const defaultCenter = [-6.350000, 106.950000];
    const defaultZoom = 10;

    // Batas wilayah operasional Indonesia
    const indonesiaBounds = L.latLngBounds(
        L.latLng(-11.5, 95.0),
        L.latLng(6.5, 141.0)
    );

    // ========================================================
    // 1. TILE LAYERS RESMI GOOGLE MAPS INDONESIA & SATELIT
    // ========================================================
    // Google Maps Streets (Peta Jalan Resmi Berbahasa Indonesia)
    const googleStreetsLayer = L.tileLayer('https://mt{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}&hl=id', {
        subdomains: ['0', '1', '2', '3'],
        minZoom: 6,
        maxZoom: 20,
        bounds: indonesiaBounds,
        attribution: '&copy; Google Maps Indonesia'
    });

    // Google Maps Satelit Hibrida (Citra Satelit Google Resolusi Tinggi + Label Jalan & Kota Resmi Indonesia)
    const googleHybridLayer = L.tileLayer('https://mt{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}&hl=id', {
        subdomains: ['0', '1', '2', '3'],
        minZoom: 6,
        maxZoom: 20,
        bounds: indonesiaBounds,
        attribution: '&copy; Google Maps Indonesia'
    });

    // Google Maps Terrain / Topografi (Kontur & Relief Google Maps)
    const googleTerrainLayer = L.tileLayer('https://mt{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}&hl=id', {
        subdomains: ['0', '1', '2', '3'],
        minZoom: 6,
        maxZoom: 20,
        bounds: indonesiaBounds,
        attribution: '&copy; Google Maps Indonesia'
    });

    // Google Maps Satelit Murni (Foto Udara Tanpa Teks)
    const googleSatelliteLayer = L.tileLayer('https://mt{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        subdomains: ['0', '1', '2', '3'],
        minZoom: 6,
        maxZoom: 20,
        bounds: indonesiaBounds,
        attribution: '&copy; Google Maps'
    });

    // Carto Voyager Layer (Minimalis)
    const voyagerLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        minZoom: 6,
        maxZoom: 19,
        bounds: indonesiaBounds,
        attribution: '&copy; OpenStreetMap &copy; CARTO'
    });

    // Mode Gelap (Dark Matter Command Center)
    const darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/dark_all/{z}/{x}/{y}{r}.png', {
        minZoom: 6,
        maxZoom: 19,
        bounds: indonesiaBounds,
        attribution: '&copy; OpenStreetMap &copy; CARTO'
    });

    // ========================================================
    // 2. OVERLAY TITIK POOL & HUB OPERASIONAL ARMADA
    // ========================================================
    const operationalHubsOverlay = L.featureGroup();
    const operationalHubs = [
        { name: 'Pool Pusat Gambir (DKI Jakarta)', lat: -6.175392, lng: 106.827153, type: 'Pool Utama', desc: 'Pusat komando operasional armada pusat Jakarta' },
        { name: 'Pelabuhan Tanjung Priok (Jakarta Utara)', lat: -6.126588, lng: 106.905663, type: 'Hub Petikemas', desc: 'Terminal ekspor/impor dan logistik kontainer laut' },
        { name: 'Terminal Kargo Bandara Soekarno-Hatta', lat: -6.125000, lng: 106.655000, type: 'Hub Udara', desc: 'Distribusi air freight & kargo ekspres' },
        { name: 'Hub MM2100 Cikarang (Bekasi)', lat: -6.301500, lng: 107.112000, type: 'Hub Industri', desc: 'Kawasan industri manufaktur & logistik MM2100' },
        { name: 'Hub KIIC Karawang Barat', lat: -6.345000, lng: 107.280000, type: 'Hub Industri', desc: 'Kawasan industri KIIC Karawang Barat' },
        { name: 'Hub Surya Cipta Karawang Timur', lat: -6.365000, lng: 107.340000, type: 'Hub Industri', desc: 'Pusat manufaktur & pergudangan Surya Cipta' },
        { name: 'Pool Pasteur Bandung', lat: -6.892000, lng: 107.578000, type: 'Hub Regional', desc: 'Pool & transit distribusi wilayah Bandung Raya' },
        { name: 'Hub Pergudangan Soekarno-Hatta Bandung', lat: -6.938500, lng: 107.625000, type: 'Gudang Logistik', desc: 'Pusat pergudangan tekstil dan FMCG' },
        { name: 'Sentul City Warehouse Hub (Bogor)', lat: -6.536000, lng: 106.862000, type: 'Hub Pergudangan', desc: 'Pusat logistik distribusi Bogor & Sentul' },
        { name: 'Pelabuhan Penyeberangan Merak (Cilegon)', lat: -5.932000, lng: 105.998000, type: 'Pelabuhan Feri', desc: 'Gerbang penyeberangan logistik Jawa - Sumatera' }
    ];

    operationalHubs.forEach(function(hub) {
        const hubIcon = L.divIcon({
            className: 'hub-landmark-marker',
            html: `<div class="hub-pin"><i class="bi bi-building"></i></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });
        const marker = L.marker([hub.lat, hub.lng], { icon: hubIcon });
        marker.bindTooltip(`<b>${hub.name}</b><br><small class="text-primary">${hub.type}</small>`, { direction: 'top', offset: [0, -10] });
        marker.bindPopup(`
            <div style="font-family: 'Inter', sans-serif; font-size: 0.82rem; width: 220px; line-height: 1.4;">
                <div class="fw-bold text-primary mb-1"><i class="bi bi-building me-1"></i> ${hub.name}</div>
                <span class="badge bg-primary-subtle text-primary mb-1.5" style="font-size:0.68rem;">${hub.type}</span>
                <p class="text-muted small mb-2">${hub.desc}</p>
                <button type="button" class="btn btn-xs btn-outline-primary w-100 py-1 fw-semibold btn-focus-this-hub" data-lat="${hub.lat}" data-lng="${hub.lng}" style="border-radius: 6px; font-size: 0.72rem;">
                    <i class="bi bi-crosshair"></i> Fokuskan Peta ke Hub Ini
                </button>
            </div>
        `);
        marker.addTo(operationalHubsOverlay);
    });

    // Inisialisasi Peta Leaflet Utama dengan Google Maps Streets sebagai Default
    const map = L.map('liveFleetMap', {
        center: defaultCenter,
        zoom: defaultZoom,
        minZoom: 6,
        maxZoom: 20,
        maxBounds: indonesiaBounds,
        maxBoundsViscosity: 0.9,
        layers: [googleStreetsLayer],
        scrollWheelZoom: true,
        doubleClickZoom: true,
        dragging: true,
        tap: true
    });

    // Kontrol Skala Metrik (KM / Meter) pada Peta
    L.control.scale({ imperial: false, metric: true, position: 'bottomleft' }).addTo(map);

    // Layer Switcher Events
    let currentBaseLayer = googleStreetsLayer;
    const allBaseLayers = [googleStreetsLayer, googleHybridLayer, googleTerrainLayer, googleSatelliteLayer, voyagerLayer, darkLayer];

    function setBaseLayer(newLayer, clickedEl) {
        allBaseLayers.forEach(l => {
            if (map.hasLayer(l)) map.removeLayer(l);
        });
        map.addLayer(newLayer);
        currentBaseLayer = newLayer;

        document.querySelectorAll('#layerGoogleStreets, #layerGoogleHybrid, #layerGoogleTerrain, #layerGoogleSatellite, #layerVoyager, #layerDark').forEach(el => el.classList.remove('active'));
        if (clickedEl) clickedEl.classList.add('active');
    }

    document.getElementById('layerGoogleStreets')?.addEventListener('click', function(e) {
        e.preventDefault();
        setBaseLayer(googleStreetsLayer, this);
    });

    document.getElementById('layerGoogleHybrid')?.addEventListener('click', function(e) {
        e.preventDefault();
        setBaseLayer(googleHybridLayer, this);
    });

    document.getElementById('layerGoogleTerrain')?.addEventListener('click', function(e) {
        e.preventDefault();
        setBaseLayer(googleTerrainLayer, this);
    });

    document.getElementById('layerGoogleSatellite')?.addEventListener('click', function(e) {
        e.preventDefault();
        setBaseLayer(googleSatelliteLayer, this);
    });

    document.getElementById('layerVoyager')?.addEventListener('click', function(e) {
        e.preventDefault();
        setBaseLayer(voyagerLayer, this);
    });

    document.getElementById('layerDark')?.addEventListener('click', function(e) {
        e.preventDefault();
        setBaseLayer(darkLayer, this);
    });

    // Overlay Toggles (Titik Hub Operasional)
    document.getElementById('toggleHubMarkers')?.addEventListener('change', function() {
        if (this.checked) {
            if (!map.hasLayer(operationalHubsOverlay)) map.addLayer(operationalHubsOverlay);
            showMapToast('Titik Pool & Hub Operasional: Ditampilkan');
        } else {
            if (map.hasLayer(operationalHubsOverlay)) map.removeLayer(operationalHubsOverlay);
            showMapToast('Titik Pool & Hub Operasional: Disembunyikan');
        }
    });

    // Layer Groups
    let markersMap = {};
    let markerGroup = L.featureGroup();
    let routeGroup = L.featureGroup().addTo(map);

    // Render Markers untuk Semua 14 Kendaraan Armada
    function renderMarkers(vehicles) {
        markerGroup.clearLayers();
        markersMap = {};

        vehicles.forEach(function (v) {
            const lat = parseFloat(v.latitude);
            const lng = parseFloat(v.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            // Speed tag on top of marker
            const speedTagHtml = v.kecepatan_kmh > 0 
                ? `<div class="marker-speed-tag"><i class="bi bi-speedometer2"></i> ${v.kecepatan_kmh} km/h</div>` 
                : '';

            // Custom DivIcon dengan pin, speed badge, dan pulse
            const vehicleIcon = L.divIcon({
                className: 'custom-vehicle-marker ' + v.marker_type,
                html: `${speedTagHtml}<div class="marker-pin"><i class="bi ${v.icon_class}"></i></div><div class="marker-pulse"></div>`,
                iconSize: [38, 38],
                iconAnchor: [19, 38],
                popupAnchor: [0, -36]
            });

            // Status badge classes
            const statusBadgeClass = v.status === 'Siap Pakai' ? 'bg-success' : (v.status === 'Sedang Diservis' ? 'bg-warning text-dark' : 'bg-secondary');
            
            // Popup HTML Eksekutif Lengkap dengan Rute & ETA
            const popupHtml = `
                <div style="font-family: 'Inter', sans-serif; font-size: 0.85rem; width: 280px; line-height: 1.45;">
                    <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                        <img src="${v.foto_url}" class="rounded-3 object-fit-cover shadow-sm" style="width: 50px; height: 50px; background: #f8fafc; border: 1px solid #e2e8f0;" />
                        <div>
                            <div class="plat-badge mb-1">${v.plat_nomor}</div>
                            <small class="text-muted fw-semibold d-block">${v.merek} ${v.tipe}</small>
                        </div>
                    </div>

                    <!-- Live Trip Manifest in Popup -->
                    <div class="p-2 bg-light rounded-3 border mb-2" style="font-size: 0.78rem;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="bi bi-geo text-primary"></i> Asal:</span>
                            <span class="fw-semibold text-dark text-truncate" style="max-width: 170px;">${v.lokasi_asal}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="bi bi-flag text-danger"></i> Tujuan:</span>
                            <span class="fw-bold text-danger text-truncate" style="max-width: 170px;">${v.lokasi_tujuan}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="bi bi-clock-history text-success"></i> ETA:</span>
                            <span class="fw-bold text-success">${v.eta_clock} (${v.eta_duration})</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-speedometer2 text-info"></i> Kecepatan:</span>
                            <span class="fw-bold text-dark font-monospace">${v.kecepatan_kmh} km/jam</span>
                        </div>
                    </div>

                    <div style="font-size: 0.78rem;" class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Status:</span>
                            <span class="badge ${statusBadgeClass}">${v.status}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Supir:</span>
                            <span class="fw-semibold text-dark">${v.supir_utama}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Sisa Jarak:</span>
                            <span class="fw-bold text-dark font-monospace">${v.jarak_sisa_km} km</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-1.5 pt-1.5 border-top">
                        <button type="button" class="btn btn-xs btn-primary text-white w-100 text-center py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1 btn-popup-route" data-id="${v.id}" style="border-radius: 6px; font-size: 0.75rem;">
                            <i class="bi bi-signpost-2"></i> Tampilkan Rute & ETA Lengkap
                        </button>
                        <a href="https://www.google.com/maps/dir/?api=1&origin=${v.latitude},${v.longitude}&destination=${v.dest_lat},${v.dest_lng}" target="_blank" class="btn btn-xs btn-light border w-100 text-center py-1 fw-semibold d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px; font-size: 0.75rem; text-decoration: none;">
                            <i class="bi bi-map"></i> Buka Navigasi Google Maps
                        </a>
                        <a href="${v.detail_url}" class="btn btn-xs btn-outline-secondary w-100 text-center py-1 fw-bold d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px; font-size: 0.75rem; text-decoration: none;">
                            <i class="bi bi-card-checklist"></i> Buka Profil Kendaraan
                        </a>
                    </div>
                </div>
            `;

            const marker = L.marker([lat, lng], { icon: vehicleIcon }).addTo(markerGroup);

            marker.bindTooltip(`<b>${v.plat_nomor}</b> &bull; ${v.lokasi_tujuan}<br><small class="text-success">${v.eta_short}</small>`, { direction: 'top', offset: [0, -36] });

            marker.on('click', function (e) {
                if (e && e.originalEvent) e.originalEvent.stopPropagation();
                focusAndShowVehicle(v.id);
            });

            markersMap[v.id] = marker;
        });

        markerGroup.addTo(map);

        // Auto Focus Langsung ke Sebaran 14 Armada Operasional
        if (vehicles.length > 0 && !selectedVehicleId) {
            setTimeout(function() {
                map.invalidateSize();
                if (markerGroup.getLayers().length > 0) {
                    map.fitBounds(markerGroup.getBounds().pad(0.12));
                } else {
                    map.setView(defaultCenter, defaultZoom);
                }
            }, 300);
        }
    }

    // Render Awal Markers
    renderMarkers(vehiclesData);

    // ========================================================
    // FUNGSI VISUALISASI RUTE NYATA (ASAL -> KENDARAAN -> TUJUAN)
    // ========================================================
    function drawFullTripRoute(vehicle) {
        routeGroup.clearLayers();

        const originLat = parseFloat(vehicle.origin_lat);
        const originLng = parseFloat(vehicle.origin_lng);
        const currLat = parseFloat(vehicle.latitude);
        const currLng = parseFloat(vehicle.longitude);
        const destLat = parseFloat(vehicle.dest_lat);
        const destLng = parseFloat(vehicle.dest_lng);

        if (isNaN(originLat) || isNaN(destLat)) return;

        const isComplete = (vehicle.status_perjalanan === 'Selesai Mengantar');

        // 1. Marker Asal (Pool / Origin)
        const originIcon = L.divIcon({
            className: 'custom-origin-marker',
            html: '<div class="origin-pool-pin" title="Titik Asal"><i class="bi bi-geo-alt"></i></div>',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });
        const originMarker = L.marker([originLat, originLng], { icon: originIcon }).addTo(routeGroup);
        originMarker.bindPopup(`<b>Titik Asal:</b><br>${vehicle.lokasi_asal}`);

        // 2. Marker Tujuan (Destination Flag)
        const destIcon = L.divIcon({
            className: 'custom-dest-marker',
            html: '<div class="dest-flag-pin" title="Titik Tujuan"><i class="bi bi-flag-fill"></i></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });
        const destMarker = L.marker([destLat, destLng], { icon: destIcon }).addTo(routeGroup);
        destMarker.bindPopup(`<b>Titik Tujuan:</b><br>${vehicle.lokasi_tujuan}<br><span class="badge ${isComplete ? 'bg-success' : 'bg-danger'} text-white mt-1">${isComplete ? 'Pengantaran Selesai' : 'Estimasi Tiba: ' + vehicle.eta_clock}</span>`);

        if (isComplete) {
            // Segmen Rute Penuh Selesai (Asal -> Mobil di Tujuan)
            const midLat1 = (originLat + destLat) / 2 + 0.01;
            const midLng1 = (originLng + destLng) / 2 - 0.01;
            const completedPath = [
                [originLat, originLng],
                [midLat1, midLng1],
                [destLat, destLng]
            ];

            const poly = L.polyline(completedPath, {
                color: '#10b981',
                weight: 5,
                opacity: 0.95,
                lineCap: 'round',
                lineJoin: 'round'
            }).addTo(routeGroup);

            poly.bindTooltip(`<b>Pengantaran Selesai</b><br>Tiba di ${vehicle.lokasi_tujuan}`, { sticky: true, className: 'shadow-sm' });

            const fullBounds = L.latLngBounds([[originLat, originLng], [destLat, destLng]]);
            map.fitBounds(fullBounds.pad(0.22));

            const badge = document.getElementById('navRouteInfoBadge');
            const badgeText = document.getElementById('navRouteInfoText');
            if (badge && badgeText) {
                badge.style.display = 'inline-flex';
                badgeText.innerHTML = `Rute <b>${vehicle.plat_nomor}</b>: Pengantaran ke <b>${vehicle.lokasi_tujuan}</b> <span class="badge bg-success text-white ms-1">SELESAI TIBA</span>`;
            }
            return;
        }

        // 3. Segmen Rute 1: Selesai Ditempuh (Asal -> Mobil)
        const midLat1 = (originLat + currLat) / 2 + 0.01;
        const midLng1 = (originLng + currLng) / 2 - 0.01;
        const completedPath = [
            [originLat, originLng],
            [midLat1, midLng1],
            [currLat, currLng]
        ];

        L.polyline(completedPath, {
            color: '#0284c7',
            weight: 5,
            opacity: 0.9,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(routeGroup);

        // 4. Segmen Rute 2: Sisa Menuju Tujuan (Mobil -> Tujuan)
        const midLat2 = (currLat + destLat) / 2 - 0.01;
        const midLng2 = (currLng + destLng) / 2 + 0.01;
        const remainingPath = [
            [currLat, currLng],
            [midLat2, midLng2],
            [destLat, destLng]
        ];

        const remainingPolyline = L.polyline(remainingPath, {
            color: '#10b981',
            weight: 5,
            opacity: 0.95,
            dashArray: '10, 8',
            lineCap: 'round'
        }).addTo(routeGroup);

        // Tooltip pada garis sisa rute
        remainingPolyline.bindTooltip(`<b>Sisa ${vehicle.jarak_sisa_km} KM</b><br>ETA: ${vehicle.eta_clock} (${vehicle.eta_duration})`, { sticky: true, className: 'shadow-sm' });

        // Fit Bounds mencakup semua waypoint
        const fullBounds = L.latLngBounds([[originLat, originLng], [currLat, currLng], [destLat, destLng]]);
        map.fitBounds(fullBounds.pad(0.22));

        // Tampilkan info badge di legend overlay
        const badge = document.getElementById('navRouteInfoBadge');
        const badgeText = document.getElementById('navRouteInfoText');
        if (badge && badgeText) {
            badge.style.display = 'inline-flex';
            badgeText.innerHTML = `Rute <b>${vehicle.plat_nomor}</b>: Menuju <b>${vehicle.lokasi_tujuan}</b> &bull; ${vehicle.eta_text}`;
        }
    }

    // ========================================================
    // UPDATE FLOATING TRIP HUD PANEL
    // ========================================================
    function updateHud(vehicle) {
        const hud = document.getElementById('floatingTripHud');
        if (!hud) return;

        const isComplete = (vehicle.status_perjalanan === 'Selesai Mengantar');
        const isServis = (vehicle.status === 'Sedang Diservis' || vehicle.status_perjalanan === 'Sedang Diservis');

        document.getElementById('hudPlatNomor').innerText = vehicle.plat_nomor;
        
        const statusEl = document.getElementById('hudStatusPerjalanan');
        if (isComplete) {
            statusEl.className = 'badge bg-success text-white fw-bold px-2 py-1';
            statusEl.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Selesai Antar';
        } else if (isServis) {
            statusEl.className = 'badge bg-danger text-white fw-bold px-2 py-1';
            statusEl.innerHTML = '<i class="bi bi-tools me-1"></i> Sedang Diservis';
        } else if (vehicle.status_perjalanan === 'Proses Bongkar Muat') {
            statusEl.className = 'badge bg-warning text-dark fw-bold px-2 py-1';
            statusEl.innerHTML = '<i class="bi bi-box-seam me-1"></i> Bongkar Muat';
        } else {
            statusEl.className = 'badge bg-primary-subtle text-primary fw-bold px-2 py-1';
            statusEl.innerText = vehicle.status_perjalanan || 'Dalam Perjalanan';
        }

        document.getElementById('hudFoto').src = vehicle.foto_url;
        document.getElementById('hudMerekTipe').innerText = `${vehicle.merek} ${vehicle.tipe}`;
        document.getElementById('hudSupir').innerHTML = `<i class="bi bi-person me-1"></i>Supir: <b>${vehicle.supir_utama}</b>`;
        document.getElementById('hudSpeed').innerHTML = `<i class="bi bi-speedometer2 me-1"></i>Kecepatan: <b>${vehicle.kecepatan_kmh} km/jam</b>`;

        document.getElementById('hudAsal').innerText = vehicle.lokasi_asal;
        document.getElementById('hudTujuan').innerText = vehicle.lokasi_tujuan;

        if (isComplete) {
            document.getElementById('hudProgressText').innerText = '100% (Tiba di Lokasi)';
            document.getElementById('hudProgressBar').style.width = '100%';
            document.getElementById('hudProgressBar').style.backgroundColor = '#10b981';
            document.getElementById('hudEtaText').innerText = 'Pengantaran Selesai (Tiba di Lokasi)';
        } else {
            document.getElementById('hudProgressText').innerText = `${vehicle.progress_percent}% (Sisa ${vehicle.jarak_sisa_km} km)`;
            document.getElementById('hudProgressBar').style.width = `${vehicle.progress_percent}%`;
            document.getElementById('hudProgressBar').style.backgroundColor = '';
            document.getElementById('hudEtaText').innerText = vehicle.eta_text;
        }

        // Catatan
        let catatanText = vehicle.catatan_perjalanan;
        if (!isComplete && (!catatanText || catatanText.includes('telah selesai dilaksanakan'))) {
            catatanText = `Pengiriman muatan logistik menuju ${vehicle.lokasi_tujuan}`;
        }
        document.getElementById('hudCatatan').innerText = catatanText || 'Pengiriman logistik armada operasional.';

        document.getElementById('hudBtnGoogleMaps').href = `https://www.google.com/maps/dir/?api=1&origin=${vehicle.latitude},${vehicle.longitude}&destination=${vehicle.dest_lat},${vehicle.dest_lng}`;
        document.getElementById('hudBtnDetail').href = vehicle.detail_url;

        // Button Complete Trip inside HUD
        const btnHudComplete = document.getElementById('hudBtnCompleteTrip');
        if (btnHudComplete) {
            if (isComplete) {
                btnHudComplete.className = 'btn btn-sm btn-outline-success py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-xs';
                btnHudComplete.innerHTML = '<i class="bi bi-check-circle-fill"></i> Sudah Selesai';
                btnHudComplete.disabled = true;
            } else if (isServis) {
                btnHudComplete.className = 'btn btn-sm btn-light border text-muted py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-xs';
                btnHudComplete.innerHTML = '<i class="bi bi-tools"></i> Servis';
                btnHudComplete.disabled = true;
            } else {
                btnHudComplete.className = 'btn btn-sm btn-success text-white py-1.5 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-xs';
                btnHudComplete.innerHTML = '<i class="bi bi-check2-circle fs-6"></i> Selesai Antar';
                btnHudComplete.disabled = false;
            }
        }

        // Button Assign Trip inside HUD
        const btnHudAssign = document.getElementById('hudBtnAssignTrip');
        if (btnHudAssign) {
            btnHudAssign.innerHTML = isComplete 
                ? '<i class="bi bi-send-plus-fill"></i> Rute Baru'
                : '<i class="bi bi-send-plus-fill"></i> Rute Berikutnya';
            btnHudAssign.onclick = function() {
                openAssignTripModal(vehicle.id);
            };
        }

        hud.classList.add('show');
    }

    document.getElementById('btnCloseHud')?.addEventListener('click', function() {
        document.getElementById('floatingTripHud').classList.remove('show');
    });

    document.getElementById('btnClearNavRoute')?.addEventListener('click', function() {
        routeGroup.clearLayers();
        document.getElementById('navRouteInfoBadge').style.display = 'none';
    });

    // ========================================================
    // FOCUS & SELECT VEHICLE
    // ========================================================
    function focusAndShowVehicle(vehicleId) {
        const vehicle = vehiclesData.find(v => v.id == vehicleId);
        if (!vehicle) return;

        highlightCard(vehicleId);

        // Close any open leaflet popup
        map.closePopup();

        // Fly to marker smoothly
        if (markersMap[vehicleId]) {
            const latLng = markersMap[vehicleId].getLatLng();
            map.flyTo(latLng, 14, { animate: true, duration: 1.0 });
        }

        // Gambar rute lengkap Asal -> Mobil -> Tujuan
        drawFullTripRoute(vehicle);

        // Update & show HUD
        updateHud(vehicle);
    }

    function highlightCard(vehicleId) {
        document.querySelectorAll('.vehicle-track-card').forEach(c => c.classList.remove('active'));
        const targetCard = document.querySelector(`.vehicle-track-card[data-id="${vehicleId}"]`);
        if (targetCard) {
            targetCard.classList.add('active');
            targetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // Event Card Click
    document.querySelectorAll('.vehicle-track-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.card-action-bar') || e.target.closest('button') || e.target.closest('a')) return;
            const id = this.getAttribute('data-id');
            focusAndShowVehicle(id);
        });
    });

    document.querySelectorAll('.btn-draw-nav').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            focusAndShowVehicle(id);
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-popup-route')) {
            const btn = e.target.closest('.btn-popup-route');
            const id = btn.getAttribute('data-id');
            focusAndShowVehicle(id);
        }
    });

    if (selectedVehicleId) {
        setTimeout(function() {
            focusAndShowVehicle(selectedVehicleId);
        }, 600);
    }

    // ========================================================
    // MODAL ASSIGN TRIP (TUGASKAN / UBAH RUTE TUJUAN BARU)
    // ========================================================
    let currentAssigningVehicleId = null;
    let currentSelectedVehicleId = null;
    const modalAssignTrip = new bootstrap.Modal(document.getElementById('modalAssignTrip'));
    const modalCompleteTripSuccess = new bootstrap.Modal(document.getElementById('modalCompleteTripSuccess'));
    let lastCompletedVehicleId = null;

    function openAssignTripModal(vehicleId) {
        const vehicle = vehiclesData.find(v => v.id == vehicleId);
        if (!vehicle) return;

        currentAssigningVehicleId = vehicleId;
        document.getElementById('assignPlatNomor').value = vehicle.plat_nomor;
        document.getElementById('assignSupir').value = vehicle.supir_utama;
        
        // Titik Asal: Gunakan lokasi dropoff terakhir jika baru selesai antar atau lokasi terkini mobil
        const originLocation = vehicle.status_perjalanan === 'Selesai Mengantar'
            ? (vehicle.lokasi_tujuan || vehicle.lokasi_asal)
            : (vehicle.lokasi_asal || vehicle.lokasi_pool);
        document.getElementById('assignLokasiAsal').value = originLocation;

        // Reset tujuan agar pengguna bisa memilih atau memasukkan tujuan baru
        document.getElementById('selectPresetDestination').value = '';
        document.getElementById('assignLokasiTujuan').value = '';
        document.getElementById('assignTujuanLat').value = '';
        document.getElementById('assignTujuanLng').value = '';
        document.getElementById('assignStatusPerjalanan').value = 'Dalam Perjalanan ke Tujuan';
        document.getElementById('assignKecepatan').value = vehicle.kecepatan_kmh > 0 ? vehicle.kecepatan_kmh : 60;
        document.getElementById('assignEstimasiMenit').value = '';
        document.getElementById('assignCatatan').value = '';

        document.getElementById('formAssignTrip').action = `/tracking/${vehicleId}/trip`;
        modalAssignTrip.show();
    }

    // ========================================================
    // COMPLETE TRIP HANDLER (TANDAI SELESAI PENGANTARAN)
    // ========================================================
    function handleCompleteTrip(vehicleId) {
        const vehicle = vehiclesData.find(v => v.id == vehicleId);
        if (!vehicle) return;

        if (!confirm(`Tandai pengantaran armada ${vehicle.plat_nomor} menuju ${vehicle.lokasi_tujuan} telah SELESAI TIBA?`)) {
            return;
        }

        const hudBtn = document.getElementById('hudBtnCompleteTrip');
        if (hudBtn) {
            hudBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
            hudBtn.disabled = true;
        }

        fetch(`/tracking/${vehicleId}/complete-trip`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (hudBtn) {
                hudBtn.innerHTML = '<i class="bi bi-check2-circle fs-6"></i> Selesai Antar';
                hudBtn.disabled = false;
            }

            if (data.status === 'success') {
                lastCompletedVehicleId = vehicleId;
                document.getElementById('completeSuccessPlat').innerText = vehicle.plat_nomor;
                document.getElementById('completeSuccessTujuan').innerText = data.current_location || vehicle.lokasi_tujuan;
                
                // Segarkan data pelacakan di peta dan sidebar
                fetchTrackingData(function() {
                    focusAndShowVehicle(vehicleId);
                    modalCompleteTripSuccess.show();
                });
            }
        })
        .catch(err => {
            if (hudBtn) {
                hudBtn.innerHTML = '<i class="bi bi-check2-circle fs-6"></i> Selesai Antar';
                hudBtn.disabled = false;
            }
            console.error(err);
            alert("Terjadi kesalahan saat menandai pengantaran selesai.");
        });
    }

    // Tombol Selesai Antar di HUD Panel
    document.getElementById('hudBtnCompleteTrip')?.addEventListener('click', function() {
        const activeCard = document.querySelector('.vehicle-track-card.active');
        const vehicleId = activeCard ? activeCard.getAttribute('data-id') : (selectedVehicleId || vehiclesData[0]?.id);
        if (vehicleId) handleCompleteTrip(vehicleId);
    });

    // Tombol Selesai Antar di Sidebar Card
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-complete-trip')) {
            e.stopPropagation();
            const btn = e.target.closest('.btn-complete-trip');
            const id = btn.getAttribute('data-id');
            handleCompleteTrip(id);
        }
    });

    // Tombol Lanjut Tugaskan Rute Baru dari Modal Sukses
    document.getElementById('btnProceedNextTrip')?.addEventListener('click', function() {
        modalCompleteTripSuccess.hide();
        if (lastCompletedVehicleId) {
            setTimeout(() => {
                openAssignTripModal(lastCompletedVehicleId);
            }, 350);
        }
    });

    document.querySelectorAll('.btn-assign-trip').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            openAssignTripModal(id);
        });
    });

    // Preset Destination Change
    document.getElementById('selectPresetDestination').addEventListener('change', function() {
        if (this.value) {
            const parts = this.value.split('|');
            const coords = parts[0].split(',');
            const destName = parts[1];
            const estMinutes = parts[2] || 35;

            document.getElementById('assignTujuanLat').value = coords[0];
            document.getElementById('assignTujuanLng').value = coords[1];
            document.getElementById('assignLokasiTujuan').value = destName;
            document.getElementById('assignEstimasiMenit').value = estMinutes;
            document.getElementById('assignStatusPerjalanan').value = 'Dalam Perjalanan ke Tujuan';
        }
    });

    // Submit Assign Trip via AJAX
    document.getElementById('formAssignTrip').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('btnSubmitAssignTrip');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menugaskan...';
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
            submitBtn.innerHTML = '<i class="bi bi-send-check me-1"></i> Tugaskan Pengantaran & Mulai Rute';
            submitBtn.disabled = false;

            if (data.status === 'success') {
                modalAssignTrip.hide();
                fetchTrackingData(function() {
                    focusAndShowVehicle(currentAssigningVehicleId);
                });
                alert(data.message);
            }
        })
        .catch(err => {
            submitBtn.innerHTML = '<i class="bi bi-send-check me-1"></i> Tugaskan Pengantaran & Mulai Rute';
            submitBtn.disabled = false;
            console.error(err);
            alert("Terjadi kesalahan saat menugaskan rute perjalanan.");
        });
    });

    // ========================================================
    // GPS SYNC MODAL & DEVICE GEOLOCATION
    // ========================================================
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

    document.getElementById('btnAcquireDeviceGps').addEventListener('click', function() {
        const btn = this;
        if ("geolocation" in navigator) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengambil koordinat GPS...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    btn.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5 me-1"></i> Koordinat Berhasil Didapat!';
                    btn.disabled = false;
                    document.getElementById('modalLatitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('modalLongitude').value = position.coords.longitude.toFixed(6);
                    document.getElementById('modalLokasiPool').value = "GPS Live Device";
                },
                function(error) {
                    btn.innerHTML = '<i class="bi bi-crosshair fs-5"></i> Ambil Koordinat dari GPS HP Sekarang';
                    btn.disabled = false;
                    alert("Gagal membaca GPS perangkat: " + error.message);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert("Perangkat Anda tidak mendukung fitur Geolocation.");
        }
    });

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
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan Posisi GPS';
            submitBtn.disabled = false;

            if (data.status === 'success') {
                modalUpdateGps.hide();
                fetchTrackingData(function() {
                    focusAndShowVehicle(currentUpdatingVehicleId);
                });
                alert(data.message);
            }
        })
        .catch(err => {
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan Posisi GPS';
            submitBtn.disabled = false;
            console.error(err);
            alert("Terjadi kesalahan saat menyimpan lokasi GPS.");
        });
    });

    // ========================================================
    // REFRESH DATA VIA AJAX API
    // ========================================================
    function updateSidebarCards(vehicles) {
        vehicles.forEach(v => {
            const card = document.querySelector(`.vehicle-track-card[data-id="${v.id}"]`);
            if (!card) return;

            card.setAttribute('data-asal', (v.lokasi_asal || '').toLowerCase());
            card.setAttribute('data-tujuan', (v.lokasi_tujuan || '').toLowerCase());
            card.setAttribute('data-status-perjalanan', v.status_perjalanan);
            card.setAttribute('data-lat', v.latitude);
            card.setAttribute('data-lng', v.longitude);
            card.setAttribute('data-dest-lat', v.dest_lat);
            card.setAttribute('data-dest-lng', v.dest_lng);

            // Update badge
            const badgeContainer = card.querySelector('.plat-badge')?.parentElement?.querySelector('div:last-child');
            if (badgeContainer) {
                if (v.status_perjalanan === 'Dalam Perjalanan ke Tujuan') {
                    badgeContainer.innerHTML = `<span class="badge bg-primary-subtle text-primary py-0.5 px-2 font-monospace fw-bold" style="font-size: 0.68rem; border-radius: 6px;"><i class="bi bi-speedometer2 me-0.5"></i> ${v.kecepatan_kmh} km/h</span>`;
                } else if (v.status_perjalanan === 'Selesai Mengantar') {
                    badgeContainer.innerHTML = `<span class="badge bg-success-subtle text-success py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;"><i class="bi bi-check-circle-fill me-0.5"></i> Selesai</span>`;
                } else if (v.status_perjalanan === 'Proses Bongkar Muat') {
                    badgeContainer.innerHTML = `<span class="badge bg-warning-subtle text-warning py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;">Bongkar</span>`;
                } else if (v.status === 'Sedang Diservis') {
                    badgeContainer.innerHTML = `<span class="badge bg-danger-subtle text-danger py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;">Servis</span>`;
                } else {
                    badgeContainer.innerHTML = `<span class="badge bg-secondary-subtle text-secondary py-0.5 px-2 fw-semibold" style="font-size: 0.68rem; border-radius: 6px;">Standby</span>`;
                }
            }

            // Update timeline Asal & Tujuan
            const items = card.querySelectorAll('.route-timeline-item');
            if (items.length >= 2) {
                const asalSpan = items[0].querySelector('span:last-child');
                if (asalSpan) asalSpan.innerText = v.lokasi_asal;
                const tujuanSpan = items[1].querySelector('span:last-child');
                if (tujuanSpan) tujuanSpan.innerText = v.lokasi_tujuan;
            }

            // Update ETA & Sisa
            const etaBadge = card.querySelector('.card-action-bar')?.previousElementSibling?.querySelector('.badge');
            if (etaBadge) {
                etaBadge.className = `badge ${v.eta_badge_class} text-white py-1 px-2 d-inline-flex align-items-center gap-1`;
                etaBadge.innerHTML = `<i class="bi bi-clock-history"></i> ${v.eta_short}`;
            }
            const sisaSpan = card.querySelector('.card-action-bar')?.previousElementSibling?.querySelector('.font-monospace');
            if (sisaSpan) {
                sisaSpan.innerHTML = `Sisa <b>${v.jarak_sisa_km} km</b>`;
            }

            // Update Action Button
            const actionCol5 = card.querySelector('.card-action-bar .row:first-child .col-5');
            if (actionCol5) {
                if (v.status === 'Sedang Diservis') {
                    actionCol5.innerHTML = `<span class="btn btn-sm btn-light border text-muted w-100 btn-card-main" style="cursor: not-allowed;" title="Armada dalam perawatan bengkel"><i class="bi bi-tools text-warning"></i> Servis</span>`;
                } else if (v.status_perjalanan === 'Selesai Mengantar') {
                    actionCol5.innerHTML = `<button class="btn btn-sm btn-primary text-white w-100 btn-card-main btn-assign-trip" data-id="${v.id}" data-plat="${v.plat_nomor}" data-supir="${v.supir_utama}" title="Tugaskan rute pengantaran baru ke alamat berikutnya"><i class="bi bi-send-plus-fill"></i> Rute Baru</button>`;
                } else {
                    actionCol5.innerHTML = `<button class="btn btn-sm btn-success text-white w-100 btn-card-main btn-complete-trip" data-id="${v.id}" data-plat="${v.plat_nomor}" data-tujuan="${v.lokasi_tujuan}" title="Tandai pengantaran telah selesai tiba di tujuan"><i class="bi bi-check2-circle"></i> Selesai</button>`;
                }
            }
        });
    }

    function fetchTrackingData(callback) {
        const refreshIcon = document.getElementById('refreshIcon');
        if (refreshIcon) refreshIcon.classList.add('spin-animation');

        fetch("{{ route('tracking.api') }}")
            .then(res => res.json())
            .then(data => {
                if (refreshIcon) refreshIcon.classList.remove('spin-animation');
                if (data.status === 'success') {
                    vehiclesData = data.vehicles;
                    renderMarkers(vehiclesData);
                    updateSidebarCards(vehiclesData);
                    document.getElementById('activeVehicleCount').innerHTML = `Menampilkan <b>${vehiclesData.length}</b> armada aktif (Diperbarui ${new Date().toLocaleTimeString()})`;
                    if (typeof callback === 'function') callback();
                }
            })
            .catch(err => {
                if (refreshIcon) refreshIcon.classList.remove('spin-animation');
                console.error("Gagal menyegarkan data pelacakan:", err);
            });
    }

    document.getElementById('btnRefreshTracking').addEventListener('click', function() {
        fetchTrackingData();
    });

    // ========================================================
    // FILTER & SEARCH LOGIC DENGAN NAVIGASI WILAYAH OPERASIONAL
    // ========================================================
    const searchInput = document.getElementById('trackingSearch');
    const filterPills = document.querySelectorAll('.filter-pill');
    const cityPills = document.querySelectorAll('.city-pill');
    let currentFilter = 'all';
    let currentCity = 'all';

    // Koordinat Presisi Wilayah Operasional Armada
    const cityCenters = {
        'jakarta': [-6.175392, 106.827153, 12, 'DKI Jakarta (Pusat & Gambir)'],
        'priok': [-6.126588, 106.905663, 13, 'Pelabuhan Tanjung Priok'],
        'soetta': [-6.125000, 106.655000, 13, 'Bandara Soekarno-Hatta Cengkareng'],
        'bekasi': [-6.238000, 106.992000, 13, 'Kota & Kab. Bekasi'],
        'cikarang': [-6.294000, 107.120000, 13, 'Kawasan Industri Cikarang (MM2100 & Jababeka)'],
        'karawang': [-6.345000, 107.280000, 13, 'Kawasan Industri Karawang (KIIC & Suryacipta)'],
        'bandung': [-6.914744, 107.609810, 13, 'Kota Bandung (Pasteur & Soetta)'],
        'tangerang': [-6.200000, 106.650000, 13, 'Kota Tangerang & BSD City'],
        'depok': [-6.372000, 106.832000, 13, 'Kota Depok & Margonda'],
        'bogor': [-6.580000, 106.820000, 13, 'Kota Bogor & Sentul City'],
        'merak': [-5.932000, 105.998000, 13, 'Pelabuhan Penyeberangan Merak Banten']
    };

    function showMapToast(text) {
        const toast = document.getElementById('mapCityToast');
        const toastText = document.getElementById('mapCityToastText');
        if (toast && toastText) {
            toastText.innerText = text;
            toast.classList.add('show');
            clearTimeout(window._mapToastTimer);
            window._mapToastTimer = setTimeout(() => {
                toast.classList.remove('show');
            }, 2600);
        }
    }

    function focusToCity(cityKey) {
        if (!cityCenters[cityKey]) return;
        const target = cityCenters[cityKey];
        const lat = target[0];
        const lng = target[1];
        const zoom = target[2];
        const label = target[3];

        map.flyTo([lat, lng], zoom, { duration: 1.2 });
        showMapToast(`Fokus Wilayah: ${label}`);
        
        const regionLabelEl = document.getElementById('currentRegionLabel');
        if (regionLabelEl) {
            regionLabelEl.innerText = label.split(' (')[0];
        }

        cityPills.forEach(p => {
            if (p.getAttribute('data-city') === cityKey) {
                cityPills.forEach(x => x.classList.remove('active'));
                p.classList.add('active');
                currentCity = cityKey;
            }
        });

        applyFilters();
    }

    function applyFilters() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleMarkers = [];

        document.querySelectorAll('.vehicle-track-card').forEach(card => {
            const id = card.getAttribute('data-id');
            const plat = card.getAttribute('data-plat');
            const merek = card.getAttribute('data-merek');
            const supir = card.getAttribute('data-supir');
            const asal = card.getAttribute('data-asal');
            const tujuan = card.getAttribute('data-tujuan');
            const pool = card.getAttribute('data-pool');
            const status = card.getAttribute('data-status');
            const statusPerjalanan = card.getAttribute('data-status-perjalanan');
            const markerType = card.getAttribute('data-marker-type');

            const matchesSearch = !query || plat.includes(query) || merek.includes(query) || supir.includes(query) || asal.includes(query) || tujuan.includes(query) || pool.includes(query);
            
            let matchesFilter = true;
            if (currentFilter === 'on_trip') {
                matchesFilter = (statusPerjalanan === 'Dalam Perjalanan ke Tujuan');
            } else if (currentFilter === 'standby') {
                matchesFilter = (statusPerjalanan === 'Standby di Pool' || statusPerjalanan === 'Selesai Mengantar' || status === 'Siap Pakai');
            } else if (currentFilter === 'servis') {
                matchesFilter = (status === 'Sedang Diservis' || statusPerjalanan === 'Perawatan/Servis');
            } else if (currentFilter === 'warning') {
                matchesFilter = (markerType === 'marker-warning');
            }

            let matchesCity = true;
            if (currentCity !== 'all') {
                const searchKeywords = {
                    'jakarta': ['jakarta', 'gambir', 'priok', 'senayan', 'daan mogot', 'rawamangun', 'cawang'],
                    'priok': ['priok', 'tanjung priok', 'petikemas', 'pelabuhan'],
                    'soetta': ['bandara', 'soekarno', 'cengkareng', 'kargo'],
                    'bekasi': ['bekasi', 'summarecon'],
                    'cikarang': ['cikarang', 'mm2100', 'jababeka', 'cibitung'],
                    'karawang': ['karawang', 'kiic', 'surya cipta', 'suryacipta'],
                    'bandung': ['bandung', 'pasteur', 'soekarno-hatta bandung', 'priangan', 'garut'],
                    'tangerang': ['tangerang', 'bsd', 'serpong'],
                    'depok': ['depok', 'margonda'],
                    'bogor': ['bogor', 'sentul', 'pajajaran'],
                    'merak': ['merak', 'cilegon', 'banten']
                };

                const keywords = searchKeywords[currentCity] || [currentCity];
                matchesCity = keywords.some(k => asal.includes(k) || tujuan.includes(k) || pool.includes(k));
            }

            if (matchesSearch && matchesFilter && matchesCity) {
                card.style.display = 'block';
                if (markersMap[id]) {
                    visibleMarkers.push(markersMap[id]);
                }
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleMarkers.length > 0 && currentFilter !== 'all' && currentCity === 'all') {
            const group = L.featureGroup(visibleMarkers);
            map.fitBounds(group.getBounds().pad(0.12));
        }
    }

    searchInput.addEventListener('input', applyFilters);

    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            applyFilters();
        });
    });

    document.querySelectorAll('.btn-stat-filter').forEach(statCard => {
        statCard.addEventListener('click', function() {
            const targetFilter = this.getAttribute('data-filter');
            if (targetFilter) {
                filterPills.forEach(p => {
                    if (p.getAttribute('data-filter') === targetFilter) {
                        filterPills.forEach(x => x.classList.remove('active'));
                        p.classList.add('active');
                        currentFilter = targetFilter;
                        applyFilters();
                    }
                });
            }
        });
    });

    cityPills.forEach(pill => {
        pill.addEventListener('click', function() {
            cityPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentCity = this.getAttribute('data-city');
            if (currentCity === 'all') {
                document.getElementById('currentRegionLabel').innerText = 'Fokus Wilayah';
                if (markerGroup.getLayers().length > 0) {
                    map.fitBounds(markerGroup.getBounds().pad(0.12));
                } else {
                    map.flyTo(defaultCenter, defaultZoom, { duration: 1.0 });
                }
                showMapToast('Menampilkan Seluruh Sebaran Armada');
            } else if (cityCenters[currentCity]) {
                focusToCity(currentCity);
            } else {
                applyFilters();
            }
        });
    });

    // Event Klik Dropdown Kota / Daerah
    document.querySelectorAll('.btn-focus-city').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const cityKey = this.getAttribute('data-city');
            focusToCity(cityKey);
        });
    });

    // Event Klik Tombol Fokus di Popup Hub
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-focus-this-hub')) {
            const btn = e.target.closest('.btn-focus-this-hub');
            const lat = parseFloat(btn.getAttribute('data-lat'));
            const lng = parseFloat(btn.getAttribute('data-lng'));
            if (!isNaN(lat) && !isNaN(lng)) {
                map.flyTo([lat, lng], 14, { duration: 1.0 });
            }
        }
    });

    // Area & Focus Button Controls (Fokus ke Seluruh 14 Armada Operasional)
    document.getElementById('btnFitAll').addEventListener('click', function() {
        document.getElementById('currentRegionLabel').innerText = 'Fokus Wilayah';
        cityPills.forEach(x => x.classList.remove('active'));
        document.querySelector('.city-pill[data-city="all"]')?.classList.add('active');
        currentCity = 'all';
        applyFilters();

        if (markerGroup.getLayers().length > 0) {
            map.fitBounds(markerGroup.getBounds().pad(0.12));
        } else {
            map.flyTo(defaultCenter, defaultZoom, { animate: true });
        }
        showMapToast('Fokus ke Seluruh Armada Aktif');
    });

    // Fullscreen Toggle
    document.getElementById('btnToggleFullscreen').addEventListener('click', function() {
        const card = document.querySelector('.tracking-map-card');
        if (!document.fullscreenElement) {
            card.requestFullscreen().catch(err => {
                alert(`Error layar penuh: ${err.message}`);
            });
            this.innerHTML = '<i class="bi bi-fullscreen-exit"></i>';
        } else {
            document.exitFullscreen();
            this.innerHTML = '<i class="bi bi-arrows-fullscreen"></i>';
        }
    });

    // ========================================================
    // MULTI-ROUTE LIVE ROAD SIMULATION PLAYBACK WITH ETA COUNTDOWN
    // ========================================================
    let simulationPolyline = null;
    let simulationAnimMarker = null;
    let simulationDestMarker = null;
    let simInterval = null;

    const simulationRoutes = {
        'cikarang': {
            plat: 'B 9482 TKL',
            vehicle: 'Hino Dutro 130 HD (Truk Boks)',
            asal: 'Pool Gambir Jakarta Pusat',
            tujuan: 'Kawasan Industri MM2100 Cikarang',
            totalKm: 38,
            points: [
                [-6.175392, 106.827153], // Gambir
                [-6.208763, 106.845000], // Matraman
                [-6.241500, 106.872000], // Tol Cawang
                [-6.240000, 106.940000], // Halim / Jatiwaringin
                [-6.242000, 106.992000], // Bekasi Barat
                [-6.255000, 107.050000], // Tambun
                [-6.275000, 107.085000], // Cibitung
                [-6.301500, 107.112000], // MM2100 Cikarang (Tujuan)
            ],
            speedBase: 65
        },
        'bandung': {
            plat: 'D 8812 ABF',
            vehicle: 'Mitsubishi Colt Diesel Box',
            asal: 'Pool Cawang Jakarta Timur',
            tujuan: 'Hub Distribusi Pasteur Bandung',
            totalKm: 142,
            points: [
                [-6.258882, 106.852443], // Cawang
                [-6.240000, 106.992000], // Bekasi Barat
                [-6.284000, 107.150000], // Cikarang
                [-6.310000, 107.300000], // Karawang Barat
                [-6.420000, 107.450000], // Purwakarta
                [-6.720000, 107.480000], // Padalarang
                [-6.892000, 107.578000], // Bandung Pasteur (Tujuan)
            ],
            speedBase: 78
        },
        'bogor': {
            plat: 'F 1045 PK',
            vehicle: 'Toyota Hilux Pick Up',
            asal: 'Pool Rawamangun',
            tujuan: 'Sentul City Warehouse Bogor',
            totalKm: 46,
            points: [
                [-6.194000, 106.888000], // Rawamangun
                [-6.258882, 106.852443], // Cawang
                [-6.370000, 106.870000], // Cibubur
                [-6.450000, 106.850000], // Citeureup
                [-6.536000, 106.862000], // Sentul City (Tujuan)
            ],
            speedBase: 68
        },
        'bandara': {
            plat: 'B 3021 UYT',
            vehicle: 'Isuzu Elf Long Box Cargo',
            asal: 'Pool Daan Mogot Jakarta Barat',
            tujuan: 'Terminal Kargo Bandara Soekarno-Hatta',
            totalKm: 22,
            points: [
                [-6.155000, 106.745000], // Daan Mogot
                [-6.145000, 106.705000], // Rawa Buaya
                [-6.138000, 106.685000], // Cengkareng
                [-6.132000, 106.665000], // Akses Tol Sedyatmo
                [-6.125000, 106.655000], // Terminal Kargo (Tujuan)
            ],
            speedBase: 55
        }
    };

    function startRouteSimulation(routeKey) {
        stopSimulation();

        const route = simulationRoutes[routeKey];
        if (!route) return;

        const routePoints = route.points;
        const totalSteps = routePoints.length;
        const destCoord = routePoints[totalSteps - 1];

        // 1. Draw Full Polyline
        simulationPolyline = L.polyline(routePoints, {
            color: '#2563eb',
            weight: 5,
            opacity: 0.85,
            dashArray: '8, 8',
            lineCap: 'round'
        }).addTo(map);

        // 2. Destination Flag Marker
        const destIcon = L.divIcon({
            className: 'custom-dest-marker',
            html: '<div class="dest-flag-pin"><i class="bi bi-flag-fill"></i></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });
        simulationDestMarker = L.marker(destCoord, { icon: destIcon }).addTo(map);
        simulationDestMarker.bindPopup(`<b>Tujuan Simulasi:</b><br>${route.tujuan}`);

        map.fitBounds(simulationPolyline.getBounds().pad(0.25));

        // 3. Moving Vehicle Marker
        let currentStep = 0;
        const simIcon = L.divIcon({
            className: 'custom-vehicle-marker marker-ready',
            html: `<div class="marker-speed-tag"><i class="bi bi-speedometer2"></i> ${route.speedBase} km/h</div><div class="marker-pin" style="background:#2563eb !important;"><i class="bi bi-truck"></i></div><div class="marker-pulse"></div>`,
            iconSize: [38, 38],
            iconAnchor: [19, 38]
        });

        simulationAnimMarker = L.marker(routePoints[0], { icon: simIcon }).addTo(map);

        // Update HUD with live simulation data
        const hud = document.getElementById('floatingTripHud');
        document.getElementById('hudPlatNomor').innerText = route.plat;
        document.getElementById('hudStatusPerjalanan').innerText = 'Simulasi Live';
        document.getElementById('hudFoto').src = '/images/box_truck_real.jpg';
        document.getElementById('hudMerekTipe').innerText = route.vehicle;
        document.getElementById('hudSupir').innerHTML = `<i class="bi bi-person me-1"></i>Supir: <b>Supir Simulasi Live</b>`;
        document.getElementById('hudAsal').innerText = route.asal;
        document.getElementById('hudTujuan').innerText = route.tujuan;
        document.getElementById('hudCatatan').innerText = 'Simulasi realtime pergerakan armada sepanjang jalur tol dan arteri.';
        hud.classList.add('show');

        // Interval step
        simInterval = setInterval(() => {
            if (currentStep >= totalSteps) {
                clearInterval(simInterval);
                simulationAnimMarker.bindPopup(`<b>Armada Telah Tiba di Lokasi Tujuan!</b><br>${route.tujuan}`).openPopup();
                document.getElementById('hudProgressText').innerText = `100% (Telah Tiba di Tujuan)`;
                document.getElementById('hudProgressBar').style.width = `100%`;
                document.getElementById('hudEtaText').innerText = `Tiba di Lokasi Tujuan`;
                return;
            }

            const pt = routePoints[currentStep];
            simulationAnimMarker.setLatLng(pt);

            // Calculate live remaining distance & ETA
            const percent = Math.round(((currentStep + 1) / totalSteps) * 100);
            const remainingKm = Math.max(0, Math.round(route.totalKm * (1 - (currentStep / totalSteps))));
            const minsLeft = Math.max(1, Math.round((remainingKm / route.speedBase) * 60));
            const etaTime = new Date(Date.now() + minsLeft * 60000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            document.getElementById('hudSpeed').innerHTML = `<i class="bi bi-speedometer2 me-1"></i>Kecepatan: <b>${route.speedBase + (currentStep % 3 * 4)} km/jam</b>`;
            document.getElementById('hudProgressText').innerText = `${percent}% (Sisa ${remainingKm} km)`;
            document.getElementById('hudProgressBar').style.width = `${percent}%`;
            document.getElementById('hudEtaText').innerText = `Tiba pkl ${etaTime} WIB (± ${minsLeft} mnt)`;

            currentStep++;
        }, 1300);
    }

    function stopSimulation() {
        if (simInterval) clearInterval(simInterval);
        if (simulationPolyline) map.removeLayer(simulationPolyline);
        if (simulationAnimMarker) map.removeLayer(simulationAnimMarker);
        if (simulationDestMarker) map.removeLayer(simulationDestMarker);
    }

    document.querySelectorAll('.btn-run-sim').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const routeKey = this.getAttribute('data-route');
            startRouteSimulation(routeKey);
        });
    });

    document.getElementById('btnStopSim')?.addEventListener('click', function(e) {
        e.preventDefault();
        stopSimulation();
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
