<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Tampilan utama halaman Pelacakan Kendaraan (Live Fleet GPS Tracking)
     */
    public function index(Request $request)
    {
        $query = Vehicle::with(['latestChecklist', 'lastServiceExpense']);

        // Filter berdasarkan pencarian kata kunci (plat nomor, supir, merk/tipe, pool)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%")
                  ->orWhere('supir_utama', 'like', "%{$search}%")
                  ->orWhere('lokasi_pool', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis kendaraan
        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $query->where('jenis_kendaraan', 'like', "%{$request->jenis}%");
        }

        $vehicles = $query->orderBy('plat_nomor')->get();

        // Default koordinat pool Jakarta jika latitude/longitude kosong
        $defaultCoords = [
            [-6.208763, 106.845599], // Jakarta Pusat
            [-6.175392, 106.827153], // Monas
            [-6.244431, 106.800635], // Blok M
            [-6.126588, 106.905663], // Tj Priok
            [-6.300641, 106.814095], // Ragunan
            [-6.201720, 106.782155], // Palmerah
            [-6.258882, 106.852443], // Cawang
            [-6.195301, 106.822301], // Sudirman
        ];

        // Format data kendaraan untuk Leaflet Map JS
        $mapVehicles = $vehicles->map(function ($vehicle, $index) use ($defaultCoords) {
            $lat = $vehicle->latitude;
            $lng = $vehicle->longitude;

            // Fallback jika belum ada koordinat
            if (empty($lat) || empty($lng) || $lat == 0 || $lng == 0) {
                $coord = $defaultCoords[$index % count($defaultCoords)];
                $lat = $coord[0];
                $lng = $coord[1];
            }

            // Tentukan status servis & KIR
            $statusKir = $vehicle->status_kir;
            $statusServis = $vehicle->status_servis_berkala;

            // Marker visual category: 'ready' (hijau), 'servis' (kuning), 'warning' (merah)
            $markerType = 'marker-ready';
            if ($vehicle->status === 'Sedang Diservis') {
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
                'lokasi_pool' => $vehicle->lokasi_pool ?: 'Pool Pusat',
                'odometer' => (int) $vehicle->odometer_terkini,
                'status_kir' => $statusKir,
                'status_servis' => $statusServis,
                'latitude' => (float) $lat,
                'longitude' => (float) $lng,
                'has_real_gps' => !empty($vehicle->latitude) && !empty($vehicle->longitude),
                'foto_url' => $vehicle->foto_url,
                'marker_type' => $markerType,
                'icon_class' => $iconClass,
                'updated_at_formatted' => $vehicle->updated_at ? $vehicle->updated_at->diffForHumans() : '-',
                'detail_url' => route('vehicles.show', $vehicle->id),
            ];
        });

        // Hitung statistik ringkasan
        $stats = [
            'total' => $vehicles->count(),
            'siap_pakai' => $vehicles->where('status', 'Siap Pakai')->count(),
            'sedang_diservis' => $vehicles->where('status', 'Sedang Diservis')->count(),
            'peringatan' => $mapVehicles->where('marker_type', 'marker-warning')->count(),
        ];

        $selectedVehicleId = $request->query('vehicle_id');

        return view('tracking.index', compact('vehicles', 'mapVehicles', 'stats', 'selectedVehicleId'));
    }

    /**
     * Endpoint API JSON untuk polling / real-time updates data koordinat armada
     */
    public function apiVehicles(Request $request)
    {
        $vehicles = Vehicle::with(['latestChecklist', 'lastServiceExpense'])->orderBy('plat_nomor')->get();

        $defaultCoords = [
            [-6.208763, 106.845599],
            [-6.175392, 106.827153],
            [-6.244431, 106.800635],
            [-6.126588, 106.905663],
            [-6.300641, 106.814095],
            [-6.201720, 106.782155],
            [-6.258882, 106.852443],
            [-6.195301, 106.822301],
        ];

        $data = $vehicles->map(function ($vehicle, $index) use ($defaultCoords) {
            $lat = $vehicle->latitude;
            $lng = $vehicle->longitude;

            if (empty($lat) || empty($lng) || $lat == 0 || $lng == 0) {
                $coord = $defaultCoords[$index % count($defaultCoords)];
                $lat = $coord[0];
                $lng = $coord[1];
            }

            $statusKir = $vehicle->status_kir;
            $statusServis = $vehicle->status_servis_berkala;

            $markerType = 'marker-ready';
            if ($vehicle->status === 'Sedang Diservis') {
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
                'lokasi_pool' => $vehicle->lokasi_pool ?: 'Pool Pusat',
                'odometer' => (int) $vehicle->odometer_terkini,
                'latitude' => (float) $lat,
                'longitude' => (float) $lng,
                'has_real_gps' => !empty($vehicle->latitude) && !empty($vehicle->longitude),
                'foto_url' => $vehicle->foto_url,
                'marker_type' => $markerType,
                'icon_class' => $iconClass,
                'updated_at_formatted' => $vehicle->updated_at ? $vehicle->updated_at->diffForHumans() : '-',
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
     * Memperbarui koordinat GPS lokasi kendaraan (dapat dipicu dari GPS HP pengemudi atau admin)
     */
    public function updateLocation(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'lokasi_pool' => 'nullable|string|max:255',
        ]);

        $updateData = [
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ];

        if (!empty($validated['lokasi_pool'])) {
            $updateData['lokasi_pool'] = $validated['lokasi_pool'];
        }

        $vehicle->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Lokasi GPS kendaraan {$vehicle->plat_nomor} berhasil diperbarui.",
                'latitude' => (float) $vehicle->latitude,
                'longitude' => (float) $vehicle->longitude,
                'lokasi_pool' => $vehicle->lokasi_pool,
            ]);
        }

        return redirect()->back()->with('success', "Lokasi GPS kendaraan {$vehicle->plat_nomor} berhasil diperbarui.");
    }
}
