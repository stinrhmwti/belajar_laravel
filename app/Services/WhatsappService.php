<?php

namespace App\Services;

use App\Models\WhatsappLog;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
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
     * Mengirim payload HTTP request ke Ervelia Gateway API.
     *
     * @param string $target Nomor telepon tujuan format internasional (contoh: 628xxx)
     * @param string $message Isi pesan yang akan dikirim
     * @return Response
     */
    public function dispatchToProvider(string $target, string $message): Response
    {
        $token = (string) config('services.whatsapp.token');
        $baseUrl = rtrim(config('services.whatsapp.base_url', ''), '/');

        return Http::withHeaders([
            'X-API-KEY' => $token,
            'Content-Type' => 'application/json',
        ])
        ->timeout(20)
        ->retry(2, 500)
        ->post($baseUrl . '/api/v1/messages/send', [
            'to' => $target,
            'message' => $message,
        ]);
    }

    /**
     * Memeriksa apakah response dari provider mengindikasikan status sukses.
     *
     * @param array $body Response body dalam bentuk array
     * @return bool
     */
    public function isProviderSuccess(array $body): bool
    {
        return isset($body['success']) ? (bool) $body['success'] : true;
    }

    /**
     * Mengirim pesan WhatsApp teks biasa via Ervelia Gateway API dan mencatat history ke database.
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

        $token = config('services.whatsapp.token');
        // Validasi ketersediaan token API
        if (empty($token)) {
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => 'Token API WhatsApp belum dikonfigurasi (WHATSAPP_TOKEN kosong).',
            ]);
            return $log;
        }

        // Kirim request ke Ervelia Gateway API dengan proteksi try-catch
        try {
            $response = $this->dispatchToProvider($formattedPhone, $message);
            $responseData = $response->json();
            $bodyArray = is_array($responseData) ? $responseData : [];

            if ($response->successful() && $this->isProviderSuccess($bodyArray)) {
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
                $errorMsg = $bodyArray['message'] ?? $bodyArray['error'] ?? $bodyArray['reason'] ?? ('Gagal mengirim pesan via Ervelia Gateway (HTTP ' . $response->status() . ').');

                $log->update([
                    'status' => WhatsappLog::STATUS_FAILED,
                    'error_message' => is_string($errorMsg) ? $errorMsg : json_encode($errorMsg),
                    'response' => $bodyArray ?: ['raw_body' => $response->body()],
                ]);
            }
        } catch (Throwable $e) {
            $log->update([
                'status' => WhatsappLog::STATUS_FAILED,
                'error_message' => 'Terjadi kesalahan sistem saat kirim WA: ' . $e->getMessage(),
                'response' => [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
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
