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
            --brand-primary: #4f46e5;
            --brand-secondary: #7c3aed;
            --dark-bg: #0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Background Spheres */
        .bg-glow-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.28) 0%, rgba(15, 23, 42, 0) 70%);
            pointer-events: none;
        }
        .bg-glow-2 {
            position: absolute;
            bottom: -15%;
            right: -10%;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.22) 0%, rgba(15, 23, 42, 0) 70%);
            pointer-events: none;
        }

        /* Split Screen / Modern Glass Container */
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 960px;
            z-index: 10;
        }

        .brand-side {
            background: linear-gradient(145deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            padding: 3.5rem 3rem;
            color: #ffffff;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-side::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.6;
        }

        .brand-logo-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
            margin-bottom: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 1.25rem;
        }
        .feature-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #a5b4fc;
            flex-shrink: 0;
        }

        /* Form Side */
        .form-side {
            padding: 3.5rem 3rem;
            background: #ffffff;
        }

        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px 12px 42px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .input-icon-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            transition: color 0.2s ease;
            z-index: 5;
        }
        .form-control:focus + .input-icon,
        .input-icon-wrapper:focus-within .input-icon {
            color: #6366f1;
        }

        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            color: #ffffff;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 25px rgba(79, 70, 229, 0.45);
            color: #ffffff;
        }

        /* Quick Account Pills */
        .quick-hint {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.78rem;
        }
    </style>
</head>
<body>

<div class="bg-glow-1"></div>
<div class="bg-glow-2"></div>

<div class="container p-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="login-card mx-auto">
                <div class="row g-0">
                    <!-- Left Brand Info Column -->
                    <div class="col-lg-5 brand-side">
                        <div style="position: relative; z-index: 2;">
                            <div class="brand-logo-wrapper">
                                <svg viewBox="0 0 24 24" width="30" height="30" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 30px; height: 30px;">
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
                            <h2 class="fw-extrabold mb-2" style="letter-spacing: -0.5px;">FleetMaintenance</h2>
                            <p class="text-white-50 fs-6 mb-4">Sistem Manajemen &amp; Perawatan Armada Terpadu</p>

                            <div class="mt-4 pt-2">
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-white" style="font-size: 0.88rem;">Monitoring Jadwal Servis</h6>
                                        <small class="text-white-50" style="font-size: 0.78rem;">Notifikasi otomatis H-7 servis berkala &amp; pajak KIR.</small>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="bi bi-clipboard-data"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-white" style="font-size: 0.88rem;">Checklist Harian &amp; Biaya</h6>
                                        <small class="text-white-50" style="font-size: 0.78rem;">Pencatatan rekap pengeluaran operasional &amp; pemeriksaan.</small>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon"><i class="bi bi-tools"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-white" style="font-size: 0.88rem;">Penanganan Keluhan Cepat</h6>
                                        <small class="text-white-50" style="font-size: 0.78rem;">Laporan masalah teknis langsung ditindaklanjuti teknisi.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-top border-white-10 text-white-50" style="font-size: 0.75rem; position: relative; z-index: 2;">
                            &copy; {{ date('Y') }} FleetMaintenance System &bull; Versi 2.4 Active
                        </div>
                    </div>

                    <!-- Right Form Column -->
                    <div class="col-lg-7 form-side d-flex flex-column justify-content-center">
                        <div class="mb-4">
                            <h4 class="fw-bold text-dark mb-1">Selamat Datang Kembali! 👋</h4>
                            <p class="text-muted" style="font-size: 0.9rem;">Masukkan kredensial akun Anda untuk mengakses sistem armada.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-4" style="background-color: #fef2f2; color: #991b1b; font-size: 0.875rem;">
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
                                <label class="form-label fw-semibold text-dark" style="font-size: 0.825rem;">Email atau Username</label>
                                <div class="input-icon-wrapper">
                                    <input type="text" name="login" class="form-control" placeholder="Contoh: admin@fleet.com atau admin_fleet" value="{{ old('login') }}" required autofocus>
                                    <i class="bi bi-person input-icon"></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark" style="font-size: 0.825rem;">Password</label>
                                <div class="input-icon-wrapper">
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                    <i class="bi bi-lock input-icon"></i>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember" style="border-radius: 4px;">
                                    <label class="form-check-label text-muted" for="remember" style="font-size: 0.85rem;">Ingat Sesi Saya</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-submit w-100 d-flex align-items-center justify-content-center gap-2 mb-4">
                                <span>Masuk ke Sistem Armada</span>
                                <i class="bi bi-arrow-right-short fs-4"></i>
                            </button>

                            <div class="quick-hint text-muted d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle-fill text-primary fs-6"></i>
                                <span>Gunakan akun <strong>Admin</strong>, <strong>Teknisi</strong>, atau <strong>User/Pengemudi</strong> yang telah terdaftar oleh administrator.</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>