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

        // Center coordinates: Jakarta/Java region
        $baseLat = -6.17511;
        $baseLng = 106.82717;

        foreach ($vehicles as $index => $v) {
            // Assign coordinate offsets based on vehicle locations
            if (str_contains(strtolower($v['lokasi_pool']), 'bandung')) {
                $v['latitude'] = -6.91746 + ($index * 0.0035);
                $v['longitude'] = 107.61912 + ($index * 0.0042);
            } else {
                $v['latitude'] = $baseLat + (($index % 5) * 0.0073) - 0.015;
                $v['longitude'] = $baseLng + (($index % 5) * 0.0084) - 0.015;
            }

            Vehicle::updateOrCreate(
                ['plat_nomor' => $v['plat_nomor']],
                $v
            );
        }
    }
}
