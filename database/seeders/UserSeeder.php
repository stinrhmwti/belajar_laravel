<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminNumber = config('services.whatsapp.admin_number', '087738565383');

        $users = [
            // ===== ADMIN =====
            ['name' => 'Admin Fleet', 'username' => 'admin_fleet', 'email' => 'admin@fleet.com', 'role' => 'admin', 'no_wa' => $adminNumber, 'no_telepon' => $adminNumber],
            ['name' => 'Andi Wijaya', 'username' => 'admin_andi', 'email' => 'andi.admin@fleet.com', 'role' => 'admin', 'no_wa' => '081298765432', 'no_telepon' => '081298765432'],
            ['name' => 'Siti Rahmawati', 'username' => 'siti_admin', 'email' => 'sitirahmawat083@gmail.com', 'role' => 'admin', 'no_wa' => $adminNumber, 'no_telepon' => $adminNumber],
            ['name' => 'Siti Rahmawati', 'username' => 'sitirahmawati', 'email' => 'sitirahmawati083@gmail.com', 'role' => 'admin', 'no_wa' => $adminNumber, 'no_telepon' => $adminNumber],


            // ===== TEKNISI (4) =====
            ['name' => 'Budi Santoso', 'username' => 'teknisi_budi', 'email' => 'budi.teknisi@fleet.com', 'role' => 'teknisi', 'no_wa' => '081234567801', 'no_telepon' => '081234567801'],
            ['name' => 'Fajar Nugroho', 'username' => 'teknisi_fajar', 'email' => 'fajar.teknisi@fleet.com', 'role' => 'teknisi', 'no_wa' => '081234567802', 'no_telepon' => '081234567802'],
            ['name' => 'Rian Saputra', 'username' => 'teknisi_rian', 'email' => 'rian.teknisi@fleet.com', 'role' => 'teknisi', 'no_wa' => '081234567803', 'no_telepon' => '081234567803'],
            ['name' => 'Teknisi Utama', 'username' => 'teknisi_utama', 'email' => 'teknisi@fleet.com', 'role' => 'teknisi', 'no_wa' => '081234567804', 'no_telepon' => '081234567804'],

            // ===== DRIVER / USER (7) - nama dicocokkan dengan "Supir Utama" di data kendaraan =====
            ['name' => 'Dedi Kurniawan', 'username' => 'driver_dedi', 'email' => 'dedi.driver@fleet.com', 'role' => 'user', 'no_wa' => '085712345601', 'no_telepon' => '085712345601'],
            ['name' => 'Agus Setiawan', 'username' => 'driver_agus', 'email' => 'agus.driver@fleet.com', 'role' => 'user', 'no_wa' => '085712345602', 'no_telepon' => '085712345602'],
            ['name' => 'Rudi Hartono', 'username' => 'driver_rudi', 'email' => 'rudi.driver@fleet.com', 'role' => 'user', 'no_wa' => '085712345603', 'no_telepon' => '085712345603'],
            ['name' => 'Slamet Riyadi', 'username' => 'driver_slamet', 'email' => 'slamet.driver@fleet.com', 'role' => 'user', 'no_wa' => '085712345604', 'no_telepon' => '085712345604'],
            ['name' => 'Tono Wijaya', 'username' => 'driver_tono', 'email' => 'tono.driver@fleet.com', 'role' => 'user', 'no_wa' => '085712345605', 'no_telepon' => '085712345605'],
            ['name' => 'Hendra Gunawan', 'username' => 'driver_hendra', 'email' => 'hendra.driver@fleet.com', 'role' => 'user', 'no_wa' => '085712345606', 'no_telepon' => '085712345606'],
            ['name' => 'Wawan Setiadi', 'username' => 'driver_wawan', 'email' => 'wawan.driver@fleet.com', 'role' => 'user', 'no_wa' => '085712345607', 'no_telepon' => '085712345607'],
            ['name' => 'Driver Utama', 'username' => 'driver_utama', 'email' => 'user@fleet.com', 'role' => 'user', 'no_wa' => '085712345608', 'no_telepon' => '085712345608'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'username' => $u['username'],
                    'password' => Hash::make('password'),
                    'role' => $u['role'],
                    'no_wa' => $u['no_wa'],
                    'no_telepon' => $u['no_telepon'],
                ]
            );
        }
    }
}
