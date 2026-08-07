@extends('layouts.app')
@section('title', 'Rekap Biaya')

@section('content')
<style>
    .badge-soft-success { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-soft-warning { background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-soft-danger { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .table-hover tbody tr:hover { background-color: #f9fafb; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Rekap Biaya Operasional</h3>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Catat dan kelola pengeluaran harian operasional kendaraan.</p>
    </div>
    <div class="d-flex gap-2">
        <button id="exportCsvBtn" class="btn btn-outline-success px-4 py-2 d-flex align-items-center gap-2" title="Ekspor Data Tampil ke CSV">
            <i class="bi bi-file-earmark-excel-fill"></i>
            <span>Ekspor CSV</span>
        </button>
        @if (in_array(auth()->user()->role, ['admin', 'teknisi']))
            <a href="{{ route('expenses.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Catat Pengeluaran</span>
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
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Biaya Disetujui (Bulan Ini)</span>
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
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Menunggu Persetujuan Manager</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $expenses->where('status_approval', 'Menunggu Persetujuan')->count() }} <small class="text-muted fs-6 font-normal">Pengajuan</small></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form action="{{ route('expenses.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Cari plat nomor..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    @foreach (['BBM', 'Tol', 'Bengkel', 'Parkir', 'Pajak', 'Lainnya'] as $j)
                        <option value="{{ $j }}" @selected(request('jenis') === $j)>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" @selected((string) request('bulan') === (string) $m)>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                @if (request('q') || request('jenis') || request('bulan'))
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="p-2 rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-pie-chart-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Ringkasan Biaya per Kendaraan (Bulan Ini)</h6>
                <small class="text-muted" style="font-size: 0.78rem;">Analisis akumulasi biaya operasional &amp; pemeliharaan per armada</small>
            </div>
        </div>
        @if($rekapPerKendaraan->count() > 0)
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.78rem;">
                Total: Rp {{ number_format($rekapPerKendaraan->sum('total'), 0, ',', '.') }}
            </span>
        @endif
    </div>
    <div class="card-body p-4">
        @forelse ($rekapPerKendaraan as $r)
            @php
                $maxTotal = $rekapPerKendaraan->max('total') ?: 1;
                $percentage = min(100, round(($r->total / $maxTotal) * 100));
                $barColor = $loop->first ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-primary');
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
                <span style="font-size: 0.85rem;">Tidak ada akumulasi biaya bulan ini.</span>
            </div>
        @endforelse

        <div class="mt-3 pt-2 border-top d-flex align-items-center gap-2 text-muted" style="font-size: 0.8rem;">
            <i class="bi bi-info-circle text-primary"></i>
            <span>Grafik di atas memprioritaskan kendaraan dengan total biaya pengeluaran tertinggi bulan ini untuk kemudahan evaluasi unit boros/masuk bengkel.</span>
        </div>
    </div>
</div>

<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-grid-fill text-primary fs-5"></i>
            <span class="fw-bold text-dark fs-5">Rincian Pengeluaran Operasional</span>
        </div>
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-semibold" id="totalBiayaTampilBadge" style="font-size: 0.82rem;">
            Total Terfilter: Rp 0
        </span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <input type="text" id="expenseSearch" class="form-control form-control-sm" placeholder="🔍 Cari pengeluaran..." style="width: 250px; border-radius: 8px; font-size: 0.85rem; box-shadow: none;">
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
        <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs hover-card transition-all" style="transition: all 0.25s ease; border-radius: 16px;">
            <!-- Vehicle Image -->
            <div class="position-relative" style="height: 160px; overflow: hidden; background: #f8fafc;">
                <img src="{{ $e->vehicle->foto_url }}" alt="{{ $e->vehicle->plat_nomor }}" class="w-100 h-100 object-fit-cover transition-img" style="transition: transform 0.3s ease;">
                <!-- Floating Plate Badge -->
                <span class="position-absolute badge bg-dark text-white font-monospace px-3 py-2 fs-6 border border-secondary shadow-sm" style="border-radius: 10px; letter-spacing: 0.8px; top: 12px; left: 12px; z-index: 5;">
                    {{ $e->vehicle->plat_nomor }}
                </span>
                <!-- Floating Status Badge -->
                <div class="position-absolute" style="top: 12px; right: 12px; z-index: 5;">
                    @if ($e->status_approval === 'Disetujui')
                        <span class="badge bg-success text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">Disetujui</span>
                    @elseif ($e->status_approval === 'Menunggu Persetujuan')
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">Menunggu</span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">Ditolak</span>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                @if ($e->status_approval === 'Menunggu Persetujuan')
                    <div class="alert alert-warning py-1.5 px-2.5 mb-3 d-flex align-items-center gap-2" style="font-size: 0.76rem; border-radius: 8px; border: 1px solid #fde68a; background: #fffbeb;">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        <span class="fw-bold" style="color: #92400e;">Menunggu persetujuan Manager</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase text-primary fw-bold font-monospace" style="font-size: 0.72rem; letter-spacing: 0.8px;">{{ $e->jenis_pengeluaran }}</span>
                    <span class="text-muted font-monospace" style="font-size: 0.8rem;"><i class="bi bi-calendar3"></i> {{ $e->tanggal->format('d M Y') }}</span>
                </div>

                <h4 class="fw-extrabold text-dark mb-2">Rp {{ number_format($e->jumlah_biaya, 0, ',', '.') }}</h4>
                <p class="text-secondary font-monospace" style="font-size: 0.85rem; line-height: 1.4;">{{ $e->keterangan }}</p>

                <!-- Actions -->
                @if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'pimpinan']))
                <div class="d-flex flex-column gap-2 pt-3 border-top mt-3">
                    @if ($e->status_approval === 'Menunggu Persetujuan')
                    <div class="d-flex gap-2">
                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="w-100">
                            @csrf @method('PUT')
                            <button type="submit" name="status_approval" value="Disetujui" class="btn btn-sm btn-success w-100" style="border-radius: 8px;">Setujui</button>
                        </form>
                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="w-100">
                            @csrf @method('PUT')
                            <button type="submit" name="status_approval" value="Ditolak" class="btn btn-sm btn-danger w-100" style="border-radius: 8px;">Tolak</button>
                        </form>
                    </div>
                    @endif
                    @if (in_array(auth()->user()->role, ['superadmin', 'admin']))
                    <form action="{{ route('expenses.destroy', $e) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="w-100">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger w-100" style="border-radius: 8px;">Hapus Catatan</button>
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
    });
</script>
@endsection