<?php

namespace App\Services;

use App\Models\WhatsappLog;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        // Return null kalau hasil akhir kurang dari 10 digit
        if (strlen($cleaned) < 10) {
            return null;
        }

        return $cleaned;
    }

    /**
     * Membuat tautan langsung ke WhatsApp Web / WhatsApp App (wa.me / api.whatsapp.com).
     */
    public function getDirectLink(string $phone, string $message): string
    {
        $formatted = $this->formatPhone($phone) ?: preg_replace('/[^0-9]/', '', $phone);
        $encodedText = rawurlencode($message);
        return "https://api.whatsapp.com/send?phone={$formatted}&text={$encodedText}";
    }

    /**
     * Mengirim payload HTTP request ke penyedia WhatsApp Gateway API (Ervelia, Fonnte, Wablas, dll).
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
            return Http::withHeaders([
                'Authorization' => $token,
            ])
            ->connectTimeout(5)
            ->timeout(10)
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
            return Http::withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ])
            ->connectTimeout(5)
            ->timeout(10)
            ->post($url, [
                'phone' => $target,
                'message' => $message,
            ]);
        }

        // 3. Driver ERVELIA / CUSTOM REST API (Default)
        $endpoint = str_ends_with($baseUrl, '/api/v1/messages/send') ? $baseUrl : $baseUrl . '/api/v1/messages/send';
        return Http::withHeaders([
            'X-API-KEY' => $token,
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])
        ->connectTimeout(3)
        ->timeout(8)
        ->post($endpoint, [
            'to' => $target,
            'phone' => $target,
            'message' => $message,
        ]);
    }

    /**
     * Memeriksa apakah response dari provider mengindikasikan status sukses.
     *
     * @param array $body Response body dalam bentuk array
     * @param string $driver Jenis provider driver
     * @return bool
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
     * Mengirim pesan WhatsApp teks biasa via WhatsApp Gateway API dan mencatat history ke database.
     *
     * @param string $phone Nomor tujuan
     * @param string $message Isi pesan yang akan dikirim
     * @param array $meta Metadata tambahan (misal: template_id, user_id)
     * @return WhatsappLog
     */
    public function send(string $phone, string $message, array $meta = []): WhatsappLog
    {
        $driver = config('services.whatsapp.driver', 'ervelia');
        $formattedPhone = $this->formatPhone($phone);

        // SELALU buat record WhatsappLog status 'pending' terlebih dahulu
        $log = WhatsappLog::create([
            'whatsapp_template_id' => $meta['template_id'] ?? null,
            'user_id' => $meta['user_id'] ?? null,
            'phone' => $formattedPhone ?? $phone,
            'message' => $message,
            'status' => WhatsappLog::STATUS_PENDING,
            'provider' => $driver,
        ]);

        // Validasi format nomor telepon
        if (! $formattedPhone) {
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => 'Nomor WhatsApp tidak valid (kurang dari 10 digit atau format salah).',
            ]);
            return $log;
        }

        // Validasi fitur aktif/nonaktif
        if (! config('services.whatsapp.enabled', true)) {
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => 'Fitur pengiriman WhatsApp sedang dinonaktifkan di pengaturan sistem (WHATSAPP_ENABLED=false).',
            ]);
            return $log;
        }

        // Mode Sandbox / Simulasi Lokal (berguna saat offline atau pengujian di localhost tanpa server gateway aktif)
        if (in_array($driver, ['sandbox', 'mock', 'log', 'local'])) {
            $mockMessageId = 'SANDBOX_WA_' . strtoupper(bin2hex(random_bytes(6)));
            $log->update([
                'status' => WhatsappLog::STATUS_SUCCESS,
                'message_id' => $mockMessageId,
                'response' => [
                    'status' => 'success',
                    'driver' => $driver,
                    'mode' => 'sandbox_simulation',
                    'message_id' => $mockMessageId,
                    'target' => $formattedPhone,
                    'message' => $message,
                    'timestamp' => now()->toIso8601String(),
                ],
                'sent_at' => now(),
                'error_message' => null,
            ]);
            Log::info("WhatsApp [Sandbox] terkirim ke {$formattedPhone}: {$message}");
            return $log;
        }

        $token = config('services.whatsapp.token');
        // Validasi ketersediaan token API
        if (empty($token) && !in_array($driver, ['sandbox', 'mock', 'log'])) {
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => 'Token API WhatsApp belum dikonfigurasi (WHATSAPP_TOKEN kosong di file .env).',
            ]);
            return $log;
        }

        // Kirim request ke Gateway API dengan proteksi try-catch
        try {
            $response = $this->dispatchToProvider($formattedPhone, $message, $driver);
            $responseData = $response->json();
            $bodyArray = is_array($responseData) ? $responseData : [];

            if ($response->successful() && $this->isProviderSuccess($bodyArray, $driver)) {
                // Ambil message_id dari response jika ada
                $messageId = $bodyArray['id'] ?? $bodyArray['message_id'] ?? $bodyArray['data']['message_id'] ?? $bodyArray['data']['id'] ?? null;
                if (is_array($messageId)) {
                    $messageId = implode(',', $messageId);
                }

                $log->update([
                    'status' => WhatsappLog::STATUS_SUCCESS,
                    'message_id' => $messageId ? (string) $messageId : null,
                    'response' => $bodyArray ?: ['raw_body' => $response->body()],
                    'sent_at' => now(),
                    'error_message' => null,
                ]);
            } else {
                $errorMsg = $bodyArray['message'] ?? $bodyArray['error'] ?? $bodyArray['reason'] ?? ('Gagal mengirim pesan via WhatsApp Gateway (HTTP ' . $response->status() . ').');

                $log->update([
                    'status' => WhatsappLog::STATUS_FAILED,
                    'error_message' => is_string($errorMsg) ? $errorMsg : json_encode($errorMsg),
                    'response' => $bodyArray ?: ['raw_body' => $response->body()],
                ]);
            }
        } catch (Throwable $e) {
            $cleanError = $e->getMessage();
            if (str_contains($cleanError, 'cURL error 7') || str_contains($cleanError, 'Failed to connect') || str_contains($cleanError, 'Connection refused')) {
                $cleanError = "Server WhatsApp Gateway sedang offline atau tidak dapat dijangkau (" . config('services.whatsapp.base_url') . "). Silakan periksa koneksi server gateway atau gunakan tombol Kirim via WhatsApp Web.";
            }

            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => $cleanError,
                'response' => [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'hint' => 'Gunakan WhatsApp Web atau sesuaikan konfigurasi WHATSAPP_BASE_URL di file .env',
                ],
            ]);
        }

        return $log;
    }

    /**
     * Mengirim pesan WhatsApp menggunakan template yang tersimpan di database.
     *
     * @param string $code Kode template (contoh: 'kir_expired')
     * @param string $phone Nomor tujuan
     * @param array $data Data variabel untuk menggantikan placeholder {{variabel}}
     * @param array $meta Metadata tambahan (misal: user_id)
     * @return WhatsappLog
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
                'provider' => $driver,
                'error_message' => "Template WhatsApp dengan kode '{$code}' tidak ditemukan atau sedang tidak aktif.",
            ]);
        }

        // Render isi pesan dengan mengganti placeholder variabel
        $message = $this->renderTemplate($template->content, $data);

        // Panggil method send() dengan metadata template_id
        return $this->send($phone, $message, array_merge($meta, ['template_id' => $template->id]));
    }

    /**
     * Merender konten template dengan menggantikan {{variabel}} dan {{ variabel }} sesuai data.
     *
     * @param string $content Teks template dengan placeholder
     * @param array $data Data pengganti variabel ['nama' => 'Budi', ...]
     * @return string
     */
    public function renderTemplate(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $valStr = is_scalar($value) ? (string) $value : json_encode($value);
            // Ganti placeholder dengan format {{key}} maupun {{ key }}
            $content = preg_replace('/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/', $valStr, $content);
        }

        return $content;
    }
}
