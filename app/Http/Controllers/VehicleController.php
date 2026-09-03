<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['latestChecklist', 'lastServiceExpense'])->orderBy('plat_nomor')->get();

        return view('vehicles.index', compact('vehicles'));
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['checklists' => function ($q) {
            $q->latest('tanggal')->take(10);
        }, 'expenses' => function ($q) {
            $q->latest('tanggal')->take(10);
        }, 'histories' => function ($q) {
            $q->with('teknisi')->orderByDesc('tanggal');
        }]);

        // Riwayat 7 hari terakhir
        $seminggu = now()->subDays(7);

        $checklistSeminggu = $vehicle->checklists()
            ->where('tanggal', '>=', $seminggu)
            ->orderByDesc('tanggal')
            ->get();

        // Mengambil riwayat pengeluaran 7 hari terakhir yang spesifik kategori "Servis"
        // Pastikan nama kolom 'jenis_pengeluaran' sesuai dengan yang ada di database Anda
        $expenseSeminggu = $vehicle->expenses()
            ->where('tanggal', '>=', $seminggu)
            ->where('jenis_pengeluaran', 'like', '%Servis%')
            ->orderByDesc('tanggal')
            ->get();

        // Logika untuk menampilkan Jadwal Servis Berikutnya
        if ($vehicle->tanggal_servis_manual) {
            $nextServiceDate = Carbon::parse($vehicle->tanggal_servis_manual);
        } else {
            // Mengambil data servis terakhir untuk dijadikan acuan
            $lastService = $vehicle->expenses()
                ->where('jenis_pengeluaran', 'like', '%Servis%')
                ->latest('tanggal')
                ->first();

            // Menentukan estimasi 3 bulan setelah servis terakhir
            $nextServiceDate = $lastService ? Carbon::parse($lastService->tanggal)->addMonths(3) : null;
        }

        return view('vehicles.show', compact(
            'vehicle',
            'checklistSeminggu',
            'expenseSeminggu',
            'nextServiceDate'
        ));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'plat_nomor' => 'required|string|max:20|unique:vehicles,plat_nomor',
            'lokasi_pool' => 'nullable|string|max:255',
            'supir_utama' => 'nullable|string|max:255',
            'odometer_awal' => 'required|integer|min:0',
            'pajak_tahunan' => 'nullable|numeric|min:0',
            'pajak_5_tahunan' => 'nullable|numeric|min:0',
            'jatuh_tempo_kir' => 'nullable|date',
            'tanggal_servis_manual' => 'nullable|date',
            'foto' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        if ($value->getSize() > 2097152) {
                            $fail('Foto tidak boleh lebih besar dari 2MB.');
                        }
                        $ext = strtolower($value->getClientOriginalExtension() ?: $value->guessExtension());
                        $mime = strtolower($value->getClientMimeType());
                        $isImageMime = str_starts_with($mime, 'image/');
                        $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'webp', 'jfif', 'heic', 'heif'];

                        if (!in_array($ext, $allowedExtensions) && !$isImageMime) {
                            $fail('Foto harus berupa file bertipe: jpeg, png, jpg, gif.');
                        }
                    } else if ($value) {
                        $fail('Gagal mengunggah file.');
                    }
                }
            ],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('vehicles', 'public');
        }

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'plat_nomor' => 'required|string|max:20|unique:vehicles,plat_nomor,'.$vehicle->id,
            'lokasi_pool' => 'nullable|string|max:255',
            'supir_utama' => 'nullable|string|max:255',
            'odometer_awal' => 'required|integer|min:0',
            'pajak_tahunan' => 'nullable|numeric|min:0',
            'pajak_5_tahunan' => 'nullable|numeric|min:0',
            'jatuh_tempo_kir' => 'nullable|date',
            'tanggal_servis_manual' => 'nullable|date',
            'status' => 'required|string|in:Siap Pakai,Sedang Diservis,Selesai',
            'foto' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        if ($value->getSize() > 2097152) {
                            $fail('Foto tidak boleh lebih besar dari 2MB.');
                        }
                        $ext = strtolower($value->getClientOriginalExtension() ?: $value->guessExtension());
                        $mime = strtolower($value->getClientMimeType());
                        $isImageMime = str_starts_with($mime, 'image/');
                        $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'webp', 'jfif', 'heic', 'heif'];

                        if (!in_array($ext, $allowedExtensions) && !$isImageMime) {
                            $fail('Foto harus berupa file bertipe: jpeg, png, jpg, gif.');
                        }
                    } else if ($value) {
                        $fail('Gagal mengunggah file.');
                    }
                }
            ],
        ]);

        if ($request->hasFile('foto')) {
            if ($vehicle->foto) {
                Storage::disk('public')->delete($vehicle->foto);
            }
            $validated['foto'] = $request->file('foto')->store('vehicles', 'public');
        } else {
            unset($validated['foto']);
        }

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'status' => 'required|in:Siap Pakai,Sedang Diservis,Selesai',
        ]);

        $vehicle->update($validated);

        return redirect()->back()->with('success', 'Status kendaraan '.$vehicle->plat_nomor.' berhasil diperbarui.');
    }

    public function readNotification(Vehicle $vehicle)
    {
        $read = session()->get('read_notifications', []);
        $status = $vehicle->status_servis_berkala;

        if (! isset($read[$vehicle->id]) || $read[$vehicle->id] !== $status) {
            $read[$vehicle->id] = $status;
            session()->put('read_notifications', $read);
        }

        return redirect()->route('vehicles.show', $vehicle->id);
    }
}
