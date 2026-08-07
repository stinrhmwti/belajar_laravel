<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    protected $table = 'hasil_ujian';

    public $timestamps = false;

    protected $fillable = ['user_id', 'mapel_id', 'kategori_id', 'jumlah_benar', 'jumlah_salah', 'nilai', 'tanggal'];

    public function murid()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriUjian::class, 'kategori_id');
    }
}
