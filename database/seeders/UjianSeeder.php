<?php

namespace Database\Seeders;

use App\Models\HasilUjian;
use App\Models\KategoriUjian;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UjianSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Mapel (Subjects)
        $mtk = Mapel::create(['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK01']);
        $bing = Mapel::create(['nama_mapel' => 'Bahasa Inggris', 'kode_mapel' => 'ING01']);
        $ipa = Mapel::create(['nama_mapel' => 'Ilmu Pengetahuan Alam', 'kode_mapel' => 'IPA01']);

        // 2. Seed KategoriUjian (Exam Categories)
        $uts = KategoriUjian::create([
            'nama_kategori' => 'Ujian Tengah Semester',
            'kode_kategori' => 'UTS',
            'deskripsi' => 'Evaluasi tengah semester ganjil'
        ]);
        $uas = KategoriUjian::create([
            'nama_kategori' => 'Ujian Akhir Semester',
            'kode_kategori' => 'UAS',
            'deskripsi' => 'Evaluasi akhir semester ganjil'
        ]);
        $latihan = KategoriUjian::create([
            'nama_kategori' => 'Latihan Harian',
            'kode_kategori' => 'LATHAR',
            'deskripsi' => 'Kuis dan latihan harian mandiri'
        ]);

        // 3. Seed Soal (Questions)
        // Matematika - UTS
        Soal::create([
            'mapel_id' => $mtk->id,
            'kategori_id' => $uts->id,
            'pertanyaan' => 'Berapakah hasil dari 5 + 3 * 2?',
            'pilihan_a' => '16',
            'pilihan_b' => '11',
            'pilihan_c' => '13',
            'pilihan_d' => '10',
            'jawaban_benar' => 'B',
        ]);
        Soal::create([
            'mapel_id' => $mtk->id,
            'kategori_id' => $uts->id,
            'pertanyaan' => 'Jika x + 5 = 12, berapakah nilai x?',
            'pilihan_a' => '5',
            'pilihan_b' => '6',
            'pilihan_c' => '7',
            'pilihan_d' => '8',
            'jawaban_benar' => 'C',
        ]);

        // Bahasa Inggris - UAS
        Soal::create([
            'mapel_id' => $bing->id,
            'kategori_id' => $uas->id,
            'pertanyaan' => 'What is the synonym of "Happy"?',
            'pilihan_a' => 'Sad',
            'pilihan_b' => 'Joyful',
            'pilihan_c' => 'Angry',
            'pilihan_d' => 'Tired',
            'jawaban_benar' => 'B',
        ]);
        Soal::create([
            'mapel_id' => $bing->id,
            'kategori_id' => $uas->id,
            'pertanyaan' => 'Translate: "Saya sedang membaca buku" into English.',
            'pilihan_a' => 'I read a book',
            'pilihan_b' => 'I am reading a book',
            'pilihan_c' => 'I was reading a book',
            'pilihan_d' => 'I have read a book',
            'jawaban_benar' => 'B',
        ]);

        // IPA - Latihan
        Soal::create([
            'mapel_id' => $ipa->id,
            'kategori_id' => $latihan->id,
            'pertanyaan' => 'Planet apakah yang terdekat dari Matahari?',
            'pilihan_a' => 'Venus',
            'pilihan_b' => 'Bumi',
            'pilihan_c' => 'Merkurius',
            'pilihan_d' => 'Mars',
            'jawaban_benar' => 'C',
        ]);
        Soal::create([
            'mapel_id' => $ipa->id,
            'kategori_id' => $latihan->id,
            'pertanyaan' => 'Gas apakah yang kita hirup saat bernapas?',
            'pilihan_a' => 'Karbondioksida',
            'pilihan_b' => 'Oksigen',
            'pilihan_c' => 'Nitrogen',
            'pilihan_d' => 'Hidrogen',
            'jawaban_benar' => 'B',
        ]);

        // 4. Seed HasilUjian (Exam Results)
        $muridRizky = User::where('username', 'murid_rizky')->first();
        $muridDewi = User::where('username', 'murid_dewi')->first();

        if ($muridRizky) {
            // Rizky Matematika UTS
            HasilUjian::create([
                'user_id' => $muridRizky->id,
                'mapel_id' => $mtk->id,
                'kategori_id' => $uts->id,
                'jumlah_benar' => 2,
                'jumlah_salah' => 0,
                'nilai' => 100.00,
                'tanggal' => Carbon::now()->subDays(5)->toDateString(),
            ]);

            // Rizky Bahasa Inggris UAS
            HasilUjian::create([
                'user_id' => $muridRizky->id,
                'mapel_id' => $bing->id,
                'kategori_id' => $uas->id,
                'jumlah_benar' => 1,
                'jumlah_salah' => 1,
                'nilai' => 50.00,
                'tanggal' => Carbon::now()->subDays(2)->toDateString(),
            ]);
        }

        if ($muridDewi) {
            // Dewi IPA Latihan
            HasilUjian::create([
                'user_id' => $muridDewi->id,
                'mapel_id' => $ipa->id,
                'kategori_id' => $latihan->id,
                'jumlah_benar' => 2,
                'jumlah_salah' => 0,
                'nilai' => 100.00,
                'tanggal' => Carbon::now()->subDays(3)->toDateString(),
            ]);
        }
    }
}
