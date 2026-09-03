@extends('layouts.app')
@section('title', __('Checklist Harian'))

@section('content')
<style>
    /* ===== PAGE HEADER ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }
    .page-header-left h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }
    .page-header-left p {
        font-size: 0.875rem;
        color: #64748b;
        margin: 0;
    }
    .btn-input-checklist {
        background: linear-gradient(135deg, #0e3054 0%, #0891b2 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25);
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-input-checklist:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(8, 145, 178, 0.35);
        background: linear-gradient(135deg, #0f172a 0%, #0e7490 100%);
        color: #fff;
    }
    .btn-input-checklist i {
        font-size: 1rem;
    }

    /* ===== SUMMARY CARDS ===== */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .summary-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .summary-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        transform: translateY(-2px);
    }
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .summary-icon.blue   { background: rgba(8, 145, 178, 0.08); color: #0891b2; }
    .summary-icon.green  { background: #f0fdf4; color: #16a34a; }
    .summary-icon.red    { background: #fef2f2; color: #dc2626; }
    .summary-card-body .label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .summary-card-body .value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }

    body.dark-theme .summary-card {
        background: #111827 !important;
        border-color: #1e293b !important;
    }
    body.dark-theme .summary-card-body .value {
        color: #f1f5f9 !important;
    }
    body.dark-theme .summary-card-body .label {
        color: #94a3b8 !important;
    }
    body.dark-theme .page-header-left h3 {
        color: #f1f5f9 !important;
    }
    body.dark-theme .page-header-left p {
        color: #94a3b8 !important;
    }
    body.dark-theme .summary-icon.blue { background: #1e293b !important; color: #60a5fa !important; }
    body.dark-theme .summary-icon.green { background: #064e3b !important; color: #34d399 !important; }
    body.dark-theme .summary-icon.red { background: #7f1d1d !important; color: #f87171 !important; }
    body.dark-theme .vehicle-image-wrapper { background-color: #0f172a !important; }

    /* Custom Input Group & Dropdown Styles */
    .custom-input-group {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background-color: #ffffff !important;
    }
    .custom-input-group input {
        box-shadow: none !important;
        color: #0f172a !important;
    }
    .custom-input-group input::placeholder {
        color: #64748b !important;
        opacity: 1 !important;
    }
    .custom-input-group .input-group-text {
        color: #64748b !important;
    }
    #checklistFilterDropdown {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px;
    }

    /* Pendukung tema gelap (dark theme) */
    body.dark-theme .custom-input-group {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    body.dark-theme .custom-input-group input {
        color: #f8fafc !important;
    }
    body.dark-theme .custom-input-group input::placeholder {
        color: #94a3b8 !important;
    }
    body.dark-theme .custom-input-group .input-group-text {
        color: #94a3b8 !important;
    }
    body.dark-theme #checklistFilterDropdown {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .table-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .table-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-card-title i {
        color: #dc2626;
    }

    /* ===== CUSTOM TABLE ===== */
    #tabelChecklist thead th {
        background: #f8fafc !important;
        color: #64748b !important;
        font-weight: 600 !important;
        font-size: 0.72rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.7px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 13px 18px !important;
        white-space: nowrap;
    }
    #tabelChecklist tbody td {
        padding: 14px 18px !important;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
    }
    #tabelChecklist tbody tr {
        transition: background-color 0.15s ease;
    }
    #tabelChecklist tbody tr:hover {
        background-color: #f8fafc !important;
    }
    #tabelChecklist tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Baris bermasalah */
    .row-issue {
        background-color: #fffbeb !important;
        border-left: 3px solid #f59e0b;
    }
    .row-issue:hover {
        background-color: #fef9e7 !important;
    }

    /* ===== BADGES ===== */
    .badge-kondisi {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .badge-kondisi.ok {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }
    .badge-kondisi.warn {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }
    .badge-kondisi.danger {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .badge-masalah {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        margin: 2px 2px 2px 0;
    }
    .badge-masalah i {
        font-size: 0.7rem;
    }
    .text-semua-baik {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #16a34a;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Plat nomor */
    .plat-nomor {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
    }

    /* Tanggal */
    .tgl-cell {
        color: #64748b;
        font-size: 0.825rem;
        font-weight: 500;
    }

    /* Teknisi */
    .teknisi-cell {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .teknisi-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #dc2626;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* Catatan */
    .catatan-cell {
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #64748b;
        font-style: italic;
        font-size: 0.82rem;
    }
    .catatan-empty {
        color: #cbd5e1;
        font-size: 0.8rem;
    }

    /* Delete button */
    .btn-delete {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #dc2626;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .btn-delete:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3);
        transform: scale(1.05);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state-icon {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #94a3b8;
        margin: 0 auto 16px;
    }
    .empty-state h6 {
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }
    .empty-state p {
        font-size: 0.85rem;
        color: #94a3b8;
        margin: 0;
    }

    /* DataTables override */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.85rem;
        outline: none;
        box-shadow: none;
        transition: border-color 0.2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #dc2626;
    }
    .dataTables_wrapper .dataTables_filter label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }
    .dataTables_wrapper .dataTables_info {
        font-size: 0.8rem;
        color: #94a3b8;
        padding-top: 12px !important;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 8px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 7px !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        padding: 5px 11px !important;
        color: #64748b !important;
        border: 1px solid transparent !important;
        transition: all 0.15s ease;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #dc2626 !important;
        border-color: transparent !important;
        color: #fff !important;
        box-shadow: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: #fff !important;
    }
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0;
    }
    div.dataTables_wrapper div.dataTables_filter {
        text-align: left;
    }
    .dt-footer-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    /* Filter Tabs */
    .nav-pills .nav-link {
        color: #64748b;
        background: #fff;
        border: 1px solid #e2e8f0;
    }
    .nav-pills .nav-link:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .nav-pills .nav-link.active {
        background: #dc2626 !important;
        color: #fff !important;
        border-color: transparent !important;
        box-shadow: none;
    }
    .hover-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.08) !important;
    }
    .hover-card:hover .transition-img {
        transform: scale(1.06);
    }
</style>

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <h3><i class="bi bi-clipboard2-check-fill me-2" style="color:#0891b2;"></i>{{ __('Checklist Harian') }}</h3>
        <p>{{ __('Pantau & rekap kondisi harian seluruh armada kendaraan.') }}</p>
    </div>
    @if (in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi', 'user']))
        <a href="{{ route('checklist.create') }}" class="btn-input-checklist">
            <i class="bi bi-plus-circle-fill"></i> {{ __('Input Checklist') }}
        </a>
    @endif
</div>

{{-- Summary Cards --}}
@php
    $paramMap = [
        'oli_mesin'    => 'Oli',
        'air_radiator' => 'Radiator',
        'minyak_rem'   => 'Rem',
        'ban_rem'      => 'Ban',
        'lampu_klakson'=> 'Lampu',
        'kebersihan'   => 'Kebersihan',
    ];
    $totalChecklist  = $checklists->count();
    $totalBaik       = $checklists->filter(fn($c) => !$c->ada_masalah)->count();
    $totalBermasalah = $checklists->filter(fn($c) =>  $c->ada_masalah)->count();
@endphp
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-icon blue"><i class="bi bi-clipboard-data-fill"></i></div>
        <div class="summary-card-body">
            <div class="label">{{ __('Total Checklist') }}</div>
            <div class="value">{{ $totalChecklist }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon green"><i class="bi bi-shield-fill-check"></i></div>
        <div class="summary-card-body">
            <div class="label">{{ __('Kondisi Baik') }}</div>
            <div class="value">{{ $totalBaik }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon red"><i class="bi bi-exclamation-octagon-fill"></i></div>
        <div class="summary-card-body">
            <div class="label">{{ __('Ada Masalah') }}</div>
            <div class="value">{{ $totalBermasalah }}</div>
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="d-flex align-items-center gap-3 justify-content-start flex-wrap">
        <div class="input-group custom-input-group" style="width: 380px !important; flex-shrink: 0;">
            <span class="input-group-text bg-transparent border-0 text-muted" style="padding-right: 0;"><i class="bi bi-search"></i></span>
            <input type="text" id="checklistSearch" class="form-control bg-transparent border-0 shadow-none" placeholder="{{ __('Cari berdasarkan Plat, Driver, Merk, atau Catatan...') }}" style="font-size: 0.88rem; padding-left: 8px;">
        </div>
        <select id="checklistFilterDropdown" class="form-select border shadow-none" style="width: 195px !important; min-width: 195px !important; font-size: 0.88rem; flex-shrink: 0;">
            <option value="all">{{ __('Semua Status') }} ({{ $totalChecklist }})</option>
            <option value="baik">{{ __('Kondisi Baik') }} ({{ $totalBaik }})</option>
            <option value="masalah">{{ __('Ada Masalah') }} ({{ $totalBermasalah }})</option>
        </select>
    </div>
</div>

<div class="row g-4" id="checklistGrid">
    @forelse ($checklists as $c)
    @php
        $totalOk = collect($paramMap)->keys()->filter(fn($k) => $c->$k === 'OK')->count();
        $persen  = round(($totalOk / 6) * 100);
    @endphp
    <div class="col-md-4 checklist-card-col" 
         data-id="{{ $c->id }}"
         data-odometer-raw="{{ $c->odometer }}"
         data-search="{{ strtolower($c->vehicle->plat_nomor . ' ' . $c->nama_teknisi . ' ' . $c->tanggal->format('d/m/Y') . ' ' . $c->catatan_tambahan) }}"
         data-plat="{{ $c->vehicle->plat_nomor }}"
         data-merek="{{ $c->vehicle->merek }} {{ $c->vehicle->tipe }}"
         data-jenis="{{ $c->vehicle->jenis_kendaraan }}"
         data-foto="{{ $c->vehicle->foto_url }}"
         data-teknisi="{{ $c->nama_teknisi }}"
         data-tanggal="{{ $c->tanggal->translatedFormat('d F Y') }}"
         data-odometer="{{ number_format($c->odometer, 0, ',', '.') }} km"
         data-oli="{{ $c->oli_mesin }}"
         data-radiator="{{ $c->air_radiator }}"
         data-rem="{{ $c->minyak_rem }}"
         data-ban="{{ $c->ban_rem }}"
         data-lampu="{{ $c->lampu_klakson }}"
         data-kebersihan="{{ $c->kebersihan }}"
         data-catatan="{{ $c->catatan_tambahan ?? __('Tidak ada catatan tambahan.') }}"
         data-masalah="{{ $c->ada_masalah ? 1 : 0 }}">
        <div class="card h-100 border border-slate-100 rounded-4 overflow-hidden shadow-xs hover-card transition-all p-3 {{ $c->ada_masalah ? 'border-warning' : '' }}" style="transition: all 0.25s ease; border-radius: 16px;">
            <!-- Plate & Status Header (Above Image) -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Plate Badge -->
                <span class="badge bg-dark text-white font-monospace px-3 py-2 fs-6 border border-secondary shadow-sm" style="border-radius: 10px; letter-spacing: 0.8px;">
                    {{ $c->vehicle->plat_nomor }}
                </span>
                <!-- Status Score -->
                <div>
                    @if ($totalOk === 6)
                        <span class="badge bg-success text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                            <i class="bi bi-check-circle-fill"></i> 6/6 {{ __('Baik') }}
                        </span>
                    @elseif ($totalOk >= 4)
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $totalOk }}/6 {{ __('Baik') }}
                        </span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                            <i class="bi bi-x-circle-fill"></i> {{ $totalOk }}/6 {{ __('Baik') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Vehicle Image -->
            <div class="position-relative vehicle-image-wrapper rounded-3" style="height: 230px; overflow: hidden; background: #f8fafc;">
                <img src="{{ $c->vehicle->foto_url }}" alt="{{ $c->vehicle->plat_nomor }}" class="w-100 h-100 object-fit-cover transition-img" style="transition: transform 0.3s ease;">
            </div>

            <!-- Card Body -->
            <div class="card-body px-0 pt-3 pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="tgl-cell text-muted fw-bold font-monospace" style="font-size: 0.82rem;">
                        <i class="bi bi-calendar3 me-1"></i> {{ $c->tanggal->translatedFormat('d M Y') }}
                    </span>
                    <div class="teknisi-cell">
                        <div class="teknisi-avatar">{{ strtoupper(substr($c->nama_teknisi, 0, 2)) }}</div>
                        <span class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $c->nama_teknisi }}</span>
                    </div>
                </div>

                <!-- Parameters Status -->
                <div class="mb-3 py-2 border-top border-bottom">
                    <span class="text-muted d-block mb-1" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Detail Kondisi') }}</span>
                    @php $adaMasalah = false; @endphp
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($paramMap as $field => $label)
                            @if ($c->$field === 'Not OK')
                                @php $adaMasalah = true; @endphp
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded" style="font-size: 0.7rem; font-weight: 600;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> {{ __($label) }}
                                </span>
                            @endif
                        @endforeach
                        @if (!$adaMasalah)
                            <span class="text-success fw-bold d-inline-flex align-items-center gap-2" style="font-size: 0.82rem;">
                                <i class="bi bi-patch-check-fill fs-5"></i> {{ __('Semua Sistem Aman') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-3">
                    <span class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Catatan Tambahan') }}</span>
                    @if ($c->catatan_tambahan)
                        <p class="mb-0 text-secondary font-monospace" style="font-size: 0.82rem; font-style: italic;">"{{ $c->catatan_tambahan }}"</p>
                    @else
                        <span class="text-muted" style="font-size: 0.82rem;">—</span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end align-items-center gap-2 pt-2 border-top">
                    <a href="{{ route('checklist.show', $c->id) }}" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; border-radius: 8px;" title="{{ __('Cetak Berita Acara / Simpan PDF') }}">
                        <i class="bi bi-printer-fill"></i> {{ __('Cetak') }}
                    </a>
                    <button class="btn btn-sm btn-outline-primary px-3 py-1.5 d-inline-flex align-items-center gap-1 btn-detail-checklist" style="font-size: 0.78rem; border-radius: 8px;">
                        <i class="bi bi-info-circle-fill"></i> {{ __('Detail') }}
                    </button>
                    @if (in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi']))
                    <form action="{{ route('checklist.destroy', $c->id) }}" method="POST" class="d-inline form-confirm-delete" data-text="{{ __('Data checklist harian ini akan dihapus secara permanen dari sistem!') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1.5 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem; border-radius: 8px;">
                            <i class="bi bi-trash-fill"></i> {{ __('Hapus') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-clipboard-x"></i>
            </div>
            <h6>{{ __('Belum Ada Data Checklist') }}</h6>
            <p>{{ __('Mulai tambahkan checklist harian untuk memantau kondisi kendaraan.') }}</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Modal Detail Checklist -->
<div class="modal fade" id="detailChecklistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark mb-0"><i class="bi bi-clipboard2-check-fill text-primary me-2"></i> {{ __('Detail Inspeksi Kendaraan') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Info Armada Header -->
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-4">
                    <img id="modalFoto" src="" alt="Armada" class="rounded-3 object-fit-cover" style="width: 70px; height: 70px; background: #fff;">
                    <div>
                        <span class="badge bg-dark text-white font-monospace px-2 py-1 mb-1" id="modalPlat" style="letter-spacing: 0.5px; border-radius: 6px;"></span>
                        <h6 class="fw-bold mb-0 text-dark" id="modalMerek"></h6>
                        <small class="text-muted" id="modalJenis"></small>
                    </div>
                </div>

                <!-- Detail Inspeksi Meta -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-2 border rounded-3 bg-white">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('Inspektur / Teknisi') }}</small>
                            <span class="fw-bold text-dark" id="modalTeknisi" style="font-size: 0.85rem;"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded-3 bg-white">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ __('Tanggal Inspeksi') }}</small>
                            <span class="fw-bold text-dark" id="modalTanggal" style="font-size: 0.85rem;"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <form id="updateOdometerForm" action="" method="POST" class="p-3 border rounded-3 bg-white d-flex flex-column gap-2">
                            @csrf
                            @method('PUT')
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="text-muted mb-0 fw-semibold" style="font-size: 0.75rem;">{{ __('Odometer Saat Inspeksi (km)') }}</label>
                                <i class="bi bi-speedometer2 text-secondary fs-5"></i>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" name="odometer" id="modalOdometerInput" class="form-control form-control-sm fw-bold text-dark shadow-none border" style="font-size: 0.95rem; padding: 6px 12px; border-radius: 8px;" required>
                                <button type="submit" class="btn btn-sm btn-primary fw-bold px-3 py-2" style="border-radius: 8px; font-size: 0.8rem; flex-shrink: 0;">
                                    <i class="bi bi-save me-1"></i> {{ __('Simpan') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Status Parameter Grid -->
                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">{{ __('Status Parameter Kondisi:') }}</h6>
                <div class="row g-2 mb-4" id="modalParams">
                    <!-- Parameter items will be filled here dynamically -->
                </div>

                <!-- Catatan Tambahan -->
                <div class="p-3 bg-light rounded-4">
                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Catatan Tambahan') }}</small>
                    <p class="mb-0 text-secondary font-monospace" id="modalCatatan" style="font-size: 0.85rem; font-style: italic;"></p>
                </div>
            </div>
            <div class="modal-footer border-top p-2 bg-light d-flex gap-2">
                <a href="#" id="modalBtnPrint" class="btn btn-outline-primary fw-bold py-2 flex-grow-1 d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
                    <i class="bi bi-printer-fill"></i> {{ __('Cetak Berita Acara / PDF') }}
                </a>
                <button type="button" class="btn btn-secondary fw-bold py-2 px-4" data-bs-dismiss="modal" style="border-radius: 10px;">{{ __('Tutup') }}</button>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        // Dropdown filter handler
        $('#checklistFilterDropdown').on('change', function () {
            applyFilters();
        });

        // Search text handler
        $('#checklistSearch').on('keyup', function () {
            applyFilters();
        });

        function applyFilters() {
            var filter = $('#checklistFilterDropdown').val();
            var searchVal = $('#checklistSearch').val().toLowerCase().trim();

            $('.checklist-card-col').each(function () {
                var searchData = $(this).data('search');
                var isIssue = $(this).data('masalah') === 1;

                var matchesFilter = (filter === 'all') || 
                                    (filter === 'baik' && !isIssue) || 
                                    (filter === 'masalah' && isIssue);
                                    
                var matchesSearch = searchData.indexOf(searchVal) > -1;

                if (matchesFilter && matchesSearch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Modal detail trigger handler
        $('.btn-detail-checklist').on('click', function () {
            var card = $(this).closest('.checklist-card-col');
            var id = card.data('id');
            var plat = card.data('plat');
            var merek = card.data('merek');
            var jenis = card.data('jenis');
            var foto = card.data('foto');
            var teknisi = card.data('teknisi');
            var tanggal = card.data('tanggal');
            var odometerRaw = card.data('odometer-raw');
            var catatan = card.data('catatan');

            $('#modalPlat').text(plat);
            $('#modalMerek').text(merek);
            $('#modalJenis').text(jenis);
            $('#modalFoto').attr('src', foto);
            $('#modalTeknisi').text(teknisi);
            $('#modalTanggal').text(tanggal);
            $('#modalOdometerInput').val(odometerRaw);
            $('#updateOdometerForm').attr('action', '/checklist/' + id + '/odometer');
            $('#modalBtnPrint').attr('href', '/checklist/' + id);
            $('#modalCatatan').text(catatan);

            // Populate parameters list with beautiful badges
            var trans = {
                'Oli Mesin': '{{ __('Oli Mesin') }}',
                'Air Radiator': '{{ __('Air Radiator') }}',
                'Minyak Rem': '{{ __('Minyak Rem') }}',
                'Ban & Rem': '{{ __('Ban & Rem') }}',
                'Lampu & Klakson': '{{ __('Lampu & Klakson') }}',
                'Kebersihan': '{{ __('Kebersihan') }}',
                'AMAN': '{{ __('AMAN') }}',
                'MASALAH': '{{ __('MASALAH') }}'
            };

            var params = [
                { name: 'Oli Mesin', val: card.data('oli'), icon: 'bi-droplet-fill' },
                { name: 'Air Radiator', val: card.data('radiator'), icon: 'bi-thermometer-half' },
                { name: 'Minyak Rem', val: card.data('rem'), icon: 'bi-disc' },
                { name: 'Ban & Rem', val: card.data('ban'), icon: 'bi-circle' },
                { name: 'Lampu & Klakson', val: card.data('lampu'), icon: 'bi-lightbulb-fill' },
                { name: 'Kebersihan', val: card.data('kebersihan'), icon: 'bi-stars' }
            ];

            var container = $('#modalParams').empty();
            params.forEach(function (p) {
                var isOk = (p.val === 'OK');
                var bg = isOk ? 'bg-success-subtle border-success-subtle text-success' : 'bg-danger-subtle border-danger-subtle text-danger';
                var iconStatus = isOk ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                var textStatus = isOk ? 'AMAN' : 'MASALAH';
                
                var col = $('<div class="col-6"></div>');
                var pCard = $(`
                    <div class="d-flex align-items-center justify-content-between p-2.5 border rounded-3 ${bg}" style="border: 1px solid; font-size: 0.8rem;">
                        <span class="d-flex align-items-center gap-2 fw-semibold">
                            <i class="bi ${p.icon}"></i> ${trans[p.name]}
                        </span>
                        <span class="fw-bold d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi ${iconStatus}"></i> ${trans[textStatus]}
                        </span>
                    </div>
                `);
                col.append(pCard);
                container.append(col);
            });

            var modal = new bootstrap.Modal(document.getElementById('detailChecklistModal'));
            modal.show();
        });
    });
</script>
@endpush
@endsection