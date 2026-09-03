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
    ];

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

        return $lastChecklist->odometer ?? $this->odometer_awal;
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
