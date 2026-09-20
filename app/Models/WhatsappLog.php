<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $whatsapp_template_id
 * @property int|null $user_id
 * @property string $phone
 * @property string $message
 * @property string $status
 * @property string|null $provider
 * @property string|null $message_id
 * @property string|null $error_message
 * @property array|null $response
 * @property \Carbon\Carbon|null $sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class WhatsappLog extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'whatsapp_template_id',
        'user_id',
        'phone',
        'message',
        'status',
        'provider',
        'message_id',
        'error_message',
        'response',
        'sent_at',
    ];

    protected $casts = [
        'response' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Relasi ke template WhatsApp yang digunakan (jika ada).
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(WhatsappTemplate::class, 'whatsapp_template_id');
    }

    /**
     * Relasi ke data User/Pengemudi tujuan pesan (jika ada).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Cek apakah pengiriman pesan berstatus sukses.
     */
    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * Mengembalikan nama modifier badge Bootstrap sesuai status log.
     * 'success' => success, 'failed' => danger, lainnya => secondary.
     */
    public function statusBadge(): string
    {
        return match ($this->status) {
            self::STATUS_SUCCESS => 'success',
            self::STATUS_FAILED => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Ekstraksi nama pelapor/pengemudi dari teks pesan atau relasi user.
     */
    public function getPelaporAttribute(): string
    {
        // 1. Pola label "Pelapor:", "Pemeriksa:", "Driver:", "Pengemudi:"
        if (preg_match('/(?:Pelapor|Pemeriksa|Driver|Pengemudi)\*?:\s*([^\n\r•]+)/i', $this->message, $matches)) {
            $name = trim(str_replace('*', '', $matches[1]));
            if (!empty($name) && !str_starts_with($name, '{{')) {
                return $name;
            }
        }

        // 2. Pola "oleh *Nama*" (seperti pada checklist peringatan)
        if (preg_match('/oleh\s+\*([^\*]+)\*/i', $this->message, $matches)) {
            $name = trim($matches[1]);
            if (!empty($name) && !str_starts_with($name, '{{')) {
                return $name;
            }
        }

        // 3. Pola sapaan "Halo Nama,"
        if (preg_match('/Halo\s+([^,\n\r]+)/i', $this->message, $matches)) {
            $name = trim(str_replace('*', '', $matches[1]));
            if (!empty($name) && !str_starts_with($name, '{{')) {
                return $name;
            }
        }

        // 4. Fallback ke relasi User
        if ($this->user) {
            return $this->user->name;
        }

        return 'Pengemudi / Staff';
    }

    /**
     * Ekstraksi identitas kendaraan (Plat Nomor / Merk) dari isi pesan.
     */
    public function getKendaraanAttribute(): ?string
    {
        // 1. Pola label "Kendaraan: Plat (Merk)"
        if (preg_match('/Kendaraan\*?:\s*([^\n\r•]+)/i', $this->message, $matches)) {
            $val = trim(str_replace('*', '', $matches[1]));
            if (!empty($val) && !str_starts_with($val, '{{')) {
                return $val;
            }
        }

        // 2. Pola "kendaraan *PLAT* (MERK)" atau "kendaraan *PLAT*"
        if (preg_match('/kendaraan\s+\*([^\*]+)\*(?:\s*(\([^\)]+\)))?/i', $this->message, $matches)) {
            $plat = trim($matches[1]);
            $merk = isset($matches[2]) ? ' ' . trim($matches[2]) : '';
            $full = trim($plat . $merk);
            if (!empty($full) && !str_starts_with($plat, '{{')) {
                return $full;
            }
        }

        // 3. Pola umum Plat Nomor Indonesia (contoh: B 1234 ABC, AD 5555 XY)
        if (preg_match('/([A-Z]{1,2}\s+\d{1,4}\s+[A-Z]{1,3})/i', $this->message, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    /**
     * Ekstraksi rincian kendala / kerusakan / isi ringkas laporan.
     */
    public function getKendalaAttribute(): string
    {
        // 1. Pola kerusakan/kendala/komponen/deskripsi/catatan
        if (preg_match('/(?:Bagian \/ Kerusakan|Deskripsi|Komponen Bermasalah|Catatan Teknisi|Keterangan|Catatan)\*?:\s*([^\n\r•]+)/i', $this->message, $matches)) {
            $val = trim(str_replace('*', '', $matches[1]));
            if (!empty($val) && !str_starts_with($val, '{{')) {
                return $val;
            }
        }

        // 2. Pola update status perbaikan
        if (preg_match('/saat ini:\s*\*([^\*]+)\*/i', $this->message, $matches)) {
            return 'Status: ' . trim($matches[1]);
        }

        $cleanMsg = trim(preg_replace('/[*•_~]/', '', $this->message));
        return \Illuminate\Support\Str::limit($cleanMsg, 60);
    }
}
