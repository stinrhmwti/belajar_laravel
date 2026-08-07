<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriUjian extends Model
{
    protected $table = 'kategori_ujian';

    public $timestamps = false;

    protected $fillable = ['nama_kategori', 'kode_kategori', 'deskripsi'];
}
