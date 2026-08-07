<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $b1234 = Vehicle::where('plat_nomor', 'B 1234 KTR')->first();
        $b1112 = Vehicle::where('plat_nomor', 'B 1112 KTR')->first();

        $dataSpesifik = [];

        if ($b1234 && $b1112) {
            $dataSpesifik = [
                [
                    'vehicle_id' => $b1234->id,
                    'tanggal' => '2026-07-06',
                    'jenis_pengeluaran' => 'BBM',
                    'jumlah_biaya' => 200000,
                    'keterangan' => 'Isi bensin full tank',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1112->id,
                    'tanggal' => '2026-07-06',
                    'jenis_pengeluaran' => 'Tol',
                    'jumlah_biaya' => 50000,
                    'keterangan' => 'Biaya tol dalam kota',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1234->id,
                    'tanggal' => '2026-04-06',
                    'jenis_pengeluaran' => 'Bengkel',
                    'jumlah_biaya' => 350000,
                    'keterangan' => 'Ganti oli mesin berkala',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1112->id,
                    'tanggal' => '2026-05-15',
                    'jenis_pengeluaran' => 'Bengkel',
                    'jumlah_biaya' => 450000,
                    'keterangan' => 'Servis rem dan ganti kampas',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1112->id,
                    'tanggal' => '2026-07-06',
                    'jenis_pengeluaran' => 'Parkir',
                    'jumlah_biaya' => 10000,
                    'keterangan' => 'Biaya parkir bongkar muat',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1234->id,
                    'tanggal' => '2026-07-06',
                    'jenis_pengeluaran' => 'BBM',
                    'jumlah_biaya' => 150000,
                    'keterangan' => 'Isi bensin tambahan',
                    'status_approval' => 'Disetujui',
                ],
                // August 2026 specific data
                [
                    'vehicle_id' => $b1234->id,
                    'tanggal' => '2026-08-01',
                    'jenis_pengeluaran' => 'BBM',
                    'jumlah_biaya' => 250000,
                    'keterangan' => 'Isi Pertamax Dex awal bulan',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1112->id,
                    'tanggal' => '2026-08-02',
                    'jenis_pengeluaran' => 'Bengkel',
                    'jumlah_biaya' => 650000,
                    'keterangan' => 'Perbaikan rem dan ganti minyak rem di bengkel resmi',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1234->id,
                    'tanggal' => '2026-08-03',
                    'jenis_pengeluaran' => 'Tol',
                    'jumlah_biaya' => 85000,
                    'keterangan' => 'Biaya tol Trans Jawa',
                    'status_approval' => 'Disetujui',
                ],
                [
                    'vehicle_id' => $b1112->id,
                    'tanggal' => '2026-08-04',
                    'jenis_pengeluaran' => 'BBM',
                    'jumlah_biaya' => 180000,
                    'keterangan' => 'Pengisian BBM rutin mingguan',
                    'status_approval' => 'Menunggu Persetujuan',
                ],
            ];
        }

        $allVehicles = Vehicle::all();
        $dataOtomatis = [];

        foreach ($allVehicles as $index => $vehicle) {
            $sudahPunyaServis = Expense::where('vehicle_id', $vehicle->id)
                ->where('jenis_pengeluaran', 'Bengkel')
                ->exists();

            if (! $sudahPunyaServis) {
                if ($index % 3 === 0) {
                    $tglServis = Carbon::now()->subMonths(4)->toDateString();
                } elseif ($index % 3 === 1) {
                    $tglServis = Carbon::now()->subDays(80)->toDateString();
                } else {
                    $tglServis = Carbon::now()->subDays(15)->toDateString();
                }

                $dataOtomatis[] = [
                    'vehicle_id' => $vehicle->id,
                    'tanggal' => $tglServis,
                    'jenis_pengeluaran' => 'Bengkel',
                    'jumlah_biaya' => 600000,
                    'keterangan' => 'Servis berkala rutin otomatis',
                    'status_approval' => 'Disetujui',
                ];
            }
        }

        $mergedData = array_merge($dataSpesifik, $dataOtomatis);

        foreach ($mergedData as $d) {
            Expense::firstOrCreate(
                [
                    'vehicle_id' => $d['vehicle_id'],
                    'tanggal' => $d['tanggal'],
                    'jenis_pengeluaran' => $d['jenis_pengeluaran'],
                    'jumlah_biaya' => $d['jumlah_biaya'],
                ],
                $d
            );
        }
    }
}
