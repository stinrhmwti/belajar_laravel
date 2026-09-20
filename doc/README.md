# 📚 Dokumentasi Proyek - Fleet Management System

Selamat datang di direktori dokumentasi resmi **Fleet Management System (Sistem Manajemen Armada)** berbasis Laravel 12.

---

## 📑 Daftar Berkas Dokumentasi

1. **[belajar_sistem_armada.md](file:///c:/xampppp/htdocs/belajar-laravel/doc/belajar_sistem_armada.md)**  
   *Dokumentasi Utama & Analisis Lengkap Sistem* yang memuat:
   * **Ikhtisar Sistem & 9 Fitur Unggulan**: Live GPS Fleet Tracking, Trip Dispatcher & ETA Telemetry, Smart Maintenance Alert, Rekap Biaya & Export CSV, Quick BBM Odometer Sync, Otorisasi Anggaran Berjenjang, Pemulihan Sandi OTP, Multi-role RBAC, dan **Integrasi Notifikasi Otomatis WhatsApp Gateway API (Ervelia Gateway)**.
   * **11 Modul Fungsional Lengkap**: Alur bisnis, validasi, controller, dan integrasi WhatsApp event-driven.
   * **Matriks Hak Akses Peran (RBAC)** untuk 3 peran utama (`admin`, `teknisi`, `user`/driver).
   * **Kamus Data & Skema Database**: Detail 9 tabel utama (`users`, `vehicles`, `daily_checklists`, `expenses`, `complaints`, `vehicle_histories`, `password_reset_tokens`, `whatsapp_templates`, `whatsapp_logs`).
   * **Diagram Arsitektur Mermaid**: Entity Relationship Diagram (ERD), Sequence Diagram Alur Servis & Keuangan, Sequence Diagram Reset Password OTP, Sequence Diagram WhatsApp Gateway API, dan Component Architecture.
   * **Tabel Lengkap Seluruh Endpoint Rute HTTP (Routing Table)**.
   * **Panduan Menjalankan Sistem, Konfigurasi WhatsApp Gateway API (`.env`), Akses HP/Wi-Fi Lokal, & Akun Demo Default**.

---

## 🚀 Panduan Ringkas Menjalankan Sistem

### 1. Menjalankan di Localhost PC
```bash
# Pindah ke root project
cd C:\xampppp\htdocs\belajar-laravel

# Jalankan server lokal
php artisan serve
```
Akses di browser: `http://127.0.0.1:8000`

### 2. Menjalankan untuk Akses Handphone / Jaringan Wi-Fi Lokal
Klik ganda file batch di root direktori:
```bash
jalankan_di_hp.bat
```
Atau jalankan perintah:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
Buka browser di HP yang terhubung ke Wi-Fi yang sama: `http://<IP_PC_ANDA>:8000`

---

## 👥 Akun Demo Pengujian (Password: `password`)

| Peran | Username | Email |
| :--- | :--- | :--- |
| **Admin** | `admin_fleet` | `admin@fleet.com` |
| **Teknisi** | `teknisi_utama` | `teknisi@fleet.com` |
| **User (Driver)** | `driver_utama` | `user@fleet.com` |
