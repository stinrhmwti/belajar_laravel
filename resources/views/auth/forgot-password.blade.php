<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Lupa Password') }} - FleetMaintenance</title>

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
            background: #0b1e36 url('{{ asset('images/fleet_showroom_bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow-x: hidden;
            color: var(--text-light);
        }

        /* Subtle modern backdrop overlay */
        .bg-backdrop-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(8, 24, 48, 0.78) 0%, rgba(9, 30, 58, 0.52) 45%, rgba(12, 28, 50, 0.28) 100%);
            pointer-events: none;
            z-index: 1;
        }

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
        .brand-sub {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.55);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

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

        .login-card-wrapper {
            position: relative;
            z-index: 2;
            margin-bottom: 2.5rem;
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

        /* WhatsApp Floating Help Widget */
        .whatsapp-widget {
            position: fixed;
            bottom: 24px;
            left: 24px; /* Diletakkan di pojok kiri bawah agar tidak overlap dengan login card */
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

        @media (max-width: 991.98px) {
            .hero-section {
                text-align: center;
                padding-bottom: 1rem;
            }
            .hero-desc {
                margin-left: auto;
                margin-right: auto;
            }
            .login-card {
                padding: 2rem;
            }
            .hero-title {
                font-size: 2.2rem;
            }
            .login-card-wrapper {
                margin-bottom: 5rem;
            }
        }
        
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

<div class="bg-backdrop-overlay"></div>

<!-- Top Alert Bar -->
<div class="w-100 text-center py-2 px-3 fw-medium" style="background: rgba(7, 24, 48, 0.75); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); font-size: 0.8rem; letter-spacing: 0.3px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); position: relative; z-index: 5;">
    <i class="bi bi-shield-lock-fill me-1 text-warning"></i> {{ __('Pemulihan Akses Akun FleetMaintenance') }}
</div>

<!-- Main Wrapper Container -->
<div class="container d-flex flex-column justify-content-between flex-grow-1 py-4" style="position: relative; z-index: 5;">
    
    <!-- Top Header Navigation -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <a href="/" class="navbar-brand-custom">
            <div class="brand-logo-container">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.7 4.3a2.5 2.5 0 0 0-3.5 0l-2 2 3.5 3.5 2-2a2.5 2.5 0 0 0 0-3.5ZM12.7 7.8l-8.5 8.5a1.2 1.2 0 0 0 0 1.7l1.3 1.3a1.2 1.2 0 0 0 1.7 0l8.5-8.5-3-3Z" fill="#fbbf24" />
                    <path d="M 2.5,5.5 H 12.5 V 7.5 H 15.5 L 18.5,10.5 V 13.5 H 2.5 Z" fill="#0891b2" stroke="#1e1b4b" stroke-width="1.2" stroke-linejoin="round" />
                    <path d="M 13.5,8.5 H 15.2 L 16.8,10.5 H 13.5 Z" fill="#1e1b4b" />
                    <circle cx="6" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                    <circle cx="15" cy="13.5" r="2" fill="#1e1b4b" stroke="#ffffff" stroke-width="1.2" />
                </svg>
            </div>
            <div>
                <h1 class="brand-title">FleetMaintenance</h1>
                <div class="brand-sub">{{ __('Sistem Manajemen Armada') }}</div>
            </div>
        </a>
    </header>

    <!-- Content Hero & Form Row -->
    <div class="row align-items-center g-4 my-auto">
        
        <!-- Left Side: Hero Info -->
        <div class="col-lg-6 hero-section text-center text-lg-start">
            <span class="badge-digital">{{ __('Lupa Password Akun') }}</span>
            <h2 class="hero-title">{{ __('Kembalikan Akses') }} <br><span>{{ __('Akun Anda') }}</span></h2>
            <p class="hero-desc">
                {{ __('Masukkan alamat email terdaftar Anda di bawah ini. Kami akan mengirimkan link untuk menyetel ulang password melalui email tersebut.') }}
            </p>
        </div>

        <!-- Right Side: Request Form Box -->
        <div class="col-lg-5 offset-lg-1 login-card-wrapper">
            <div class="login-card">

                <!-- Container untuk alert respons cepat (AJAX) -->
                <div id="dynamicAlertContainer"></div>

                @if (session('status'))
                    <div id="serverStatusAlert" class="alert alert-success border-0 shadow-sm rounded-3 py-3 px-3 mb-4" style="background-color: #f0fdf4; color: #166534; font-size: 0.85rem;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div class="fw-semibold">{{ session('status') }}</div>
                        </div>
                        @if (session('resetUrl'))
                            <div class="mt-2 pt-2 border-top border-success-subtle">
                                <a href="{{ session('resetUrl') }}" class="btn btn-sm btn-success text-white w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-xs" style="border-radius: 10px; font-size: 0.84rem;">
                                    <i class="bi bi-key-fill"></i> {{ __('Klik di Sini untuk Buat Password Baru') }} &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($errors->any())
                    <div id="serverErrorAlert" class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-4" style="background-color: #fef2f2; color: #991b1b; font-size: 0.825rem;">
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

                <!-- STEP 1: Masukkan Email untuk Kirim Kode OTP -->
                <div id="step1Container">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 54px; height: 54px; background: rgba(8, 145, 178, 0.1); color: var(--brand-primary);">
                            <i class="bi bi-shield-lock-fill fs-3"></i>
                        </div>
                        <h3 class="fw-extrabold text-dark mb-1">{{ __('Kirim Kode OTP') }}</h3>
                        <p class="text-secondary small">{{ __('Kami akan mengirimkan 6 digit Kode OTP pemulihan ke email Anda.') }}</p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" id="sendOtpForm">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Alamat Email / Username') }}</label>
                            <div class="input-icon-wrapper">
                                <input type="text" name="email" id="emailStep1" class="form-control-custom w-100" placeholder="nama@email.com atau username" value="{{ old('email') }}" required autofocus autocomplete="username email">
                                <i class="bi bi-envelope input-icon"></i>
                            </div>
                            <div class="form-text small text-muted mt-1">
                                <i class="bi bi-info-circle me-1"></i> Masukkan email akun Anda atau username yang digunakan saat login.
                            </div>
                        </div>

                        <button type="submit" id="btnSendOtp" class="btn btn-submit w-100 d-flex align-items-center justify-content-center gap-2 mb-3">
                            <span>{{ __('Kirim Kode OTP ke Email') }}</span>
                            <i class="bi bi-send-fill fs-6 ms-1"></i>
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none small fw-semibold" style="color: var(--brand-primary);">
                                <i class="bi bi-arrow-left me-1"></i> {{ __('Kembali ke Halaman Login') }}
                            </a>
                        </div>
                    </form>
                </div>

                <!-- STEP 2: Verifikasi Kode OTP & Buat Password Baru (Muncul setelah OTP terkirim) -->
                <div id="step2Container" class="d-none">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 54px; height: 54px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                            <i class="bi bi-key-fill fs-3"></i>
                        </div>
                        <h3 class="fw-extrabold text-dark mb-1">{{ __('Verifikasi Kode OTP') }}</h3>
                        <p class="text-secondary small mb-1">{{ __('Masukkan 6 digit kode OTP yang dikirimkan ke:') }}</p>
                        <span id="targetEmailBadge" class="badge bg-light text-dark border px-3 py-1.5 fw-bold" style="font-size: 0.82rem;"></span>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" id="verifyOtpForm">
                        @csrf
                        <input type="hidden" name="email" id="emailStep2">

                        <!-- Input Kode OTP -->
                        <div class="mb-3 text-center">
                            <label class="form-label fw-bold text-secondary mb-1.5 d-block text-start" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Kode OTP (6 Digit)') }}</label>
                            <input type="text" name="otp" id="otpInput" class="form-control text-center fw-extrabold" placeholder="• • • • • •" maxlength="6" inputmode="numeric" pattern="[0-9]*" style="font-size: 1.5rem; letter-spacing: 10px; border-radius: 12px; border: 2px solid #cbd5e1; height: 54px; font-family: monospace;" required autocomplete="one-time-code">
                            <div class="form-text small text-muted text-start mt-1">
                                <i class="bi bi-clock-history me-1"></i> Kode OTP berlaku selama 15 menit.
                            </div>
                        </div>

                        <!-- Password Baru -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Password Baru') }}</label>
                            <div class="input-icon-wrapper position-relative">
                                <input type="password" id="newPassword" name="password" class="form-control-custom w-100" placeholder="Min. 8 karakter" style="padding-right: 44px;" required autocomplete="new-password">
                                <i class="bi bi-lock input-icon"></i>
                                <button type="button" id="toggleNewPassword" class="btn p-0 border-0 position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 5; background: transparent; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye-slash" id="toggleNewPasswordIcon" style="font-size: 1.15rem;"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Konfirmasi Password Baru') }}</label>
                            <div class="input-icon-wrapper position-relative">
                                <input type="password" id="newPasswordConfirm" name="password_confirmation" class="form-control-custom w-100" placeholder="Ulangi password baru" style="padding-right: 44px;" required autocomplete="new-password">
                                <i class="bi bi-lock-fill input-icon"></i>
                                <button type="button" id="toggleNewPasswordConfirm" class="btn p-0 border-0 position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 5; background: transparent; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye-slash" id="toggleNewPasswordConfirmIcon" style="font-size: 1.15rem;"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" id="btnVerifyOtp" class="btn btn-submit w-100 d-flex align-items-center justify-content-center gap-2 mb-3">
                            <span>{{ __('Verifikasi OTP & Reset Password') }}</span>
                            <i class="bi bi-check-circle-fill fs-6 ms-1"></i>
                        </button>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top text-muted small">
                            <button type="button" onclick="switchToStep1()" class="btn btn-link p-0 text-decoration-none small text-secondary fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> {{ __('Ganti Email') }}
                            </button>
                            <button type="button" id="btnResendOtp" onclick="resendOtpCode()" class="btn btn-link p-0 text-decoration-none small fw-bold text-primary">
                                {{ __('Kirim Ulang OTP') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <!-- Footer Copyright -->
    <footer class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-4 mt-4 border-top" style="border-color: rgba(255, 255, 255, 0.12) !important; font-size: 0.75rem; color: var(--text-muted-light);">
        <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} FleetMaintenance System &bull; {{ __('Versi 2.5 Premium Active.') }}</p>
    </footer>

</div>

<!-- WhatsApp Support Button Trigger -->
<a href="#whatsappModal" class="whatsapp-widget" data-bs-toggle="modal" data-bs-target="#whatsappModal">
    <div class="whatsapp-icon-bg"><i class="bi bi-whatsapp"></i></div>
    <span>{{ __('Butuh bantuan?') }}</span>
</a>

<!-- WhatsApp Support Modal -->
<div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true" style="color: #1e293b;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="whatsappModalLabel">
                    <i class="bi bi-whatsapp text-success me-2"></i> {{ __('Hubungi Admin via WhatsApp') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 72px; height: 72px; background-color: #d1fae5;">
                    <i class="bi bi-whatsapp" style="font-size: 2.2rem; color: #25d366;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">{{ __('Hubungi Administrator') }}</h5>
                <p class="text-muted small mb-4 px-3">
                    {{ __('Silakan hubungi administrator via WhatsApp di nomor 0877-3856-5383 untuk bantuan teknis sistem armada.') }}
                </p>
                <!-- Issue Category Dropdown -->
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Pilih Kendala:') }}</label>
                    <select id="whatsappIssueSelect" class="form-select border shadow-none" style="border-radius: 10px; font-size: 0.88rem; padding: 10px;" onchange="toggleCustomIssueInput()">
                        <option value="Saya tidak menerima kode OTP di email saya">{{ __('Saya tidak menerima kode OTP di email saya') }}</option>
                        <option value="Kode OTP saya dinyatakan salah atau kedaluwarsa">{{ __('Kode OTP saya dinyatakan salah atau kedaluwarsa') }}</option>
                        <option value="Email saya belum terdaftar di sistem armada">{{ __('Email saya belum terdaftar di sistem armada') }}</option>
                        <option value="custom">{{ __('Masalah Lainnya (Tulis Masalah Sendiri)') }}</option>
                    </select>
                </div>

                <!-- Custom Issue Input (Hidden by default) -->
                <div id="customIssueWrapper" class="mb-4 text-start d-none">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Tuliskan Masalah Anda:') }}</label>
                    <textarea id="whatsappCustomIssueText" class="form-control border shadow-none" placeholder="{{ __('Jelaskan kendala Anda secara singkat...') }}" rows="3" style="border-radius: 10px; font-size: 0.88rem; padding: 10px;"></textarea>
                </div>

                <div class="d-flex flex-column gap-2 mt-2">
                    <button onclick="showWhatsappRedirectScreen()" class="btn btn-success py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 w-100" style="border-radius: 10px;">
                        <i class="bi bi-box-arrow-up-right"></i> {{ __('Lanjutkan ke WhatsApp') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary py-2.5 fw-semibold" data-bs-dismiss="modal" style="border-radius: 10px;">
                        {{ __('Kembali') }}
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
        
        <h2 class="fw-extrabold mb-3">{{ __('Menghubungkan ke WhatsApp Admin...') }}</h2>
        <p class="text-white-50 mb-4 fs-6">
            {{ __('Kami sedang membuka chat WhatsApp dengan Administrator (0877-3856-5383) di tab baru. Silakan selesaikan chat Anda di sana.') }}
        </p>
        
        <div class="d-flex flex-column gap-3">
            <button onclick="hideWhatsappRedirectScreen()" class="btn btn-light py-3 px-4 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; color: #1e293b; box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);">
                <i class="bi bi-arrow-left"></i> Batal &amp; {{ __('Kembali') }}
            </button>
            <a id="whatsappManualLink" href="https://wa.me/6287738565383?text=Halo%20Admin" target="_blank" class="text-white text-decoration-none small opacity-75 hover-opacity-100 mt-2">
                {{ __('WhatsApp tidak terbuka otomatis?') }} <span class="text-warning fw-bold text-decoration-underline">{{ __('Klik di sini untuk membuka manual') }}</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let resendTimer = null;

    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Password Baru Step 2
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const newPassword = document.getElementById('newPassword');
        const toggleNewPasswordIcon = document.getElementById('toggleNewPasswordIcon');

        if (toggleNewPassword && newPassword && toggleNewPasswordIcon) {
            toggleNewPassword.addEventListener('click', function () {
                const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                newPassword.setAttribute('type', type);
                toggleNewPasswordIcon.classList.toggle('bi-eye');
                toggleNewPasswordIcon.classList.toggle('bi-eye-slash');
            });
        }

        // Toggle Konfirmasi Password Baru Step 2
        const toggleNewPasswordConfirm = document.getElementById('toggleNewPasswordConfirm');
        const newPasswordConfirm = document.getElementById('newPasswordConfirm');
        const toggleNewPasswordConfirmIcon = document.getElementById('toggleNewPasswordConfirmIcon');

        if (toggleNewPasswordConfirm && newPasswordConfirm && toggleNewPasswordConfirmIcon) {
            toggleNewPasswordConfirm.addEventListener('click', function () {
                const type = newPasswordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                newPasswordConfirm.setAttribute('type', type);
                toggleNewPasswordConfirmIcon.classList.toggle('bi-eye');
                toggleNewPasswordConfirmIcon.classList.toggle('bi-eye-slash');
            });
        }

        // Otomatis fokus dan filter angka saja pada input OTP
        const otpInput = document.getElementById('otpInput');
        if (otpInput) {
            otpInput.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // ==================== STEP 1: KIRIM KODE OTP ====================
        const sendOtpForm = document.getElementById('sendOtpForm');
        const btnSendOtp = document.getElementById('btnSendOtp');
        const alertContainer = document.getElementById('dynamicAlertContainer');
        const serverStatusAlert = document.getElementById('serverStatusAlert');
        const serverErrorAlert = document.getElementById('serverErrorAlert');

        if (sendOtpForm && btnSendOtp) {
            const originalSendBtnHtml = btnSendOtp.innerHTML;

            sendOtpForm.addEventListener('submit', function (event) {
                if (!sendOtpForm.checkValidity()) {
                    return;
                }
                
                event.preventDefault();

                if (serverStatusAlert) serverStatusAlert.style.display = 'none';
                if (serverErrorAlert) serverErrorAlert.style.display = 'none';
                if (alertContainer) alertContainer.innerHTML = '';

                btnSendOtp.disabled = true;
                btnSendOtp.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>{{ __('Mengirim Kode OTP...') }}</span>
                `;

                const formData = new FormData(sendOtpForm);

                fetch(sendOtpForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || !data.success) {
                        throw data;
                    }
                    
                    btnSendOtp.disabled = false;
                    btnSendOtp.innerHTML = originalSendBtnHtml;

                    const userEmail = formData.get('email');
                    
                    // Pindah ke Step 2
                    switchToStep2(userEmail, data);
                })
                .catch((error) => {
                    btnSendOtp.disabled = false;
                    btnSendOtp.innerHTML = originalSendBtnHtml;

                    let errorMsg = 'Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.';
                    if (error && error.errors && error.errors.email) {
                        errorMsg = Array.isArray(error.errors.email) ? error.errors.email.join('<br>') : error.errors.email;
                    } else if (error && error.message) {
                        errorMsg = error.message;
                    }

                    if (alertContainer) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-4" style="background-color: #fef2f2; color: #991b1b; font-size: 0.825rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                                    <div>${errorMsg}</div>
                                </div>
                            </div>
                        `;
                    }
                });
            });
        }

        // ==================== STEP 2: VERIFIKASI OTP & RESET PASSWORD ====================
        const verifyOtpForm = document.getElementById('verifyOtpForm');
        const btnVerifyOtp = document.getElementById('btnVerifyOtp');

        if (verifyOtpForm && btnVerifyOtp) {
            const originalVerifyBtnHtml = btnVerifyOtp.innerHTML;

            verifyOtpForm.addEventListener('submit', function (event) {
                if (!verifyOtpForm.checkValidity()) {
                    return;
                }
                
                event.preventDefault();

                if (alertContainer) alertContainer.innerHTML = '';

                btnVerifyOtp.disabled = true;
                btnVerifyOtp.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>{{ __('Memverifikasi & Menyimpan...') }}</span>
                `;

                const formData = new FormData(verifyOtpForm);

                fetch(verifyOtpForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || !data.success) {
                        throw data;
                    }
                    
                    btnVerifyOtp.innerHTML = `
                        <i class="bi bi-check2-circle fs-6"></i>
                        <span>{{ __('Password Berhasil Direset') }}</span>
                    `;
                    btnVerifyOtp.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';

                    if (alertContainer) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-success border-0 shadow-sm rounded-3 py-3 px-3 mb-4" style="background-color: #f0fdf4; color: #166534; font-size: 0.85rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <div class="fw-semibold">${data.message || 'Password Anda berhasil direset! Mengalihkan ke halaman login...'}</div>
                                </div>
                            </div>
                        `;
                    }

                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("login") }}';
                    }, 1200);
                })
                .catch((error) => {
                    btnVerifyOtp.disabled = false;
                    btnVerifyOtp.innerHTML = originalVerifyBtnHtml;

                    let errorMsg = 'Kode OTP salah atau terjadi kesalahan.';
                    if (error && error.errors) {
                        const msgs = [];
                        for (let k in error.errors) {
                            msgs.push(Array.isArray(error.errors[k]) ? error.errors[k].join('<br>') : error.errors[k]);
                        }
                        if (msgs.length > 0) errorMsg = msgs.join('<br>');
                    } else if (error && error.message) {
                        errorMsg = error.message;
                    }

                    if (alertContainer) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-4" style="background-color: #fef2f2; color: #991b1b; font-size: 0.825rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                                    <div>${errorMsg}</div>
                                </div>
                            </div>
                        `;
                    }
                });
            });
        }
    });

    function switchToStep2(email, data) {
        const step1Container = document.getElementById('step1Container');
        const step2Container = document.getElementById('step2Container');
        const emailStep2 = document.getElementById('emailStep2');
        const targetEmailBadge = document.getElementById('targetEmailBadge');
        const alertContainer = document.getElementById('dynamicAlertContainer');
        const otpInput = document.getElementById('otpInput');

        if (step1Container && step2Container) {
            step1Container.classList.add('d-none');
            step2Container.classList.remove('d-none');
        }

        if (emailStep2) emailStep2.value = email;
        if (targetEmailBadge) targetEmailBadge.textContent = email;

        if (alertContainer) {
            let otpBadgeHtml = '';
            if (data && data.otp) {
                otpBadgeHtml = `
                    <div class="mt-2 pt-2 border-top border-success-subtle d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="small fw-semibold text-dark">
                            <i class="bi bi-shield-lock text-success me-1"></i> Kode OTP: 
                            <span class="badge bg-success text-white px-2.5 py-1 fs-6 font-monospace" style="letter-spacing: 2px;">${data.otp}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success py-1 px-2.5 fw-bold" style="font-size: 0.75rem; border-radius: 6px;" onclick="document.getElementById('otpInput').value='${data.otp}';">
                            <i class="bi bi-clipboard-check me-1"></i> {{ __('Isi Otomatis') }}
                        </button>
                    </div>
                `;
            }

            alertContainer.innerHTML = `
                <div class="alert alert-success border-0 shadow-sm rounded-3 py-3 px-3 mb-4" style="background-color: #f0fdf4; color: #166534; font-size: 0.85rem;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <div class="fw-semibold">{{ __('Kode OTP Berhasil Dibuat & Dikirim!') }}</div>
                    </div>
                    <div class="small">${data && data.message ? data.message : 'Silakan periksa kotak masuk atau folder spam email Anda.'}</div>
                    ${otpBadgeHtml}
                </div>
            `;
        }

        if (otpInput) {
            if (data && data.otp) {
                otpInput.value = data.otp;
            }
            setTimeout(() => {
                const newPasswordInput = document.getElementById('newPassword');
                if (newPasswordInput) newPasswordInput.focus();
                else otpInput.focus();
            }, 300);
        }

        startResendTimer();
    }

    function switchToStep1() {
        const step1Container = document.getElementById('step1Container');
        const step2Container = document.getElementById('step2Container');
        const alertContainer = document.getElementById('dynamicAlertContainer');

        if (step1Container && step2Container) {
            step2Container.classList.add('d-none');
            step1Container.classList.remove('d-none');
        }
        if (alertContainer) alertContainer.innerHTML = '';
        if (resendTimer) clearInterval(resendTimer);
    }

    function startResendTimer() {
        const btnResend = document.getElementById('btnResendOtp');
        if (!btnResend) return;

        let seconds = 60;
        btnResend.disabled = true;
        btnResend.classList.add('text-muted');
        btnResend.classList.remove('text-primary');

        if (resendTimer) clearInterval(resendTimer);

        resendTimer = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(resendTimer);
                btnResend.disabled = false;
                btnResend.classList.remove('text-muted');
                btnResend.classList.add('text-primary');
                btnResend.textContent = '{{ __("Kirim Ulang OTP") }}';
            } else {
                btnResend.textContent = `Kirim Ulang OTP (${seconds}s)`;
            }
        }, 1000);
    }

    function resendOtpCode() {
        const email = document.getElementById('emailStep2').value;
        if (!email) return;

        const btnResend = document.getElementById('btnResendOtp');
        btnResend.disabled = true;
        btnResend.textContent = 'Mengirim ulang...';

        const formData = new FormData();
        formData.append('email', email);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("password.email") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(async (response) => {
            const data = await response.json();
            const alertContainer = document.getElementById('dynamicAlertContainer');
            if (alertContainer) {
                alertContainer.innerHTML = `
                    <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-4" style="background-color: #f0fdf4; color: #166534; font-size: 0.85rem;">
                        <i class="bi bi-check-circle-fill text-success me-1"></i> Kode OTP baru telah berhasil dikirim ulang ke email Anda!
                    </div>
                `;
            }
            startResendTimer();
        })
        .catch(() => {
            btnResend.disabled = false;
            btnResend.textContent = 'Kirim Ulang OTP';
        });
    }

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
        const selectEl = document.getElementById('whatsappIssueSelect');
        let issueText = selectEl ? selectEl.value : '';
        
        if (issueText === 'custom') {
            const textareaEl = document.getElementById('whatsappCustomIssueText');
            issueText = textareaEl ? textareaEl.value.trim() : '';
            if (!issueText) {
                issueText = "Kendala Lupa Password & Kode OTP";
            }
        }
        
        const baseMessage = "Halo Admin, saya mengalami kendala lupa password / kode OTP pada FleetMaintenance.\n\nMasalah: " + issueText;
        const waUrl = "https://wa.me/6287738565383?text=" + encodeURIComponent(baseMessage);
        
        const waModalEl = document.getElementById('whatsappModal');
        let waModal = bootstrap.Modal.getInstance(waModalEl);
        if (waModal) waModal.hide();
        
        const redirectScreen = document.getElementById('whatsappRedirectScreen');
        if (redirectScreen) {
            redirectScreen.classList.remove('d-none');
            redirectScreen.style.setProperty('display', 'flex', 'important');
        }
        
        window.open(waUrl, "_blank");
        
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
