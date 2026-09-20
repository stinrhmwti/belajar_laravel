<?php

namespace App\Console\Commands;

use App\Jobs\KirimPesanWhatsApp;
use App\Models\WhatsappLog;
use Illuminate\Console\Command;

class RetryStuckWhatsappLogs extends Command
{
    /**
     * Nama dan signature perintah Artisan.
     *
     * @var string
     */
    protected $signature = 'whatsapp:retry-stuck {--minutes=15 : Batas menit log berstatus pending yang dianggap tertahan}';

    /**
     * Deskripsi perintah Artisan.
     *
     * @var string
     */
    protected $description = 'Mencari dan mengirim ulang pesan WhatsApp berstatus pending yang tertahan lebih dari 15 menit';

    /**
     * Eksekusi perintah console.
     */
    public function handle(): int
    {
        $minutes = (int) $this->option('minutes');
        $threshold = now()->subMinutes($minutes);

        // Ambil semua log yang berstatus pending dan dibuat lebih dari $minutes menit yang lalu
        $stuckLogs = WhatsappLog::where('status', WhatsappLog::STATUS_PENDING)
            ->where('created_at', '<=', $threshold)
            ->where('attempts', '<', 5)
            ->get();

        if ($stuckLogs->isEmpty()) {
            $this->info("Tidak ada pesan WhatsApp tertahan (pending > {$minutes} menit).");
            return Command::SUCCESS;
        }

        $this->warn("Ditemukan {$stuckLogs->count()} pesan tertahan. Memasukkan kembali ke antrean...");

        foreach ($stuckLogs as $log) {
            KirimPesanWhatsApp::dispatch($log->id);
            $this->line("• Log #{$log->id} (Tujuan: {$log->phone}) -> Job didispatch ulang ke antrean.");
        }

        $this->info("Selesai memasukkan {$stuckLogs->count()} pesan tertahan ke antrean.");

        return Command::SUCCESS;
    }
}
