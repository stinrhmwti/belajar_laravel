<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Ujian Online</title>
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
        .hero-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        .hero-icon {
            font-size: 80px;
            color: #667eea;
            margin-bottom: 20px;
        }
        .hero-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        .hero-sub {
            color: #888;
            margin-bottom: 30px;
            font-size: 15px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: opacity 0.2s;
        }
        .btn-login:hover { opacity: 0.9; color: white; }
        .btn-register {
            background: transparent;
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 11px 40px;
            font-size: 16px;
            font-weight: 600;
            color: #667eea;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: all 0.2s;
        }
        .btn-register:hover {
            background: #667eea;
            color: white;
        }
    </style>
</head>
<body>
<div class="hero-card">
    <div class="hero-icon">
        <i class="bi bi-mortarboard-fill"></i>
    </div>
    <div class="hero-title">Sistem Ujian Online</div>
    <div class="hero-sub">Platform ujian digital yang mudah dan efisien untuk guru dan murid</div>
    <div>
        <a href="/login" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
        <a href="/register" class="btn-register">
            <i class="bi bi-person-plus"></i> Daftar
        </a>
    </div>
</div>
</body>
</html>