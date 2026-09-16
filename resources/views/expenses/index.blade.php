@extends('layouts.app')
@section('title', 'Rekap Biaya')

@section('content')
<style>
    .badge-soft-success { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-soft-warning { background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-soft-danger { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .table-hover tbody tr:hover { background-color: #f9fafb; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1">{{ __('Rekap Biaya Operasional') }}</h3>
        <p class="text-muted mb-0" style="font-size: 0.92rem;">{{ __('Catat dan kelola pengeluaran harian operasional kendaraan.') }}</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Tools Action Group -->
        <div class="btn-group shadow-xs" role="group" style="border-radius: 10px; overflow: hidden;">
            <button type="button" class="btn btn-sm btn-outline-warning text-dark px-3 py-2 d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#fuelExpenseCalcModal" title="{{ __('Hitung Efisiensi Konsumsi BBM') }}" style="font-size: 0.82rem; font-weight: 600;">
                <i class="bi bi-fuel-pump-fill text-warning"></i>
                <span>{{ __('Kalkulator BBM') }}</span>
            </button>
            <button id="exportCsvBtn" class="btn btn-sm btn-outline-success px-3 py-2 d-inline-flex align-items-center gap-1.5" title="{{ __('Ekspor Data Tampil ke CSV') }}" style="font-size: 0.82rem; font-weight: 600;">
                <i class="bi bi-file-earmark-excel-fill"></i>
                <span>{{ __('Ekspor CSV') }}</span>
            </button>
            <button id="printReportBtn" class="btn btn-sm btn-outline-primary px-3 py-2 d-inline-flex align-items-center gap-1.5" title="{{ __('Cetak Laporan / Simpan PDF') }}" style="font-size: 0.82rem; font-weight: 600;">
                <i class="bi bi-printer-fill"></i>
                <span>{{ __('Cetak Laporan') }}</span>
            </button>
        </div>

        @if (in_array(auth()->user()->role, ['admin', 'teknisi']))
            <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-primary px-3.5 py-2 d-inline-flex align-items-center gap-1.5 shadow-xs" style="border-radius: 10px; font-size: 0.82rem; font-weight: 700; height: 36px;">
                <i class="bi bi-plus-circle-fill"></i>
                <span>{{ __('Catat Pengeluaran') }}</span>
            </a>
        @endif
    </div>
</div>

<!-- Dashboard Biaya Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-radius:14px;">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; border-radius: 12px;">
                    <i class="bi bi-wallet2 fs-3"></i>
                </div>
                <div>
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Total Biaya Disetujui (Bulan Ini)') }}</span>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($expenses->where('status_approval', 'Disetujui')->where('tanggal', '>=', now()->startOfMonth())->where('tanggal', '<=', now()->endOfMonth())->sum('jumlah_biaya'), 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-radius:14px;">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-warning-subtle text-warning rounded-4 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; border-radius: 12px;">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Menunggu persetujuan Manager') }}</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $expenses->where('status_approval', 'Menunggu Persetujuan')->count() }} <small class="text-muted fs-6 font-normal">{{ __('Pengajuan') }}</small></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 border-0 shadow-xs" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-funnel-fill text-primary me-1"></i> {{ __('Penyaringan Laporan Pengeluaran') }}</h6>
        <form action="{{ route('expenses.index') }}" method="GET" class="row g-3">
            <div class="col-md-4 col-sm-12">
                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">{{ __('Cari Plat Nomor') }}</label>
                <div class="input-group shadow-xs" style="border-radius: 8px; overflow: hidden;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Contoh: B 1234 KTR" value="{{ request('q') }}" style="font-size: 0.88rem;">
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">{{ __('Kategori Biaya') }}</label>
                <select name="jenis" class="form-select shadow-xs" style="border-radius: 8px; font-size: 0.88rem;">
                    <option value="">{{ __('Semua Kategori') }}</option>
                    @foreach (['BBM', 'Tol', 'Bengkel', 'Parkir', 'Pajak', 'Lainnya'] as $j)
                        <option value="{{ $j }}" @selected(request('jenis') === $j)>{{ __($j) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">{{ __('Filter Bulan') }}</label>
                <select name="bulan" class="form-select shadow-xs" style="border-radius: 8px; font-size: 0.88rem;">
                    <option value="">{{ __('Semua Bulan') }}</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" @selected((string) request('bulan') === (string) $m)>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">{{ __('Tanggal Mulai') }}</label>
                <input type="date" name="tanggal_mulai" class="form-control shadow-xs" value="{{ request('tanggal_mulai') }}" style="border-radius: 8px; font-size: 0.88rem;">
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.78rem;">{{ __('Tanggal Selesai') }}</label>
                <input type="date" name="tanggal_selesai" class="form-control shadow-xs" value="{{ request('tanggal_selesai') }}" style="border-radius: 8px; font-size: 0.88rem;">
            </div>
            <div class="col-md-4 col-sm-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 py-2 d-flex align-items-center justify-content-center gap-1.5 shadow-xs" style="border-radius: 8px; font-weight: 600; font-size: 0.88rem;">
                    <i class="bi bi-funnel"></i> {{ __('Terapkan Filter') }}
                </button>
                @if (request('q') || request('jenis') || request('bulan') || request('tanggal_mulai') || request('tanggal_selesai'))
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary py-2 px-3 shadow-xs" style="border-radius: 8px;" title="Reset Semua Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-pie-chart-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">{{ __('Ringkasan Biaya per Kendaraan (Bulan Ini)') }}</h6>
                <small class="text-muted" style="font-size: 0.78rem;">{{ __('Analisis akumulasi biaya operasional & pemeliharaan per armada') }}</small>
            </div>
        </div>
        @if($rekapPerKendaraan->count() > 0)
            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.78rem;">
                Total: Rp {{ number_format($rekapPerKendaraan->sum('total'), 0, ',', '.') }}
            </span>
        @endif
    </div>
    <div class="card-body p-4">
        @forelse ($rekapPerKendaraan as $r)
            @php
                $maxTotal = $rekapPerKendaraan->max('total') ?: 1;
                $percentage = min(100, round(($r->total / $maxTotal) * 100));
                $barColor = $loop->first ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-danger');
                $badgeBg = $loop->first ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-light text-dark border';
            @endphp
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $badgeBg }} fw-bold px-2 py-1 fs-6">
                            <i class="bi bi-car-front-fill me-1"></i>{{ $r->vehicle->plat_nomor ?? 'N/A' }}
                        </span>
                        @if($r->vehicle && $r->vehicle->merk)
                            <span class="text-muted" style="font-size: 0.8rem;">{{ $r->vehicle->merek }} {{ $r->vehicle->tipe }}</span>
                        @endif
                    </div>
                    <span class="fw-extrabold text-dark">Rp {{ number_format($r->total, 0, ',', '.') }}</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px;">
                    <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        @empty
            <div class="text-center py-3 text-muted">
                <i class="bi bi-pie-chart fs-2 d-block mb-1 text-secondary opacity-50"></i>
                <span style="font-size: 0.85rem;">{{ __('Tidak ada akumulasi biaya bulan ini.') }}</span>
            </div>
        @endforelse

        <div class="mt-3 pt-2 border-top d-flex align-items-center gap-2 text-muted" style="font-size: 0.8rem;">
            <i class="bi bi-info-circle text-danger"></i>
            <span>{{ __('Grafik di atas memprioritaskan kendaraan dengan total biaya pengeluaran tertinggi bulan ini untuk kemudahan evaluasi unit boros/masuk bengkel.') }}</span>
        </div>
    </div>
