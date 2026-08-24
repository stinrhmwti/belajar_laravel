<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Expense;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Complaint::with(['vehicle', 'user'])->latest('tanggal');

        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        $complaints = $query->get();

        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('plat_nomor')->get();

        return view('complaints.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal' => 'required|date',
            'keluhan' => 'required|string|max:1000',
            'foto_kerusakan' => 'nullable|image|max:10240', // max 10MB
            'video_kerusakan' => 'nullable|mimes:mp4,mov,avi,webm|max:51200', // max 50MB
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Baru';
        $validated['progress_perbaikan'] = 0;

        // Process File Uploads
        if ($request->hasFile('foto_kerusakan')) {
            $file = $request->file('foto_kerusakan');
            $filename = time().'_foto_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/complaints'), $filename);
            $validated['foto_kerusakan'] = 'uploads/complaints/'.$filename;
        }

        if ($request->hasFile('video_kerusakan')) {
            $file = $request->file('video_kerusakan');
            $filename = time().'_video_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/complaints'), $filename);
            $validated['video_kerusakan'] = 'uploads/complaints/'.$filename;
        }

        Complaint::create($validated);

        return redirect()->route('complaints.index')->with('success', 'Keluhan berhasil dilaporkan. Teknisi akan segera menindaklanjuti.');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:Baru,Diproses,Selesai',
            'progress_perbaikan' => 'nullable|integer|min:0|max:100',
            'diterima_at' => 'nullable|date',
            'diperbaiki_at' => 'nullable|date',
            'selesai_at' => 'nullable|date',
            'catatan_penyelesaian' => 'nullable|string|max:500',
            'jumlah_biaya' => 'nullable|numeric|min:0',
        ]);

        $status = $validated['status'];

        // Parse dates from manual inputs if provided
        $diterima = $request->filled('diterima_at') ? Carbon::parse($request->diterima_at) : null;
        $diperbaiki = $request->filled('diperbaiki_at') ? Carbon::parse($request->diperbaiki_at) : null;
        $selesai = $request->filled('selesai_at') ? Carbon::parse($request->selesai_at) : null;

        if ($status === 'Baru') {
            $validated['progress_perbaikan'] = 0;
            $validated['diterima_at'] = null;
            $validated['diperbaiki_at'] = null;
            $validated['selesai_at'] = null;
        } elseif ($status === 'Diproses') {
            $validated['diterima_at'] = $diterima ?: ($complaint->diterima_at ?: now());
            $validated['diperbaiki_at'] = $diperbaiki ?: ($complaint->diperbaiki_at ?: now());
            $validated['progress_perbaikan'] = $request->input('progress_perbaikan', $complaint->progress_perbaikan ?: 20);
            $validated['selesai_at'] = null;
        } elseif ($status === 'Selesai') {
            $validated['diterima_at'] = $diterima ?: ($complaint->diterima_at ?: now());
            $validated['diperbaiki_at'] = $diperbaiki ?: ($complaint->diperbaiki_at ?: now());
            $validated['selesai_at'] = $selesai ?: ($complaint->selesai_at ?: now());
            $validated['progress_perbaikan'] = 100;
        }

        // Hapus jumlah_biaya dari array $validated agar tidak mengganggu update tabel complaints
        $biaya = $validated['jumlah_biaya'] ?? null;
        unset($validated['jumlah_biaya']);

        $complaint->update($validated);

        // Jika status selesai, catat otomatis ke rekap pengeluaran (Expenses) dan riwayat kendaraan (VehicleHistory)
        if ($status === 'Selesai') {
            if ($biaya && $biaya > 0) {
                $batasAnggaranBesar = 1000000;
                $statusApproval = ($biaya > $batasAnggaranBesar) ? 'Menunggu Persetujuan' : 'Disetujui';

                Expense::create([
                    'vehicle_id' => $complaint->vehicle_id,
                    'tanggal' => now()->toDateString(),
                    'jenis_pengeluaran' => 'Bengkel',
                    'jumlah_biaya' => $biaya,
                    'keterangan' => 'Biaya perbaikan keluhan: "'.$complaint->keluhan.'"'.
                                           ($complaint->catatan_penyelesaian ? ' - '.$complaint->catatan_penyelesaian : ''),
                    'status_approval' => $statusApproval,
                ]);
            }

            // Catat otomatis ke Riwayat Kendaraan (VehicleHistory)
            \App\Models\VehicleHistory::create([
                'vehicle_id' => $complaint->vehicle_id,
                'tanggal' => $complaint->selesai_at ?: now()->toDateString(),
                'teknisi_id' => Auth::id(),
                'jenis_pekerjaan' => 'Perbaikan Keluhan: ' . $complaint->keluhan,
                'sparepart_digunakan' => '-',
                'biaya' => $biaya ?? 0,
                'keterangan' => $complaint->catatan_penyelesaian ?? 'Keluhan diselesaikan oleh teknisi.',
            ]);
        }

        // Auto-update vehicle status based on complaint status to keep quick stats synchronized
        if ($complaint->vehicle) {
            if ($status === 'Diproses') {
                $complaint->vehicle->update(['status' => 'Sedang Diservis']);
            } elseif ($status === 'Selesai') {
                $complaint->vehicle->update(['status' => 'Siap Pakai']);
            } elseif ($status === 'Baru') {
                $complaint->vehicle->update(['status' => 'Siap Pakai']);
            }
        }

        return redirect()->route('complaints.index')->with('success', 'Status & riwayat keluhan berhasil diperbarui.');
    }
}
