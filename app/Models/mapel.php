<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';

    public $timestamps = false;

    protected $fillable = ['nama_mapel', 'kode_mapel'];

    public function soals()
    {
        return $this->hasMany(Soal::class, 'mapel_id');
    }

    public function kategoris()
    {
        return $this->hasMany(kategoriujian::class, 'mapel_id');
    }
}
