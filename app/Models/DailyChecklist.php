<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'tanggal',
        'nama_teknisi',
        'odometer',
        'oli_mesin',
        'air_radiator',
        'minyak_rem',
        'ban_rem',
        'lampu_klakson',
        'kebersihan',
        'catatan_tambahan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * True kalau ada minimal satu parameter yang "Not OK"
     */
    public function getAdaMasalahAttribute(): bool
    {
        return in_array('Not OK', [
            $this->oli_mesin,
            $this->air_radiator,
            $this->minyak_rem,
            $this->ban_rem,
            $this->lampu_klakson,
            $this->kebersihan,
        ]);
    }
}
