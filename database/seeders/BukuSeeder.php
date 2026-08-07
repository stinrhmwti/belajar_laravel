<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Buku::create(['judul' => 'Pemrograman Laravel', 'penulis' => 'Andi']);
        Buku::create(['judul' => 'Database SQL', 'penulis' => 'Budi']);
        Buku::create(['judul' => 'Desain Web', 'penulis' => 'Citra']);
    }
}
