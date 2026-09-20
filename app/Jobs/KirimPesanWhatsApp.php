<?php

namespace App\Jobs;

use App\Exceptions\PermanentWhatsappException;
use App\Models\WhatsappLog;
use App\Services\WhatsappService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class KirimPesanWhatsApp implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Jumlah maksimum percobaan pengiriman tugas antrean.
     */
    public int $tries = 5;

    /**
     * Jeda antar percobaan ulang dalam satuan detik:
     * Percobaan 1 -> gagal -> jeda 60 detik (1 menit)
     * Percobaan 2 -> gagal -> jeda 300 detik (5 menit)
     * Percobaan 3 -> gagal -> jeda 900 detik (15 menit)
     * Percobaan 4 -> gagal -> jeda 1800 detik (30 menit)
     */
    public array $backoff = [60, 300, 900, 1800];

    /**
     * ID record riwayat pesan (whatsapp_logs).
     */
    public int $whatsappLogId;

    /**
     * Buat instance Job baru.
     * Hanya menerima ID log pesan agar data selalu diambil segar dari database saat dieksekusi.
     */
    public function __construct(int $whatsappLogId)
    {
        $this->whatsappLogId = $whatsappLogId;
    }

    /**
     * Eksekusi tugas pengiriman pesan WhatsApp di antrean latar belakang.
     */
    public function handle(WhatsappService $whatsapp): void
    {
        // Ambil data log pesan terbaru dari database
        $log = WhatsappLog::find($this->whatsappLogId);

        // Jika data log tidak ditemukan, hentikan eksekusi
        if (! $log) {
            return;
        }

        // Cek apakah pesan sudah berstatus 'sent'/'success' untuk mencegah pesan terkirim ganda
        if ($log->isSuccess()) {
            return;
        }

        // Tambah jumlah percobaan (attempts)
        $log->increment('attempts');

        try {
            // Panggil service pengirim untuk mengeksekusi request HTTP ke API gateway
            $whatsapp->processLog($log);
        } catch (PermanentWhatsappException $e) {
            // Kasus error permanen (nomor tidak valid, token 401, error 4xx selain 429):
            // Tandai status 'failed' langsung dan hentikan Job tanpa percobaan ulang
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
            $this->fail($e);
        } catch (Throwable $e) {
            // Kasus error sementara (koneksi mati, timeout, status 5xx, rate limit 429):
            // Catat pesan error sementara dan lempar kembali exception agar Laravel Queue melakukan retry sesuai jadwal backoff
            $log->update([
                'error_message' => 'Percobaan ke-' . $log->attempts . ' gagal: ' . $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Tangani kegagalan ketika seluruh 5 kali percobaan telah habis atau terjadi error fatal.
     */
    public function failed(?Throwable $exception): void
    {
        $log = WhatsappLog::find($this->whatsappLogId);

        if ($log && ! $log->isSuccess()) {
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => $exception ? $exception->getMessage() : 'Gagal terkirim setelah ' . $this->tries . ' kali percobaan antrean.',
            ]);
        }
    }
}
