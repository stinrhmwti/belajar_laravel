<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\DailyChecklist;
use App\Models\Expense;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vehicles = Vehicle::with('expenses')->get(); // Pastikan relasi expenses dimuat

        $totalKendaraan = $vehicles->count();
        $kendaraanBermasalah = $vehicles->filter(fn ($v) => $v->status_kir === 'merah')->count();
        $kendaraanMendekatiJatuhTempo = $vehicles->filter(fn ($v) => $v->status_kir === 'kuning')->count();

        // Statistik Cepat untuk Dashboard
        $keluhanHariIni = Complaint::whereDate('tanggal', now()->toDateString())->count();
        $sedangServis = $vehicles->where('status', 'Sedang Diservis')->count();
        $selesaiServis = $vehicles->where('status', 'Siap Pakai')->count() + $vehicles->where('status', 'Selesai')->count();

        $totalBiayaBulanIni = Expense::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where('status_approval', 'Disetujui') // Hanya hitung yang disetujui
            ->sum('jumlah_biaya');

        // Logika untuk mendeteksi mobil yang lewat jatuh tempo servis atau memiliki keluhan terlambat
        $perluServis = $vehicles->filter(function ($vehicle) {
            // 1. Cek servis berdasarkan tanggal servis terakhir (3 bulan)
            $lastService = $vehicle->expenses()
                ->where('jenis_pengeluaran', 'like', '%Servis%')
                ->latest('tanggal')
                ->first();

            $telatServis = false;
            if ($lastService) {
                $nextServiceDate = Carbon::parse($lastService->tanggal)->addMonths(3);
                if (now()->greaterThan($nextServiceDate)) {
                    $telatServis = true;
                }
            }

            // 2. Cek apakah ada keluhan aktif yang terlambat (status != Selesai dan tanggal + 2 hari < hari ini)
            $telatKeluhan = false;
            $activeComplaints = Complaint::where('vehicle_id', $vehicle->id)
                ->where('status', '!=', 'Selesai')
                ->get();

            foreach ($activeComplaints as $c) {
                $targetDate = Carbon::parse($c->tanggal)->addDays(2);
                if (now()->startOfDay()->greaterThan($targetDate->startOfDay())) {
                    $telatKeluhan = true;
                    break;
                }
            }

            return $telatServis || $telatKeluhan;
        });

        // Data untuk donut chart: distribusi status kendaraan
        $statusChart = [
            'labels' => ['Siap Pakai', 'Sedang Diservis', 'Selesai'],
            'data' => [
                $vehicles->where('status', 'Siap Pakai')->count(),
                $vehicles->where('status', 'Sedang Diservis')->count(),
                $vehicles->where('status', 'Selesai')->count(),
            ],
        ];

        // Real historical 7-day sparkline data
        $sparklineTotal = [];
        $sparklineKeluhan = [];
        $sparklineServis = [];
        $sparklineSiap = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $sparklineTotal[] = DailyChecklist::whereDate('tanggal', $date)->count();
            $sparklineKeluhan[] = Complaint::whereDate('tanggal', $date)->count();
            $sparklineServis[] = Expense::whereDate('tanggal', $date)
                ->where(function ($q) {
                    $q->where('jenis_pengeluaran', 'like', '%Servis%')
                        ->orWhere('jenis_pengeluaran', 'like', '%Bengkel%');
                })->count();

            $checklistsToday = DailyChecklist::whereDate('tanggal', $date)->get();
            $sparklineSiap[] = $checklistsToday->filter(fn ($c) => ! $c->ada_masalah)->count();
        }

        $sparklineData = [
            'total' => $sparklineTotal,
            'keluhan' => $sparklineKeluhan,
            'servis' => $sparklineServis,
            'siap' => $sparklineSiap,
        ];

        // Compile Calendar Events (KIR & Service deadliness)
        $calendarEvents = [];
        foreach ($vehicles as $v) {
            if ($v->jatuh_tempo_kir) {
                $calendarEvents[] = [
                    'title' => 'KIR: '.$v->plat_nomor,
                    'date' => $v->jatuh_tempo_kir->toDateString(),
                    'type' => 'kir',
                    'color' => $v->status_kir === 'merah' ? '#ef4444' : ($v->status_kir === 'kuning' ? '#f59e0b' : '#10b981'),
                    'plat_nomor' => $v->plat_nomor,
                    'merek' => $v->merek,
                ];
            }
            if ($v->tanggal_servis_berikutnya) {
                $calendarEvents[] = [
                    'title' => 'Servis: '.$v->plat_nomor,
                    'date' => $v->tanggal_servis_berikutnya->toDateString(),
                    'type' => 'servis',
                    'color' => $v->status_servis_berkala === 'merah' ? '#ef4444' : ($v->status_servis_berkala === 'kuning' ? '#f59e0b' : '#3b82f6'),
                    'plat_nomor' => $v->plat_nomor,
                    'merek' => $v->merek,
                ];
            }
        }

        // Calculate Driver Compliance Leaderboard (from DailyChecklists count)
        $leaderboard = DailyChecklist::selectRaw('nama_teknisi, count(*) as total')
            ->groupBy('nama_teknisi')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $data = compact(
            'vehicles',
            'totalKendaraan',
            'kendaraanBermasalah',
            'kendaraanMendekatiJatuhTempo',
            'keluhanHariIni',
            'sedangServis',
            'selesaiServis',
            'totalBiayaBulanIni',
            'statusChart',
            'perluServis',
            'sparklineData',
            'calendarEvents',
            'leaderboard'
        );

        if (in_array($user->role, ['superadmin', 'admin', 'pimpinan'])) {
            $data['menungguPersetujuan'] = Expense::where('status_approval', 'Menunggu Persetujuan')
                ->with('vehicle')->latest('tanggal')->get();
            $data['keluhanBaru'] = Complaint::where('status', 'Baru')->with(['vehicle', 'user'])->latest('tanggal')->take(5)->get();

            $topBoros = Expense::selectRaw('vehicle_id, SUM(jumlah_biaya) as total')
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->where('status_approval', 'Disetujui')
                ->groupBy('vehicle_id')
                ->with('vehicle')
                ->orderByDesc('total')
                ->take(5)
                ->get();

            $data['borosChart'] = [
                'labels' => $topBoros->map(fn ($e) => $e->vehicle->plat_nomor ?? 'Tidak diketahui')->toArray(),
                'data' => $topBoros->map(fn ($e) => (float) $e->total)->toArray(),
            ];
        }

        if (in_array($user->role, ['superadmin', 'admin', 'teknisi', 'pimpinan'])) {
            $bulanLabel = [];
            $bulanTotal = [];
            for ($i = 5; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $bulanLabel[] = $bulan->translatedFormat('M Y');
                $bulanTotal[] = (float) Expense::whereMonth('tanggal', $bulan->month)
                    ->whereYear('tanggal', $bulan->year)
                    ->where('status_approval', 'Disetujui')
                    ->sum('jumlah_biaya');
            }
            $data['trenBiayaChart'] = [
                'labels' => $bulanLabel,
                'data' => $bulanTotal,
            ];
        }

        if ($user->role === 'teknisi') {
            $data['checklistHariIni'] = DailyChecklist::whereDate('tanggal', now()->toDateString())->count();
            $data['keluhanPerluDitangani'] = Complaint::whereIn('status', ['Baru', 'Diproses'])->with(['vehicle', 'user'])->latest('tanggal')->take(5)->get();
        }

        if ($user->role === 'user') {
            $data['kendaraanSaya'] = $vehicles->filter(fn ($v) => $v->supir_utama === $user->name);
            $data['kendaraanSiapPakai'] = $vehicles->where('status', 'Siap Pakai');
            $data['keluhanSaya'] = Complaint::where('user_id', $user->id)->latest('tanggal')->take(5)->get();
            $data['totalKeluhanSaya'] = Complaint::where('user_id', $user->id)->count();
            $data['totalKeluhanSelesaiSaya'] = Complaint::where('user_id', $user->id)->where('status', 'Selesai')->count();
        }

        return view('dashboard', $data);
    }
}
