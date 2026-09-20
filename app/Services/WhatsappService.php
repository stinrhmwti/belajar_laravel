<?php

namespace App\Services;

use App\Exceptions\PermanentWhatsappException;
use App\Jobs\KirimPesanWhatsApp;
use App\Models\WhatsappLog;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class WhatsappService
{
    /**
     * Memformat nomor telepon ke standar internasional (contoh: 628123456789).
     *
     * - Menghapus seluruh karakter non-angka
     * - Mengganti awalan '0' dengan country code (misal: 62)
     * - Menambahkan country code jika diawali angka '8'
     * - Mengembalikan null jika hasil akhir kurang dari 10 digit
     */
    public function formatPhone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        // Hapus semua karakter non-angka
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        if (empty($cleaned)) {
            return null;
        }

        $countryCode = (string) config('services.whatsapp.country_code', '62');

        // Awalan '0' diganti country code (08123... jadi 628123...)
        if (str_starts_with($cleaned, '0')) {
            $cleaned = $countryCode . substr($cleaned, 1);
        }
        // Awalan '8' ditambah country code di depan (8123... jadi 628123...)
        elseif (str_starts_with($cleaned, '8')) {
            $cleaned = $countryCode . $cleaned;
        }

        // Return null jika hasil akhir kurang dari 10 digit
        if (strlen($cleaned) < 10) {
            return null;
        }

        return $cleaned;
    }

    /**
     * Membuat tautan langsung ke WhatsApp Web / WhatsApp App (wa.me).
     */
    public function getDirectLink(string $phone, string $message): string
    {
        $formatted = $this->formatPhone($phone) ?: preg_replace('/[^0-9]/', '', $phone);
        $encodedText = rawurlencode($message);
        return "https://api.whatsapp.com/send?phone={$formatted}&text={$encodedText}";
    }

    /**
     * Mengirim payload HTTP request ke penyedia WhatsApp Gateway API dengan batas timeout aman.
     *
     * @param string $target Nomor telepon tujuan format internasional (contoh: 628xxx)
     * @param string $message Isi pesan yang akan dikirim
     * @param string $driver Jenis provider driver
     * @return Response
     */
    public function dispatchToProvider(string $target, string $message, string $driver = 'ervelia'): Response
    {
        $token = (string) config('services.whatsapp.token');
        $baseUrl = rtrim(config('services.whatsapp.base_url', ''), '/');

        // 1. Driver FONNTE (https://api.fonnte.com/send)
        if ($driver === 'fonnte') {
            $url = !empty($baseUrl) ? $baseUrl . '/send' : 'https://api.fonnte.com/send';
            return Http::connectTimeout(5)
                ->timeout(10)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->asForm()
                ->post($url, [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => config('services.whatsapp.country_code', '62'),
                ]);
        }

        // 2. Driver WABLAS (https://pati.wablas.com/api/send-message)
        if ($driver === 'wablas') {
            $url = !empty($baseUrl) ? $baseUrl . '/api/send-message' : 'https://pati.wablas.com/api/send-message';
            return Http::connectTimeout(5)
                ->timeout(10)
                ->withHeaders([
                    'Authorization' => $token,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'phone' => $target,
                    'message' => $message,
                ]);
        }

        // 3. Driver ERVELIA / CUSTOM REST API (Default)
        $endpoint = str_ends_with($baseUrl, '/api/v1/messages/send') ? $baseUrl : $baseUrl . '/api/v1/messages/send';
        return Http::connectTimeout(5)
            ->timeout(10)
            ->withHeaders([
                'X-API-KEY' => $token,
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])
            ->post($endpoint, [
                'to' => $target,
                'phone' => $target,
                'message' => $message,
            ]);
    }

    /**
     * Memeriksa apakah response dari provider mengindikasikan status sukses.
     */
    public function isProviderSuccess(array $body, string $driver = 'ervelia'): bool
    {
        if (isset($body['status']) && ($body['status'] === true || strtolower((string)$body['status']) === 'success' || (int)$body['status'] === 200)) {
            return true;
        }
        if (isset($body['success']) && ($body['success'] === true || $body['success'] === 'true')) {
            return true;
        }
        if (isset($body['status']) && $body['status'] === false) {
            return false;
        }
        return !isset($body['error']) && !isset($body['errors']);
    }

    /**
     * Mendaftarkan pengiriman pesan ke database berstatus 'pending', lalu memasukkannya ke antrean (Queue Job).
     *
     * @param string $phone Nomor tujuan
     * @param string $message Isi pesan
     * @param array $meta Metadata tambahan (template_id, user_id)
     * @return WhatsappLog
     */
    public function send(string $phone, string $message, array $meta = []): WhatsappLog
    {
        $driver = config('services.whatsapp.driver', 'ervelia');
        $formattedPhone = $this->formatPhone($phone);

        // 1. Simpan pesan ke tabel whatsapp_logs dengan status awal 'pending' dan attempts = 0
        $log = WhatsappLog::create([
            'whatsapp_template_id' => $meta['template_id'] ?? null,
            'user_id' => $meta['user_id'] ?? null,
            'phone' => $formattedPhone ?? $phone,
            'message' => $message,
            'status' => WhatsappLog::STATUS_PENDING,
            'attempts' => 0,
            'provider' => $driver,
        ]);

        // 2. Validasi format nomor telepon (Error Permanen)
        if (! $formattedPhone) {
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => 'Nomor WhatsApp tidak valid (kurang dari 10 digit atau format salah).',
            ]);
            return $log;
        }

        // 3. Masukkan tugas ke Antrean (Queue Job) dengan afterCommit agar transaksi database tuntas lebih dulu
        KirimPesanWhatsApp::dispatch($log->id)->afterCommit();

        return $log;
    }

    /**
     * Mengirim pesan WhatsApp berbasis template yang tersimpan di database.
     */
    public function sendTemplate(string $code, string $phone, array $data = [], array $meta = []): WhatsappLog
    {
        $template = WhatsappTemplate::active()->where('code', $code)->first();

        // Jika template tidak ditemukan atau tidak aktif
        if (! $template) {
            $driver = config('services.whatsapp.driver', 'ervelia');
            $formattedPhone = $this->formatPhone($phone);

            return WhatsappLog::create([
                'user_id' => $meta['user_id'] ?? null,
                'phone' => $formattedPhone ?? $phone,
                'message' => "Template [{$code}]",
                'status' => WhatsappLog::STATUS_FAILED,
                'attempts' => 0,
                'provider' => $driver,
                'error_message' => "Template WhatsApp dengan kode '{$code}' tidak ditemukan atau sedang tidak aktif.",
            ]);
        }

        // Render isi pesan dengan mengganti placeholder variabel
        $message = $this->renderTemplate($template->content, $data);

        // Panggil method send() yang akan memasukkan pesan ke Queue
        return $this->send($phone, $message, array_merge($meta, ['template_id' => $template->id]));
    }

    /**
     * Memproses pengiriman pesan aktual (dieksekusi oleh Queue Worker di latar belakang).
     *
     * @param WhatsappLog $log
     * @return void
     * @throws PermanentWhatsappException Jika terjadi error permanen (tidak perlu retry)
     * @throws RuntimeException Jika terjadi error sementara (harus di-retry oleh Queue)
     */
    public function processLog(WhatsappLog $log): void
    {
        $driver = config('services.whatsapp.driver', 'ervelia');

        // 1. Cek apakah fitur WhatsApp diaktifkan
        if (! config('services.whatsapp.enabled', true)) {
            throw new PermanentWhatsappException('Fitur pengiriman WhatsApp dinonaktifkan di pengaturan sistem (WHATSAPP_ENABLED=false).');
        }

        // 2. Mode Sandbox / Simulasi Lokal (Selalu berhasil tanpa koneksi luar)
        if (in_array($driver, ['sandbox', 'mock', 'log', 'local'])) {
            $mockMessageId = 'SANDBOX_WA_' . strtoupper(bin2hex(random_bytes(6)));
            $log->update([
                'status' => WhatsappLog::STATUS_SENT,
                'message_id' => $mockMessageId,
                'response' => [
                    'status' => 'success',
                    'driver' => $driver,
                    'mode' => 'sandbox_simulation',
                    'message_id' => $mockMessageId,
                    'target' => $log->phone,
                    'message' => $log->message,
                    'attempts' => $log->attempts,
                    'timestamp' => now()->toIso8601String(),
                ],
                'sent_at' => now(),
                'error_message' => null,
            ]);
            Log::info("WhatsApp [Sandbox Queue] terkirim ke {$log->phone}: {$log->message}");
            return;
        }

        // 3. Cek Ketersediaan Token API
        $token = config('services.whatsapp.token');
        if (empty($token)) {
            throw new PermanentWhatsappException('Token API WhatsApp belum dikonfigurasi (WHATSAPP_TOKEN kosong di file .env).');
        }

        // 4. Eksekusi HTTP Request ke API Gateway
        try {
            $response = $this->dispatchToProvider($log->phone, $log->message, $driver);
        } catch (ConnectionException $e) {
            // Error koneksi / timeout / server mati -> Error Sementara (Temporary) -> Lempar RuntimeException agar di-retry
            throw new RuntimeException("Gagal koneksi ke server gateway ({$e->getMessage()}). Server gateway mungkin sedang offline.", 0, $e);
        } catch (Throwable $e) {
            throw new RuntimeException("Kesalahan jaringan: " . $e->getMessage(), 0, $e);
        }

        $statusCode = $response->status();
        $responseData = $response->json();
        $bodyArray = is_array($responseData) ? $responseData : [];

        // 5. Analisis Kode Respon HTTP
        // a. Status 429 (Rate Limit) atau 5xx (Server Error) -> Error Sementara -> Retry
        if ($statusCode === 429 || $statusCode >= 500) {
            $errDetail = $bodyArray['message'] ?? $bodyArray['error'] ?? $response->body();
            throw new RuntimeException("Server gateway mengalami gangguan (HTTP {$statusCode}): {$errDetail}");
        }

        // b. Status 4xx selain 429 (400, 401, 403, 404, 422) -> Error Permanen -> Jangan Retry
        if ($statusCode >= 400 && $statusCode < 500) {
            $errDetail = $bodyArray['message'] ?? $bodyArray['error'] ?? $response->body();
            $log->update([
                'response' => $bodyArray ?: ['raw_body' => $response->body()],
            ]);
            throw new PermanentWhatsappException("Gateway menolak permintaan (HTTP {$statusCode}): {$errDetail}");
        }

        // c. Status 2xx (Sukses)
        if ($response->successful() && $this->isProviderSuccess($bodyArray, $driver)) {
            $messageId = $bodyArray['id'] ?? $bodyArray['message_id'] ?? $bodyArray['data']['message_id'] ?? $bodyArray['data']['id'] ?? null;
            if (is_array($messageId)) {
                $messageId = implode(',', $messageId);
            }

            $log->update([
                'status' => WhatsappLog::STATUS_SENT,
                'message_id' => $messageId ? (string) $messageId : null,
                'response' => $bodyArray ?: ['raw_body' => $response->body()],
                'sent_at' => now(),
                'error_message' => null,
            ]);
        } else {
            // Jika status HTTP 200 namun body menyatakan kegagalan
            $errorMsg = $bodyArray['message'] ?? $bodyArray['error'] ?? $bodyArray['reason'] ?? ('Gagal mengirim pesan via WhatsApp Gateway (HTTP ' . $statusCode . ').');
            
            // Cek apakah error body mengindikasikan nomor tidak valid atau token salah (permanen)
            $errorStr = is_string($errorMsg) ? $errorMsg : json_encode($errorMsg);
            if (str_contains(strtolower($errorStr), 'invalid') || str_contains(strtolower($errorStr), 'not registered') || str_contains(strtolower($errorStr), 'unauthorized')) {
                $log->update([
                    'response' => $bodyArray ?: ['raw_body' => $response->body()],
                ]);
                throw new PermanentWhatsappException($errorStr);
            }

            throw new RuntimeException($errorStr);
        }
    }

    /**
     * Merender konten template dengan menggantikan {{variabel}} dan {{ variabel }} sesuai data.
     */
    public function renderTemplate(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $valStr = is_scalar($value) ? (string) $value : json_encode($value);
            $content = preg_replace('/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/', $valStr, $content);
        }

        return $content;
    }
}
