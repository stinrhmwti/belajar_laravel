<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'jenis_kendaraan' => 'Mobil Boks',
                'merek' => 'Mitsubishi',
                'tipe' => 'Canter FE 74',
                'plat_nomor' => 'B 1234 KTR',
                'lokasi_pool' => 'Jakarta',
                'supir_utama' => 'Driver Utama',
                'odometer_awal' => 120500,
                'pajak_tahunan' => 4500000,
                'pajak_5_tahunan' => 20000000,
                'jatuh_tempo_kir' => '2026-03-15',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Mobil Pick Up',
                'merek' => 'Suzuki',
                'tipe' => 'Carry Pickup',
                'plat_nomor' => 'B 1112 KTR',
                'lokasi_pool' => 'Bandung',
                'supir_utama' => 'Dedi Kurniawan',
                'odometer_awal' => 76800,
                'pajak_tahunan' => 2100000,
                'pajak_5_tahunan' => 10000000,
                'jatuh_tempo_kir' => '2026-02-05',
                'status' => 'Sedang Diservis',
            ],
            [
                'jenis_kendaraan' => 'Mobil Boks',
                'merek' => 'Isuzu',
                'tipe' => 'Elf NMR 71',
                'plat_nomor' => 'B 2201 KTR',
                'lokasi_pool' => 'Jakarta',
                'supir_utama' => 'Agus Setiawan',
                'odometer_awal' => 95300,
                'pajak_tahunan' => 5200000,
                'pajak_5_tahunan' => 22000000,
                'jatuh_tempo_kir' => '2026-09-10',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Mobil Pick Up',
                'merek' => 'Daihatsu',
                'tipe' => 'Gran Max Pick Up',
                'plat_nomor' => 'B 3305 KTR',
                'lokasi_pool' => 'Bekasi',
                'supir_utama' => 'Rudi Hartono',
                'odometer_awal' => 62100,
                'pajak_tahunan' => 1900000,
                'pajak_5_tahunan' => 9500000,
                'jatuh_tempo_kir' => '2026-01-20',
                'status' => 'Sedang Diservis',
            ],
            [
                'jenis_kendaraan' => 'Mobil Boks',
                'merek' => 'Mitsubishi',
                'tipe' => 'Colt Diesel FE 71',
                'plat_nomor' => 'B 4410 KTR',
                'lokasi_pool' => 'Tangerang',
                'supir_utama' => 'Slamet Riyadi',
                'odometer_awal' => 143200,
                'pajak_tahunan' => 4800000,
                'pajak_5_tahunan' => 21000000,
                'jatuh_tempo_kir' => '2026-11-25',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Motor Kurir',
                'merek' => 'Honda',
                'tipe' => 'Revo X',
                'plat_nomor' => 'B 5521 KTR',
                'lokasi_pool' => 'Jakarta',
                'supir_utama' => 'Tono Wijaya',
                'odometer_awal' => 28900,
                'pajak_tahunan' => 350000,
                'pajak_5_tahunan' => 1200000,
                'jatuh_tempo_kir' => '2026-08-14',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Mobil Pick Up',
                'merek' => 'Suzuki',
                'tipe' => 'Carry Futura',
                'plat_nomor' => 'B 6630 KTR',
                'lokasi_pool' => 'Depok',
                'supir_utama' => 'Hendra Gunawan',
                'odometer_awal' => 87400,
                'pajak_tahunan' => 2300000,
                'pajak_5_tahunan' => 11000000,
                'jatuh_tempo_kir' => '2025-12-30',
                'status' => 'Sedang Diservis',
            ],
            [
                'jenis_kendaraan' => 'Mobil Boks',
                'merek' => 'Hino',
                'tipe' => 'Dutro 110 SD',
                'plat_nomor' => 'B 7741 KTR',
                'lokasi_pool' => 'Bogor',
                'supir_utama' => 'Wawan Setiadi',
                'odometer_awal' => 55600,
                'pajak_tahunan' => 6100000,
                'pajak_5_tahunan' => 25000000,
                'jatuh_tempo_kir' => '2026-06-18',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Mobil Boks',
                'merek' => 'Isuzu',
                'tipe' => 'Elf NLR 55',
                'plat_nomor' => 'B 9214 KTR',
                'lokasi_pool' => 'Tangerang',
                'supir_utama' => 'Yudi Pratama',
                'odometer_awal' => 104200,
                'pajak_tahunan' => 5000000,
                'pajak_5_tahunan' => 21500000,
                'jatuh_tempo_kir' => '2026-08-25',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Mobil Pick Up',
                'merek' => 'Daihatsu',
                'tipe' => 'Gran Max Pick Up 1.5',
                'plat_nomor' => 'B 8130 KTR',
                'lokasi_pool' => 'Bekasi',
                'supir_utama' => 'Andi Wijaya',
                'odometer_awal' => 51200,
                'pajak_tahunan' => 1950000,
                'pajak_5_tahunan' => 9800000,
                'jatuh_tempo_kir' => '2026-05-12',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Motor Kurir',
                'merek' => 'Yamaha',
                'tipe' => 'Gear 125',
                'plat_nomor' => 'B 3089 KTR',
                'lokasi_pool' => 'Jakarta',
                'supir_utama' => 'Rian Hidayat',
                'odometer_awal' => 15300,
                'pajak_tahunan' => 320000,
                'pajak_5_tahunan' => 1100000,
                'jatuh_tempo_kir' => '2026-04-10',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Mobil Boks',
                'merek' => 'Hino',
                'tipe' => 'Dutro 130 HD',
                'plat_nomor' => 'B 9972 KTR',
                'lokasi_pool' => 'Bogor',
                'supir_utama' => 'Mulyono',
                'odometer_awal' => 135800,
                'pajak_tahunan' => 6400000,
                'pajak_5_tahunan' => 26000000,
                'jatuh_tempo_kir' => '2026-02-18',
                'status' => 'Sedang Diservis',
            ],
            [
                'jenis_kendaraan' => 'Mobil Pick Up',
                'merek' => 'Toyota',
                'tipe' => 'Hilux Single Cabin',
                'plat_nomor' => 'B 4118 KTR',
                'lokasi_pool' => 'Depok',
                'supir_utama' => 'Eko Prasetyo',
                'odometer_awal' => 42100,
                'pajak_tahunan' => 2800000,
                'pajak_5_tahunan' => 13000000,
                'jatuh_tempo_kir' => '2026-06-05',
                'status' => 'Siap Pakai',
            ],
            [
                'jenis_kendaraan' => 'Motor Kurir',
                'merek' => 'Honda',
                'tipe' => 'Vario 125',
                'plat_nomor' => 'B 6245 KTR',
                'lokasi_pool' => 'Jakarta',
                'supir_utama' => 'Fajar Ramadhan',
                'odometer_awal' => 32400,
                'pajak_tahunan' => 380000,
                'pajak_5_tahunan' => 1300000,
                'jatuh_tempo_kir' => '2026-07-22',
                'status' => 'Siap Pakai',
            ],
        ];

        // Definisi titik koordinat presisi per Kota Pool Operasional
        $cityPoolCoords = [
            'Jakarta' => [
                [-6.175392, 106.827153, 'Pool Pusat Gambir'],
                [-6.126588, 106.905663, 'Pool Tanjung Priok'],
                [-6.215000, 106.817000, 'Pool Sudirman Senayan'],
                [-6.155000, 106.745000, 'Pool Daan Mogot'],
                [-6.194000, 106.888000, 'Pool Rawamangun'],
            ],
            'Bandung' => [
                [-6.938500, 107.625000, 'Pool Soekarno-Hatta Bandung'],
                [-6.892000, 107.578000, 'Pool Pasteur Bandung'],
            ],
            'Bekasi' => [
                [-6.238000, 106.992000, 'Pool Summarecon Bekasi'],
                [-6.284000, 107.150000, 'Pool Kawasan Cikarang'],
            ],
            'Tangerang' => [
                [-6.301000, 106.652000, 'Pool BSD City Serpong'],
                [-6.185000, 106.635000, 'Pool Cikokol Tangerang'],
            ],
            'Depok' => [
                [-6.372000, 106.832000, 'Pool Margonda Depok'],
                [-6.398000, 106.772000, 'Pool Sawangan Depok'],
            ],
            'Bogor' => [
                [-6.595000, 106.806000, 'Pool Pajajaran Bogor'],
                [-6.536000, 106.862000, 'Pool Sentul City'],
            ],
        ];

        $poolUsageCounts = [];

        foreach ($vehicles as $index => $v) {
            $kota = trim($v['lokasi_pool'] ?? 'Jakarta');
            
            // Cari kecocokan kota
            $matchedKey = 'Jakarta';
            foreach (array_keys($cityPoolCoords) as $k) {
                if (stripos($kota, $k) !== false) {
                    $matchedKey = $k;
                    break;
                }
            }

            $coordsList = $cityPoolCoords[$matchedKey];
            $useIndex = ($poolUsageCounts[$matchedKey] ?? 0) % count($coordsList);
            $poolUsageCounts[$matchedKey] = ($poolUsageCounts[$matchedKey] ?? 0) + 1;

            $assignedCoord = $coordsList[$useIndex];
            $v['latitude'] = $assignedCoord[0];
            $v['longitude'] = $assignedCoord[1];
            $v['lokasi_pool'] = $assignedCoord[2];

            Vehicle::updateOrCreate(
                ['plat_nomor' => $v['plat_nomor']],
                $v
            );
        }
    }
}
