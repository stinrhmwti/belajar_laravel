<?php

namespace App\Http\Controllers;

use App\Models\DailyChecklist;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DailyChecklistController extends Controller
{
    public function index()
    {
        $checklists = DailyChecklist::with('vehicle')->latest('tanggal')->get();

        return view('checklist.index', compact('checklists'));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('plat_nomor')->get();

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
        $checklist = DailyChecklist::findOrFail($id);
        $checklist->delete();

        return redirect()->route('checklist.index')->with('success', 'Data checklist berhasil dihapus!');
    }
}
