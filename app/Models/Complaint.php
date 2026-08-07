<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $vehicle_id
 * @property int $user_id
 * @property string $tanggal
 * @property string $keluhan
 * @property string $status
 * @property string|null $catatan_penyelesaian
 * @property-read Vehicle $vehicle
 * @property-read User $user
 */
class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'tanggal',
        'keluhan',
        'status',
        'progress_perbaikan',
        'diterima_at',
        'diperbaiki_at',
        'selesai_at',
        'foto_kerusakan',
        'video_kerusakan',
        'catatan_penyelesaian',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'diterima_at' => 'datetime',
        'diperbaiki_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
