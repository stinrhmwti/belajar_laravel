<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Preset rute & tujuan realistis regional Jabodetabek - Jawa Barat untuk demonstrasi eksekutif
     */
    protected function getPresetTrips(): array
    {
        return [
            [
                'lokasi_asal' => 'Pool Pusat Gambir Jakarta',
                'origin_lat' => -6.175392,
                'origin_lng' => 106.827153,
                'current_lat' => -6.241500,
                'current_lng' => 106.985000, // Tol Jakarta-Cikampek KM 14
                'lokasi_tujuan' => 'Kawasan Industri MM2100 Cikarang',
                'dest_lat' => -6.301500,
                'dest_lng' => 107.112000,
                'status_perjalanan' => 'Dalam Perjalanan ke Tujuan',
                'kecepatan_kmh' => 64,
                'catatan_perjalanan' => 'Pengiriman 120 box komponen sparepart otomotif',
                'estimasi_menit' => 35,
            ],
            [
                'lokasi_asal' => 'Pool Tanjung Priok',
                'origin_lat' => -6.126588,
                'origin_lng' => 106.905663,
                'current_lat' => -6.262000,
                'current_lng' => 107.125000, // Tol Cikampek Utama
                'lokasi_tujuan' => 'Kawasan Industri KIIC Karawang Barat',
                'dest_lat' => -6.345000,
                'dest_lng' => 107.280000,
                'status_perjalanan' => 'Dalam Perjalanan ke Tujuan',
                'kecepatan_kmh' => 72,
                'catatan_perjalanan' => 'Distribusi kontainer bahan baku ekspor',
                'estimasi_menit' => 45,
            ],
            [
                'lokasi_asal' => 'Pool Sudirman Senayan',
                'origin_lat' => -6.215000,
                'origin_lng' => 106.817000,
                'current_lat' => -6.680000,
                'current_lng' => 107.450000, // Tol Cipularang KM 88
                'lokasi_tujuan' => 'Pool Pasteur Bandung',
                'dest_lat' => -6.892000,
                'dest_lng' => 107.578000,
                'status_perjalanan' => 'Dalam Perjalanan ke Tujuan',
                'kecepatan_kmh' => 78,
                'catatan_perjalanan' => 'Pengantaran dokumen & kargo VIP kantor cabang Bandung',
                'estimasi_menit' => 50,
            ],
            [
                'lokasi_asal' => 'Pool Daan Mogot Jakarta Barat',
                'origin_lat' => -6.155000,
                'origin_lng' => 106.745000,
                'current_lat' => -6.132000,
                'current_lng' => 106.665000, // Akses Tol Bandara Sedyatmo
                'lokasi_tujuan' => 'Terminal Kargo Bandara Soekarno-Hatta',
                'dest_lat' => -6.125000,
                'dest_lng' => 106.655000,
                'status_perjalanan' => 'Proses Bongkar Muat',
                'kecepatan_kmh' => 15,
                'catatan_perjalanan' => 'Serah terima muatan cargo air freight express',
                'estimasi_menit' => 10,
            ],
            [
                'lokasi_asal' => 'Pool Rawamangun Jakarta Timur',
                'origin_lat' => -6.194000,
                'origin_lng' => 106.888000,
                'current_lat' => -6.485000,
                'current_lng' => 106.855000, // Tol Jagorawi KM 32
                'lokasi_tujuan' => 'Sentul City Convention & Warehouse Bogor',
                'dest_lat' => -6.536000,
                'dest_lng' => 106.862000,
                'status_perjalanan' => 'Dalam Perjalanan ke Tujuan',
                'kecepatan_kmh' => 68,
                'catatan_perjalanan' => 'Logistik event display & material promosi',
                'estimasi_menit' => 25,
            ],
            [
                'lokasi_asal' => 'Pool BSD City Serpong',
                'origin_lat' => -6.301000,
                'origin_lng' => 106.652000,
                'current_lat' => -6.210000,
                'current_lng' => 106.450000, // Tol Jakarta - Merak KM 42
                'lokasi_tujuan' => 'Pelabuhan Penyeberangan Merak Cilegon',
                'dest_lat' => -5.932000,
                'dest_lng' => 105.998000,
                'status_perjalanan' => 'Dalam Perjalanan ke Tujuan',
                'kecepatan_kmh' => 80,
                'catatan_perjalanan' => 'Muatan logistik antar pulau Sumatera-Jawa',
                'estimasi_menit' => 85,
            ],
            [
                'lokasi_asal' => 'Pool Soekarno-Hatta Bandung',
                'origin_lat' => -6.938500,
                'origin_lng' => 107.625000,
                'current_lat' => -6.955000,
                'current_lng' => 107.720000, // Rancaekek
                'lokasi_tujuan' => 'Pabrik Tekstil & Garmen Garut',
                'dest_lat' => -7.125000,
                'dest_lng' => 107.870000,
                'status_perjalanan' => 'Dalam Perjalanan ke Tujuan',
                'kecepatan_kmh' => 50,
                'catatan_perjalanan' => 'Distribusi bahan kain dan benang garmen',
                'estimasi_menit' => 55,
            ],
            [
                'lokasi_asal' => 'Pool Summarecon Bekasi',
                'origin_lat' => -6.238000,
                'origin_lng' => 106.992000,
                'current_lat' => -6.185000,
                'current_lng' => 106.940000, // Cakung Cilincing
                'lokasi_tujuan' => 'Terminal Petikemas Tanjung Priok',
                'dest_lat' => -6.105000,
                'dest_lng' => 106.885000,
                'status_perjalanan' => 'Dalam Perjalanan ke Tujuan',
                'kecepatan_kmh' => 45,
                'catatan_perjalanan' => 'Pengantaran kontainer impor elektronik',
                'estimasi_menit' => 28,
            ],
            [
                'lokasi_asal' => 'Pool Margonda Depok',
                'origin_lat' => -6.372000,
                'origin_lng' => 106.832000,
                'current_lat' => -6.372000,
                'current_lng' => 106.832000,
                'lokasi_tujuan' => 'Kawasan Industri Surya Cipta Karawang',
                'dest_lat' => -6.365000,
                'dest_lng' => 107.340000,
                'status_perjalanan' => 'Standby di Pool',
                'kecepatan_kmh' => 0,
                'catatan_perjalanan' => 'Menunggu jadwal muat barang shift sore',
                'estimasi_menit' => 0,
            ],
            [
                'lokasi_asal' => 'Pool Pajajaran Bogor',
                'origin_lat' => -6.595000,
                'origin_lng' => 106.806000,
                'current_lat' => -6.320000,
                'current_lng' => 106.820000, // TB Simatupang
                'lokasi_tujuan' => 'Gudang Distribusi Cilandak Jakarta Selatan',
                'dest_lat' => -6.295000,
                'dest_lng' => 106.805000,
                'status_perjalanan' => 'Perjalanan Kembali ke Pool',
                'kecepatan_kmh' => 58,
                'catatan_perjalanan' => 'Selesai pengantaran paket retail Jabodetabek',
                'estimasi_menit' => 40,
            ],
        ];
    }

    /**
     * Hitung jarak darat realistis antara dua koordinat (Haversine + koreksi jalan darat 1.25x)
     */
    protected function calculateRoadDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // KM
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        // Faktor jalan berliku / jalan tol (rata-rata 1.25x garis lurus)
        return round($distance * 1.25, 1);
    }

    /**
     * Format waktu estimasi tiba yang informatif
     */
    protected function formatEta(?Carbon $etaDate, int $minutesLeft, string $statusPerjalanan): array
    {
        if ($statusPerjalanan === 'Selesai Mengantar' || $statusPerjalanan === 'Selesai') {
            return [
                'text' => 'Telah Tiba di Tujuan (Pengantaran Selesai)',
                'short_text' => 'Selesai Antar',
                'clock_text' => 'Tiba di Lokasi',
                'duration_text' => 'Tuntas',
                'minutes_left' => 0,
                'iso' => null,
                'badge_class' => 'bg-success text-white',
            ];
        }

        if ($statusPerjalanan === 'Standby di Pool' || $minutesLeft <= 0) {
            $statusText = $statusPerjalanan === 'Proses Bongkar Muat' ? 'Sedang di Lokasi (Bongkar Muat)' : 'Standby di Pool';
            $shortText = $statusPerjalanan === 'Proses Bongkar Muat' ? 'Bongkar Muat' : 'Standby';
            return [
                'text' => $statusText,
                'short_text' => $shortText,
                'clock_text' => '-',
                'duration_text' => '-',
                'minutes_left' => 0,
                'iso' => null,
                'badge_class' => 'bg-secondary',
            ];
        }

        $now = Carbon::now();
        $target = $etaDate ?: $now->copy()->addMinutes($minutesLeft);

        $hours = floor($minutesLeft / 60);
        $mins = $minutesLeft % 60;

        $durationText = $hours > 0 ? "{$hours}j {$mins}m" : "{$mins} mnt";
        $clockText = $target->format('H:i') . ' WIB';

        return [
            'text' => "Tiba pkl {$clockText} (± {$durationText})",
            'short_text' => "ETA: {$clockText} ({$durationText})",
            'clock_text' => $clockText,
            'duration_text' => $durationText,
            'minutes_left' => $minutesLeft,
            'iso' => $target->toISOString(),
            'badge_class' => $minutesLeft <= 15 ? 'bg-success' : 'bg-primary',
        ];
    }

    /**
     * Tampilan utama halaman Pelacakan Kendaraan (Live Fleet GPS Tracking)
     */
    public function index(Request $request)
    {
        $query = Vehicle::with(['latestChecklist', 'lastServiceExpense']);

        // Filter pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%")
                  ->orWhere('supir_utama', 'like', "%{$search}%")
                  ->orWhere('lokasi_pool', 'like', "%{$search}%")
                  ->orWhere('lokasi_tujuan', 'like', "%{$search}%");
            });
        }

        // Filter status armada
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter jenis kendaraan
        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $query->where('jenis_kendaraan', 'like', "%{$request->jenis}%");
        }

        $vehicles = $query->orderBy('plat_nomor')->get();
        $presetTrips = $this->getPresetTrips();

        // Format data lengkap untuk Leaflet Map JS & Kartu Armada
        $mapVehicles = $vehicles->map(function ($vehicle, $index) use ($presetTrips) {
            $preset = $presetTrips[$index % count($presetTrips)];

            // Status Armada dasar dari database
            $isServis = ($vehicle->status === 'Sedang Diservis');
            $dbStatusPerjalanan = $vehicle->status_perjalanan;

            // Asal
            $lokasiAsal = $vehicle->lokasi_asal ?: ($vehicle->lokasi_pool ?: $preset['lokasi_asal']);
            $originLat = (float) $preset['origin_lat'];
            $originLng = (float) $preset['origin_lng'];

            if ($isServis) {
                $statusPerjalanan = 'Sedang Diservis';
                $kecepatanKmh = 0;
                $catatanPerjalanan = $vehicle->catatan_perjalanan ?: 'Armada sedang dalam masa perawatan & perbaikan rutin bengkel';
                $lokasiTujuan = 'Bengkel Servis / Workshop';
                $destLat = (float) $originLat;
                $destLng = (float) $originLng;
                $currentLat = $vehicle->latitude ? (float) $vehicle->latitude : (float) $originLat;
                $currentLng = $vehicle->longitude ? (float) $vehicle->longitude : (float) $originLng;
            } elseif ($dbStatusPerjalanan === 'Selesai Mengantar') {
                $statusPerjalanan = 'Selesai Mengantar';
                $kecepatanKmh = 0;
                $lokasiTujuan = $vehicle->lokasi_tujuan ?: ($vehicle->lokasi_asal ?: $preset['lokasi_tujuan']);
                $destLat = $vehicle->tujuan_latitude ? (float) $vehicle->tujuan_latitude : ($vehicle->latitude ? (float) $vehicle->latitude : (float) $preset['dest_lat']);
                $destLng = $vehicle->tujuan_longitude ? (float) $vehicle->tujuan_longitude : ($vehicle->longitude ? (float) $vehicle->longitude : (float) $preset['dest_lng']);
                $currentLat = $destLat;
                $currentLng = $destLng;
                $catatanPerjalanan = $vehicle->catatan_perjalanan ?: "Pengantaran ke {$lokasiTujuan} telah selesai dilaksanakan. Armada siap untuk pengantaran rute berikutnya.";
            } else {
                // Armada Siap Pakai / Sedang Jalan
                $hasCustomTrip = !empty($vehicle->lokasi_tujuan) || !empty($dbStatusPerjalanan);
                $statusPerjalanan = $dbStatusPerjalanan ?: ($hasCustomTrip ? 'Dalam Perjalanan ke Tujuan' : $preset['status_perjalanan']);
                $lokasiTujuan = $vehicle->lokasi_tujuan ?: $preset['lokasi_tujuan'];
                $destLat = $vehicle->tujuan_latitude ? (float) $vehicle->tujuan_latitude : (float) $preset['dest_lat'];
                $destLng = $vehicle->tujuan_longitude ? (float) $vehicle->tujuan_longitude : (float) $preset['dest_lng'];
                $currentLat = $vehicle->latitude ? (float) $vehicle->latitude : (float) $preset['current_lat'];
                $currentLng = $vehicle->longitude ? (float) $vehicle->longitude : (float) $preset['current_lng'];
                $kecepatanKmh = $vehicle->kecepatan_kmh !== null ? (int) $vehicle->kecepatan_kmh : (int) $preset['kecepatan_kmh'];

                // Jika statusnya dalam perjalanan tetapi catatannya masih sisa pesan 'selesai' sebelumnya, sesuaikan catatan
                if ($statusPerjalanan === 'Dalam Perjalanan ke Tujuan' && str_contains($vehicle->catatan_perjalanan ?? '', 'telah selesai dilaksanakan')) {
                    $catatanPerjalanan = "Pengiriman muatan logistik menuju {$lokasiTujuan}";
                } else {
                    $catatanPerjalanan = $vehicle->catatan_perjalanan ?: $preset['catatan_perjalanan'];
                }
            }

            // Hitung Jarak & ETA
            if ($statusPerjalanan === 'Selesai Mengantar') {
                $jarakTotalKm = $this->calculateRoadDistance($originLat, $originLng, $destLat, $destLng);
                $jarakSisaKm = 0;
                $progressPercent = 100;
                $minutesLeft = 0;
            } elseif ($isServis) {
                $jarakTotalKm = 0;
                $jarakSisaKm = 0;
                $progressPercent = 0;
                $minutesLeft = 0;
            } else {
                $jarakTotalKm = $this->calculateRoadDistance($originLat, $originLng, $destLat, $destLng);
                $jarakSisaKm = $this->calculateRoadDistance($currentLat, $currentLng, $destLat, $destLng);
                $speed = max($kecepatanKmh, 35);
                $minutesLeft = (int) round(($jarakSisaKm / $speed) * 60);
                if ($statusPerjalanan === 'Standby di Pool') {
                    $minutesLeft = 0;
                    $progressPercent = 0;
                } else {
                    if ($minutesLeft < 3) $minutesLeft = 5;
                    $progressPercent = $jarakTotalKm > 0 ? min(100, max(0, round((($jarakTotalKm - $jarakSisaKm) / $jarakTotalKm) * 100))) : 0;
                }
                if ($statusPerjalanan === 'Proses Bongkar Muat') $progressPercent = 100;
            }

            $etaDate = ($statusPerjalanan !== 'Selesai Mengantar' && $vehicle->estimasi_tiba) ? Carbon::parse($vehicle->estimasi_tiba) : now()->addMinutes($minutesLeft);
            $etaInfo = $this->formatEta($etaDate, $minutesLeft, $statusPerjalanan);

            // Status KIR & Servis
            $statusKir = $vehicle->status_kir;
            $statusServis = $vehicle->status_servis_berkala;

            // Marker visual category
            $markerType = 'marker-ready';
            if ($isServis || $statusPerjalanan === 'Sedang Diservis' || $statusPerjalanan === 'Perawatan/Servis') {
                $markerType = 'marker-servis';
            } elseif ($statusKir === 'merah' || $statusServis === 'merah') {
                $markerType = 'marker-warning';
            } elseif ($statusKir === 'kuning' || $statusServis === 'kuning') {
                $markerType = 'marker-servis';
            }

            // Icon Bootstrap
            $iconClass = 'bi-car-front-fill';
            $jenisLower = strtolower($vehicle->jenis_kendaraan ?? '');
            if (str_contains($jenisLower, 'boks') || str_contains($jenisLower, 'box') || str_contains($jenisLower, 'truk') || str_contains($jenisLower, 'truck')) {
                $iconClass = 'bi-truck';
            } elseif (str_contains($jenisLower, 'pick up') || str_contains($jenisLower, 'pickup')) {
                $iconClass = 'bi-truck-flatbed';
            } elseif (str_contains($jenisLower, 'motor')) {
                $iconClass = 'bi-bicycle';
            }

            return [
                'id' => $vehicle->id,
                'plat_nomor' => $vehicle->plat_nomor,
                'merek' => $vehicle->merek,
                'tipe' => $vehicle->tipe,
                'jenis_kendaraan' => $vehicle->jenis_kendaraan,
                'status' => $vehicle->status,
                'supir_utama' => $vehicle->supir_utama ?: 'Belum Ditugaskan',
                'lokasi_pool' => $vehicle->lokasi_pool ?: $lokasiAsal,

                // Trip Manifest & Route Telemetry
                'lokasi_asal' => $lokasiAsal,
                'origin_lat' => $originLat,
                'origin_lng' => $originLng,

                'latitude' => $currentLat,
                'longitude' => $currentLng,

                'lokasi_tujuan' => $lokasiTujuan,
                'dest_lat' => $destLat,
                'dest_lng' => $destLng,

                'status_perjalanan' => $statusPerjalanan,
                'kecepatan_kmh' => $kecepatanKmh,
                'catatan_perjalanan' => $catatanPerjalanan,

                'jarak_total_km' => $jarakTotalKm,
                'jarak_sisa_km' => $jarakSisaKm,
                'progress_percent' => $progressPercent,

                'eta_text' => $etaInfo['text'] ?? '-',
                'eta_short' => $etaInfo['short_text'] ?? ($isServis ? 'Servis' : 'Standby'),
                'eta_clock' => $etaInfo['clock_text'] ?? '-',
                'eta_duration' => $etaInfo['duration_text'] ?? '-',
                'eta_minutes_left' => $etaInfo['minutes_left'] ?? 0,
                'eta_iso' => $etaInfo['iso'] ?? null,
                'eta_badge_class' => $etaInfo['badge_class'] ?? ($isServis ? 'bg-warning text-dark' : 'bg-secondary'),

                'odometer' => (int) $vehicle->odometer_terkini,
                'status_kir' => $statusKir,
                'status_servis' => $statusServis,
                'has_real_gps' => !empty($vehicle->latitude) && !empty($vehicle->longitude),
                'foto_url' => $vehicle->foto_url,
                'marker_type' => $markerType,
                'icon_class' => $iconClass,
                'updated_at_formatted' => $vehicle->updated_at ? $vehicle->updated_at->diffForHumans() : 'Baru saja',
                'detail_url' => route('vehicles.show', $vehicle->id),
            ];
        });

        // Hitung statistik ringkasan eksekutif yang akurat sesuai data armada
        $stats = [
            'total' => $vehicles->count(),
            'siap_pakai' => $vehicles->where('status', 'Siap Pakai')->count(),
            'sedang_diservis' => $vehicles->where('status', 'Sedang Diservis')->count(),
            'on_trip' => $mapVehicles->where('status_perjalanan', 'Dalam Perjalanan ke Tujuan')->count(),
            'standby' => $mapVehicles->where('status_perjalanan', 'Standby di Pool')->count(),
            'peringatan' => $mapVehicles->where('marker_type', 'marker-warning')->count(),
            'avg_speed' => (int) round($mapVehicles->where('kecepatan_kmh', '>', 0)->avg('kecepatan_kmh') ?: 0),
        ];

        $selectedVehicleId = $request->query('vehicle_id');

        return view('tracking.index', compact('vehicles', 'mapVehicles', 'stats', 'selectedVehicleId'));
    }

    /**
     * Endpoint API JSON untuk polling / real-time updates data koordinat & rute armada
     */
    public function apiVehicles(Request $request)
    {
        $vehicles = Vehicle::with(['latestChecklist', 'lastServiceExpense'])->orderBy('plat_nomor')->get();
        $presetTrips = $this->getPresetTrips();

        $data = $vehicles->map(function ($vehicle, $index) use ($presetTrips) {
            $preset = $presetTrips[$index % count($presetTrips)];
            $isServis = ($vehicle->status === 'Sedang Diservis');
            $dbStatusPerjalanan = $vehicle->status_perjalanan;

            $lokasiAsal = $vehicle->lokasi_asal ?: ($vehicle->lokasi_pool ?: $preset['lokasi_asal']);
            $originLat = (float) $preset['origin_lat'];
            $originLng = (float) $preset['origin_lng'];

            if ($isServis) {
                $statusPerjalanan = 'Sedang Diservis';
                $kecepatanKmh = 0;
                $catatanPerjalanan = $vehicle->catatan_perjalanan ?: 'Armada sedang dalam masa perawatan & perbaikan rutin bengkel';
                $lokasiTujuan = 'Bengkel Servis / Workshop';
                $destLat = (float) $originLat;
                $destLng = (float) $originLng;
                $currentLat = $vehicle->latitude ? (float) $vehicle->latitude : (float) $originLat;
                $currentLng = $vehicle->longitude ? (float) $vehicle->longitude : (float) $originLng;
            } elseif ($dbStatusPerjalanan === 'Selesai Mengantar') {
                $statusPerjalanan = 'Selesai Mengantar';
                $kecepatanKmh = 0;
                $lokasiTujuan = $vehicle->lokasi_tujuan ?: ($vehicle->lokasi_asal ?: $preset['lokasi_tujuan']);
                $destLat = $vehicle->tujuan_latitude ? (float) $vehicle->tujuan_latitude : ($vehicle->latitude ? (float) $vehicle->latitude : (float) $preset['dest_lat']);
                $destLng = $vehicle->tujuan_longitude ? (float) $vehicle->tujuan_longitude : ($vehicle->longitude ? (float) $vehicle->longitude : (float) $preset['dest_lng']);
                $currentLat = $destLat;
                $currentLng = $destLng;
                $catatanPerjalanan = $vehicle->catatan_perjalanan ?: "Pengantaran ke {$lokasiTujuan} telah selesai dilaksanakan. Armada siap untuk pengantaran rute berikutnya.";
            } else {
                $hasCustomTrip = !empty($vehicle->lokasi_tujuan) || !empty($dbStatusPerjalanan);
                $statusPerjalanan = $dbStatusPerjalanan ?: ($hasCustomTrip ? 'Dalam Perjalanan ke Tujuan' : $preset['status_perjalanan']);
                $lokasiTujuan = $vehicle->lokasi_tujuan ?: $preset['lokasi_tujuan'];
                $destLat = $vehicle->tujuan_latitude ? (float) $vehicle->tujuan_latitude : (float) $preset['dest_lat'];
                $destLng = $vehicle->tujuan_longitude ? (float) $vehicle->tujuan_longitude : (float) $preset['dest_lng'];
                $currentLat = $vehicle->latitude ? (float) $vehicle->latitude : (float) $preset['current_lat'];
                $currentLng = $vehicle->longitude ? (float) $vehicle->longitude : (float) $preset['current_lng'];
                $kecepatanKmh = $vehicle->kecepatan_kmh !== null ? (int) $vehicle->kecepatan_kmh : (int) $preset['kecepatan_kmh'];

                if ($statusPerjalanan === 'Dalam Perjalanan ke Tujuan' && str_contains($vehicle->catatan_perjalanan ?? '', 'telah selesai dilaksanakan')) {
                    $catatanPerjalanan = "Pengiriman muatan logistik menuju {$lokasiTujuan}";
                } else {
                    $catatanPerjalanan = $vehicle->catatan_perjalanan ?: $preset['catatan_perjalanan'];
                }
            }

            if ($statusPerjalanan === 'Selesai Mengantar') {
                $jarakTotalKm = $this->calculateRoadDistance($originLat, $originLng, $destLat, $destLng);
                $jarakSisaKm = 0;
                $progressPercent = 100;
                $minutesLeft = 0;
            } elseif ($isServis) {
                $jarakTotalKm = 0;
                $jarakSisaKm = 0;
                $progressPercent = 0;
                $minutesLeft = 0;
            } else {
                $jarakTotalKm = $this->calculateRoadDistance($originLat, $originLng, $destLat, $destLng);
                $jarakSisaKm = $this->calculateRoadDistance($currentLat, $currentLng, $destLat, $destLng);
                $speed = max($kecepatanKmh, 35);
                $minutesLeft = (int) round(($jarakSisaKm / $speed) * 60);
                if ($statusPerjalanan === 'Standby di Pool') {
                    $minutesLeft = 0;
                    $progressPercent = 0;
                } else {
                    if ($minutesLeft < 3) $minutesLeft = 5;
                    $progressPercent = $jarakTotalKm > 0 ? min(100, max(0, round((($jarakTotalKm - $jarakSisaKm) / $jarakTotalKm) * 100))) : 0;
                }
                if ($statusPerjalanan === 'Proses Bongkar Muat') $progressPercent = 100;
            }

            $etaDate = ($statusPerjalanan !== 'Selesai Mengantar' && $vehicle->estimasi_tiba) ? Carbon::parse($vehicle->estimasi_tiba) : now()->addMinutes($minutesLeft);
            $etaInfo = $this->formatEta($etaDate, $minutesLeft, $statusPerjalanan);

            $statusKir = $vehicle->status_kir;
            $statusServis = $vehicle->status_servis_berkala;

            $markerType = 'marker-ready';
            if ($isServis || $statusPerjalanan === 'Sedang Diservis' || $statusPerjalanan === 'Perawatan/Servis') {
                $markerType = 'marker-servis';
            } elseif ($statusKir === 'merah' || $statusServis === 'merah') {
                $markerType = 'marker-warning';
            } elseif ($statusKir === 'kuning' || $statusServis === 'kuning') {
                $markerType = 'marker-servis';
            }

            $iconClass = 'bi-car-front-fill';
            $jenisLower = strtolower($vehicle->jenis_kendaraan ?? '');
            if (str_contains($jenisLower, 'boks') || str_contains($jenisLower, 'box') || str_contains($jenisLower, 'truk') || str_contains($jenisLower, 'truck')) {
                $iconClass = 'bi-truck';
            } elseif (str_contains($jenisLower, 'pick up') || str_contains($jenisLower, 'pickup')) {
                $iconClass = 'bi-truck-flatbed';
            } elseif (str_contains($jenisLower, 'motor')) {
                $iconClass = 'bi-bicycle';
            }

            return [
                'id' => $vehicle->id,
                'plat_nomor' => $vehicle->plat_nomor,
                'merek' => $vehicle->merek,
                'tipe' => $vehicle->tipe,
                'status' => $vehicle->status,
                'supir_utama' => $vehicle->supir_utama ?: 'Belum Ditugaskan',
                'lokasi_pool' => $vehicle->lokasi_pool ?: $lokasiAsal,

                'lokasi_asal' => $lokasiAsal,
                'origin_lat' => $originLat,
                'origin_lng' => $originLng,

                'latitude' => $currentLat,
                'longitude' => $currentLng,

                'lokasi_tujuan' => $lokasiTujuan,
                'dest_lat' => $destLat,
                'dest_lng' => $destLng,

                'status_perjalanan' => $statusPerjalanan,
                'kecepatan_kmh' => $kecepatanKmh,
                'catatan_perjalanan' => $catatanPerjalanan,

                'jarak_total_km' => $jarakTotalKm,
                'jarak_sisa_km' => $jarakSisaKm,
                'progress_percent' => $progressPercent,

                'eta_text' => $etaInfo['text'] ?? '-',
                'eta_short' => $etaInfo['short_text'] ?? ($isServis ? 'Servis' : 'Standby'),
                'eta_clock' => $etaInfo['clock_text'] ?? '-',
                'eta_duration' => $etaInfo['duration_text'] ?? '-',
                'eta_minutes_left' => $etaInfo['minutes_left'] ?? 0,
                'eta_iso' => $etaInfo['iso'] ?? null,
                'eta_badge_class' => $etaInfo['badge_class'] ?? ($isServis ? 'bg-warning text-dark' : 'bg-secondary'),

                'odometer' => (int) $vehicle->odometer_terkini,
                'marker_type' => $markerType,
                'icon_class' => $iconClass,
                'foto_url' => $vehicle->foto_url,
                'updated_at_formatted' => $vehicle->updated_at ? $vehicle->updated_at->diffForHumans() : 'Baru saja',
                'detail_url' => route('vehicles.show', $vehicle->id),
            ];
        });

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->toISOString(),
            'total' => $data->count(),
            'vehicles' => $data,
        ]);
    }

    /**
     * Menugaskan rute perjalanan & lokasi tujuan baru untuk armada (Trip Dispatcher)
     */
    public function assignTrip(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'lokasi_asal' => 'nullable|string|max:255',
            'lokasi_tujuan' => 'required|string|max:255',
            'tujuan_latitude' => 'required|numeric|between:-90,90',
            'tujuan_longitude' => 'required|numeric|between:-180,180',
            'status_perjalanan' => 'required|string|max:100',
            'kecepatan_kmh' => 'nullable|integer|min:0|max:160',
            'catatan_perjalanan' => 'nullable|string|max:1000',
            'estimasi_menit' => 'nullable|integer|min:0',
        ]);

        $currentLat = $vehicle->latitude ?: -6.175392;
        $currentLng = $vehicle->longitude ?: 106.827153;
        $destLat = (float) $validated['tujuan_latitude'];
        $destLng = (float) $validated['tujuan_longitude'];

        // Hitung jarak sisa
        $jarakSisaKm = $this->calculateRoadDistance($currentLat, $currentLng, $destLat, $destLng);

        // Kecepatan & ETA
        $speed = !empty($validated['kecepatan_kmh']) ? (int) $validated['kecepatan_kmh'] : 55;
        $minutesLeft = !empty($validated['estimasi_menit'])
            ? (int) $validated['estimasi_menit']
            : (int) max(5, round(($jarakSisaKm / max($speed, 30)) * 60));

        $estimasiTiba = now()->addMinutes($minutesLeft);

        $updateData = [
            'lokasi_tujuan' => $validated['lokasi_tujuan'],
            'tujuan_latitude' => $destLat,
            'tujuan_longitude' => $destLng,
            'status_perjalanan' => $validated['status_perjalanan'],
            'kecepatan_kmh' => $speed,
            'jarak_sisa_km' => $jarakSisaKm,
            'estimasi_tiba' => $estimasiTiba,
            'catatan_perjalanan' => !empty($validated['catatan_perjalanan']) ? $validated['catatan_perjalanan'] : "Pengiriman muatan logistik menuju {$validated['lokasi_tujuan']}",
        ];

        if (!empty($validated['lokasi_asal'])) {
            $updateData['lokasi_asal'] = $validated['lokasi_asal'];
        }

        $vehicle->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Rute perjalanan menuju {$vehicle->lokasi_tujuan} berhasil ditugaskan ke armada {$vehicle->plat_nomor}.",
                'vehicle' => $vehicle->fresh(),
                'eta_text' => "Tiba pkl " . $estimasiTiba->format('H:i') . " WIB (± {$minutesLeft} menit)",
                'jarak_sisa_km' => $jarakSisaKm,
            ]);
        }

        return redirect()->back()->with('success', "Rute perjalanan menuju {$vehicle->lokasi_tujuan} berhasil ditugaskan.");
    }

    /**
     * Menandai pengantaran armada telah selesai tiba di alamat tujuan (Complete Delivery Trip)
     */
    public function completeTrip(Request $request, Vehicle $vehicle)
    {
        $presetTrips = $this->getPresetTrips();
        $preset = $presetTrips[($vehicle->id - 1) % count($presetTrips)] ?? $presetTrips[0];

        // Koordinat titik tujuan akhir pengantaran
        $destLat = $vehicle->tujuan_latitude ? (float)$vehicle->tujuan_latitude : ($vehicle->latitude ? (float)$vehicle->latitude : (float)$preset['dest_lat']);
        $destLng = $vehicle->tujuan_longitude ? (float)$vehicle->tujuan_longitude : ($vehicle->longitude ? (float)$vehicle->longitude : (float)$preset['dest_lng']);
        $destLocation = $vehicle->lokasi_tujuan ?: ($vehicle->lokasi_pool ?: $preset['lokasi_tujuan']);
        $originLocation = $vehicle->lokasi_asal ?: ($vehicle->lokasi_pool ?: $preset['lokasi_asal']);

        $updateData = [
            // Mobil kini berada di titik pengantaran (Dropoff point)
            'latitude' => $destLat,
            'longitude' => $destLng,
            'lokasi_asal' => $originLocation,
            'lokasi_tujuan' => $destLocation,
            'tujuan_latitude' => $destLat,
            'tujuan_longitude' => $destLng,
            'lokasi_pool' => $destLocation,
            'status_perjalanan' => 'Selesai Mengantar',
            'kecepatan_kmh' => 0,
            'jarak_sisa_km' => 0,
            'estimasi_tiba' => null,
            'catatan_perjalanan' => $request->input('catatan') ?: "Pengantaran ke {$destLocation} telah selesai dilaksanakan. Armada siap untuk pengantaran rute berikutnya.",
        ];

        $vehicle->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Pengantaran armada {$vehicle->plat_nomor} ke {$destLocation} telah ditandai SELESAI. Armada kini berada di {$destLocation} dan siap ditugaskan ke tujuan berikutnya.",
                'vehicle' => $vehicle->fresh(),
                'current_location' => $destLocation,
                'latitude' => $destLat,
                'longitude' => $destLng,
            ]);
        }

        return redirect()->back()->with('success', "Pengantaran armada {$vehicle->plat_nomor} ke {$destLocation} telah selesai.");
    }

    /**
     * Memperbarui koordinat GPS lokasi kendaraan (dapat dipicu dari GPS HP pengemudi atau admin)
     */
    public function updateLocation(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'lokasi_pool' => 'nullable|string|max:255',
            'kecepatan_kmh' => 'nullable|integer|min:0|max:160',
            'status_perjalanan' => 'nullable|string|max:100',
        ]);

        $updateData = [
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ];

        if (!empty($validated['lokasi_pool'])) {
            $updateData['lokasi_pool'] = $validated['lokasi_pool'];
        }
        if (isset($validated['kecepatan_kmh'])) {
            $updateData['kecepatan_kmh'] = (int) $validated['kecepatan_kmh'];
        }
        if (!empty($validated['status_perjalanan'])) {
            $updateData['status_perjalanan'] = $validated['status_perjalanan'];
        }

        // Jika ada tujuan, hitung ulang sisa jarak & ETA
        if ($vehicle->tujuan_latitude && $vehicle->tujuan_longitude) {
            $jarakSisaKm = $this->calculateRoadDistance((float)$validated['latitude'], (float)$validated['longitude'], (float)$vehicle->tujuan_latitude, (float)$vehicle->tujuan_longitude);
            $speed = max((int)($validated['kecepatan_kmh'] ?? $vehicle->kecepatan_kmh), 35);
            $minutesLeft = (int) max(3, round(($jarakSisaKm / $speed) * 60));
            $updateData['jarak_sisa_km'] = $jarakSisaKm;
            $updateData['estimasi_tiba'] = now()->addMinutes($minutesLeft);
        }

        $vehicle->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Lokasi GPS kendaraan {$vehicle->plat_nomor} berhasil diperbarui.",
                'latitude' => (float) $vehicle->latitude,
                'longitude' => (float) $vehicle->longitude,
                'lokasi_pool' => $vehicle->lokasi_pool,
                'kecepatan_kmh' => $vehicle->kecepatan_kmh,
                'jarak_sisa_km' => $vehicle->jarak_sisa_km,
                'status_perjalanan' => $vehicle->status_perjalanan,
            ]);
        }

        return redirect()->back()->with('success', "Lokasi GPS kendaraan {$vehicle->plat_nomor} berhasil diperbarui.");
    }
}
