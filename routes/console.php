<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {email=sitirahmawat083@gmail.com}', function ($email) {
    $this->info("Menguji pengiriman email ke: {$email}");
    $this->line("Driver: " . config('mail.default'));
    $this->line("Host: " . config('mail.mailers.smtp.host') . ":" . config('mail.mailers.smtp.port'));
    $this->line("Username: " . (config('mail.mailers.smtp.username') ?? '(belum diisi)'));

    try {
        \Illuminate\Support\Facades\Mail::raw("Halo!\n\nIni adalah email uji coba dari sistem FleetMaintenance untuk memastikan pengiriman kode OTP ke email Anda ({$email}) berhasil masuk ke inbox.", function ($message) use ($email) {
            $message->to($email)->subject("Uji Coba Pengiriman Email FleetMaintenance - " . date('d/m/Y H:i'));
        });
        $this->info("SUKSES: Email berhasil dikirim ke {$email}!");
    } catch (\Throwable $e) {
        $this->error("GAGAL: " . $e->getMessage());
        $this->warn("Pastikan MAIL_USERNAME dan MAIL_PASSWORD di file .env sudah diisi dengan benar.");
    }
})->purpose('Menguji pengiriman email SMTP secara langsung');
