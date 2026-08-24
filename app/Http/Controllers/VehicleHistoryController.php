<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleHistory;
use App\Models\User;
use Illuminate\Http\Request;

class VehicleHistoryController extends Controller
{
    public function index()
    {
        $histories = VehicleHistory::with(['vehicle', 'teknisi'])
            ->orderByDesc('tanggal')
            ->get();

        return view('vehicle_histories.index', compact('histories'));
    }

    public function create(Request $request)
    {
        $vehicles = Vehicle::orderBy('plat_nomor')->get();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();
        $selectedVehicleId = $request->query('vehicle_id');

        return view('vehicle_histories.create', compact('vehicles', 'technicians', 'selectedVehicleId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal' => 'required|date',
            'teknisi_id' => 'nullable|exists:users,id',
            'jenis_pekerjaan' => 'required|string|max:255',
            'sparepart_digunakan' => 'nullable|string|max:500',
            'biaya' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        VehicleHistory::create($validated);

        return redirect()->route('vehicle-histories.index')->with('success', 'Riwayat servis berhasil ditambahkan.');
    }

    public function edit(VehicleHistory $vehicleHistory)
    {
        $vehicles = Vehicle::orderBy('plat_nomor')->get();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();

        return view('vehicle_histories.edit', compact('vehicleHistory', 'vehicles', 'technicians'));
    }

    public function update(Request $request, VehicleHistory $vehicleHistory)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal' => 'required|date',
            'teknisi_id' => 'nullable|exists:users,id',
            'jenis_pekerjaan' => 'required|string|max:255',
            'sparepart_digunakan' => 'nullable|string|max:500',
            'biaya' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $vehicleHistory->update($validated);

        return redirect()->route('vehicle-histories.index')->with('success', 'Riwayat servis berhasil diperbarui.');
    }

    public function destroy(VehicleHistory $vehicleHistory)
    {
        $vehicleHistory->delete();

        return redirect()->route('vehicle-histories.index')->with('success', 'Riwayat servis berhasil dihapus.');
    }
}
