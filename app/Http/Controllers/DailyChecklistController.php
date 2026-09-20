<?php

namespace App\Http\Controllers;

use App\Models\DailyChecklist;
use App\Models\Vehicle;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class DailyChecklistController extends Controller
{
    public function index()
    {
        $query = DailyChecklist::with('vehicle');

        // Jika peran pengguna adalah 'user' (Driver), batasi hanya kendaraan mereka sendiri
        if (auth()->user()->role === 'user') {
            $user = auth()->user();
            $query->whereHas('vehicle', function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere('supir_utama', $user->name);
            });
        }

        $checklists = $query->latest('tanggal')->get();

        return view('checklist.index', compact('checklists'));
    }

    public function create()
    {
        // Jika peran pengguna adalah 'user' (Driver), hanya tampilkan kendaraan milik mereka
        if (auth()->user()->role === 'user') {
            $user = auth()->user();
            $vehicles = Vehicle::where(function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere('supir_utama', $user->name);
            })->orderBy('plat_nomor')->get();
        } else {
            $vehicles = Vehicle::orderBy('plat_nomor')->get();
        }

        return view('checklist.create', compact('vehicles'));
    }

    public function store(Request $request, WhatsappService $whatsapp)
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
            $user = auth()->user();
            if (!$vehicle || ($vehicle->driver_id !== $user->id && $vehicle->supir_utama !== $user->name)) {
                return redirect()->back()->withErrors(['vehicle_id' => 'Anda hanya diperbolehkan melakukan checklist untuk kendaraan penugasan Anda sendiri.'])->withInput();
            }
        }

        DailyChecklist::create($validated);

        if (! empty($validated['odometer'])) {
            Vehicle::where('id', $validated['vehicle_id'])
                ->update(['odometer_awal' => $validated['odometer']]);
        }

        // Cek apakah ada komponen yang Not OK untuk dikirimkan notifikasi peringatan ke Admin
        $notOkItems = [];
        if ($validated['oli_mesin'] === 'Not OK') $notOkItems[] = 'Oli Mesin';
        if ($validated['air_radiator'] === 'Not OK') $notOkItems[] = 'Air Radiator';
        if ($validated['minyak_rem'] === 'Not OK') $notOkItems[] = 'Minyak Rem';
        if ($validated['ban_rem'] === 'Not OK') $notOkItems[] = 'Ban & Rem';
        if ($validated['lampu_klakson'] === 'Not OK') $notOkItems[] = 'Lampu & Klakson';
        if ($validated['kebersihan'] === 'Not OK') $notOkItems[] = 'Kebersihan';

        if (! empty($notOkItems)) {
            $adminNumber = config('services.whatsapp.admin_number');
            if (! empty($adminNumber)) {
                try {
                    $vehicle = Vehicle::find($validated['vehicle_id']);
                    $platNomor = $vehicle ? $vehicle->plat_nomor : '—';

                    $whatsapp->sendTemplate('checklist_peringatan', $adminNumber, [
                        'plat_nomor' => $platNomor,
                        'nama_pemeriksa' => $validated['nama_teknisi'],
                        'tanggal' => Carbon::parse($validated['tanggal'])->format('d/m/Y'),
                        'komponen_bermasalah' => implode(', ', $notOkItems),
                        'catatan' => $validated['catatan_tambahan'] ?: 'Tidak ada catatan tambahan.',
                    ], [
                        'user_id' => auth()->id(),
                    ]);
                } catch (Throwable $e) {
                    // Abaikan kesalahan kirim WA agar proses checklist tetap sukses
                }
            }
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

        if ($checklist->vehicle && $checklist->vehicle->odometer_awal < $validated['odometer']) {
            $checklist->vehicle->update(['odometer_awal' => $validated['odometer']]);
        }

        return redirect()->back()->with('success', 'Angka odometer berhasil diperbarui.');
    }
}
