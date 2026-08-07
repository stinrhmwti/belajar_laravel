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
        'keterangan',
        'status_approval',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