</div>

<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-grid-fill text-danger fs-5"></i>
            <span class="fw-bold text-dark fs-5">{{ __('Rincian Pengeluaran Operasional') }}</span>
        </div>
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-semibold" id="totalBiayaTampilBadge" style="font-size: 0.82rem;">
            {{ __('Total Terfilter:') }} Rp 0
        </span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <input type="text" id="expenseSearch" class="form-control form-control-sm" placeholder="🔍 {{ __('Cari pengeluaran...') }}" style="width: 250px; border-radius: 8px; font-size: 0.85rem; box-shadow: none;">
    </div>
</div>

<div class="row g-4" id="expensesGrid">
    @forelse ($expenses as $e)
    <div class="col-md-4 expense-card-col" 
         data-search="{{ strtolower($e->vehicle->plat_nomor . ' ' . $e->jenis_pengeluaran . ' ' . $e->keterangan . ' ' . $e->tanggal->format('d/m/Y')) }}"
         data-plat="{{ $e->vehicle->plat_nomor ?? 'N/A' }}" 
         data-jenis="{{ $e->jenis_pengeluaran }}" 
         data-tanggal="{{ $e->tanggal->format('d/m/Y') }}" 
         data-jumlah="{{ $e->jumlah_biaya }}" 
         data-keterangan="{{ $e->keterangan ?? '-' }}" 
         data-status="{{ $e->status_approval }}">
        <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs hover-card transition-all p-3" style="transition: all 0.25s ease; border-radius: 16px;">
            <!-- Plate & Status Header (Above Image) -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Plate Badge -->
                <span class="badge bg-dark text-white font-monospace px-3 py-2 fs-6 border border-secondary shadow-sm" style="border-radius: 10px; letter-spacing: 0.8px;">
                    {{ $e->vehicle->plat_nomor }}
                </span>
                <!-- Status Badge -->
                <div>
                    @if ($e->status_approval === 'Disetujui')
                        <span class="badge bg-success text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">{{ __('Disetujui') }}</span>
                    @elseif ($e->status_approval === 'Menunggu Persetujuan')
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">{{ __('Menunggu') }}</span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">{{ __('Ditolak') }}</span>
                    @endif
                </div>
            </div>

            <!-- Vehicle Image -->
            <div class="position-relative mb-3" style="height: 160px; border-radius: 12px; overflow: hidden; background: #f8fafc;">
                <img src="{{ $e->vehicle->foto_url }}" alt="{{ $e->vehicle->plat_nomor }}" class="w-100 h-100 object-fit-cover transition-img" style="transition: transform 0.3s ease;">
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                @if ($e->status_approval === 'Menunggu Persetujuan')
                    <div class="alert alert-warning py-1.5 px-2.5 mb-3 d-flex align-items-center gap-2" style="font-size: 0.76rem; border-radius: 8px; border: 1px solid #fde68a; background: #fffbeb;">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        <span class="fw-bold" style="color: #92400e;">Menunggu persetujuan Manager</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase text-danger fw-bold font-monospace" style="font-size: 0.72rem; letter-spacing: 0.8px;">{{ __($e->jenis_pengeluaran) }}</span>
                    <span class="text-muted font-monospace" style="font-size: 0.8rem;"><i class="bi bi-calendar3"></i> {{ $e->tanggal->format('d M Y') }}</span>
                </div>

                <h4 class="fw-extrabold text-dark mb-2">Rp {{ number_format($e->jumlah_biaya, 0, ',', '.') }}</h4>
                @if ($e->liter_bbm || $e->odometer_pengisian)
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap" style="font-size: 0.78rem;">
                        @if ($e->liter_bbm)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"><i class="bi bi-fuel-pump me-1"></i>{{ $e->liter_bbm }} L</span>
                        @endif
                        @if ($e->odometer_pengisian)
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1"><i class="bi bi-speedometer2 me-1"></i>{{ number_format($e->odometer_pengisian, 0, ',', '.') }} KM</span>
                        @endif
                    </div>
                @endif
                <p class="text-secondary font-monospace" style="font-size: 0.85rem; line-height: 1.4;">{{ $e->keterangan }}</p>

                <!-- Actions -->
                @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'pimpinan', 'teknisi']))
                <div class="d-flex flex-column gap-2 pt-3 border-top mt-3">
                    @if ($e->status_approval === 'Menunggu Persetujuan' && in_array(auth()->user()->role, ['superadmin', 'admin', 'pimpinan']))
                    <div class="d-flex gap-2">
                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="w-100">
                            @csrf @method('PUT')
                            <button type="submit" name="status_approval" value="Disetujui" class="btn btn-sm btn-success w-100" style="border-radius: 8px;">{{ __('Setujui') }}</button>
                        </form>
                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="w-100">
                            @csrf @method('PUT')
                            <button type="submit" name="status_approval" value="Ditolak" class="btn btn-sm btn-danger w-100" style="border-radius: 8px;">{{ __('Tolak') }}</button>
                        </form>
                    </div>
                    @endif
                    @if (in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                    <a href="{{ route('expenses.edit', $e->id) }}" class="btn btn-sm btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 8px;">
                        <i class="bi bi-pencil-square"></i> {{ __('Edit Data') }}
                    </a>
                    @endif
                    @if (in_array(auth()->user()->role, ['superadmin', 'admin']))
                    <form action="{{ route('expenses.destroy', $e) }}" method="POST" class="w-100 form-confirm-delete" data-text="{{ __('Data pengeluaran ini akan dihapus secara permanen dari sistem!') }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger w-100" style="border-radius: 8px;">{{ __('Hapus Catatan') }}</button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
        <span>Belum ada data pengeluaran.</span>
    </div>
    @endforelse
</div>

<!-- Floating Action Button (FAB) to Catat Pengeluaran -->
@if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
<div class="fab-container">
    <a href="{{ route('expenses.create') }}" class="fab-btn" title="Catat Pengeluaran Baru">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        function calculateTotalBiayaTampil() {
            var total = 0;
            $('.expense-card-col:visible').each(function () {
                var val = parseFloat($(this).data('jumlah')) || 0;
                total += val;
            });
            $('#totalBiayaTampilBadge').text('Total Terfilter: Rp ' + total.toLocaleString('id-ID'));
        }

        // Run calculation on initial load
        calculateTotalBiayaTampil();

        $('#expenseSearch').on('keyup', function () {
            var searchVal = this.value.toLowerCase().trim();
            $('.expense-card-col').each(function () {
                var searchData = $(this).data('search');
                if (searchData.indexOf(searchVal) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            calculateTotalBiayaTampil();
        });

        $('#exportCsvBtn').on('click', function () {
            var csv = ['sep=;', 'No;Plat Nomor;Jenis Pengeluaran;Tanggal;Jumlah Biaya (Rp);Keterangan;Status Persetujuan'];
            var no = 1;
            
            $('.expense-card-col:visible').each(function () {
                var row = [
                    no++,
                    '"' + $(this).data('plat') + '"',
                    '"' + $(this).data('jenis') + '"',
                    '"' + $(this).data('tanggal') + '"',
                    $(this).data('jumlah'),
                    '"' + String($(this).data('keterangan')).replace(/"/g, '""') + '"',
                    '"' + $(this).data('status') + '"'
                ];
                csv.push(row.join(';'));
            });

            var csvContent = "\uFEFF" + csv.join("\n");
            var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            var url = URL.createObjectURL(blob);
            var link = document.createElement("a");
            
            var tgl = new Date().toISOString().slice(0, 10);
            link.setAttribute("href", url);
            link.setAttribute("download", "laporan-pengeluaran-" + tgl + ".csv");
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // Handler for printing report PDF/Paper in a highly aesthetic corporate format
        $('#printReportBtn').on('click', function () {
            var visibleCards = $('.expense-card-col:visible');
            if (visibleCards.length === 0) {
                alert("Tidak ada data untuk dicetak!");
                return;
            }

            var printWindow = window.open('', '_blank');
            if (!printWindow) {
                alert("Pop-up diblokir oleh browser! Harap izinkan pop-up untuk mencetak laporan.");
                return;
            }
            
            // Gather active filter parameters for display
            var searchVal = "{{ request('q') }}" || "-";
            var jenisVal = "{{ request('jenis') }}" || "Semua Kategori";
            var bulanVal = "";
            @if(request('bulan'))
                bulanVal = "{{ \Carbon\Carbon::create(null, (int) request('bulan'), 1)->translatedFormat('F') }}";
            @else
                bulanVal = "Semua Bulan";
            @endif
            var tglMulai = "{{ request('tanggal_mulai') }}" || "";
            var tglSelesai = "{{ request('tanggal_selesai') }}" || "";
            var periodeStr = "";
            if (tglMulai && tglSelesai) {
                periodeStr = tglMulai + " s/d " + tglSelesai;
            } else if (tglMulai) {
                periodeStr = "Mulai dari " + tglMulai;
            } else if (tglSelesai) {
                periodeStr = "Sampai dengan " + tglSelesai;
            } else {
                periodeStr = bulanVal;
            }

            var rowsHtml = '';
            var no = 1;
            var totalBiaya = 0;
            var totalApproved = 0;
            var totalPending = 0;

            visibleCards.each(function () {
                var plat = $(this).data('plat');
                var jenis = $(this).data('jenis');
                var tanggal = $(this).data('tanggal');
                var jumlah = parseFloat($(this).data('jumlah')) || 0;
                var keterangan = $(this).data('keterangan');
                var status = $(this).data('status');

                totalBiaya += jumlah;
                if (status === 'Disetujui') {
                    totalApproved += jumlah;
                } else if (status === 'Menunggu Persetujuan') {
                    totalPending += jumlah;
                }

                var badgeClass = 'status-pending';
                if (status === 'Disetujui') badgeClass = 'status-approved';
                else if (status === 'Ditolak') badgeClass = 'status-rejected';

                rowsHtml += `
                    <tr>
                        <td style="text-align: center;">${no++}</td>
                        <td style="font-family: monospace; font-weight: bold; text-align: center; font-size: 11px;">${plat}</td>
                        <td style="text-align: center;">${jenis}</td>
                        <td style="text-align: center;">${tanggal}</td>
                        <td>${keterangan}</td>
                        <td style="text-align: center;"><span class="badge-print ${badgeClass}">${status}</span></td>
                        <td style="text-align: right; font-weight: bold; font-family: monospace;">Rp ${jumlah.toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });

            var tglCetak = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' WIB';
            
            var documentHtml = `
                <html>
                <head>
                    <title>Laporan Pengeluaran Operasional Armada</title>
                    <style>
                        body {
                            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                            color: #1e293b;
                            margin: 30px;
                            font-size: 11px;
                            line-height: 1.5;
                        }
                        .kop-surat {
                            border-bottom: 2px solid #0f172a;
                            padding-bottom: 12px;
                            margin-bottom: 20px;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                        }
                        .kop-left h1 {
                            margin: 0;
                            font-size: 22px;
                            color: #0f172a;
                            font-weight: 800;
                            letter-spacing: 0.5px;
                        }
                        .kop-left p {
                            margin: 3px 0 0 0;
                            color: #64748b;
                            font-size: 10px;
                            font-weight: 500;
                        }
                        .kop-right {
                            text-align: right;
                        }
                        .kop-right h2 {
                            margin: 0;
                            font-size: 12px;
                            color: #0f172a;
                            font-weight: 700;
                            letter-spacing: 0.3px;
                        }
                        .kop-right p {
                            margin: 3px 0 0 0;
                            font-size: 9px;
                            color: #64748b;
                        }
                        .laporan-title {
                            text-align: center;
                            font-size: 15px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin-bottom: 18px;
                            color: #0f172a;
                            letter-spacing: 0.5px;
                            text-decoration: underline;
                        }
                        .meta-info {
                            margin-bottom: 18px;
                            background: #f8fafc;
                            border: 1px solid #e2e8f0;
                            border-radius: 6px;
                            padding: 10px 14px;
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: space-between;
                        }
                        .meta-item {
                            flex-basis: 48%;
                            margin-bottom: 5px;
                        }
                        .meta-item strong {
                            color: #475569;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        th {
                            background-color: #f1f5f9;
                            border: 1px solid #cbd5e1;
                            padding: 8px 6px;
                            font-weight: bold;
                            text-align: center;
                            color: #1e293b;
                            font-size: 11px;
                        }
                        td {
                            border: 1px solid #cbd5e1;
                            padding: 7px 6px;
                            vertical-align: middle;
                            color: #334155;
                        }
                        tr:nth-child(even) {
                            background-color: #f8fafc;
                        }
                        .badge-print {
                            padding: 2.5px 6px;
                            border-radius: 4px;
                            font-size: 9px;
                            font-weight: bold;
                            text-transform: uppercase;
                            display: inline-block;
                        }
                        .status-approved {
                            background-color: #d1fae5;
                            color: #065f46;
                            border: 1px solid #a7f3d0;
                        }
                        .status-pending {
                            background-color: #fef3c7;
                            color: #92400e;
                            border: 1px solid #fde68a;
                        }
                        .status-rejected {
                            background-color: #fee2e2;
                            color: #991b1b;
                            border: 1px solid #fecaca;
                        }
                        .ringkasan-total {
                            display: flex;
                            justify-content: flex-end;
                            gap: 15px;
                            margin-bottom: 25px;
                        }
                        .total-box {
                            background: #f8fafc;
                            border: 1px solid #cbd5e1;
                            border-radius: 6px;
                            padding: 8px 12px;
                            text-align: right;
                            min-width: 130px;
                        }
                        .total-box h4 {
                            margin: 0 0 3px 0;
                            font-size: 9px;
                            color: #64748b;
                            text-transform: uppercase;
                        }
                        .total-box span {
                            font-size: 13px;
                            font-weight: bold;
                            font-family: monospace;
                        }
                        .total-box.approved-box {
                            border-left: 3px solid #10b981;
                        }
                        .total-box.grand-box {
                            border-left: 3px solid #3b82f6;
                            background: #eff6ff;
                        }
                        .ttd-section {
                            margin-top: 40px;
                            display: flex;
                            justify-content: space-between;
                            page-break-inside: avoid;
                        }
                        .ttd-box {
                            text-align: center;
                            width: 180px;
                        }
                        .ttd-space {
                            height: 60px;
                        }
                        .ttd-line {
                            border-top: 1px solid #0f172a;
                            margin-top: 5px;
                            font-weight: bold;
                            color: #0f172a;
                        }
                        @media print {
                            body {
                                margin: 10px;
                            }
                            .no-print {
                                display: none;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="kop-surat">
                        <div class="kop-left">
                             <h1>FleetMaintenance</h1>
                             <p>Sistem Manajemen Operasional & Pemeliharaan Kendaraan Perusahaan</p>
                         </div>
                         <div class="kop-right">
                             <h2>LAPORAN KEUANGAN & OPERASIONAL</h2>
                             <p>Sistem Manajemen Armada</p>
                        </div>
                    </div>
                    
                    <div class="laporan-title">Laporan Rekap Pengeluaran Biaya Kendaraan</div>
                    
                    <div class="meta-info">
                        <div class="meta-item"><strong>Tanggal Cetak:</strong> ${tglCetak}</div>
                        <div class="meta-item"><strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} (${{ auth()->user()->role }})</div>
                        <div class="meta-item"><strong>Filter Kategori:</strong> ${jenisVal}</div>
                        <div class="meta-item"><strong>Periode Laporan:</strong> ${periodeStr}</div>
                        ${searchVal !== '-' ? `<div class="meta-item"><strong>Pencarian Plat Nomor:</strong> ${searchVal}</div>` : ''}
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">Plat Nomor</th>
                                <th style="width: 15%;">Kategori</th>
                                <th style="width: 12%;">Tanggal</th>
                                <th style="width: 28%;">Keterangan</th>
                                <th style="width: 13%;">Status Approval</th>
                                <th style="width: 12%;">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>

                    <div class="ringkasan-total">
                        <div class="total-box">
                            <h4>Total Pending</h4>
                            <span style="color: #d97706;">Rp ${totalPending.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="total-box approved-box">
                            <h4>Total Disetujui</h4>
                            <span style="color: #059669;">Rp ${totalApproved.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="total-box grand-box">
                            <h4>Total Keseluruhan</h4>
                            <span style="color: #1e293b;">Rp ${totalBiaya.toLocaleString('id-ID')}</span>
                        </div>
                    </div>

                    <div class="ttd-section">
                        <div class="ttd-box">
                            <p>Dibuat Oleh,</p>
                            <div class="ttd-space"></div>
                            <div class="ttd-line">{{ auth()->user()->name }}</div>
                            <p style="margin: 3px 0 0 0; color: #64748b; font-size: 9px;">${{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <div class="ttd-box">
                            <p>Disetujui Oleh,</p>
                            <div class="ttd-space"></div>
                            <div class="ttd-line">Manager Operational</div>
                            <p style="margin: 3px 0 0 0; color: #64748b; font-size: 9px;">Direksi / Pimpinan</p>
                        </div>
                    </div>

                    <script>
                        window.onafterprint = function() {
                            window.close();
                        };
                        window.addEventListener('load', function() {
                            setTimeout(function() {
                                window.print();
                            }, 50);
                        });
                    <\/script>
                </body>
                </html>
            `;

            printWindow.document.write(documentHtml);
            printWindow.document.close();
        });
    });
</script>

<!-- Modal Kalkulator Efisiensi BBM di Menu Biaya -->
<div class="modal fade" id="fuelExpenseCalcModal" tabindex="-1" aria-labelledby="fuelExpenseCalcModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-warning text-dark py-3 px-4">
                <h6 class="modal-title fw-bold mb-0 d-flex align-items-center gap-2" id="fuelExpenseCalcModalLabel">
                    <i class="bi bi-fuel-pump-fill fs-5"></i> {{ __('Kalkulator Efisiensi BBM (KM/Liter)') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning py-2 px-3 d-flex align-items-center gap-2 mb-3 text-dark border-0 shadow-sm" style="border-radius: 10px; font-size: 0.82rem; background: #fef3c7;">
                    <i class="bi bi-calculator-fill fs-5 text-warning-emphasis"></i>
                    <div>{{ __('Hitung konsumsi rata-rata bahan bakar dan perkiraan biaya per kilometer.') }}</div>
                </div>

                {{-- Alert Sukses Simpan Odometer --}}
                <div id="alertOdoSaveSuccess" class="alert alert-success py-2 px-3 align-items-center gap-2 mb-3 text-success shadow-sm border-0" style="border-radius: 10px; font-size: 0.82rem; display: none; background: #dcfce7;">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div id="alertOdoSaveSuccessText">{{ __('Odometer akhir berhasil dipindahkan menjadi Odometer Awal!') }}</div>
                </div>

                {{-- Dropdown Pilihan Kendaraan --}}
                <div class="mb-3 p-3 bg-light rounded-3 border">
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <label class="form-label fw-bold text-dark text-uppercase mb-0" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="bi bi-truck me-1 text-primary"></i> {{ __('PILIH KENDARAAN ARMADA') }}
                        </label>
                        <button type="button" id="btnResetVehicleOdoMemory" class="btn btn-link p-0 text-danger text-decoration-none fw-semibold" style="font-size: 0.72rem; display: none;">
                            <i class="bi bi-arrow-counterclockwise"></i> {{ __('Reset ke Odometer Master') }}
                        </button>
                    </div>
                    <select id="calcExpVehicleSelect" class="form-select shadow-none border" style="font-size: 0.85rem; border-radius: 8px; background-color: #fff;">
                        <option value="">-- {{ __('Pilih Kendaraan (Auto-Fill)') }} --</option>
                        @foreach($vehicles ?? \App\Models\Vehicle::orderBy('plat_nomor')->get() as $v)
                            <option value="{{ $v->id }}" 
                                    data-odo="{{ (int)$v->odometer_terkini }}" 
                                    data-plat="{{ $v->plat_nomor }}"
                                    data-nama="{{ $v->plat_nomor }} - {{ $v->merek }} {{ $v->tipe }}">
                                {{ $v->plat_nomor }} - {{ $v->merek }} {{ $v->tipe }} (Odo: {{ number_format($v->odometer_terkini, 0, ',', '.') }} KM)
                            </option>
                        @endforeach
                    </select>
                    <div id="calcVehicleInfo" class="mt-2 text-muted" style="font-size: 0.75rem; line-height: 1.35;">
                        <span id="calcVehicleInfoText"><i class="bi bi-info-circle me-1"></i> {{ __('Pilih kendaraan agar odometer awal terisi otomatis.') }}</span>
                    </div>
                </div>

                {{-- Form Input Angka (Grid 2x2 Rapi & Tidak Tabrakan) --}}
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.72rem;">
                            {{ __('ODOMETER AWAL (KM)') }}
                        </label>
                        <input type="number" id="calcExpOdoPrev" class="form-control fw-bold" placeholder="Contoh: 0" value="0" style="border-radius: 8px; font-size: 0.95rem;">
                        <div class="mt-1" style="min-height: 22px;">
                            <span id="badgeOdoAwalAuto" class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.68rem; display: none;">
                                <i class="bi bi-clock-history me-1"></i> Lanjutan: 0 KM
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.72rem;">
                            {{ __('ODOMETER AKHIR (KM)') }}
                        </label>
                        <input type="number" id="calcExpOdoCurr" class="form-control fw-bold" placeholder="Contoh: 4000" value="4000" style="border-radius: 8px; font-size: 0.95rem;">
                        <div class="mt-1" style="min-height: 22px;">
                            <span class="text-muted d-inline-flex align-items-center" style="font-size: 0.7rem;">
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i> Perjalanan Baru
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.72rem;">
                            {{ __('BBM TERISI (LITER)') }}
                        </label>
                        <div class="input-group">
                            <input type="number" step="0.1" id="calcExpLiters" class="form-control fw-bold" placeholder="Contoh: 35" value="35" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; font-size: 0.95rem;">
                            <span class="input-group-text bg-light text-muted fw-bold" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; font-size: 0.78rem;">Liter</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.72rem;">
                            {{ __('NOMINAL BIAYA (RP)') }}
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted fw-bold" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; font-size: 0.78rem;">Rp</span>
                            <input type="number" id="calcExpCost" class="form-control fw-bold" placeholder="Contoh: 350000" value="350000" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; font-size: 0.95rem;">
                        </div>
                    </div>
                </div>

                {{-- Hasil Analisis Konsumsi Box --}}
                <div class="p-3 bg-light rounded-4 border text-center">
                    <span class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ __('Hasil Analisis Konsumsi') }}</span>
                    <div class="d-flex justify-content-around align-items-center my-3">
                        <div>
                            <small class="text-muted d-block fw-medium" style="font-size: 0.75rem;">{{ __('Jarak Tempuh') }}</small>
                            <h5 class="fw-bold text-dark mb-0 font-monospace" id="calcExpResDist">4.000 km</h5>
                        </div>
                        <div class="border-start border-end px-3">
                            <small class="text-muted d-block fw-medium" style="font-size: 0.75rem;">{{ __('Konsumsi Rata-rata') }}</small>
                            <h4 class="fw-bold text-primary mb-0 font-monospace" id="calcExpResKmL">114.3 km/L</h4>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-medium" style="font-size: 0.75rem;">{{ __('Biaya / KM') }}</small>
                            <h5 class="fw-bold text-dark mb-0 font-monospace" id="calcExpResCostKm">Rp 88 /km</h5>
                        </div>
                    </div>
                    <div id="calcExpBadgeStatus" class="mt-2">
                        <span class="badge bg-success px-3 py-1.5 fw-bold"><i class="bi bi-patch-check-fill me-1"></i> Efisiensi Sangat Baik (Sangat Irit)</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-3 px-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btnSaveOdoFooter" class="btn btn-primary fw-bold px-3 py-2 d-flex align-items-center gap-1.5 shadow-sm" style="background: linear-gradient(135deg, #0e3054 0%, #0891b2 100%); border: none; border-radius: 8px; font-size: 0.84rem;">
                        <i class="bi bi-cloud-arrow-up-fill"></i> {{ __('Simpan Odometer Terbaru') }}
                    </button>
                    @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                    <button type="button" id="btnRecordQuickBbm" class="btn btn-warning text-dark fw-bold px-3 py-2 d-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 8px; font-size: 0.84rem;">
                        <i class="bi bi-wallet2"></i> {{ __('Catat Biaya BBM & Update Odo') }}
                    </button>
                    @endif
                </div>
                <button type="button" class="btn btn-outline-secondary fw-semibold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">{{ __('Tutup') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vehicleSelect = document.getElementById('calcExpVehicleSelect');
    const odoPrevInput = document.getElementById('calcExpOdoPrev');
    const odoCurrInput = document.getElementById('calcExpOdoCurr');
    const litersInput = document.getElementById('calcExpLiters');
    const costInput = document.getElementById('calcExpCost');
    const vehicleInfoText = document.getElementById('calcVehicleInfoText');
    const btnResetOdo = document.getElementById('btnResetVehicleOdoMemory');
    const badgeOdoAuto = document.getElementById('badgeOdoAwalAuto');
    const btnSaveFooter = document.getElementById('btnSaveOdoFooter');
    const btnRecordQuickBbm = document.getElementById('btnRecordQuickBbm');
    const alertSuccess = document.getElementById('alertOdoSaveSuccess');
    const alertSuccessText = document.getElementById('alertOdoSaveSuccessText');

    function calculateExpFuel() {
        const odoPrev = parseFloat(odoPrevInput?.value) || 0;
        const odoCurr = parseFloat(odoCurrInput?.value) || 0;
        const liters = parseFloat(litersInput?.value) || 0;
        const cost = parseFloat(costInput?.value) || 0;

        const distance = Math.max(0, odoCurr - odoPrev);
        const kmL = liters > 0 ? (distance / liters) : 0;
        const costPerKm = distance > 0 ? (cost / distance) : 0;

        const distEl = document.getElementById('calcExpResDist');
        const kmlEl = document.getElementById('calcExpResKmL');
        const costEl = document.getElementById('calcExpResCostKm');
        const badgeContainer = document.getElementById('calcExpBadgeStatus');

        if (distEl) distEl.innerText = distance.toLocaleString('id-ID') + ' km';
        if (kmlEl) kmlEl.innerText = kmL.toFixed(1) + ' km/L';
        if (costEl) costEl.innerText = 'Rp ' + Math.round(costPerKm).toLocaleString('id-ID') + ' /km';

        if (badgeContainer) {
            if (distance === 0 || liters === 0) {
                badgeContainer.innerHTML = '<span class="badge bg-secondary px-3 py-1.5 fw-bold"><i class="bi bi-info-circle me-1"></i> Masukkan data Odometer & Liter BBM</span>';
            } else if (kmL >= 12) {
                badgeContainer.innerHTML = '<span class="badge bg-success px-3 py-1.5 fw-bold"><i class="bi bi-patch-check-fill me-1"></i> Efisiensi Sangat Baik (Sangat Irit)</span>';
            } else if (kmL >= 8) {
                badgeContainer.innerHTML = '<span class="badge bg-warning text-dark px-3 py-1.5 fw-bold"><i class="bi bi-check-circle me-1"></i> Efisiensi Standar / Normal</span>';
            } else {
                badgeContainer.innerHTML = '<span class="badge bg-danger px-3 py-1.5 fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Konsumsi Boros (Perlu Tune-up)</span>';
            }
        }
    }

    // Perbarui teks dalam dropdown opsi jika ada rekaman baru
    function refreshDropdownOptionText(option, vehicleId, odoVal, plat, namaLengkap) {
        if (!option) return;
        option.setAttribute('data-odo', odoVal);
        option.text = plat + ' - ' + namaLengkap + ' (Odo Terkini: ' + parseFloat(odoVal).toLocaleString('id-ID') + ' KM)';
    }

    // Sinkronkan seluruh list dropdown pada awal muat
    function refreshAllDropdownOptions() {
        if (!vehicleSelect) return;
        for (let i = 1; i < vehicleSelect.options.length; i++) {
            const opt = vehicleSelect.options[i];
            const vId = opt.value;
            const defOdo = parseFloat(opt.getAttribute('data-odo')) || 0;
            const plat = opt.getAttribute('data-plat') || '';
            const nama = opt.getAttribute('data-nama') || plat;
            
            const savedLastOdo = localStorage.getItem('calc_last_odo_akhir_vehicle_' + vId);
            const finalOdo = (savedLastOdo !== null && parseFloat(savedLastOdo) >= defOdo) ? parseFloat(savedLastOdo) : defOdo;
            refreshDropdownOptionText(opt, vId, finalOdo, plat, nama);
        }
    }

    // Fungsi saat kendaraan dipilih di dropdown
    function onVehicleChange() {
        if (!vehicleSelect) return;
        const vehicleId = vehicleSelect.value;

        if (!vehicleId) {
            if (badgeOdoAuto) badgeOdoAuto.style.display = 'none';
            if (btnResetOdo) btnResetOdo.style.display = 'none';
            if (vehicleInfoText) vehicleInfoText.innerHTML = '<i class="bi bi-info-circle me-1"></i> {{ __("Pilih kendaraan agar odometer awal terisi otomatis.") }}';
            odoPrevInput.value = 0;
            odoCurrInput.value = 0;
            calculateExpFuel();
            return;
        }

        const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
        const defaultOdo = parseFloat(selectedOption?.getAttribute('data-odo')) || 0;
        const plat = selectedOption?.getAttribute('data-plat') || '';
        const nama = selectedOption?.getAttribute('data-nama') || plat;

        // Cek memori odometer terkini
        const savedLastOdoStr = localStorage.getItem('calc_last_odo_akhir_vehicle_' + vehicleId);
        const savedLastOdo = savedLastOdoStr !== null ? parseFloat(savedLastOdoStr) : null;
        const currentActiveOdo = (savedLastOdo !== null && !isNaN(savedLastOdo) && savedLastOdo >= defaultOdo) ? savedLastOdo : defaultOdo;

        odoPrevInput.value = currentActiveOdo;
        odoCurrInput.value = currentActiveOdo + 350;

        if (badgeOdoAuto) {
            badgeOdoAuto.style.display = 'inline-block';
            badgeOdoAuto.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 py-1';
            badgeOdoAuto.innerHTML = '<i class="bi bi-speedometer2 me-1"></i> Odo Terkini: ' + currentActiveOdo.toLocaleString('id-ID') + ' KM';
        }

        if (vehicleInfoText) {
            vehicleInfoText.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> <strong>' + plat + '</strong>: Odometer awal <strong>' + currentActiveOdo.toLocaleString('id-ID') + ' KM</strong>.';
        }
        if (btnResetOdo) btnResetOdo.style.display = 'inline-block';

        refreshDropdownOptionText(selectedOption, vehicleId, currentActiveOdo, plat, nama);
        localStorage.setItem('calc_last_selected_vehicle', vehicleId);
        calculateExpFuel();
    }

    // FUNGSI UTAMA: SIMPAN ODOMETER KE DATABASE SECARA PERMANEN
    async function executeShiftOdoToAwal() {
        const vehicleId = vehicleSelect?.value;
        const odoAkhirVal = parseFloat(odoCurrInput?.value);

        if (!vehicleId) {
            alert('Silakan pilih armada kendaraan terlebih dahulu.');
            vehicleSelect?.focus();
            return;
        }

        if (isNaN(odoAkhirVal) || odoAkhirVal <= 0) {
            alert('Silakan masukkan angka Odometer Akhir yang valid terlebih dahulu.');
            odoCurrInput.focus();
            return;
        }

        const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
        const plat = selectedOption?.getAttribute('data-plat') || 'Armada';
        const nama = selectedOption?.getAttribute('data-nama') || plat;

        const origBtnHtml = btnSaveFooter.innerHTML;
        btnSaveFooter.disabled = true;
        btnSaveFooter.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan ke Database...';

        try {
            const response = await fetch('/vehicles/' + vehicleId + '/odometer', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    odometer: Math.round(odoAkhirVal)
                })
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                // 1. Pindahkan nilai Odometer Akhir menjadi Odometer Awal
                odoPrevInput.value = odoAkhirVal;

                // 2. Simpan ke LocalStorage agar browser selalu ingat
                localStorage.setItem('calc_last_odo_akhir_vehicle_' + vehicleId, odoAkhirVal);

                // 3. Update atribut data-odo dan teks dropdown secara permanen
                refreshDropdownOptionText(selectedOption, vehicleId, odoAkhirVal, plat, nama);

                // 4. Update Badge Odometer Awal & Subtext
                if (badgeOdoAuto) {
                    badgeOdoAuto.style.display = 'inline-block';
                    badgeOdoAuto.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 py-1';
                    badgeOdoAuto.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Tersimpan: ' + odoAkhirVal.toLocaleString('id-ID') + ' KM';
                }

                if (vehicleInfoText) {
                    vehicleInfoText.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> <strong>' + plat + '</strong>: Odometer terbaru <strong>' + odoAkhirVal.toLocaleString('id-ID') + ' KM</strong> berhasil tersimpan permanen di database.';
                }

                if (btnResetOdo) btnResetOdo.style.display = 'inline-block';

                // 5. Siapkan Odometer Akhir untuk perjalanan berikutnya (+350 KM)
                const nextEstimatedOdo = odoAkhirVal + 350;
                odoCurrInput.value = nextEstimatedOdo;

                // 6. Tampilkan Notifikasi Alert Sukses
                if (alertSuccess) {
                    alertSuccess.style.display = 'flex';
                    if (alertSuccessText) {
                        alertSuccessText.innerHTML = '<strong>Berhasil Tersimpan Permanen!</strong> Odometer <strong>' + plat + '</strong> kini diset ke <strong>' + odoAkhirVal.toLocaleString('id-ID') + ' KM</strong> di database & dijadikan Odometer Awal.';
                    }
                    setTimeout(() => {
                        if (alertSuccess) alertSuccess.style.display = 'none';
                    }, 5000);
                }

                calculateExpFuel();
                if (litersInput) litersInput.focus();
            } else {
                alert('Gagal menyimpan odometer: ' + (result.message || 'Terjadi kesalahan sistem.'));
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kendala jaringan saat menghubungi server.');
        } finally {
            btnSaveFooter.disabled = false;
            btnSaveFooter.innerHTML = origBtnHtml;
        }
    }

    // FUNGSI KEDUA: CATAT LANGSUNG PENGELUARAN BBM & UPDATE ODOMETER KE DATABASE
    async function executeQuickBbmExpense() {
        const vehicleId = vehicleSelect?.value;
        const odoAkhirVal = parseFloat(odoCurrInput?.value);
        const litersVal = parseFloat(litersInput?.value);
        const costVal = parseFloat(costInput?.value);

        if (!vehicleId) {
            alert('Silakan pilih armada kendaraan terlebih dahulu.');
            vehicleSelect?.focus();
            return;
        }

        if (isNaN(odoAkhirVal) || odoAkhirVal <= 0) {
            alert('Silakan masukkan angka Odometer Akhir yang valid.');
            odoCurrInput.focus();
            return;
        }

        if (isNaN(litersVal) || litersVal <= 0) {
            alert('Silakan masukkan jumlah Liter BBM.');
            litersInput.focus();
            return;
        }

        if (isNaN(costVal) || costVal <= 0) {
            alert('Silakan masukkan nominal biaya BBM.');
            costInput.focus();
            return;
        }

        if (!btnRecordQuickBbm) return;
        const origBtnHtml = btnRecordQuickBbm.innerHTML;
        btnRecordQuickBbm.disabled = true;
        btnRecordQuickBbm.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan Data...';

        try {
            const response = await fetch('/expenses/quick-bbm', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    vehicle_id: vehicleId,
                    odometer_akhir: Math.round(odoAkhirVal),
                    liter_bbm: litersVal,
                    jumlah_biaya: costVal,
                    keterangan: 'Pengisian BBM ' + litersVal + 'L pada Odo ' + odoAkhirVal.toLocaleString('id-ID') + ' KM'
                })
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                odoPrevInput.value = odoAkhirVal;
                localStorage.setItem('calc_last_odo_akhir_vehicle_' + vehicleId, odoAkhirVal);
                
                const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                const plat = selectedOption?.getAttribute('data-plat') || 'Armada';
                const nama = selectedOption?.getAttribute('data-nama') || plat;
                refreshDropdownOptionText(selectedOption, vehicleId, odoAkhirVal, plat, nama);

                const nextEstimatedOdo = odoAkhirVal + 350;
                odoCurrInput.value = nextEstimatedOdo;

                if (alertSuccess) {
                    alertSuccess.style.display = 'flex';
                    if (alertSuccessText) {
                        alertSuccessText.innerHTML = '<strong>Sukses!</strong> ' + result.message;
                    }
                }

                calculateExpFuel();

                // Reload halaman agar rekap biaya di tabel langsung terupdate
                setTimeout(() => {
                    window.location.reload();
                }, 1800);
            } else {
                alert('Gagal mencatat pengeluaran: ' + (result.message || 'Terjadi kesalahan sistem.'));
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kendala jaringan saat menghubungi server.');
        } finally {
            btnRecordQuickBbm.disabled = false;
            btnRecordQuickBbm.innerHTML = origBtnHtml;
        }
    }

    if (vehicleSelect) {
        vehicleSelect.addEventListener('change', onVehicleChange);
    }

    if (btnSaveFooter) {
        btnSaveFooter.addEventListener('click', executeShiftOdoToAwal);
    }

    if (btnRecordQuickBbm) {
        btnRecordQuickBbm.addEventListener('click', executeQuickBbmExpense);
    }

    if (btnResetOdo) {
        btnResetOdo.addEventListener('click', function() {
            const vehicleId = vehicleSelect?.value;
            if (vehicleId) {
                localStorage.removeItem('calc_last_odo_akhir_vehicle_' + vehicleId);
                const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                const defaultOdo = parseFloat(selectedOption?.getAttribute('data-odo')) || 0;
                const plat = selectedOption?.getAttribute('data-plat') || '';
                const nama = selectedOption?.getAttribute('data-nama') || plat;
                refreshDropdownOptionText(selectedOption, vehicleId, defaultOdo, plat, nama);
                onVehicleChange();
            }
        });
    }

    [odoPrevInput, odoCurrInput, litersInput, costInput].forEach(el => {
        if (el) {
            el.addEventListener('input', function() {
                calculateExpFuel();
            });
        }
    });

    // Inisialisasi teks dropdown
    refreshAllDropdownOptions();

    // Pulihkan kendaraan terakhir saat modal dibuka atau halaman dimuat
    const lastVehicleId = localStorage.getItem('calc_last_selected_vehicle');
    if (lastVehicleId && vehicleSelect) {
        vehicleSelect.value = lastVehicleId;
        if (vehicleSelect.value) {
            onVehicleChange();
        }
    } else if (vehicleSelect && vehicleSelect.options.length > 1) {
        vehicleSelect.selectedIndex = 1;
        onVehicleChange();
    }

    calculateExpFuel();
});
</script>
@endsection