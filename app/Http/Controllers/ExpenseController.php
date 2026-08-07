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

        $expenses = $query->get();

        $rekapPerKendaraan = Expense::selectRaw('vehicle_id, SUM(jumlah_biaya) as total')
            ->whereMonth('tanggal', now()->month)
            ->groupBy('vehicle_id')
            ->with('vehicle')
            ->orderByDesc('total')
            ->get();

        return view('expenses.index', compact('expenses', 'rekapPerKendaraan'));
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
            'jenis_pengeluaran' => 'required|in:BBM,Tol,Bengkel,Parkir,Pajak,Lainnya',
            'jumlah_biaya' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Semua pengeluaran baru default-nya adalah 'Menunggu Persetujuan' agar Admin/Manager dapat menyetujui atau menolaknya secara manual
        $validated['status_approval'] = 'Menunggu Persetujuan';

        Expense::create($validated);

        $pesan = $validated['status_approval'] === 'Menunggu Persetujuan'
            ? 'Pengeluaran besar berhasil dicatat, menunggu persetujuan Admin.'
            : 'Pengeluaran berhasil dicatat.';

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
            'jenis_pengeluaran' => 'required|in:BBM,Tol,Bengkel,Parkir,Pajak,Lainnya',
            'jumlah_biaya' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Hitung ulang status approval jika jumlah biaya diubah
        $batasAnggaranBesar = 1000000;
        $validated['status_approval'] = ($validated['jumlah_biaya'] > $batasAnggaranBesar)
            ? 'Menunggu Persetujuan'
            : 'Disetujui';

        $expense = Expense::findOrFail($id);
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

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran dihapus.');
    }
}
