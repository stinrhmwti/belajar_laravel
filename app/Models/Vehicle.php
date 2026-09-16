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
        'lokasi_asal',
        'lokasi_tujuan',
        'status_perjalanan',
        'kecepatan_kmh',
        'estimasi_tiba',
        'jarak_sisa_km',
        'catatan_perjalanan',
        'supir_utama',
        'driver_id',
        'odometer_awal',
        'pajak_tahunan',
        'pajak_5_tahunan',
        'jatuh_tempo_kir',
        'tanggal_servis_manual',
        'status',
        'foto',
        'latitude',
        'longitude',
        'tujuan_latitude',
        'tujuan_longitude',
    ];

    /**
     * URL Foto Kendaraan berdasarkan jenis kendaraan
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/'.$this->foto);
        }
        
        $brandType = strtolower($this->merek . ' ' . $this->tipe);
        $type = strtolower($this->jenis_kendaraan . ' ' . $this->tipe);
        
        if (str_contains($brandType, 'bmw')) {
            return asset('images/bmw_320i.jpg');
        } elseif (str_contains($brandType, 'vario')) {
            return asset('images/honda_vario.jpg');
        }
        
        // Check Boks/Canter/Elf/Dutro
        if (str_contains($type, 'boks') || str_contains($type, 'canter') || str_contains($type, 'elf') || str_contains($type, 'dutro')) {
            return asset('images/box_truck_real.jpg');
        }
        
        // Check Pick Up/Carry/Hilux/Gran Max
        if (str_contains($type, 'pick up') || str_contains($type, 'pickup') || str_contains($type, 'carry') || str_contains($type, 'hilux') || str_contains($type, 'gran max')) {
            return asset('images/pickup_truck_real.jpg');
        }
        
        // Check Motor/Kurir/Yamaha/Honda
        if (str_contains($type, 'motor') || str_contains($type, 'kurir') || str_contains($type, 'yamaha') || str_contains($type, 'honda')) {
            return asset('images/courier_motorcycle_real.jpg');
        }

        return asset('images/box_truck_real.jpg');
    }

    protected $casts = [
        'jatuh_tempo_kir' => 'date',
        'tanggal_servis_manual' => 'date',
        'estimasi_tiba' => 'datetime',
        'tujuan_latitude' => 'float',
        'tujuan_longitude' => 'float',
        'jarak_sisa_km' => 'float',
        'kecepatan_kmh' => 'integer',
    ];

    /**
     * Format Estimasi Waktu Tiba (ETA) yang mudah dibaca
     */
    public function getFormattedEtaAttribute(): string
    {
        if (!$this->estimasi_tiba) {
            if ($this->status_perjalanan === 'Standby di Pool') {
                return 'Standby di Pool';
            }
            return 'Belum Diatur';
        }

        $now = Carbon::now();
        $eta = Carbon::parse($this->estimasi_tiba);

        if ($now->greaterThan($eta)) {
            return 'Tiba di Lokasi (' . $eta->format('H:i') . ' WIB)';
        }

        $diffMinutes = (int) $now->diffInMinutes($eta);
        if ($diffMinutes < 60) {
            return $eta->format('H:i') . " WIB (± {$diffMinutes} menit)";
        }

        $hours = floor($diffMinutes / 60);
        $minutes = $diffMinutes % 60;
        return $eta->format('H:i') . " WIB (± {$hours}j {$minutes}m)";
    }

    /**
     * Label Badge Berwarna untuk Status Perjalanan
     */
    public function getBadgeStatusPerjalananAttribute(): string
    {
        $status = $this->status_perjalanan ?: 'Standby di Pool';
        return match($status) {
            'Dalam Perjalanan ke Tujuan' => '<span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-geo-fill me-1"></i>Dalam Perjalanan</span>',
            'Proses Bongkar Muat' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-box-seam me-1"></i>Bongkar Muat</span>',
            'Perjalanan Kembali ke Pool' => '<span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-arrow-return-left me-1"></i>Kembali ke Pool</span>',
            default => '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-p-circle me-1"></i>Standby di Pool</span>',
        };
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function checklists()
    {
        return $this->hasMany(DailyChecklist::class);
    }

    public function latestChecklist()
    {
        return $this->hasOne(DailyChecklist::class)->latestOfMany('tanggal');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function lastServiceExpense()
    {
        return $this->hasOne(Expense::class)
            ->where(function ($q) {
                $q->where('jenis_pengeluaran', 'Bengkel')
                    ->orWhere('jenis_pengeluaran', 'like', '%Servis%');
            })
            ->latestOfMany('tanggal');
    }

    public function histories()
    {
        return $this->hasMany(VehicleHistory::class)->orderByDesc('tanggal');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
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
        $lastChecklist = $this->relationLoaded('latestChecklist')
            ? $this->latestChecklist
            : $this->checklists()->latest('tanggal')->first();

        $odoChecklist = $lastChecklist ? (int) $lastChecklist->odometer : 0;
        $odoMaster = (int) $this->odometer_awal;

        return max($odoChecklist, $odoMaster);
    }

    /**
     * Menghitung tanggal servis terakhir berdasarkan Expense (Bengkel/Servis),
     * VehicleHistory, atau manual override tanggal_servis_manual (dikurangi 3 bulan).
     */
    public function getTanggalServisTerakhirAttribute(): ?Carbon
    {
        $dates = collect();

        // 1. Dari Expense Bengkel / Servis
        $lastExpense = $this->expenses()
            ->where(function ($q) {
                $q->where('jenis_pengeluaran', 'Bengkel')
                  ->orWhere('jenis_pengeluaran', 'like', '%Servis%');
            })
            ->latest('tanggal')
            ->first();
        if ($lastExpense) {
            $dates->push(Carbon::parse($lastExpense->tanggal));
        }

        // 2. Dari VehicleHistory
        $lastHistory = $this->histories()->latest('tanggal')->first();
        if ($lastHistory) {
            $dates->push(Carbon::parse($lastHistory->tanggal));
        }

        // 3. Estimasi dari manual override (tanggal_servis_manual - 3 bulan)
        if ($this->tanggal_servis_manual) {
            $dates->push(Carbon::parse($this->tanggal_servis_manual)->subMonths(3));
        }

        if ($dates->isEmpty()) {
            return null;
        }

        return $dates->max();
    }

    /**
     * Odometer kendaraan saat servis terakhir dilakukan.
     */
    public function getOdometerSaatServisTerakhirAttribute(): int
    {
        $tglServis = $this->tanggal_servis_terakhir;
        if (! $tglServis) {
            return $this->odometer_awal;
        }

        // Cari checklist terakhir pada atau sebelum tanggal servis terakhir
        $checklist = $this->checklists()
            ->where('tanggal', '<=', $tglServis->toDateString())
            ->latest('tanggal')
            ->first();

        if ($checklist) {
            return max((int) $checklist->odometer, (int) $this->odometer_awal);
        }

        return $this->odometer_awal;
    }

    /**
     * Jarak (km) tersisa menuju servis berikutnya.
     * Dihitung dari odometer saat servis terakhir + 5.000 KM.
     */
    public function getKmMenujuServisAttribute(): int
    {
        $interval = 5000;
        $odoSekarang = $this->odometer_terkini;
        $odoSaatServis = $this->odometer_saat_servis_terakhir;

        $kmServisBerikutnya = $odoSaatServis + $interval;

        return (int) ($kmServisBerikutnya - $odoSekarang);
    }

    /**
     * Tanggal servis berikutnya berdasarkan aturan 3 bulan sejak servis terakhir.
     * Servis terakhir dicek dari tanggal_servis_terakhir helper;
     * kalau belum pernah servis, dihitung dari tanggal kendaraan didaftarkan.
     */
    public function getTanggalServisBerikutnyaAttribute(): Carbon
    {
        if ($this->tanggal_servis_manual) {
            return Carbon::parse($this->tanggal_servis_manual);
        }

        $servisTerakhir = $this->tanggal_servis_terakhir;
        $tanggalDasar = $servisTerakhir ?: Carbon::parse($this->created_at);

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
