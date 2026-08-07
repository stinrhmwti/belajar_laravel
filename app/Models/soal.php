<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    public $timestamps = false;

    protected $fillable = [
        'mapel_id',
        'kategori_id',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriUjian::class, 'kategori_id');
    }
}
