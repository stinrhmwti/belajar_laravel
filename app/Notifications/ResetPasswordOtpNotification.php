<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordOtpNotification extends Notification
{
    use Queueable;

    public string $otp;
    public ?string $resetUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp, ?string $resetUrl = null)
    {
        $this->otp = $otp;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Kode OTP Reset Password: ' . $this->otp . ' - FleetMaintenance')
            ->greeting('Halo, ' . ($notifiable->name ?? 'Pengguna') . '!')
            ->line('Kami menerima permintaan untuk menyetel ulang kata sandi (password) akun FleetMaintenance Anda.')
            ->line('Gunakan Kode OTP 6 digit berikut untuk memverifikasi dan mengubah password Anda:')
            ->line('# **' . $this->otp . '**')
            ->line('Kode OTP ini bersifat rahasia dan hanya berlaku selama **15 menit**.')
            ->line('Jika Anda tidak pernah meminta kode pemulihan ini, abaikan email ini. Akun Anda tetap aman.');

        if ($this->resetUrl) {
            $mail->action('Atau Klik Di Sini untuk Buka Halaman Reset', $this->resetUrl);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'reset_url' => $this->resetUrl,
        ];
    }
}
