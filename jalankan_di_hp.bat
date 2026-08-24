@echo off
title Laravel Mobile Server Launcher
echo ===================================================
echo   LAUNCHING LARAVEL DEV SERVER FOR MOBILE ACCESS
echo ===================================================
echo.

:: Mengambil IP Lokal PC/Laptop
setlocal enabledelayedexpansion
set "LOCAL_IP="
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /i "IPv4 Address"') do (
    set "temp_ip=%%i"
    :: Menghapus spasi di depan IP
    set "temp_ip=!temp_ip:~1!"
    set "LOCAL_IP=!temp_ip!"
    goto :found
)

:found
if "%LOCAL_IP%"=="" (
    echo [ERROR] Tidak dapat menemukan IP Address lokal PC Anda.
    echo Pastikan PC Anda terhubung ke jaringan Wi-Fi/Ethernet.
    set LOCAL_IP=127.0.0.1
)

echo IP Address PC Anda: %LOCAL_IP%
echo.
echo ---------------------------------------------------
echo LANGKAH AKSES DARI HANDPHONE:
echo 1. Pastikan HP dan PC terhubung ke WI-FI YANG SAMA.
echo 2. Buka browser di HP Anda (Chrome, Safari, dll).
echo 3. Ketik alamat URL di bawah ini di browser HP Anda:
echo.
echo    http://%LOCAL_IP%:8000
echo.
echo ---------------------------------------------------
echo.
echo Menjalankan Laravel Server... (Tekan Ctrl + C untuk berhenti)
echo.
php artisan serve --host=0.0.0.0 --port=8000
pause
