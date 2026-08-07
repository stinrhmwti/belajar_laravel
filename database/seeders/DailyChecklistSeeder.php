<?php

namespace Database\Seeders;

use App\Models\DailyChecklist;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DailyChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $b1234 = Vehicle::where('plat_nomor', 'B 1234 KTR')->first();
        $b1112 = Vehicle::where('plat_nomor', 'B 1112 KTR')->first();

        if (! $b1234 || ! $b1112) {
            return;
        }

        $data = [
            [
                'vehicle_id' => $b1234->id,
                'tanggal' => '2026-07-06',
                'nama_teknisi' => 'Budi Santoso',
                'odometer' => 120600,
                'oli_mesin' => 'OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => 'Kondisi aman',
            ],
            [
                'vehicle_id' => $b1112->id,
                'tanggal' => '2026-07-06',
                'nama_teknisi' => 'Dedi Kurniawan',
                'odometer' => 76900,
                'oli_mesin' => 'Not OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'Not OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => 'Oli perlu dicek & lampu sein redup',
            ],
            [
                'vehicle_id' => $b1234->id,
                'tanggal' => '2026-07-07',
                'nama_teknisi' => 'Budi Santoso',
                'odometer' => 120750,
                'oli_mesin' => 'OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => null,
            ],
            [
                'vehicle_id' => $b1112->id,
                'tanggal' => '2026-07-07',
                'nama_teknisi' => 'Dedi Kurniawan',
                'odometer' => 77050,
                'oli_mesin' => 'OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => null,
            ],
            // August 2026 data
            [
                'vehicle_id' => $b1234->id,
                'tanggal' => '2026-08-01',
                'nama_teknisi' => 'Budi Santoso',
                'odometer' => 121100,
                'oli_mesin' => 'OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => 'Pemeriksaan awal bulan',
            ],
            [
                'vehicle_id' => $b1112->id,
                'tanggal' => '2026-08-01',
                'nama_teknisi' => 'Dedi Kurniawan',
                'odometer' => 77400,
                'oli_mesin' => 'OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'Not OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => 'Minyak rem terindikasi kurang',
            ],
            [
                'vehicle_id' => $b1234->id,
                'tanggal' => '2026-08-04',
                'nama_teknisi' => 'Budi Santoso',
                'odometer' => 121500,
                'oli_mesin' => 'OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => 'Kendaraan prima',
            ],
            [
                'vehicle_id' => $b1112->id,
                'tanggal' => '2026-08-04',
                'nama_teknisi' => 'Dedi Kurniawan',
                'odometer' => 77650,
                'oli_mesin' => 'OK',
                'air_radiator' => 'OK',
                'minyak_rem' => 'OK',
                'ban_rem' => 'OK',
                'lampu_klakson' => 'OK',
                'kebersihan' => 'OK',
                'catatan_tambahan' => 'Semua indikator normal',
            ],
        ];

        foreach ($data as $d) {
            DailyChecklist::firstOrCreate(
                ['vehicle_id' => $d['vehicle_id'], 'tanggal' => $d['tanggal']],
                $d
            );
        }
    }
}
