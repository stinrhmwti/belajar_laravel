<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Ujian Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .register-wrapper { width: 100%; max-width: 460px; }
        .register-header { text-align: center; color: white; padding: 30px 20px 20px; }
        .register-header i { font-size: 50px; margin-bottom: 10px; }
        .register-header h2 { font-weight: 700; font-size: 26px; }
        .register-header p { opacity: 0.85; font-size: 14px; }
        .register-card { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1.5px solid #e0e0e0;
            font-size: 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        }
        .input-group-text {
            background: transparent;
            border: 1.5px solid #e0e0e0;
            border-right: none;
            color: #667eea;
        }
        .input-group .form-control,
        .input-group .form-select { border-left: none; }
        .btn-daftar {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 5px;
            cursor: pointer;
        }
        .btn-daftar:hover { opacity: 0.9; }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .link-bawah { text-align: center; font-size: 13px; margin-top: 15px; }
        .link-bawah a { color: #667eea; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
<div class="register-wrapper">
    <div class="register-header">
        <i class="bi bi-mortarboard-fill"></i>
        <h2>Sistem Ujian Online</h2>
        <p>Buat akun baru untuk mulai ujian</p>
    </div>
    <div class="register-card">

        @if($errors->any())
        <div class="alert alert-danger py-2">
            @foreach($errors->all() as $error)
            <p class="mb-0"><i class="bi bi-exclamation-circle"></i> {{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                    <input type="text" 
                           name="nama" 
                           class="form-control" 
                           placeholder="Masukkan nama lengkap"
                           value="{{ old('nama') }}" 
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" 
                           name="username" 
                           class="form-control" 
                           placeholder="Masukkan username"
                           value="{{ old('username') }}" 
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Password (min. 5 karakter)" 
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Daftar Sebagai</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="murid" {{ old('role') == 'murid' ? 'selected' : '' }}>👨‍🎓 Murid</option>
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>👨‍🏫 Guru</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-daftar">
                <i class="bi bi-person-check"></i> Daftar Sekarang
            </button>
        </form>

        <hr class="divider">
        <div class="link-bawah">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a><br>
            <a href="/" style="color:#999; font-weight:400">← Kembali ke Beranda</a>
        </div>
    </div>
</div>
</body>
</html>