<?php

namespace App\Http\Controllers;

use App\Models\DailyChecklist;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DailyChecklistController extends Controller
{
    public function index()
    {
        $query = DailyChecklist::with('vehicle');

        // Jika peran pengguna adalah 'user' (Driver), batasi hanya kendaraan mereka sendiri
        if (auth()->user()->role === 'user') {
            $query->whereHas('vehicle', function ($q) {
                $q->where('supir_utama', auth()->user()->name);
            });
        }

        $checklists = $query->latest('tanggal')->get();

        return view('checklist.index', compact('checklists'));
    }

    public function create()
    {
        // Jika peran pengguna adalah 'user' (Driver), hanya tampilkan kendaraan milik mereka
        if (auth()->user()->role === 'user') {
            $vehicles = Vehicle::where('supir_utama', auth()->user()->name)->orderBy('plat_nomor')->get();
        } else {
            $vehicles = Vehicle::orderBy('plat_nomor')->get();
        }

        return view('checklist.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal' => 'required|date',
            'nama_teknisi' => 'required|string|max:255',
            'odometer' => 'nullable|integer|min:0',
            'oli_mesin' => 'required|in:OK,Not OK',
            'air_radiator' => 'required|in:OK,Not OK',
            'minyak_rem' => 'required|in:OK,Not OK',
            'ban_rem' => 'required|in:OK,Not OK',
            'lampu_klakson' => 'required|in:OK,Not OK',
            'kebersihan' => 'required|in:OK,Not OK',
            'catatan_tambahan' => 'nullable|string',
        ]);

        // Proteksi tambahan untuk Driver agar tidak memanipulasi vehicle_id ke kendaraan lain
        if (auth()->user()->role === 'user') {
            $vehicle = Vehicle::find($validated['vehicle_id']);
            if (!$vehicle || $vehicle->supir_utama !== auth()->user()->name) {
                return redirect()->back()->withErrors(['vehicle_id' => 'Anda hanya diperbolehkan melakukan checklist untuk kendaraan penugasan Anda sendiri.'])->withInput();
            }
        }

        DailyChecklist::create($validated);

        if (! empty($validated['odometer'])) {
            Vehicle::where('id', $validated['vehicle_id'])
                ->update(['odometer_awal' => $validated['odometer']]);
        }

        return redirect()->route('checklist.index')->with('success', 'Checklist harian berhasil disimpan.');
    }

    public function show(DailyChecklist $checklist)
    {
        return view('checklist.show', compact('checklist'));
    }

    public function destroy($id)
    {
        // Hanya superadmin, admin, dan teknisi yang boleh menghapus checklist
        if (!in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi'])) {
            return redirect()->route('checklist.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus data checklist.');
        }

        $checklist = DailyChecklist::findOrFail($id);
        $checklist->delete();

        return redirect()->route('checklist.index')->with('success', 'Data checklist berhasil dihapus!');
    }

    public function updateOdometer(Request $request, $id)
    {
        $validated = $request->validate([
            'odometer' => 'required|integer|min:0',
        ]);

        $checklist = DailyChecklist::findOrFail($id);
        $checklist->update([
            'odometer' => $validated['odometer']
        ]);

        return redirect()->back()->with('success', 'Angka odometer berhasil diperbarui.');
    }
}
