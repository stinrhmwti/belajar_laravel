<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil seluruh Seeder secara otomatis
        $this->call([
            UserSeeder::class,
            VehicleSeeder::class,
            VehicleExpenseSeeder::class,
            DailyChecklistSeeder::class,
            ExpenseSeeder::class,
            ComplaintSeeder::class,
        ]);
    }
}
