<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FleetMaintenance System Armada</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #0891b2;
            --brand-indigo: #0e3054;
            --brand-yellow: #fbbf24;
            --brand-yellow-hover: #d97706;
            --text-light: #f8fafc;
            --text-muted-light: rgba(255, 255, 255, 0.75);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0e3054 0%, #06182c 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow-x: hidden;
            color: var(--text-light);
        }

        /* Ambient Glow Background Spheres */
        .bg-glow-1 {
            position: absolute;
            top: -15%;
            left: -10%;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.35) 0%, rgba(15, 23, 42, 0) 70%);
            pointer-events: none;
            z-index: 1;
        }
        .bg-glow-2 {
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.25) 0%, rgba(15, 23, 42, 0) 70%);
            pointer-events: none;
            z-index: 1;
        }

        /* Header Navbar */
        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff !important;
        }
        .brand-logo-container {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        .brand-logo-container svg {
            width: 28px;
            height: 28px;
        }
        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
            line-height: 1;
        }
        .brand-subtitle {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.55);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Hero Left Side */
        .hero-section {
            padding: 3rem 0;
            position: relative;
            z-index: 2;
        }
        .badge-digital {
            background-color: var(--brand-yellow);
            color: #1e1b4b;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 6px 14px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .hero-title {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 1.25rem;
        }
        .hero-title span {
            color: var(--brand-yellow);
        }
        .hero-desc {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-muted-light);
            margin-bottom: 2rem;
            max-width: 520px;
        }

        /* Glassmorphism Login Card (Right Side) */
        .login-card-wrapper {
            position: relative;
            z-index: 2;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 2.75rem;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #1e293b;
        }
        .form-control-custom {
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 16px 12px 44px;
            font-size: 0.95rem;
            color: #0f172a;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(59, 46, 242, 0.15);
            background-color: #ffffff;
            outline: none;
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.15rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .form-control-custom:focus + .input-icon {
            color: var(--brand-primary);
        }

        /* Buttons styling */
        .btn-submit {
            background: linear-gradient(135deg, var(--brand-primary) 0%, #1d0fb0 100%);
            border: none;
            color: #ffffff;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 8px 24px rgba(59, 46, 242, 0.35);
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 12px 28px rgba(59, 46, 242, 0.45);
            color: #ffffff;
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .btn-outline-custom {
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            border-radius: 12px;
            padding: 11px 24px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
        }
        .btn-outline-custom:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ffffff;
            color: #ffffff;
        }

        /* Quick Roles Hint Container */
        .quick-hint {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.78rem;
            color: #64748b;
        }

        /* WhatsApp Floating Help Widget */
        .whatsapp-widget {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #25d366;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 30px;
            box-shadow: 0 4px 18px rgba(37, 211, 102, 0.4);
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none !important;
            transition: all 0.25s ease;
        }
        .whatsapp-widget:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.55);
            color: #ffffff;
        }
        .whatsapp-icon-bg {
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Background image decorative */
        .truck-backdrop {
            position: absolute;
            right: -10%;
            bottom: 0;
            opacity: 0.12;
            width: 55%;
            pointer-events: none;
            z-index: 1;
            transform: scaleX(-1); /* Flip horizontally to look towards form */
        }

        @media (max-width: 991.98px) {
            .hero-section {
                text-align: center;
                padding-bottom: 1rem;
            }
            .hero-desc {
                margin-left: auto;
                margin-right: auto;
            }
            .truck-backdrop {
                display: none;
            }
            .login-card {
                padding: 2rem;
            }
            .hero-title {
                font-size: 2.2rem;
            }
        }
        
        /* Pulsing animation for WhatsApp icon */
        .pulse-animation {
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 20px rgba(37, 211, 102, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }
    </style>
</head>
<body>

<div class="bg-glow-1"></div>
<div class="bg-glow-2"></div>

<!-- Top Alert Bar -->
<div class="w-100 text-center py-2 px-3 fw-medium" style="background: rgba(255, 255, 255, 0.08); font-size: 0.8rem; letter-spacing: 0.3px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); position: relative; z-index: 5;">
    <i class="bi bi-geo-alt-fill me-1 text-warning"></i> Izinkan lokasi pada browser Anda untuk pemantauan GPS armada secara real-time
</div>

<!-- Main Wrapper Container -->
<div class="container d-flex flex-column justify-content-between flex-grow-1 py-4" style="position: relative; z-index: 5;">
    
    <!-- Top Header Navigation -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <a href="/" class="navbar-brand-custom">
            <div class="brand-logo-container">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Wrench (Gold/Amber) -->
                    <path d="M19.7 4.3a2.5 2.5 0 0 0-3.5 0l-2 2 3.5 3.5 2-2a2.5 2.5 0 0 0 0-3.5ZM12.7 7.8l-8.5 8.5a1.2 1.2 0 0 0 0 1.7l1.3 1.3a1.2 1.2 0 0 0 1.7 0l8.5-8.5-3-3Z" fill="#fbbf24" />
                    <!-- Truck Silhouette (Solid Indigo) -->
                    <path d="M 2.5,5.5 H 12.5 V 7.5 H 15.5 L 18.5,10.5 V 13.5 H 2.5 Z" fill="#0891b2" stroke="#1e1b4b" stroke-width="1.2" stroke-linejoin="round" />
                    <!-- Cab Window -->
                    <path d="M 13.5,8.5 H 15.2 L 16.8,10.5 H 13.5 Z" fill="#1e1b4b" />
                    <!-- Wheels -->
                    <circle cx="6" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                    <circle cx="15" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                </svg>
            </div>
            <div>
                <h1 class="brand-title">FleetMaintenance</h1>
                <div class="brand-sub">Sistem Manajemen Armada</div>
            </div>
        </a>
        
        <div class="d-none d-md-flex align-items-center gap-2">
            <span class="badge bg-success-subtle text-success py-1.5 px-3 border border-success-subtle rounded-pill d-flex align-items-center gap-2" style="font-size: 0.72rem;">
                <span class="bg-success rounded-circle" style="width:6px; height:6px; display:inline-block; box-shadow: 0 0 8px #22c55e;"></span>
                Server Active
            </span>
        </div>
    </header>

    <!-- Content Hero & Form Row -->
    <div class="row align-items-center g-4 my-auto">
        
        <!-- Left Side: Hero Info -->
        <div class="col-lg-6 hero-section">
            <span class="badge-digital">Booking &amp; Perawatan Digital</span>
            <h2 class="hero-title">Pemantauan &amp; Servis Armada Jadi <span>Lebih Mudah</span></h2>
            <p class="hero-desc">
                Sistem informasi terpadu untuk memantau kelayakan KIR, estimasi jadwal servis berkala otomatis (setiap 5.000 KM / 3 bulan), pengisian checklist harian, dan pelaporan kendala jalan dari supir secara real-time.
            </p>
            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                <a href="#panduanAdmin" class="btn-outline-custom" data-bs-toggle="modal" data-bs-target="#quickGuideModal">
                    <i class="bi bi-book-half"></i> Panduan Peran (Role)
                </a>
                <a href="#whatsappModal" class="btn-outline-custom" data-bs-toggle="modal" data-bs-target="#whatsappModal">
                    <i class="bi bi-whatsapp"></i> Hubungi IT / Support
                </a>
            </div>
        </div>

        <!-- Right Side: Login Form Box -->
        <div class="col-lg-5 offset-lg-1 login-card-wrapper">
            <div class="login-card">
                <div class="text-center mb-4">
                    <h3 class="fw-extrabold text-dark mb-1">Masuk Sistem Armada</h3>
                    <p class="text-secondary small">Masukkan username/email dan password Anda untuk masuk ke sistem.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-4" style="background-color: #fef2f2; color: #991b1b; font-size: 0.825rem;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px;">Email atau Username</label>
                        <div class="input-icon-wrapper">
                            <input type="text" name="login" class="form-control-custom w-100" placeholder="admin_fleet atau budi.teknisi@fleet.com" value="{{ old('login') }}" required autofocus>
                            <i class="bi bi-person input-icon"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px;">Password</label>
                        <div class="input-icon-wrapper">
                            <input type="password" name="password" class="form-control-custom w-100" placeholder="••••••••" required>
                            <i class="bi bi-lock input-icon"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check m-0">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember" style="border-radius: 4px;">
                            <label class="form-check-label text-secondary" for="remember" style="font-size: 0.8rem; font-weight: 500;">Ingat sesi masuk saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 d-flex align-items-center justify-content-center gap-2 mb-3">
                        <span>Masuk ke Dashboard</span>
                        <i class="bi bi-arrow-right-short fs-4"></i>
                    </button>

                    <div class="quick-hint d-flex align-items-start gap-2.5">
                        <i class="bi bi-info-circle-fill text-primary fs-6 mt-0.5"></i>
                        <span>Akses terbatas untuk akun terdaftar. Supir / Driver dapat masuk dengan menggunakan username masing-masing.</span>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Decorative Box Truck Image Background -->
    <img src="{{ asset('images/box_truck.png') }}" class="truck-backdrop" alt="Box Truck Decoration">

    <!-- Footer Copyright -->
    <footer class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-4 mt-4 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important; font-size: 0.75rem; color: var(--text-muted-light);">
        <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} FleetMaintenance System &bull; Versi 2.5 Premium Active.</p>
        <div class="d-flex gap-3">
            <a href="#" class="text-white-50 text-decoration-none">Ketentuan Layanan</a>
            <a href="#" class="text-white-50 text-decoration-none">Kebijakan Privasi</a>
        </div>
    </footer>

</div>

<!-- WhatsApp Support Button Trigger -->
<a href="#whatsappModal" class="whatsapp-widget" data-bs-toggle="modal" data-bs-target="#whatsappModal">
    <div class="whatsapp-icon-bg"><i class="bi bi-whatsapp"></i></div>
    <span>Butuh bantuan?</span>
</a>

<!-- Quick Guide Modal -->
<div class="modal fade" id="quickGuideModal" tabindex="-1" aria-labelledby="quickGuideModalLabel" aria-hidden="true" style="color: #1e293b;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="quickGuideModalLabel">
                    <i class="bi bi-book text-primary me-2"></i> Panduan Peran Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted small mb-4">Sistem ini membagi akses menjadi beberapa peran demi tertib administrasi armada:</p>
                
                <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                    <div class="badge bg-dark px-2.5 py-1.5 rounded-3"><i class="bi bi-person-fill-lock fs-5"></i></div>
                    <div>
                        <strong class="text-dark d-block" style="font-size:0.9rem;">Superadmin / Admin Fleet</strong>
                        <small class="text-muted">Akses penuh mengelola master data kendaraan, mengelola user/akun, dan memberikan approval anggaran perbaikan.</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                    <div class="badge bg-primary px-2.5 py-1.5 rounded-3"><i class="bi bi-wrench-adjustable fs-5"></i></div>
                    <div>
                        <strong class="text-dark d-block" style="font-size:0.9rem;">Teknisi / Mekanik</strong>
                        <small class="text-muted">Mengisi checklist harian kondisi fisik kendaraan, menindaklanjuti keluhan driver, dan mencatatkan biaya perbaikan.</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="badge bg-success px-2.5 py-1.5 rounded-3"><i class="bi bi-car-front fs-5"></i></div>
                    <div>
                        <strong class="text-dark d-block" style="font-size:0.9rem;">Driver / Pengemudi</strong>
                        <small class="text-muted">Melihat status unit kendaraan penugasan pribadi dan melaporkan keluhan kerusakan lengkap dengan bukti foto/video.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary py-2 px-4 w-100" data-bs-dismiss="modal" style="border-radius:10px; font-weight:600;">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Support Modal -->
<div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true" style="color: #1e293b;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="whatsappModalLabel">
                    <i class="bi bi-whatsapp text-success me-2"></i> Hubungi Admin via WhatsApp
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 72px; height: 72px; background-color: #d1fae5;">
                    <i class="bi bi-whatsapp" style="font-size: 2.2rem; color: #25d366;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Hubungi Administrator</h5>
                <p class="text-muted small mb-4 px-3">
                    Silakan hubungi administrator via WhatsApp di nomor <strong>0877-3856-5383</strong> untuk bantuan teknis sistem armada.
                </p>
                <!-- Issue Category Dropdown -->
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">Pilih Kendala Login:</label>
                    <select id="whatsappIssueSelect" class="form-select border shadow-none" style="border-radius: 10px; font-size: 0.88rem; padding: 10px;" onchange="toggleCustomIssueInput()">
                        <option value="Saya tidak bisa masuk ke sistem (Akun Salah/Password Salah)">Saya tidak bisa masuk ke sistem (Akun Salah/Password Salah)</option>
                        <option value="Akun saya belum terdaftar di sistem armada">Akun saya belum terdaftar di sistem armada</option>
                        <option value="Saya mengalami lupa password akun saya">Saya mengalami lupa password akun saya</option>
                        <option value="Halaman web memunculkan pesan error / lambat">Halaman web memunculkan pesan error / lambat</option>
                        <option value="custom">Masalah Lainnya (Tulis Masalah Sendiri)</option>
                    </select>
                </div>

                <!-- Custom Issue Input (Hidden by default) -->
                <div id="customIssueWrapper" class="mb-4 text-start d-none">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">Tuliskan Masalah Anda:</label>
                    <textarea id="whatsappCustomIssueText" class="form-control border shadow-none" placeholder="Jelaskan kendala Anda secara singkat..." rows="3" style="border-radius: 10px; font-size: 0.88rem; padding: 10px;"></textarea>
                </div>

                <div class="d-flex flex-column gap-2 mt-2">
                    <button onclick="showWhatsappRedirectScreen()" class="btn btn-success py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 w-100" style="border-radius: 10px;">
                        <i class="bi bi-box-arrow-up-right"></i> Lanjutkan ke WhatsApp
                    </button>
                    <button type="button" class="btn btn-outline-secondary py-2.5 fw-semibold" data-bs-dismiss="modal" style="border-radius: 10px;">
                        Kembali ke Halaman Login
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Redirecting Screen Overlay -->
<div id="whatsappRedirectScreen" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: linear-gradient(135deg, #2b1bbf 0%, #140b78 100%); z-index: 9999; color: #ffffff;">
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>
    
    <div class="text-center p-4" style="position: relative; z-index: 10000; max-width: 500px;">
        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-4 pulse-animation" style="width: 90px; height: 90px; box-shadow: 0 0 30px rgba(37, 211, 102, 0.4); background-color: #25d366 !important;">
            <i class="bi bi-whatsapp" style="font-size: 3rem;"></i>
        </div>
        
        <h2 class="fw-extrabold mb-3">Menghubungkan ke WhatsApp Admin...</h2>
        <p class="text-white-50 mb-4 fs-6">
            Kami sedang membuka chat WhatsApp dengan Administrator (0877-3856-5383) di tab baru. Silakan selesaikan chat Anda di sana.
        </p>
        
        <!-- Action Buttons -->
        <div class="d-flex flex-column gap-3">
            <button onclick="hideWhatsappRedirectScreen()" class="btn btn-light py-3 px-4 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; color: #1e293b; box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);">
                <i class="bi bi-arrow-left"></i> Batal &amp; Kembali ke Halaman Login
            </button>
            
            <a id="whatsappManualLink" href="https://wa.me/6287738565383?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20login%20FleetMaintenance" target="_blank" class="text-white text-decoration-none small opacity-75 hover-opacity-100 mt-2">
                WhatsApp tidak terbuka otomatis? <span class="text-warning fw-bold text-decoration-underline">Klik di sini untuk membuka manual</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleCustomIssueInput() {
        const selectEl = document.getElementById('whatsappIssueSelect');
        const customWrapper = document.getElementById('customIssueWrapper');
        if (selectEl && customWrapper) {
            if (selectEl.value === 'custom') {
                customWrapper.classList.remove('d-none');
            } else {
                customWrapper.classList.add('d-none');
            }
        }
    }

    function showWhatsappRedirectScreen() {
        // Get issue text
        const selectEl = document.getElementById('whatsappIssueSelect');
        let issueText = selectEl ? selectEl.value : '';
        
        if (issueText === 'custom') {
            const textareaEl = document.getElementById('whatsappCustomIssueText');
            issueText = textareaEl ? textareaEl.value.trim() : '';
            if (!issueText) {
                issueText = "Kendala Teknis Lainnya di Halaman Login";
            }
        }
        
        // Construct pre-filled message text
        const baseMessage = "Halo Admin, saya mengalami kendala pada halaman login FleetMaintenance.\n\nMasalah: " + issueText;
        const encodedMessage = encodeURIComponent(baseMessage);
        const waUrl = "https://wa.me/6287738565383?text=" + encodedMessage;
        
        // Hide WhatsApp modal first
        const waModalEl = document.getElementById('whatsappModal');
        let waModal = bootstrap.Modal.getInstance(waModalEl);
        if (!waModal) {
            waModal = new bootstrap.Modal(waModalEl);
        }
        waModal.hide();
        
        // Show redirect screen overlay
        const redirectScreen = document.getElementById('whatsappRedirectScreen');
        if (redirectScreen) {
            redirectScreen.classList.remove('d-none');
            // Force flex display
            redirectScreen.style.setProperty('display', 'flex', 'important');
        }
        
        // Open WhatsApp URL in new tab
        window.open(waUrl, "_blank");
        
        // Update the manual link in redirect screen in case popup is blocked
        const manualLink = document.getElementById('whatsappManualLink');
        if (manualLink) {
            manualLink.href = waUrl;
        }
    }
    
    function hideWhatsappRedirectScreen() {
        const redirectScreen = document.getElementById('whatsappRedirectScreen');
        if (redirectScreen) {
            redirectScreen.classList.add('d-none');
            redirectScreen.style.setProperty('display', 'none', 'important');
        }
    }
</script>
</body>
</html>