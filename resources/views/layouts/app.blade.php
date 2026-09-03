@php
    // Get server's LAN IP address (highly useful on local network for Android connection)
    $localIp = gethostbyname(gethostname());
    if ($localIp === '127.0.0.1' || $localIp === '::1') {
        // Fallback to request server address if local host is returned
        $localIp = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
    }
    // Handle local dev environment port
    $port = $_SERVER['SERVER_PORT'] ?? '8000';
    $lanBaseUrl = "http://" . $localIp . ($port && $port != '80' ? ":" . $port : "");
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - FleetMaintenance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 262px;
            --body-bg: #f4f4f5;
            --border-color: #e4e4e7;
            --text-main: #18181b;
            --text-muted: #71717a;
            --sb-from: #0f172a;
            --sb-mid: #0f2742;
            --sb-to: #0891b2;
            --sb-text: #ffffff;
            --sb-text-muted: rgba(255,255,255,0.72);
            --sb-border: rgba(255,255,255,0.15);
            --sb-item-hover: rgba(255,255,255,0.12);
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* -- SIDEBAR -- */
        #sidebar {
            width: var(--sidebar-width);
            background: #090d16 !important;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.45) !important;
        }
        #sidebar::before {
            display: block !important;
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1;
        }
        #sidebar::after {
            display: block !important;
            content: '';
            position: absolute;
            bottom: -50px; left: -50px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.12) 0%, transparent 75%);
            pointer-events: none;
            z-index: 1;
        }

        /* Brand */
        .sidebar-brand {
            padding: 24px 20px !important;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            position: relative;
            z-index: 2;
            text-decoration: none;
            flex-shrink: 0;
        }
        .sidebar-brand:hover { text-decoration: none; }
        .brand-logo {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #22d3ee;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25) !important;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
        .sidebar-brand:hover .brand-logo {
            transform: scale(1.05) rotate(-4deg);
            border-color: rgba(99, 102, 241, 0.4) !important;
            background: rgba(99, 102, 241, 0.05) !important;
        }
        .brand-name {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
            margin-bottom: 2px;
            background: linear-gradient(90deg, #ffffff 30%, #818cf8 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
        }
        .brand-sub {
            font-size: 0.68rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5) !important;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        /* Nav */
        .nav-section-label {
            padding: 24px 22px 8px !important;
            font-size: 0.72rem;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.55) !important;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: transparent !important; /* overrides any highlights */
        }
        .sidebar-nav {
            padding: 10px 14px;
            flex: 1 1 0%;
            min-height: 0;
            position: relative;
            z-index: 2;
            overflow-y: auto;
            flex-wrap: nowrap !important;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.05) transparent;
        }
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            transition: background 0.3s;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        .sidebar-nav .nav-item { margin-bottom: 4px; }
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.88) !important;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            text-decoration: none;
            border: 1px solid transparent;
            margin: 2px 0;
        }
        .sidebar-nav .nav-link .nav-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.04) !important;
            color: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.03) !important;
            flex-shrink: 0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-nav .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.04) !important;
            transform: translateX(4px);
            border-color: rgba(255, 255, 255, 0.03) !important;
        }
        .sidebar-nav .nav-link:hover .nav-icon {
            background: rgba(99, 102, 241, 0.15) !important;
            color: #818cf8 !important;
            border-color: rgba(99, 102, 241, 0.25) !important;
            transform: scale(1.05) rotate(4deg);
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.12) 0%, rgba(99, 102, 241, 0.01) 100%) !important;
            color: #ffffff !important;
            font-weight: 700;
            border: 1px solid rgba(99, 102, 241, 0.15) !important;
            box-shadow: none !important;
        }
        .sidebar-nav .nav-link.active .nav-icon {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: #ffffff !important;
            border-color: #6366f1 !important;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.25) !important;
            transform: scale(1.02);
        }
        .sidebar-nav .nav-link.active::before {
            content: '';
            position: absolute;
            left: -14px; top: 20%;
            width: 4px; height: 60%;
            border-radius: 0 4px 4px 0;
            background: #6366f1 !important;
            box-shadow: 0 0 10px #6366f1 !important;
            animation: activeIndicator 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }
        @keyframes activeIndicator {
            from { transform: scaleY(0); }
            to { transform: scaleY(1); }
        }
        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.04) 50%, transparent);
            margin: 12px 14px;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 16px 14px 24px !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
            background: rgba(0, 0, 0, 0.2) !important;
        }
        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
        }
        .sidebar-user-card:hover {
            background: rgba(255, 255, 255, 0.04) !important;
            border-color: rgba(99, 102, 241, 0.2) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.1) !important;
        }
        .sidebar-user-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03) !important;
            color: #ffffff !important;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.82rem;
            flex-shrink: 0;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        .sidebar-user-avatar .online-dot {
            position: absolute;
            bottom: -2px; right: -2px;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid #090d16 !important;
            box-shadow: 0 0 6px #22c55e;
        }
        .sidebar-user-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 124px;
        }
        .sidebar-user-role {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #a5b4fc !important;
        }
        #sidebar-logout-form button:hover {
            background: rgba(239, 68, 68, 0.12) !important;
            color: #f87171 !important;
        }

        /* -- MAIN CONTENT -- */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--body-bg);
        }

        /* -- TOP NAVBAR -- */
        /* -- TOP NAVBAR PREMIUM DESIGN -- */
        .top-navbar {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
            padding: 0 32px;
            height: 64px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0; z-index: 1020;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02);
        }
        .navbar-date-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(99, 102, 241, 0.06);
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4f46e5;
            transition: all 0.2s ease;
        }
        .navbar-date-pill i { color: #4f46e5; }

        .notif-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            color: #475569;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .notif-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.12);
            background: #ffffff;
            border-color: #4f46e5;
            color: #4f46e5;
        }
        .navbar-user-avatar {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem;
            position: relative; flex-shrink: 0;
            transition: transform 0.2s ease;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
        }
        .navbar-user-avatar .online-dot {
            position: absolute; bottom: -2px; right: -2px;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #10b981;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        .navbar-user-name { font-size: 0.85rem; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .navbar-user-role {
            display: inline-block;
            font-size: 0.58rem; font-weight: 700;
            letter-spacing: 0.8px; text-transform: uppercase;
            padding: 2px 8px; border-radius: 6px;
            background: rgba(79, 70, 229, 0.08);
            color: #4f46e5;
            border: 1px solid rgba(79, 70, 229, 0.15);
        }
        .navbar-user-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }
        .navbar-user-btn:hover {
            background-color: rgba(0,0,0,0.03);
        }

        /* -- DROPDOWN -- */
        .dropdown-menu {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.1);
            animation: fadeInDD 0.2s cubic-bezier(0.16,1,0.3,1);
        }
        @keyframes fadeInDD {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Notification */
        .notification-scroll::-webkit-scrollbar { width: 4px; }
        .notification-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .notification-li { position: relative; }
        .notification-li .notification-item { transition: all 0.2s ease; background: #fff; }
        .notification-li .notification-item:hover { background: #f8fafc; padding-left: 1.75rem !important; }
        .notification-li::before {
            content: ''; position: absolute; left: 0; top: 0;
            height: 100%; width: 3px;
            background: var(--hover-accent, #6366f1);
            opacity: 0; transition: opacity 0.2s ease; z-index: 10;
        }
        .notification-li:hover::before { opacity: 1; }
        .notification-icon-wrapper { transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1); }
        .notification-li:hover .notification-icon-wrapper { transform: scale(1.15) rotate(5deg); }

        /* -- CONTENT -- */
        .content-body { padding: 32px; flex-grow: 1; }

        /* -- CARDS -- */
        .card {
            background: #fff;
            border: 1px solid var(--border-color) !important;
            border-radius: 14px !important;
            box-shadow: none !important;
            transition: box-shadow 0.2s ease;
        }
        .card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.05) !important; }
        .card-header {
            background: #fff !important;
            border-bottom: 1px solid var(--border-color) !important;
            font-weight: 600; color: var(--text-main);
            padding: 16px 20px;
            border-radius: 14px 14px 0 0 !important;
        }

        /* -- TABLE -- */
        .table { vertical-align: middle; margin-bottom: 0; }
        .table thead th {
            background: #f8fafc; color: var(--text-muted);
            font-weight: 600; border-bottom: 1px solid var(--border-color);
            border-top: none; font-size: 0.72rem;
            text-transform: uppercase; letter-spacing: 0.6px; padding: 13px 16px;
        }
        .table tbody td {
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 16px; color: var(--text-main); font-size: 0.875rem;
        }

        /* -- FORMS -- */
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 10px; padding: 10px 14px;
            font-size: 0.875rem; box-shadow: none !important;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12) !important;
        }
        .form-label { font-weight: 600; font-size: 0.8rem; color: #475569; margin-bottom: 6px; }

        /* -- BUTTONS -- */
        .btn {
            border-radius: 10px !important;
            font-weight: 600; font-size: 0.875rem;
            padding: 9px 18px;
            transition: all 0.18s ease;
            box-shadow: none !important;
        }
        .btn-primary, .btn-brand {
            background: linear-gradient(135deg, #0e3054 0%, #0891b2 100%);
            border: none; color: #fff;
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25) !important;
        }
        .btn-primary:hover, .btn-brand:hover {
            background: linear-gradient(135deg, #0f172a 0%, #0e7490 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(8, 145, 178, 0.35) !important;
            color: #fff;
        }
        .btn-outline-primary { color: #0891b2; border-color: #c5eaf2; background: #f0fdfa; }
        .btn-outline-primary:hover { background: #e0f2fe; border-color: #a5f3fc; color: #0e7490; }
        .btn-outline-danger { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
        .btn-outline-danger:hover { background: #dc2626; border-color: #dc2626; color: #fff; }
        .btn-success {
            background: linear-gradient(135deg, #16a34a, #059669);
            border: none;
            box-shadow: 0 4px 12px rgba(22,163,74,0.3) !important;
        }

        /* -- BADGES -- */
        .badge { font-weight: 600; padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; }

        /* -- SCROLLBAR -- */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .hover-notif {
            transition: all 0.2s ease;
        }
        .hover-notif:hover {
            background-color: #f8fafc !important;
            padding-left: 1.5rem !important;
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .pulse-badge {
            animation: pulse-red 2.2s infinite;
        }

        /* Dark Theme Variables & Styles */
        body.dark-theme {
            --body-bg: #121212;
            --border-color: #2d2d2d;
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
            --card-bg: #1e1e1e;
        }
        body.dark-theme .top-navbar {
            background: #1e1e1e !important;
            border-bottom: 1px solid #2d2d2d !important;
        }
        body.dark-theme .navbar-date-pill {
            background: #2d2d2d !important;
            border-color: #3f3f3f !important;
            color: #e2e8f0 !important;
        }
        body.dark-theme .notif-btn {
            background: #2d2d2d !important;
            border-color: #3f3f3f !important;
            color: #a1a1aa !important;
        }
        body.dark-theme .card, body.dark-theme .offcanvas, body.dark-theme .dropdown-menu {
            background-color: #1e1e1e !important;
            border-color: #2d2d2d !important;
            color: #f4f4f5 !important;
        }
        body.dark-theme .dropdown-item {
            color: #f4f4f5 !important;
        }
        body.dark-theme .dropdown-item:hover {
            background-color: #2d2d2d !important;
            color: #fff !important;
        }
        body.dark-theme .dropdown-menu .border-bottom {
            border-color: #2d2d2d !important;
        }
        body.dark-theme .card-header {
            background-color: #2d2d2d !important;
            border-bottom: 1px solid #3f3f3f !important;
            color: #f4f4f5 !important;
        }
        body.dark-theme input, body.dark-theme select, body.dark-theme textarea {
            background-color: #2d2d2d !important;
            border-color: #3f3f3f !important;
            color: #f4f4f5 !important;
        }
        body.dark-theme input::placeholder {
            color: #64748b !important;
        }
        body.dark-theme .text-dark {
            color: #f1f5f9 !important;
        }
        body.dark-theme .table-responsive, 
        body.dark-theme table {
            color: #f1f5f9 !important;
            background-color: #111827 !important;
        }
        body.dark-theme table tr,
        body.dark-theme table td {
            background-color: #111827 !important;
            color: #e2e8f0 !important;
            border-color: #1e293b !important;
        }
        body.dark-theme th {
            color: #94a3b8 !important;
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-theme .table-hover tbody tr:hover td {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }
        body.dark-theme .list-group-item {
            background-color: #111827 !important;
            color: #f1f5f9 !important;
        }
        body.dark-theme .modal-content {
            background-color: #111827 !important;
            color: #f1f5f9 !important;
            border-color: #1e293b !important;
        }
        body.dark-theme .hover-notif:hover {
            background-color: #1e293b !important;
        }
        body.dark-theme .bg-light {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-theme .bg-white {
            background-color: #111827 !important;
        }
        body.dark-theme .bg-body {
            background-color: #090d16 !important;
        }
        body.dark-theme .border,
        body.dark-theme .border-top,
        body.dark-theme .border-bottom,
        body.dark-theme .border-start,
        body.dark-theme .border-end {
            border-color: #1e293b !important;
        }
        body.dark-theme .text-muted {
            color: #cbd5e1 !important; /* Lighter gray for better visibility in dark mode */
        }
        body.dark-theme .text-secondary {
            color: #94a3b8 !important; /* High contrast secondary text in dark mode */
        }
        body.dark-theme .text-secondary-emphasis {
            color: #f1f5f9 !important;
        }
        body.dark-theme .border {
            border-color: #334155 !important;
        }

        /* Loading Skeleton Animation */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: loading-skeleton 1.5s infinite ease-in-out;
            border-radius: 8px;
            display: inline-block;
            height: 1em;
            width: 100%;
        }
        body.dark-theme .skeleton {
            background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
            background-size: 200% 100%;
            animation: loading-skeleton 1.5s infinite ease-in-out;
        }
        @keyframes loading-skeleton {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Floating Action Button (FAB) */
        .fab-container {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .fab-btn {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0e3054 0%, #0891b2 100%);
            color: #fff !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 16px rgba(8, 145, 178, 0.35);
            border: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
        }
        .fab-btn:hover {
            transform: scale(1.08) translateY(-2px);
            background: linear-gradient(135deg, #0f172a 0%, #0e7490 100%);
            box-shadow: 0 8px 24px rgba(8, 145, 178, 0.45);
            color: #fff !important;
        }
        .fab-btn:active {
            transform: scale(0.95);
        }

        /* Responsive Breakpoints & Mobile Friendly UI */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.15) !important;
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .top-navbar {
                padding: 0 16px !important;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0; left: 0;
                width: 100vw; height: 100vh;
                background: rgba(15, 23, 42, 0.4);
                backdrop-filter: blur(4px);
                z-index: 1025;
                display: none;
                animation: fadeInOverlay 0.2s ease-out;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }
        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        #qr-reader {
            border: 2px solid #e2e8f0 !important;
            background: #f8fafc !important;
        }
        body.dark-theme #qr-reader {
            border-color: #334155 !important;
            background: #1e293b !important;
        }

        /* ===== GLOBAL NAVY & CYAN COLOR PALETTE OVERRIDES ===== */
        :root {
            --bs-primary: #0891b2 !important;
            --bs-primary-rgb: 8, 145, 178 !important;
            --bs-link-color: #0e7490 !important;
            --bs-link-hover-color: #0891b2 !important;
        }
        .btn-primary {
            background-color: #0891b2 !important;
            border-color: #0891b2 !important;
            color: #ffffff !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #0e7490 !important;
            border-color: #0e7490 !important;
            color: #ffffff !important;
        }
        .btn-outline-primary {
            color: #0891b2 !important;
            border-color: #0891b2 !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: #0891b2 !important;
            border-color: #0891b2 !important;
            color: #ffffff !important;
        }
        .text-primary {
            color: #0891b2 !important;
        }
        .bg-primary {
            background-color: #0f172a !important;
        }
        .bg-primary-subtle {
            background-color: rgba(8, 145, 178, 0.1) !important;
            color: #0891b2 !important;
        }
        .border-primary {
            border-color: #0891b2 !important;
        }
        .badge.bg-primary {
            background-color: #0891b2 !important;
            color: #ffffff !important;
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #0891b2 !important;
        }
        a {
            color: #0e7490;
        }
        a:hover {
            color: #0891b2;
        }
        /* Top Progress & accent elements */
        .text-brand {
            color: #0891b2 !important;
        }
        .bg-brand {
            background-color: #0891b2 !important;
            color: #ffffff !important;
        }
        .btn-brand {
            background-color: #0891b2 !important;
            border-color: #0891b2 !important;
            color: #ffffff !important;
        }
        .btn-brand:hover {
            background-color: #0e7490 !important;
            border-color: #0e7490 !important;
            color: #ffffff !important;
        }
        /* Calendar / Event highlight */
        .fc-event, .fc-event-dot {
            background-color: #0891b2 !important;
        }

        /* --- MOBILE RESPONSIVE ADAPTATIONS --- */
        @media (max-width: 767.98px) {
            /* Reduce page padding on mobile to maximize content width */
            .content-body {
                padding: 16px !important;
            }
            
            /* Stack horizontal flex container filters vertically on mobile */
            .mb-4 > .d-flex, .mb-3 > .d-flex {
                flex-direction: column !important;
                align-items: stretch !important;
                width: 100% !important;
            }
            .mb-4 > .d-flex > *, .mb-3 > .d-flex > * {
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                flex-shrink: 1 !important;
            }
            
            /* Replace right-borders with bottom-borders for stacked column metrics */
            #main-content .border-end {
                border-right: 0 !important;
                border-bottom: 1px solid var(--border-color) !important;
                padding-bottom: 16px;
                margin-bottom: 12px;
            }
            #main-content .col-md-3:last-child.border-end,
            #main-content .col-md-3:last-child {
                border-bottom: 0 !important;
                padding-bottom: 0;
                margin-bottom: 0;
            }

            /* Replace left-borders with top-borders for stacked column panels */
            #main-content .border-start {
                border-left: 0 !important;
                border-top: 1px solid var(--border-color) !important;
                padding-top: 16px;
                margin-top: 12px;
            }

            /* Stack grid summaries in checklists and other pages */
            .summary-grid {
                grid-template-columns: 1fr !important;
            }

            /* Horizontal scroll for service calendar on mobile to prevent squeezing */
            .calendar-container {
                min-width: 650px !important;
            }
            .calendar-card-custom .card-body {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <script>
        // Check saved theme preference instantly to prevent flash of light theme
        (function() {
            const savedTheme = localStorage.getItem('fleet-theme') || 'light';
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
            }
        })();
    </script>

<nav id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-logo">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 28px; height: 28px;">
                <!-- Wrench (Background diagonal in Gold/Amber) -->
                <path d="M19.7 4.3a2.5 2.5 0 0 0-3.5 0l-2 2 3.5 3.5 2-2a2.5 2.5 0 0 0 0-3.5ZM12.7 7.8l-8.5 8.5a1.2 1.2 0 0 0 0 1.7l1.3 1.3a1.2 1.2 0 0 0 1.7 0l8.5-8.5-3-3Z" fill="#ffc107" />
                <!-- Truck Silhouette (Solid White with Dark outline) -->
                <path d="M 2.5,5.5 H 12.5 V 7.5 H 15.5 L 18.5,10.5 V 13.5 H 2.5 Z" fill="#ffffff" stroke="#1e1b4b" stroke-width="1.2" stroke-linejoin="round" />
                <!-- Cab Window -->
                <path d="M 13.5,8.5 H 15.2 L 16.8,10.5 H 13.5 Z" fill="#1e1b4b" />
                <!-- Wheels -->
                <circle cx="6" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                <circle cx="15" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
            </svg>
        </div>
        <div>
            <div class="brand-name">FleetMaintenance</div>
            <div class="brand-sub">{{ __('Sistem Manajemen Armada') }}</div>
        </div>
    </a>

    <ul class="nav flex-column sidebar-nav">
        <div class="nav-section-label">{{ __('Menu Utama') }}</div>

        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="bi bi-speedometer2"></i></div>
                {{ __('Dashboard') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="bi bi-car-front-fill"></i></div>
                {{ __('Kendaraan') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tracking.index') }}" class="nav-link {{ request()->routeIs('tracking*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="bi bi-geo-alt-fill"></i></div>
                {{ __('Pelacakan Kendaraan') }}
            </a>
        </li>
        @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi', 'user']))
        <li class="nav-item">
            <a href="{{ route('checklist.index') }}" class="nav-link {{ request()->routeIs('checklist*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="bi bi-clipboard-check-fill"></i></div>
                {{ __('Checklist Harian') }}
            </a>
        </li>
        @endif
        @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
        <li class="nav-item">
            <a href="{{ route('vehicle-histories.index') }}" class="nav-link {{ request()->routeIs('vehicle-histories*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="bi bi-clock-history"></i></div>
                {{ __('Riwayat Servis') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="bi bi-cash-stack"></i></div>
                {{ __('Rekap Biaya') }}
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a href="{{ route('complaints.index') }}" class="nav-link {{ request()->routeIs('complaints*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                {{ __('Keluhan Kendaraan') }}
            </a>
        </li>


        @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin']))
            <div class="sidebar-divider"></div>
            <div class="nav-section-label">{{ __('Administrasi') }}</div>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="bi bi-people-fill"></i></div>
                    {{ __('Kelola Pengguna') }}
                </a>
            </li>
        @endif
    </ul>

    @if(auth()->check())
    <div class="sidebar-footer">
        <div class="nav-section-label" style="padding: 0 0 8px 4px; font-size: 0.6rem; color: rgba(255, 255, 255, 0.35); text-transform: uppercase; letter-spacing: 1px;">{{ __('Masuk Sebagai') }}</div>
        <div class="sidebar-user-card d-flex align-items-center justify-content-between" style="padding: 10px 12px; border-radius: 12px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
            <div class="d-flex align-items-center gap-2" style="cursor: pointer; overflow: hidden; flex-grow: 1;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasProfile" title="{{ __('Lihat Profil') }}">
                <div class="sidebar-user-avatar">
                    @if(auth()->user()->kelas)
                        <img src="{{ asset(auth()->user()->kelas) }}" alt="{{ auth()->user()->name }}" class="w-100 h-100 object-fit-cover" style="border-radius:9px;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    @endif
                    <span class="online-dot"></span>
                </div>
                <div style="overflow:hidden;">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">{{ __(auth()->user()->role) }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0" id="sidebar-logout-form">
                @csrf
                <button type="submit" class="btn btn-link text-danger p-1 d-flex align-items-center justify-content-center" style="border-radius: 8px; transition: background 0.2s; width: 28px; height: 28px;" title="{{ __('Keluar') }}">
                    <i class="bi bi-box-arrow-right" style="font-size: 1rem;"></i>
                </button>
            </form>
        </div>
    </div>
    @endif
</nav>

<div id="main-content">
    <header class="top-navbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle" title="{{ __('Buka Menu') }}" style="padding: 6px 12px; border-radius: 8px;">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="navbar-date-pill d-none d-sm-inline-flex">
                <i class="bi bi-calendar-week-fill"></i>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">

            <!-- Language Switcher Dropdown -->
            <div class="dropdown">
                <button class="notif-btn" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Bahasa / Language Settings">
                    <i class="bi bi-translate"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end py-1.5 shadow-sm border-slate-150" aria-labelledby="languageDropdown" style="border-radius: 12px; min-width: 160px; font-size: 0.88rem; z-index: 1050;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between py-2 px-3 fw-semibold {{ App::getLocale() === 'id' ? 'text-primary' : 'text-dark' }}" href="{{ route('set-locale', 'id') }}">
                            <span>🇮🇩 Bahasa Indonesia</span>
                            @if(App::getLocale() === 'id')
                                <i class="bi bi-check-lg text-primary fs-6"></i>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between py-2 px-3 fw-semibold {{ App::getLocale() === 'en' ? 'text-primary' : 'text-dark' }}" href="{{ route('set-locale', 'en') }}">
                            <span>🇺🇸 English (US)</span>
                            @if(App::getLocale() === 'en')
                                <i class="bi bi-check-lg text-primary fs-6"></i>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Spotlight Command Trigger -->
            <button class="notif-btn" title="{{ __('Pencarian Cepat (Ctrl+K)') }}" data-bs-toggle="modal" data-bs-target="#spotlightModal">
                <i class="bi bi-search"></i>
            </button>



            <!-- QR Scanner Button -->
            <button class="notif-btn" title="{{ __('Scan QR Kendaraan') }}" data-bs-toggle="modal" data-bs-target="#qrScannerModal">
                <i class="bi bi-qr-code-scan"></i>
            </button>

            <!-- Notification Bell -->
            <div class="dropdown">
                <a class="notif-btn position-relative" href="#"
                   id="notificationDropdown" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false"
                   title="{{ __('Notifikasi Sistem') }}">
                    <i class="bi bi-bell-fill"></i>
                    @if(isset($notifCount) && $notifCount > 0)
                        <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger border border-white notification-bell-badge"
                              style="font-size:0.58rem; min-width:18px; padding:3px 5px;">
                            {{ $notifCount }}
                        </span>
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end py-0 mt-2"
                    style="width:360px; border-radius:16px; overflow:hidden;">
                    <li class="px-4 py-3 d-flex justify-content-between align-items-center border-bottom"
                        style="background:linear-gradient(135deg,#ef4444,#dc2626); border: none;">
                        <span class="fw-bold text-white" style="font-size:0.9rem;">
                            <i class="bi bi-bell-fill me-2"></i> {{ __('Notifikasi') }}
                        </span>
                        @if(isset($notifCount) && $notifCount > 0)
                            <span class="badge bg-white notification-header-badge" style="color:#dc2626; font-size:0.68rem;">
                                {{ $notifCount }} {{ __('Baru') }}
                            </span>
                        @endif
                    </li>

                    <div class="notification-scroll bg-white" style="max-height:290px; overflow-y:auto;">
                        <!-- Client-side empty state -->
                        <li class="notification-empty-li d-none" style="list-style: none;">
                            <div class="text-center py-5 text-muted px-4">
                                <div class="bg-success-subtle text-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width:52px;height:52px;">
                                    <i class="bi bi-shield-fill-check fs-3"></i>
                                </div>
                                <span class="fw-bold text-dark d-block mb-1" style="font-size:0.95rem;">{{ __('Armada Bebas Masalah') }}</span>
                                <small class="text-muted d-block" style="font-size:0.78rem;">{{ __('Semua armada dalam kondisi normal dan baik-baik saja.') }}</small>
                            </div>
                        </li>

                        @forelse($notifItems ?? [] as $n)
                             @php
                                 $isDanger = str_contains($n['icon'], 'text-danger');
                                 $isSuccess = str_contains($n['icon'], 'text-success');
                                 $iconClass = explode(' ', $n['icon'])[0];
                                 $bgColor = $isDanger ? 'bg-danger-subtle' : ($isSuccess ? 'bg-success-subtle' : 'bg-warning-subtle');
                                 $iconColor = $isDanger ? 'text-danger' : ($isSuccess ? 'text-success' : 'text-warning');
                                 $hoverColor = $isDanger ? '#ef4444' : ($isSuccess ? '#10b981' : '#f59e0b');
                                 $badgeText = $isDanger ? __('Urgent') : ($isSuccess ? __('Selesai') : __('Info'));
                                 $notifHash = md5($n['text'] . $n['link']);
                             @endphp
                             <li class="notification-li" style="--hover-accent:{{ $hoverColor }};" data-notif-hash="{{ $notifHash }}">
                                 <a class="dropdown-item py-3 px-4 border-bottom text-wrap notification-item"
                                    href="{{ $n['link'] }}">
                                     <div class="d-flex align-items-start gap-3">
                                         <div class="{{ $bgColor }} {{ $iconColor }} rounded-circle d-flex align-items-center justify-content-center notification-icon-wrapper"
                                              style="width:42px;height:42px;min-width:42px;flex-shrink:0;">
                                             <i class="bi {{ $iconClass }} fs-5"></i>
                                         </div>
                                         <div class="flex-grow-1">
                                             <div class="fw-bold text-dark d-flex justify-content-between align-items-center" style="font-size:0.9rem;">
                                                 <span>{{ __('Pemberitahuan Penting') }}</span>
                                                 <span class="badge {{ $isDanger ? 'bg-danger' : ($isSuccess ? 'bg-success' : 'bg-warning text-dark') }}" style="font-size:0.62rem;">{{ $badgeText }}</span>
                                             </div>
                                             <div class="text-muted mt-1" style="font-size:0.8rem; line-height:1.4;">
                                                 {{ $n['text'] }}
                                             </div>
                                         </div>
                                     </div>
                                 </a>
                             </li>
                         @empty
                             <li style="list-style: none;">
                                 <div class="text-center py-5 text-muted px-4">
                                     <div class="bg-success-subtle text-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width:52px;height:52px;">
                                         <i class="bi bi-shield-fill-check fs-3"></i>
                                     </div>
                                     <span class="fw-bold text-dark d-block mb-1" style="font-size:0.95rem;">{{ __('Armada Bebas Masalah') }}</span>
                                     <small class="text-muted d-block" style="font-size:0.78rem;">{{ __('Semua armada dalam kondisi normal dan baik-baik saja.') }}</small>
                                 </div>
                             </li>
                         @endforelse
                     </div>
 
                     @if(isset($notifCount) && $notifCount > 0)
                     <li class="bg-light text-center py-3 border-top notification-footer-link">
                         <a href="{{ route('dashboard') }}" class="text-decoration-none fw-bold" style="font-size:0.82rem; color:#4f46e5;">
                             {{ __('Buka Dashboard Utama') }} <i class="bi bi-arrow-right ms-1"></i>
                         </a>
                     </li>
                     @endif
                </ul>
            </div>

            <!-- User Dropdown -->
            @if(auth()->check())
            <div class="dropdown" style="border-left:1px solid #e2e8f0; padding-left:16px;">
                <a href="#"
                   class="navbar-user-btn text-decoration-none dropdown-toggle"
                   style="color:inherit;"
                   id="userDropdown"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">
                    <div class="d-none d-sm-block text-end">
                        <div class="navbar-user-name">{{ auth()->user()->name }}</div>
                        <span class="navbar-user-role">{{ __(auth()->user()->role) }}</span>
                    </div>
                    <div class="navbar-user-avatar">
                        @if(auth()->user()->kelas)
                            <img src="{{ asset(auth()->user()->kelas) }}" alt="{{ auth()->user()->name }}" class="w-100 h-100 object-fit-cover" style="border-radius:10px;">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        @endif
                        <span class="online-dot"></span>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end py-2 mt-2" aria-labelledby="userDropdown" style="width:230px; border-radius:14px;">
                    <li>
                        <div class="px-4 py-2 border-bottom mb-1">
                            <div class="fw-bold text-dark" style="font-size:0.88rem;">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size:0.78rem; margin-top:1px;">{{ auth()->user()->email }}</div>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 fw-semibold" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasProfile" style="font-size:0.875rem; border-radius:8px; margin:0 6px; width:calc(100% - 12px);">
                            <i class="bi bi-person-bounding-box fs-5 text-primary"></i> {{ __('Profil Saya') }}
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger fw-semibold"
                                    style="font-size:0.875rem; border-radius:8px; margin:0 6px; width:calc(100% - 12px);">
                                <i class="bi bi-box-arrow-right fs-5"></i> {{ __('Keluar Sistem') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </header>

    <main class="content-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm d-flex align-items-center gap-2" role="alert" style="border-radius: 12px; font-size: 0.9rem; background-color: #ecfdf5; color: #065f46;">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm d-flex align-items-center gap-2" role="alert" style="border-radius: 12px; font-size: 0.9rem; background-color: #fef2f2; color: #991b1b;">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>
</div>

<!-- Offcanvas Notification Center -->
<div class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvasNotification" aria-labelledby="offcanvasNotificationLabel" style="width: 400px; background: #ffffff; box-shadow: -10px 0 40px rgba(0,0,0,0.15);">
    <div class="offcanvas-header border-bottom py-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff;">
        <h5 class="offcanvas-title fw-bold" id="offcanvasNotificationLabel">
            <i class="bi bi-bell-fill me-2"></i> {{ __('Pusat Notifikasi') }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush" id="offcanvasNotificationItems">
            @forelse($notifItems ?? [] as $item)
                @php $notifId = md5($item['text']); @endphp
                <a href="{{ $item['link'] }}" data-notif-id="{{ $notifId }}" class="list-group-item list-group-item-action p-3 border-0 border-bottom d-flex align-items-start gap-3 transition-all hover-notif notification-sidebar-item" style="border-color: #f1f5f9 !important;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-light" style="width: 38px; height: 38px; min-width: 38px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <i class="bi {{ $item['icon'] }} fs-5"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0 text-dark fw-medium" style="font-size: 0.88rem; line-height: 1.45;">{{ __($item['text']) }}</p>
                        <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.72rem;">
                            <i class="bi bi-clock"></i> {{ __('Baru saja') }}
                        </small>
                    </div>
                </a>
            @empty
                <div class="text-center py-5 text-muted px-4 notification-empty-state">
                    <div class="bg-success-subtle text-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <span class="fw-bold text-dark d-block mb-1" style="font-size: 0.95rem;">{{ __('Semua Sistem Lancar!') }}</span>
                    <small class="text-muted d-block" style="font-size: 0.78rem;">{{ __('Tidak ada keluhan baru, pengeluaran tertunda, atau jadwal KIR/Servis mendesak yang memerlukan perhatian Anda saat ini.') }}</small>
                </div>
            @endforelse
        </div>
        <!-- Hidden Empty State template for JS use when all items are dismissed -->
        <div id="jsNotificationEmptyState" class="d-none">
            <div class="bg-success-subtle text-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                <i class="bi bi-shield-check fs-2"></i>
            </div>
            <span class="fw-bold text-dark d-block mb-1" style="font-size: 0.95rem;">{{ __('Semua Sistem Lancar!') }}</span>
            <small class="text-muted d-block" style="font-size: 0.78rem;">{{ __('Tidak ada keluhan baru, pengeluaran tertunda, atau jadwal KIR/Servis mendesak yang memerlukan perhatian Anda saat ini.') }}</small>
        </div>
    </div>
</div>

<!-- Offcanvas Profile Detail -->
<div class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvasProfile" aria-labelledby="offcanvasProfileLabel" style="width: 420px; background: #ffffff; box-shadow: -10px 0 40px rgba(0,0,0,0.15);">
    <div class="offcanvas-header border-bottom py-3" style="background: linear-gradient(135deg, #0f172a 0%, #450a0a 100%); color: #fff;">
        <h5 class="offcanvas-title fw-bold" id="offcanvasProfileLabel">
            <i class="bi bi-person-fill me-2"></i> {{ __('Profil Saya') }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="border-radius: 10px; font-size: 0.85rem;">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3" style="border-radius: 10px; font-size: 0.85rem;">
                <ul class="mb-0 px-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="text-center mb-4">
                <div class="position-relative d-inline-block mx-auto">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow overflow-hidden" id="profileImageContainer" style="width: 100px; height: 100px; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; font-size: 2.2rem; font-weight: 800; border: 3px solid #fff;">
                        @if(auth()->user()->kelas)
                            <img src="{{ asset(auth()->user()->kelas) }}" alt="{{ auth()->user()->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <label for="avatarUpload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; cursor: pointer; border: 2px solid #fff; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="bi bi-camera-fill" style="font-size: 0.85rem;"></i>
                    </label>
                    <input type="file" id="avatarUpload" name="avatar" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                </div>
                <h4 class="fw-extrabold text-dark mb-1 mt-2">{{ auth()->user()->name }}</h4>
                <span class="badge bg-primary px-3 py-1.5 rounded-pill text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __(auth()->user()->role) }}</span>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('Nama Lengkap') }}</label>
                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required style="border-radius: 8px; font-size: 0.88rem;">
            </div>

            <div class="mb-3">
                <label class="form-label text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('Nomor Induk Pegawai (NIP)') }}</label>
                <input type="text" name="nis" class="form-control" value="{{ auth()->user()->nis }}" placeholder="{{ __('Belum Diatur') }}" style="border-radius: 8px; font-size: 0.88rem;">
            </div>

            <div class="mb-3">
                <label class="form-label text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('Alamat Email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required style="border-radius: 8px; font-size: 0.88rem;">
            </div>

            <div class="mb-3">
                <label class="form-label text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('Password Baru (kosongkan jika tidak diubah)') }}</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" style="border-radius: 8px; font-size: 0.88rem;">
            </div>

            <div class="mb-3">
                <label class="form-label text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('Konfirmasi Password Baru') }}</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" style="border-radius: 8px; font-size: 0.88rem;">
            </div>

            <div class="d-flex flex-column gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary py-2.5 fw-bold w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; font-size: 0.9rem;">
                    <i class="bi bi-save2-fill"></i> {{ __('Simpan Perubahan') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Spotlight Command Palette Modal -->
<div class="modal fade" id="spotlightModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="p-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-search text-muted fs-4"></i>
                <input type="text" id="spotlightInput" class="form-control form-control-lg border-0 shadow-none bg-transparent ps-1" placeholder="{{ __('Ketik perintah atau cari menu... (Tekan Esc untuk batal)') }}" style="font-size: 1.1rem;">
            </div>
            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                <div class="list-group list-group-flush" id="spotlightResults">
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-speedometer2 fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Dashboard Utama') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Kembali ke halaman ringkasan monitoring armada') }}</small>
                        </div>
                    </a>
                    <a href="{{ route('vehicles.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-car-front-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Data Kendaraan / Armada') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Lihat daftar mobil, masa berlaku KIR, dan status jalan') }}</small>
                        </div>
                    </a>
                    <a href="{{ route('tracking.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-geo-alt-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Pelacakan Kendaraan (Live GPS Map)') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Pantau posisi geografis dan rute armada secara real-time') }}</small>
                        </div>
                    </a>
                    <a href="{{ route('checklist.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-clipboard-check-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Checklist Fisik Harian') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Buat laporan inspeksi kondisi fisik armada harian') }}</small>
                        </div>
                    </a>
                    @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                    <a href="{{ route('expenses.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-cash-stack fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Rekap Biaya Operasional') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Manajemen pengeluaran bensin, tol, dan servis') }}</small>
                        </div>
                    </a>
                    @endif
                    @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                    <a href="{{ route('vehicle-histories.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-clock-history fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Riwayat Servis Kendaraan') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Lihat dan kelola catatan perbaikan/pemeliharaan armada') }}</small>
                        </div>
                    </a>
                    @endif
                    <a href="{{ route('complaints.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-exclamation-triangle-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Keluhan Kendaraan') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Laporkan kerusakan atau masalah teknis pada mobil') }}</small>
                        </div>
                    </a>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 border-0">
                        <div class="rounded-3 bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;"><i class="bi bi-people-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ __('Manajemen Pengguna') }}</div>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ __('Kelola akun admin, teknisi, dan driver sistem') }}</small>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            <div class="p-2.5 border-top bg-light d-flex justify-content-between align-items-center px-3" style="font-size: 0.75rem;">
                <span class="text-muted">{!! __('Gunakan tombol <kbd class="bg-dark text-white px-1.5 py-0.5 rounded shadow-sm" style="font-size:0.68rem;">Ctrl + K</kbd> untuk membuka dari mana saja') !!}</span>
                <span class="text-muted">{!! __('Tekan <kbd class="bg-dark text-white px-1.5 py-0.5 rounded shadow-sm" style="font-size:0.68rem;">Esc</kbd> untuk menutup') !!}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Avatar upload preview
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImageContainer').innerHTML = 
                    '<img src="' + e.target.result + '" class="w-100 h-100 object-fit-cover">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // System Dark Theme Engine
    function toggleSystemTheme() {
        const body = document.body;
        const icon = document.getElementById('themeIcon');
        if (body.classList.contains('dark-theme')) {
            body.classList.remove('dark-theme');
            icon.className = 'bi bi-moon-stars-fill';
            localStorage.setItem('fleet-theme', 'light');
        } else {
            body.classList.add('dark-theme');
            icon.className = 'bi bi-sun-fill';
            localStorage.setItem('fleet-theme', 'dark');
        }
        window.dispatchEvent(new Event('themeChanged'));
    }

    // Load Theme Icon status on Init
    (function() {
        const savedTheme = localStorage.getItem('fleet-theme') || 'light';
        const icon = document.getElementById('themeIcon');
        if (savedTheme === 'dark') {
            if (icon) icon.className = 'bi bi-sun-fill';
        } else {
            if (icon) icon.className = 'bi bi-moon-stars-fill';
        }
    })();

    // Spotlight Search Command Palette (Ctrl + K)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            const spotlightModalEl = document.getElementById('spotlightModal');
            let spotlightModal = bootstrap.Modal.getInstance(spotlightModalEl);
            if (!spotlightModal) {
                spotlightModal = new bootstrap.Modal(spotlightModalEl);
            }
            spotlightModal.show();
        }
    });

    // Autofocus Spotlight search input when modal shown & Sidebar Mobile Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const spotlightModalEl = document.getElementById('spotlightModal');
        if (spotlightModalEl) {
            spotlightModalEl.addEventListener('shown.bs.modal', function () {
                document.getElementById('spotlightInput').focus();
            });
        }

        // Realtime search filtering inside Spotlight Modal
        const spotlightInput = document.getElementById('spotlightInput');
        if (spotlightInput) {
            spotlightInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                const items = document.querySelectorAll('#spotlightResults .list-group-item');
                items.forEach(function(item) {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(query)) {
                        item.style.setProperty('display', 'flex', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        }

        // Mobile Sidebar Toggle Engine
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        // Create dynamic mobile overlay
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // Instant Navigation Prefetcher
        const sidebarLinks = document.querySelectorAll(".sidebar-nav a[href], .sidebar-brand[href]");
        const prefetchedUrls = new Set();
        sidebarLinks.forEach(link => {
            const url = link.getAttribute("href");
            if (!url || url.startsWith("#") || url.includes("logout") || url.includes("javascript:")) {
                return;
            }
            const startPrefetch = () => {
                if (!prefetchedUrls.has(url)) {
                    prefetchedUrls.add(url);
                    const linkElem = document.createElement("link");
                    linkElem.rel = "prefetch";
                    linkElem.href = url;
                    document.head.appendChild(linkElem);
                }
            };
            link.addEventListener("mouseenter", startPrefetch);
            link.addEventListener("touchstart", startPrefetch, { passive: true });
        });
    });
</script>

<!-- html5-qrcode Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="qrScannerModalLabel">
                    <i class="bi bi-qr-code-scan text-primary me-2"></i> {{ __('Scan QR Kendaraan') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeQrScannerBtn"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="text-muted mb-3" style="font-size: 0.88rem;">{{ __('Arahkan kamera ke QR Code kendaraan untuk melihat informasi detail secara instan.') }}</p>
                <div id="qr-reader" style="width: 100%; max-width: 400px; margin: 0 auto; border-radius: 12px; overflow: hidden;"></div>
                <div id="qr-reader-results" class="mt-3 fw-bold text-success" style="font-size: 0.95rem;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let html5QrcodeScanner = null;

        const qrModalEl = document.getElementById('qrScannerModal');
        if (qrModalEl) {
            qrModalEl.addEventListener('shown.bs.modal', function () {
                document.getElementById('qr-reader-results').innerText = "";
                
                function onScanSuccess(decodedText, decodedResult) {
                    let targetUrl = decodedText;
                    if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                        try {
                            const parsedUrl = new URL(decodedText);
                            // Force host/port to match the current browser location to avoid 127.0.0.1/localhost redirect conflicts
                            parsedUrl.host = window.location.host;
                            targetUrl = parsedUrl.toString();
                        } catch(e) {
                            console.error("Invalid URL inside QR Code", e);
                        }
                        
                        document.getElementById('qr-reader-results').innerText = "Berhasil memindai! Mengarahkan...";
                        setTimeout(() => {
                            window.location.href = targetUrl;
                        }, 800);
                        if (html5QrcodeScanner) {
                            html5QrcodeScanner.clear();
                        }
                    } else {
                        document.getElementById('qr-reader-results').innerText = "Data QR: " + decodedText;
                    }
                }

                function onScanFailure(error) {
                    // Frame scan failed, normal behavior
                }

                // Dynamic responsive scanning area box config
                const qrboxFunction = (viewfinderWidth, viewfinderHeight) => {
                    const minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                    const qrboxSize = Math.floor(minEdgeSize * 0.7);
                    return { width: qrboxSize, height: qrboxSize };
                };

                html5QrcodeScanner = new Html5Qrcode("qr-reader");
                html5QrcodeScanner.start(
                    { facingMode: "environment" }, 
                    {
                        fps: 15,
                        qrbox: qrboxFunction
                    },
                    onScanSuccess,
                    onScanFailure
                ).catch(err => {
                    console.error("Gagal memulai kamera: ", err);
                    document.getElementById('qr-reader').innerHTML = `
                        <div class="p-4 text-danger">
                            <i class="bi bi-camera-video-off fs-1 d-block mb-2"></i>
                            <strong>Akses Kamera Ditolak / Tidak Ditemukan</strong>
                            <p class="small text-muted mt-2 mb-0">Pastikan Anda menggunakan HTTPS atau localhost dan memberikan izin akses kamera.</p>
                        </div>
                    `;
                });
            });

            qrModalEl.addEventListener('hidden.bs.modal', function () {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.stop().then(() => {
                        html5QrcodeScanner.clear();
                    }).catch(err => {
                        console.error("Gagal menghentikan kamera: ", err);
                    });
                }
            });
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Read dismissed notification hashes from localStorage
        let dismissedHashes = [];
        try {
            dismissedHashes = JSON.parse(localStorage.getItem('dismissed_notif_hashes') || '[]');
        } catch(e) {
            dismissedHashes = [];
        }

        function updateNotifBadges() {
            let activeItems = document.querySelectorAll('.notification-li:not(.d-none)');
            let activeCount = activeItems.length;

            // Update top bell badge count
            let bellBadges = document.querySelectorAll('.notification-bell-badge');
            bellBadges.forEach(badge => {
                badge.innerText = activeCount;
                if (activeCount <= 0) {
                    badge.classList.add('d-none');
                } else {
                    badge.classList.remove('d-none');
                }
            });

            // Update header count badge inside dropdown
            let headerBadges = document.querySelectorAll('.notification-header-badge');
            headerBadges.forEach(badge => {
                badge.innerText = activeCount + ' ' + '{{ __("Baru") }}';
                if (activeCount <= 0) {
                    badge.classList.add('d-none');
                } else {
                    badge.classList.remove('d-none');
                }
            });

            // Toggle client-side empty state list item
            let emptyStateLi = document.querySelector('.notification-empty-li');
            if (emptyStateLi) {
                if (activeCount === 0) {
                    emptyStateLi.classList.remove('d-none');
                    // Hide main dashboard link at bottom of dropdown if no active notifications
                    let footerLink = document.querySelector('.notification-footer-link');
                    if (footerLink) {
                        footerLink.classList.add('d-none');
                    }
                } else {
                    emptyStateLi.classList.add('d-none');
                    let footerLink = document.querySelector('.notification-footer-link');
                    if (footerLink) {
                        footerLink.classList.remove('d-none');
                    }
                }
            }
        }

        // Apply dismissed state on page load
        let items = document.querySelectorAll('.notification-li');
        items.forEach(item => {
            let notifHash = item.getAttribute('data-notif-hash');
            if (dismissedHashes.includes(notifHash)) {
                item.classList.add('d-none');
            }
        });

        updateNotifBadges();

        // Attach click handlers to notification items to save dismiss state
        let scrollContainer = document.querySelector('.notification-scroll');
        if (scrollContainer) {
            scrollContainer.addEventListener('click', function(e) {
                let notifLink = e.target.closest('.notification-item');
                if (notifLink) {
                    let notifItem = notifLink.closest('.notification-li');
                    if (notifItem) {
                        let notifHash = notifItem.getAttribute('data-notif-hash');
                        if (notifHash) {
                            if (!dismissedHashes.includes(notifHash)) {
                                dismissedHashes.push(notifHash);
                                try {
                                    localStorage.setItem('dismissed_notif_hashes', JSON.stringify(dismissedHashes));
                                } catch(err) {}
                            }
                            // Hide instantly in DOM
                            notifItem.classList.add('d-none');
                            updateNotifBadges();
                        }
                    }
                }
            });
        }

        // Global Delete Form Confirmation
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('.form-confirm-delete');
            if (form) {
                e.preventDefault();
                const warningText = form.getAttribute('data-text') || 'Data ini akan dihapus secara permanen!';
                if (confirm('{{ __('Yakin ingin menghapus?') }}' + '\n\n' + warningText)) {
                    form.submit();
                }
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
