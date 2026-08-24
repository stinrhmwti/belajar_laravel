<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VehicleExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty()) {
            return;
        }

        foreach ($vehicles as $index => $vehicle) {
            if ($index % 3 === 0) {
                $tanggalServis = Carbon::now()->subMonths(4); // Sudah lewat (Merah)
            } elseif ($index % 3 === 1) {
                $tanggalServis = Carbon::now()->subDays(80);  // Mendekati (Kuning)
            } else {
                $tanggalServis = Carbon::now()->subDays(10);  // Aman (Hijau)
            }

            Expense::create([
                'vehicle_id' => $vehicle->id,
                'tanggal' => $tanggalServis->toDateString(),
                'jenis_pengeluaran' => 'Bengkel',
                'keterangan' => 'Servis berkala rutin (ganti oli & filter)',
                'jumlah_biaya' => 750000,
                'status_approval' => 'Disetujui',
            ]);
        }
    }
}
