<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('vehicle')->latest('tanggal');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->whereHas('vehicle', function ($v) use ($keyword) {
                $v->where('plat_nomor', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_pengeluaran', $request->jenis);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $expenses = $query->get();

        $rekapPerKendaraan = Expense::selectRaw('vehicle_id, SUM(jumlah_biaya) as total')
            ->whereMonth('tanggal', now()->month)
            ->groupBy('vehicle_id')
            ->with('vehicle')
            ->orderByDesc('total')
            ->get();

        $vehicles = Vehicle::with('latestChecklist')->orderBy('plat_nomor')->get();

        return view('expenses.index', compact('expenses', 'rekapPerKendaraan', 'vehicles'));
    }

    /**
     * Export rekap data pengeluaran armada ke file CSV yang kompatibel dengan Excel
     */
    public function exportCsv(Request $request)
    {
        $query = Expense::with('vehicle')->latest('tanggal');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->whereHas('vehicle', function ($v) use ($keyword) {
                $v->where('plat_nomor', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_pengeluaran', $request->jenis);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $expenses = $query->get();

        $filename = 'Laporan_Pengeluaran_Armada_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($expenses) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel Indonesian / Unicode formatting
            fputs($file, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($file, [
                'No',
                'Tanggal',
                'Plat Nomor',
                'Jenis Kendaraan / Tipe',
                'Pengemudi',
                'Jenis Pengeluaran',
                'Jumlah Biaya (Rp)',
                'Liter BBM',
                'Odometer Pengisian (KM)',
                'Status Persetujuan',
                'Keterangan / Catatan',
            ]);

            $no = 1;
            $totalBiaya = 0;
            foreach ($expenses as $expense) {
                $biaya = (float) $expense->jumlah_biaya;
                $totalBiaya += $biaya;

                fputcsv($file, [
                    $no++,
                    $expense->tanggal ? $expense->tanggal->format('d/m/Y') : '-',
                    $expense->vehicle->plat_nomor ?? '-',
                    ($expense->vehicle->jenis_kendaraan ?? '') . ' ' . ($expense->vehicle->merek ?? '') . ' ' . ($expense->vehicle->tipe ?? ''),
                    $expense->vehicle->supir_utama ?? '-',
                    $expense->jenis_pengeluaran,
                    $biaya,
                    $expense->liter_bbm ?? '-',
                    $expense->odometer_pengisian ?? '-',
                    $expense->status_approval ?? 'Disetujui',
                    $expense->keterangan ?? '-',
                ]);
            }

            // Row Total
            fputcsv($file, [
                '',
                '',
                '',
                '',
                '',
                'TOTAL',
                $totalBiaya,
                '',
                '',
                '',
                '',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('plat_nomor')->get();

        return view('expenses.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|in:BBM,Tol,Bengkel,Parkir,Pajak,Sparepart,Lainnya',
            'jumlah_biaya' => 'required|numeric|min:0',
            'liter_bbm' => 'nullable|numeric|min:0',
            'odometer_pengisian' => 'nullable|integer|min:0',
            'status_approval' => 'nullable|in:Disetujui,Menunggu Persetujuan,Ditolak',
            'catatan_admin' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $isAdminOrPimpinan = $user && in_array($user->role, ['superadmin', 'admin', 'pimpinan']);

        if ($isAdminOrPimpinan && $request->filled('status_approval')) {
            $validated['status_approval'] = $request->status_approval;
        } else {
            // Default threshold jika tidak dipilih secara eksplisit:
            // Biaya perbaikan/pengeluaran di atas 1 Juta Rupiah otomatis memerlukan approval Manager/Admin
            $batasAnggaranBesar = 1000000;
            $validated['status_approval'] = ($validated['jumlah_biaya'] > $batasAnggaranBesar)
                ? 'Menunggu Persetujuan'
                : 'Disetujui';
        }

        Expense::create($validated);

        $pesan = match ($validated['status_approval']) {
            'Disetujui' => 'Pengeluaran berhasil dicatat dengan status Disetujui.',
            'Ditolak' => 'Pengeluaran berhasil dicatat dengan status Ditolak.',
            default => 'Pengeluaran berhasil dicatat dan menunggu persetujuan Admin/Pimpinan.',
        };

        return redirect()->route('expenses.index')->with('success', $pesan);
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $vehicles = Vehicle::orderBy('plat_nomor')->get();

        return view('expenses.edit', compact('expense', 'vehicles'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|in:BBM,Tol,Bengkel,Parkir,Pajak,Sparepart,Lainnya',
            'jumlah_biaya' => 'required|numeric|min:0',
            'liter_bbm' => 'nullable|numeric|min:0',
            'odometer_pengisian' => 'nullable|integer|min:0',
            'status_approval' => 'nullable|in:Disetujui,Menunggu Persetujuan,Ditolak',
            'catatan_admin' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $isAdminOrPimpinan = $user && in_array($user->role, ['superadmin', 'admin', 'pimpinan']);
        $expense = Expense::findOrFail($id);

        if ($isAdminOrPimpinan && $request->filled('status_approval')) {
            $validated['status_approval'] = $request->status_approval;
        } else {
            // Pertahankan status approval saat ini atau evaluasi batas anggaran
            $validated['status_approval'] = $expense->status_approval ?? (($validated['jumlah_biaya'] > 1000000) ? 'Menunggu Persetujuan' : 'Disetujui');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran/servis berhasil diperbarui.');
    }

    public function approve(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'status_approval' => 'required|in:Disetujui,Ditolak',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Status persetujuan biaya berhasil diperbarui.');
    }

    /**
     * Catat langsung pengeluaran BBM & update odometer dari Modal Kalkulator
     */
    public function storeQuickBbm(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'odometer_akhir' => 'required|integer|min:0',
            'liter_bbm' => 'required|numeric|min:0',
            'jumlah_biaya' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $newOdo = (int) $validated['odometer_akhir'];

        // 1. Simpan Rekap Pengeluaran BBM
        $batasAnggaranBesar = 1000000;
        $statusApproval = ($validated['jumlah_biaya'] > $batasAnggaranBesar) ? 'Menunggu Persetujuan' : 'Disetujui';

        $expense = Expense::create([
            'vehicle_id' => $vehicle->id,
            'tanggal' => now()->toDateString(),
            'jenis_pengeluaran' => 'BBM',
            'jumlah_biaya' => $validated['jumlah_biaya'],
            'liter_bbm' => $validated['liter_bbm'],
            'odometer_pengisian' => $newOdo,
            'keterangan' => $validated['keterangan'] ?: "Pengisian BBM {$validated['liter_bbm']}L pada Odo {$newOdo} KM",
            'status_approval' => $statusApproval,
        ]);

        // 2. Perbarui Odometer Kendaraan
        $vehicle->update(['odometer_awal' => $newOdo]);
        $latestChecklist = $vehicle->checklists()->latest('tanggal')->first();
        if ($latestChecklist && $latestChecklist->odometer < $newOdo) {
            $latestChecklist->update(['odometer' => $newOdo]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Pengeluaran BBM Rp " . number_format($validated['jumlah_biaya'], 0, ',', '.') . " tersimpan dan Odometer {$vehicle->plat_nomor} diperbarui ke " . number_format($newOdo, 0, ',', '.') . " KM!",
            'odometer' => $newOdo,
            'odometer_formatted' => number_format($newOdo, 0, ',', '.'),
            'expense' => $expense,
        ]);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran dihapus.');
    }
}
