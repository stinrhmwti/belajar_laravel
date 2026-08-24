<?php

namespace App\View\Composers;

use App\Models\Complaint;
use App\Models\Expense;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        if (! Auth::check()) {
            $view->with(['notifCount' => 0, 'notifItems' => []]);

            return;
        }

        $user = Auth::user();
        $items = [];

        $vehicles = Vehicle::all();
        $lewatTempo = $vehicles->filter(fn ($v) => $v->status_kir === 'merah');

        if (in_array($user->role, ['superadmin', 'admin', 'pimpinan'])) {
            $menunggu = Expense::where('status_approval', 'Menunggu Persetujuan')->with('vehicle')->latest('tanggal')->get();
            foreach ($menunggu as $e) {
                $items[] = [
                    'icon' => 'bi-hourglass-split text-warning',
                    'text' => "Pengeluaran {$e->jenis_pengeluaran} kendaraan {$e->vehicle->plat_nomor} menunggu persetujuan",
                    'link' => route('expenses.index'),
                ];
            }

            $keluhanBaru = Complaint::where('status', 'Baru')->with('vehicle')->latest('tanggal')->get();
            foreach ($keluhanBaru as $k) {
                $items[] = [
                    'icon' => 'bi-flag-fill text-danger',
                    'text' => "Keluhan baru untuk kendaraan {$k->vehicle->plat_nomor}",
                    'link' => route('complaints.index'),
                ];
            }

            // Notifikasi biaya perbaikan bengkel baru yang dicatat oleh teknisi (3 hari terakhir) - pimpinan tidak wajib menerima notif ini
            if (in_array($user->role, ['superadmin', 'admin'])) {
                $biayaBaru = Expense::where('jenis_pengeluaran', 'Bengkel')
                    ->where('created_at', '>=', now()->subDays(3))
                    ->with('vehicle')
                    ->latest()
                    ->get();
                foreach ($biayaBaru as $e) {
                    $items[] = [
                        'icon' => 'bi-cash-coin text-success',
                        'text' => "Biaya perbaikan kendaraan {$e->vehicle->plat_nomor} dicatat: Rp ".number_format($e->jumlah_biaya, 0, ',', '.'),
                        'link' => route('expenses.index'),
                    ];
                }
            }
        }

        if ($user->role === 'teknisi') {
            $keluhanAktif = Complaint::whereIn('status', ['Baru', 'Diproses'])->with('vehicle')->latest('tanggal')->get();
            foreach ($keluhanAktif as $k) {
                $items[] = [
                    'icon' => 'bi-flag-fill text-danger',
                    'text' => "Keluhan {$k->status} - kendaraan {$k->vehicle->plat_nomor}",
                    'link' => route('complaints.index'),
                ];
            }
        }

        if ($user->role === 'user') {
            $keluhanSaya = Complaint::where('user_id', $user->id)
                ->whereIn('status', ['Diproses', 'Selesai'])
                ->with('vehicle')->latest('tanggal')->take(5)->get();
            foreach ($keluhanSaya as $k) {
                $statusKeluhan = $k->status === 'Selesai' ? 'Selesai Diperbaiki' : 'Sedang Diproses';
                $items[] = [
                    'icon' => $k->status === 'Selesai' ? 'bi-check2-circle text-success' : 'bi-tools text-warning',
                    'text' => "Laporan keluhan Anda untuk {$k->vehicle->plat_nomor} {$statusKeluhan}!",
                    'link' => route('complaints.index'),
                ];
            }

            $kendaraanSaya = $vehicles->filter(fn ($v) => $v->supir_utama === $user->name);
            foreach ($kendaraanSaya as $v) {
                // Notifikasi KIR
                if ($v->status_kir === 'merah') {
                    $items[] = [
                        'icon' => 'bi-exclamation-octagon-fill text-danger',
                        'text' => "Dokumen KIR kendaraan Anda ({$v->plat_nomor}) sudah lewat jatuh tempo!",
                        'link' => route('vehicles.show', $v),
                    ];
                } elseif ($v->status_kir === 'kuning') {
                    $items[] = [
                        'icon' => 'bi-hourglass-split text-warning',
                        'text' => "Dokumen KIR kendaraan Anda ({$v->plat_nomor}) akan segera jatuh tempo",
                        'link' => route('vehicles.show', $v),
                    ];
                }

                // Notifikasi Servis Berkala
                if ($v->status_servis_berkala === 'merah') {
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-danger',
                        'text' => "Kendaraan Anda ({$v->plat_nomor}) sudah LEWAT jadwal servis berkala!",
                        'link' => route('vehicles.show', $v),
                    ];
                } elseif ($v->status_servis_berkala === 'kuning') {
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-warning',
                        'text' => "Kendaraan Anda ({$v->plat_nomor}) mendekati jadwal servis berkala",
                        'link' => route('vehicles.show', $v),
                    ];
                }

                // Notifikasi Checklist Harian Bermasalah
                $lastChecklist = $v->checklists()->latest('tanggal')->first();
                if ($lastChecklist && $lastChecklist->ada_masalah) {
                    $items[] = [
                        'icon' => 'bi-exclamation-triangle-fill text-danger',
                        'text' => "Ada parameter 'Not OK' pada pemeriksaan harian kendaraan Anda ({$v->plat_nomor})!",
                        'link' => route('vehicles.show', $v),
                    ];
                }
            }
        }

        // Notifikasi umum untuk admin, superadmin, teknisi, & pimpinan: kendaraan lewat jatuh tempo dokumen KIR
        if (in_array($user->role, ['superadmin', 'admin', 'teknisi', 'pimpinan'])) {
            foreach ($lewatTempo as $v) {
                $items[] = [
                    'icon' => 'bi-exclamation-octagon-fill text-danger',
                    'text' => "Kendaraan {$v->plat_nomor} sudah lewat jatuh tempo KIR",
                    'link' => route('vehicles.show', $v),
                ];
            }
        }

        // ===== NOTIFIKASI TERPISAH: SERVIS BERKALA (khusus Admin, Superadmin, Teknisi, & Pimpinan) =====
        if (in_array($user->role, ['superadmin', 'admin', 'teknisi', 'pimpinan'])) {
            foreach ($vehicles as $v) {
                $hariTersisa = now()->diffInDays($v->tanggal_servis_berikutnya, false);

                if ($hariTersisa <= -7) {
                    // Sudah lewat servis lebih dari 1 minggu
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-danger',
                        'text' => "Kendaraan {$v->plat_nomor} sudah LEWAT servis lebih dari 1 minggu (".abs((int) $hariTersisa).' hari)',
                        'link' => route('vehicles.show', $v),
                    ];
                } elseif ($hariTersisa > -7 && $hariTersisa <= 7) {
                    // Kurang dari seminggu menuju/lewat sedikit jadwal servis
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-warning',
                        'text' => "Kendaraan {$v->plat_nomor} jadwal servis kurang dari seminggu lagi",
                        'link' => route('vehicles.show', $v),
                    ];
                }
            }
        }

        $view->with([
            'notifCount' => count($items),
            'notifItems' => array_slice($items, 0, 10),
        ]);
    }
}
