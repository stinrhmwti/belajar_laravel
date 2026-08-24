<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleHistory extends Model
{
    use HasFactory;

    protected $table = 'vehicle_histories';

    protected $fillable = [
        'vehicle_id',
        'tanggal',
        'teknisi_id',
        'jenis_pekerjaan',
        'sparepart_digunakan',
        'biaya',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
