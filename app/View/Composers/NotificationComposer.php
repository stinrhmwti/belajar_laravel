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

        $vehicles = Vehicle::with(['latestChecklist', 'lastServiceExpense'])->get();

        if (in_array($user->role, ['superadmin', 'admin', 'pimpinan'])) {
            $menunggu = Expense::where('status_approval', 'Menunggu Persetujuan')->with('vehicle')->latest('tanggal')->get();
            foreach ($menunggu as $e) {
                $items[] = [
                    'icon' => 'bi-hourglass-split text-warning',
                    'text' => __("Pengeluaran :jenis kendaraan :plat menunggu persetujuan", [
                        'jenis' => $e->jenis_pengeluaran,
                        'plat' => $e->vehicle->plat_nomor ?? 'N/A'
                    ]),
                    'link' => route('expenses.index'),
                ];
            }

            $keluhanBaru = Complaint::where('status', 'Baru')->with('vehicle')->latest('tanggal')->get();
            foreach ($keluhanBaru as $k) {
                $items[] = [
                    'icon' => 'bi-flag-fill text-danger',
                    'text' => __("Keluhan baru untuk kendaraan :plat", [
                        'plat' => $k->vehicle->plat_nomor ?? 'N/A'
                    ]),
                    'link' => route('complaints.index'),
                ];
            }

            // Notifikasi biaya perbaikan bengkel baru yang dicatat oleh teknisi (3 hari terakhir)
            if (in_array($user->role, ['superadmin', 'admin'])) {
                $biayaBaru = Expense::where('jenis_pengeluaran', 'Bengkel')
                    ->where('created_at', '>=', now()->subDays(3))
                    ->with('vehicle')
                    ->latest()
                    ->get();
                foreach ($biayaBaru as $e) {
                    $items[] = [
                        'icon' => 'bi-cash-coin text-success',
                        'text' => __("Biaya perbaikan kendaraan :plat dicatat: Rp :biaya", [
                            'plat' => $e->vehicle->plat_nomor ?? 'N/A',
                            'biaya' => number_format($e->jumlah_biaya, 0, ',', '.')
                        ]),
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
                    'text' => __("Keluhan :status - kendaraan :plat", [
                        'status' => $k->status,
                        'plat' => $k->vehicle->plat_nomor ?? 'N/A'
                    ]),
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
                    'text' => __("Laporan keluhan Anda untuk :plat :status!", [
                        'plat' => $k->vehicle->plat_nomor ?? 'N/A',
                        'status' => $statusKeluhan
                    ]),
                    'link' => route('complaints.index'),
                ];
            }

            $kendaraanSaya = $vehicles->filter(fn ($v) => $v->supir_utama === $user->name);
            foreach ($kendaraanSaya as $v) {
                // Notifikasi KIR
                if ($v->status_kir === 'merah') {
                    $items[] = [
                        'icon' => 'bi-exclamation-octagon-fill text-danger',
                        'text' => __("Dokumen KIR kendaraan Anda (:plat) sudah lewat jatuh tempo! (Jatuh tempo: :tanggal)", [
                            'plat' => $v->plat_nomor,
                            'tanggal' => $v->jatuh_tempo_kir ? $v->jatuh_tempo_kir->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                } elseif ($v->status_kir === 'kuning') {
                    $items[] = [
                        'icon' => 'bi-hourglass-split text-warning',
                        'text' => __("Dokumen KIR kendaraan Anda (:plat) akan segera jatuh tempo (Jatuh tempo: :tanggal)", [
                            'plat' => $v->plat_nomor,
                            'tanggal' => $v->jatuh_tempo_kir ? $v->jatuh_tempo_kir->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                }

                // Notifikasi Servis Berkala
                if ($v->status_servis_berkala === 'merah') {
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-danger',
                        'text' => __("Kendaraan Anda (:plat) sudah LEWAT jadwal servis berkala! (Target: :target)", [
                            'plat' => $v->plat_nomor,
                            'target' => $v->tanggal_servis_berikutnya ? $v->tanggal_servis_berikutnya->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                } elseif ($v->status_servis_berkala === 'kuning') {
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-warning',
                        'text' => __("Kendaraan Anda (:plat) mendekati jadwal servis berkala (Target: :target)", [
                            'plat' => $v->plat_nomor,
                            'target' => $v->tanggal_servis_berikutnya ? $v->tanggal_servis_berikutnya->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                }

                // Notifikasi Checklist Harian Bermasalah
                $lastChecklist = $v->latestChecklist;
                if ($lastChecklist && $lastChecklist->ada_masalah) {
                    $items[] = [
                        'icon' => 'bi-exclamation-triangle-fill text-danger',
                        'text' => __("Ada parameter 'Not OK' pada pemeriksaan harian kendaraan Anda (:plat)!", ['plat' => $v->plat_nomor]),
                        'link' => route('vehicles.show', $v),
                    ];
                }
            }
        }

        // Notifikasi umum untuk admin, superadmin, teknisi, & pimpinan: kendaraan lewat/mendekati jatuh tempo KIR
        if (in_array($user->role, ['superadmin', 'admin', 'teknisi', 'pimpinan'])) {
            foreach ($vehicles as $v) {
                if ($v->status_kir === 'merah') {
                    $items[] = [
                        'icon' => 'bi-exclamation-octagon-fill text-danger',
                        'text' => __("Kendaraan :plat sudah lewat jatuh tempo KIR (Jatuh tempo: :tanggal)", [
                            'plat' => $v->plat_nomor,
                            'tanggal' => $v->jatuh_tempo_kir ? $v->jatuh_tempo_kir->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                } elseif ($v->status_kir === 'kuning') {
                    $items[] = [
                        'icon' => 'bi-hourglass-split text-warning',
                        'text' => __("Dokumen KIR kendaraan :plat akan segera jatuh tempo (Jatuh tempo: :tanggal)", [
                            'plat' => $v->plat_nomor,
                            'tanggal' => $v->jatuh_tempo_kir ? $v->jatuh_tempo_kir->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                }
            }
        }

        // ===== NOTIFIKASI SERVIS BERKALA (khusus Admin, Superadmin, Teknisi, & Pimpinan) =====
        if (in_array($user->role, ['superadmin', 'admin', 'teknisi', 'pimpinan'])) {
            foreach ($vehicles as $v) {
                if ($v->status_servis_berkala === 'merah') {
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-danger',
                        'text' => __("Kendaraan :plat sudah LEWAT jadwal servis berkala! (Target: :target)", [
                            'plat' => $v->plat_nomor,
                            'target' => $v->tanggal_servis_berikutnya ? $v->tanggal_servis_berikutnya->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                } elseif ($v->status_servis_berkala === 'kuning') {
                    $items[] = [
                        'icon' => 'bi-wrench-adjustable-circle-fill text-warning',
                        'text' => __("Kendaraan :plat mendekati jadwal servis berkala (Target: :target)", [
                            'plat' => $v->plat_nomor,
                            'target' => $v->tanggal_servis_berikutnya ? $v->tanggal_servis_berikutnya->translatedFormat('d M Y') : '-'
                        ]),
                        'link' => route('vehicles.show', $v),
                    ];
                }
            }
        }

        // ===== NOTIFIKASI CHECKLIST HARIAN BERMASALAH (khusus Admin, Superadmin, & Teknisi) =====
        if (in_array($user->role, ['superadmin', 'admin', 'teknisi'])) {
            foreach ($vehicles as $v) {
                $lastChecklist = $v->latestChecklist;
                if ($lastChecklist && $lastChecklist->ada_masalah) {
                    $items[] = [
                        'icon' => 'bi-exclamation-triangle-fill text-danger',
                        'text' => __("Kendaraan :plat melaporkan kendala pada pemeriksaan harian!", ['plat' => $v->plat_nomor]),
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
