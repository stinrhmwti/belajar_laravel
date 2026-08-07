<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $b1234 = Vehicle::where('plat_nomor', 'B 1234 KTR')->first();
        $b1112 = Vehicle::where('plat_nomor', 'B 1112 KTR')->first();
        $b2201 = Vehicle::where('plat_nomor', 'B 2201 KTR')->first();
        $b3305 = Vehicle::where('plat_nomor', 'B 3305 KTR')->first();
        $b4410 = Vehicle::where('plat_nomor', 'B 4410 KTR')->first();

        // Drivers
        $driverDedi = User::where('email', 'dedi.driver@fleet.com')->first();
        $driverAgus = User::where('email', 'agus.driver@fleet.com')->first();
        $driverRudi = User::where('email', 'rudi.driver@fleet.com')->first();
        $driverSlamet = User::where('email', 'slamet.driver@fleet.com')->first();

        // Default fallback users if not found
        $fallbackUser = User::where('role', 'user')->first() ?? User::first();
        $fallbackVehicle = Vehicle::first();

        $complaints = [
            [
                'vehicle_id' => $b1112 ? $b1112->id : $fallbackVehicle->id,
                'user_id' => $driverDedi ? $driverDedi->id : $fallbackUser->id,
                'tanggal' => '2026-08-01',
                'keluhan' => 'Rem kaki terasa keras dan kurang pakem saat muatan penuh.',
                'status' => 'Diproses',
                'progress_perbaikan' => 50,
                'diterima_at' => '2026-08-01 09:00:00',
                'diperbaiki_at' => '2026-08-02 10:30:00',
                'selesai_at' => null,
                'catatan_penyelesaian' => 'Sedang diganti kampas rem depan dan minyak rem dikuras.',
            ],
            [
                'vehicle_id' => $b1234 ? $b1234->id : $fallbackVehicle->id,
                'user_id' => $fallbackUser->id,
                'tanggal' => '2026-08-02',
                'keluhan' => 'AC bagian kabin panas dan terdengar bunyi mendengung saat blower dinyalakan.',
                'status' => 'Selesai',
                'progress_perbaikan' => 100,
                'diterima_at' => '2026-08-02 08:30:00',
                'diperbaiki_at' => '2026-08-02 09:00:00',
                'selesai_at' => '2026-08-02 15:00:00',
                'catatan_penyelesaian' => 'Freon AC diisi ulang dan dinamo blower dibersihkan.',
            ],
            [
                'vehicle_id' => $b2201 ? $b2201->id : $fallbackVehicle->id,
                'user_id' => $driverAgus ? $driverAgus->id : $fallbackUser->id,
                'tanggal' => '2026-08-03',
                'keluhan' => 'Lampu sein kanan belakang mati total dan lampu utama sebelah kiri redup.',
                'status' => 'Baru',
                'progress_perbaikan' => 0,
                'diterima_at' => null,
                'diperbaiki_at' => null,
                'selesai_at' => null,
                'catatan_penyelesaian' => null,
            ],
            [
                'vehicle_id' => $b3305 ? $b3305->id : $fallbackVehicle->id,
                'user_id' => $driverRudi ? $driverRudi->id : $fallbackUser->id,
                'tanggal' => '2026-08-03',
                'keluhan' => 'Mesin terasa tersendat (brebet) di putaran bawah, terutama saat menanjak.',
                'status' => 'Diproses',
                'progress_perbaikan' => 30,
                'diterima_at' => '2026-08-03 14:00:00',
                'diperbaiki_at' => '2026-08-04 09:00:00',
                'selesai_at' => null,
                'catatan_penyelesaian' => 'Pengecekan busi dan filter bahan bakar.',
            ],
            [
                'vehicle_id' => $b4410 ? $b4410->id : $fallbackVehicle->id,
                'user_id' => $driverSlamet ? $driverSlamet->id : $fallbackUser->id,
                'tanggal' => '2026-08-04',
                'keluhan' => 'Ban depan sebelah kiri tipis/gundul, sangat licin saat hujan.',
                'status' => 'Baru',
                'progress_perbaikan' => 0,
                'diterima_at' => null,
                'diperbaiki_at' => null,
                'selesai_at' => null,
                'catatan_penyelesaian' => null,
            ],
        ];

        foreach ($complaints as $complaint) {
            Complaint::create($complaint);
        }
    }
}
