<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // ===== ADMIN (2) =====
            ['name' => 'Admin Fleet', 'username' => 'admin_fleet', 'email' => 'admin@fleet.com', 'role' => 'admin'],
            ['name' => 'Andi Wijaya', 'username' => 'admin_andi', 'email' => 'andi.admin@fleet.com', 'role' => 'admin'],

            // ===== TEKNISI (3) =====
            ['name' => 'Budi Santoso', 'username' => 'teknisi_budi', 'email' => 'budi.teknisi@fleet.com', 'role' => 'teknisi'],
            ['name' => 'Fajar Nugroho', 'username' => 'teknisi_fajar', 'email' => 'fajar.teknisi@fleet.com', 'role' => 'teknisi'],
            ['name' => 'Rian Saputra', 'username' => 'teknisi_rian', 'email' => 'rian.teknisi@fleet.com', 'role' => 'teknisi'],

            // ===== DRIVER / USER (7) - nama dicocokkan dengan "Supir Utama" di data kendaraan =====
            ['name' => 'Dedi Kurniawan', 'username' => 'driver_dedi', 'email' => 'dedi.driver@fleet.com', 'role' => 'user'],
            ['name' => 'Agus Setiawan', 'username' => 'driver_agus', 'email' => 'agus.driver@fleet.com', 'role' => 'user'],
            ['name' => 'Rudi Hartono', 'username' => 'driver_rudi', 'email' => 'rudi.driver@fleet.com', 'role' => 'user'],
            ['name' => 'Slamet Riyadi', 'username' => 'driver_slamet', 'email' => 'slamet.driver@fleet.com', 'role' => 'user'],
            ['name' => 'Tono Wijaya', 'username' => 'driver_tono', 'email' => 'tono.driver@fleet.com', 'role' => 'user'],
            ['name' => 'Hendra Gunawan', 'username' => 'driver_hendra', 'email' => 'hendra.driver@fleet.com', 'role' => 'user'],
            ['name' => 'Wawan Setiadi', 'username' => 'driver_wawan', 'email' => 'wawan.driver@fleet.com', 'role' => 'user'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'username' => $u['username'],
                    'password' => Hash::make('password'),
                    'role' => $u['role'],
                ]
            );
        }
    }
}
