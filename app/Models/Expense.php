<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'tanggal',
        'jenis_pengeluaran',
        'jumlah_biaya',
        'liter_bbm',
        'odometer_pengisian',
        'keterangan',
        'status_approval',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'liter_bbm' => 'float',
        'odometer_pengisian' => 'integer',
        'jumlah_biaya' => 'float',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
