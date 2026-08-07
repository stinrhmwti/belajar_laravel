<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_kendaraan',
        'merek',
        'tipe',
        'tahun',
        'plat_nomor',
        'lokasi_pool',
        'supir_utama',
        'odometer_awal',
        'pajak_tahunan',
        'pajak_5_tahunan',
        'jatuh_tempo_kir',
        'tanggal_servis_manual',
        'status',
        'foto',
        'latitude',
        'longitude',
    ];

    /**
     * URL Foto Kendaraan berdasarkan jenis kendaraan
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/'.$this->foto);
        }
        if (str_contains($this->jenis_kendaraan, 'Boks')) {
            return asset('images/box_truck.png');
        } elseif (str_contains($this->jenis_kendaraan, 'Pick Up') || str_contains($this->jenis_kendaraan, 'Pickup')) {
            return asset('images/pickup_truck.png');
        } elseif (str_contains($this->jenis_kendaraan, 'Motor')) {
            return asset('images/courier_motorcycle.png');
        }

        return asset('images/box_truck.png');
    }

    protected $casts = [
        'jatuh_tempo_kir' => 'date',
        'tanggal_servis_manual' => 'date',
    ];

    public function checklists()
    {
        return $this->hasMany(DailyChecklist::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Warning system: hijau (aman), kuning (mendekati jatuh tempo <=30 hari),
     * merah (sudah lewat jatuh tempo)
     */
    public function getStatusKirAttribute(): string
    {
        if (! $this->jatuh_tempo_kir) {
            return 'unknown';
        }

        $today = Carbon::now();
        $dueDate = Carbon::parse($this->jatuh_tempo_kir);

        if ($today->greaterThan($dueDate)) {
            return 'merah';
        }

        if ($today->diffInDays($dueDate) <= 30) {
            return 'kuning';
        }

        return 'hijau';
    }

    public function getOdometerTerkiniAttribute(): int
    {
        $lastChecklist = $this->checklists()->latest('tanggal')->first();

        return $lastChecklist->odometer ?? $this->odometer_awal;
    }

    /**
     * Jarak (km) tersisa menuju servis berikutnya.
     * Aturan: servis wajib setiap kelipatan 5.000 KM.
     */
    public function getKmMenujuServisAttribute(): int
    {
        $interval = 5000;
        $odoSekarang = $this->odometer_terkini;
        $kmServisBerikutnya = ceil(($odoSekarang + 1) / $interval) * $interval;

        return (int) ($kmServisBerikutnya - $odoSekarang);
    }

    /**
     * Tanggal servis berikutnya berdasarkan aturan 3 bulan sejak servis terakhir.
     * Servis terakhir dicek dari pengeluaran berjenis "Bengkel" paling baru;
     * kalau belum pernah servis, dihitung dari tanggal kendaraan didaftarkan.
     */
    public function getTanggalServisBerikutnyaAttribute(): Carbon
    {
        if ($this->tanggal_servis_manual) {
            return Carbon::parse($this->tanggal_servis_manual);
        }

        $servisTerakhir = $this->expenses()
            ->where(function ($q) {
                $q->where('jenis_pengeluaran', 'Bengkel')
                    ->orWhere('jenis_pengeluaran', 'like', '%Servis%');
            })
            ->latest('tanggal')
            ->first();

        $tanggalDasar = $servisTerakhir
            ? Carbon::parse($servisTerakhir->tanggal)
            : Carbon::parse($this->created_at);

        return $tanggalDasar->copy()->addMonths(3);
    }

    /**
     * Status gabungan servis berkala: merah (lewat), kuning (dekat), hijau (aman)
     * Diambil dari kondisi TERBURUK antara aturan KM dan aturan waktu.
     */
    public function getStatusServisBerkalaAttribute(): string
    {
        $kmTersisa = $this->km_menuju_servis;
        $tanggalServis = $this->tanggal_servis_berikutnya;
        $hariTersisa = Carbon::now()->diffInDays($tanggalServis, false);

        $statusKm = $kmTersisa <= 0 ? 'merah' : ($kmTersisa <= 500 ? 'kuning' : 'hijau');

        // Diubah menjadi <= 7 hari (1 minggu sebelumnya) sesuai permintaan notifikasi H-7
        $statusWaktu = $hariTersisa <= 0 ? 'merah' : ($hariTersisa <= 7 ? 'kuning' : 'hijau');

        if ($statusKm === 'merah' || $statusWaktu === 'merah') {
            return 'merah';
        }
        if ($statusKm === 'kuning' || $statusWaktu === 'kuning') {
            return 'kuning';
        }

        return 'hijau';
    }
}
