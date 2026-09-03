@extends('layouts.app')
@section('title', __('Lembar Inspeksi Harian') . ' - ' . $checklist->vehicle->plat_nomor)

@section('content')
<style>
    @media print {
        #sidebar, .top-navbar, .no-print, .fab-container {
            display: none !important;
        }
        #main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background: #fff !important;
        }
        .content-body {
            padding: 0 !important;
        }
        .print-container {
            box-shadow: none !important;
            border: none !important;
            padding: 20px !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        body {
            background: #fff !important;
            color: #000 !important;
        }
        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
            background: transparent !important;
        }
    }

    .print-container {
        max-width: 860px;
        margin: 0 auto;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 25px rgba(0,0,0,0.05);
        padding: 36px 40px;
    }

    .kop-surat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px double #0f172a;
        padding-bottom: 16px;
        margin-bottom: 24px;
    }

    .kop-logo-title h2 {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    .kop-logo-title p {
        margin: 2px 0 0 0;
        font-size: 0.8rem;
        color: #64748b;
    }

    .kop-doc-info {
        text-align: right;
    }

    .kop-doc-info .doc-badge {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .kop-doc-info p {
        margin: 0;
        font-size: 0.78rem;
        color: #64748b;
    }

    .section-title {
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #0891b2;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 6px;
        margin-bottom: 14px;
    }

    .table-inspection th {
        background-color: #f8fafc;
        color: #334155;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
    }

    .table-inspection td {
        padding: 10px 14px;
        font-size: 0.88rem;
        border: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .ttd-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 40px;
        page-break-inside: avoid;
    }

    .ttd-card {
        text-align: center;
        padding: 12px;
    }

    .ttd-card p {
        margin: 0 0 50px 0;
        font-size: 0.82rem;
        color: #475569;
        font-weight: 500;
    }

    .ttd-card .nama-penandatangan {
        font-weight: 700;
        font-size: 0.88rem;
        color: #0f172a;
        border-top: 1px solid #0f172a;
        padding-top: 4px;
        display: inline-block;
        min-width: 140px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('checklist.index') }}" class="btn btn-outline-secondary px-3 py-2 d-flex align-items-center gap-2" style="border-radius: 10px; font-size: 0.88rem;">
            <i class="bi bi-arrow-left"></i> {{ __('Kembali ke Daftar') }}
        </a>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 10px;">
            <i class="bi bi-printer-fill"></i> {{ __('Cetak Dokumen / Simpan PDF') }}
        </button>
    </div>
</div>

<div class="print-container">
    <!-- Kop Surat Resmi -->
    <div class="kop-surat">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: #0891b2; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="bi bi-truck"></i>
            </div>
            <div class="kop-logo-title">
                <h2>FleetMaintenance System</h2>
                <p>Sistem Operasional Manajemen & Pemeriksaan Kelayakan Armada</p>
            </div>
        </div>
        <div class="kop-doc-info">
            <div class="doc-badge">{{ __('Berita Acara Inspeksi') }}</div>
            <p><strong>No. Ref:</strong> CHK-{{ str_pad($checklist->id, 5, '0', STR_PAD_LEFT) }}/{{ $checklist->tanggal->format('Ym') }}</p>
            <p><strong>Tanggal:</strong> {{ $checklist->tanggal->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    <div class="text-center mb-4">
        <h4 class="fw-bold text-dark mb-1">{{ __('LEMBAR PEMERIKSAAN KELAYAKAN KENDARAAN (PRE-TRIP)') }}</h4>
        <p class="text-muted small mb-0">{{ __('Standar Operasional Pemeriksaan Pra-Perjalanan Armada') }}</p>
    </div>

    <!-- Informasi Armada & Petugas -->
    <div class="section-title"><i class="bi bi-car-front me-1"></i> {{ __('1. Identitas Kendaraan & Petugas') }}</div>
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-6">
            <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
                <tr>
                    <td class="text-muted fw-semibold" style="width: 140px;">{{ __('Nomor Polisi') }}</td>
                    <td class="fw-bold text-dark">: <span class="badge bg-dark text-white font-monospace px-2 py-1">{{ $checklist->vehicle->plat_nomor }}</span></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">{{ __('Merk & Tipe') }}</td>
                    <td class="fw-semibold text-dark">: {{ $checklist->vehicle->merek }} {{ $checklist->vehicle->tipe }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">{{ __('Jenis Kendaraan') }}</td>
                    <td class="text-dark">: {{ $checklist->vehicle->jenis_kendaraan ?? 'Operasional' }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6 col-6">
            <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
                <tr>
                    <td class="text-muted fw-semibold" style="width: 140px;">{{ __('Supir Utama') }}</td>
                    <td class="fw-semibold text-dark">: {{ $checklist->vehicle->supir_utama ?: '—' }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">{{ __('Petugas Inspeksi') }}</td>
                    <td class="fw-bold text-primary">: {{ $checklist->nama_teknisi }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">{{ __('Odometer Tercatat') }}</td>
                    <td class="fw-bold text-dark font-monospace">: {{ number_format($checklist->odometer, 0, ',', '.') }} km</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Hasil Checklist Item Komponen -->
    <div class="section-title"><i class="bi bi-clipboard2-check me-1"></i> {{ __('2. Rincian Pemeriksaan Komponen Fisik & Mesin') }}</div>
    @php
        $items = [
            ['nama' => 'Oli Mesin', 'desc' => 'Volume pelumas mesin, kejernihan oli, dan deteksi kebocoran seal.', 'status' => $checklist->oli_mesin],
            ['nama' => 'Air Radiator / Coolant', 'desc' => 'Tingkat ketinggian air pendingin radiator dan selang penampung.', 'status' => $checklist->air_radiator],
            ['nama' => 'Minyak Rem', 'desc' => 'Ketinggian minyak rem pada tabung master silinder rem.', 'status' => $checklist->minyak_rem],
            ['nama' => 'Kondisi Ban & Pengereman', 'desc' => 'Ketebalan tapak ban, tekanan angin, dan ketajaman rem kaki/tangan.', 'status' => $checklist->ban_rem],
            ['nama' => 'Sistem Lampu & Klakson', 'desc' => 'Lampu utama, lampu sein, lampu rem mundur, dan fungsi klakson.', 'status' => $checklist->lampu_klakson],
            ['nama' => 'Kebersihan Kendaraan', 'desc' => 'Kebersihan kabin interior, bak muatan, dan kaca pandangan pengemudi.', 'status' => $checklist->kebersihan],
        ];
    @endphp

    <table class="table table-inspection mb-4">
        <thead>
            <tr>
                <th style="width: 6%; text-align: center;">No</th>
                <th style="width: 30%;">Item Pemeriksaan</th>
                <th style="width: 44%;">Deskripsi Standar Kelayakan</th>
                <th style="width: 20%; text-align: center;">Hasil Inspeksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr>
                    <td class="text-center font-monospace">{{ $index + 1 }}</td>
                    <td class="fw-bold text-dark">{{ __($item['nama']) }}</td>
                    <td class="text-muted" style="font-size: 0.8rem;">{{ __($item['desc']) }}</td>
                    <td class="text-center">
                        @if ($item['status'] === 'OK')
                            <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 fw-bold">
                                <i class="bi bi-check-circle-fill"></i> {{ __('OK / BAIK') }}
                            </span>
                        @else
                            <span class="badge bg-danger text-white px-2.5 py-1 fw-bold">
                                <i class="bi bi-x-circle-fill"></i> {{ __('NOT OK / MASALAH') }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Kesimpulan Status & Catatan -->
    <div class="section-title"><i class="bi bi-chat-left-text me-1"></i> {{ __('3. Kesimpulan & Catatan Petugas') }}</div>
    <div class="p-3 bg-light rounded-3 mb-4 border">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ __('Status Kelayakan Jalan Armada:') }}</span>
            @if (!$checklist->ada_masalah)
                <span class="badge bg-success px-3 py-1.5 fs-6 fw-bold">
                    <i class="bi bi-shield-check"></i> {{ __('LAYAK JALAN / SIAP OPERASIONAL') }}
                </span>
            @else
                <span class="badge bg-danger px-3 py-1.5 fs-6 fw-bold">
                    <i class="bi bi-exclamation-triangle"></i> {{ __('PERLU PERBAIKAN BENGKEL') }}
                </span>
            @endif
        </div>
        <p class="mb-0 text-secondary" style="font-size: 0.85rem;">
            <strong>{{ __('Catatan Petugas:') }}</strong> 
            {{ $checklist->catatan_tambahan ?: __('Seluruh parameter inspeksi telah diperiksa dan armada dinyatakan dalam kondisi baik tanpa keluhan.') }}
        </p>
    </div>

    <!-- Kolom Tanda Tangan -->
    <div class="ttd-grid">
        <div class="ttd-card">
            <p>{{ __('Pengemudi / Supir') }}</p>
            <span class="nama-penandatangan">{{ $checklist->vehicle->supir_utama ?: '( ................................... )' }}</span>
        </div>
        <div class="ttd-card">
            <p>{{ __('Petugas Pemeriksa / Teknisi') }}</p>
            <span class="nama-penandatangan">{{ $checklist->nama_teknisi }}</span>
        </div>
        <div class="ttd-card">
            <p>{{ __('Mengetahui, Kepala Pool / SPV') }}</p>
            <span class="nama-penandatangan">( ................................... )</span>
        </div>
    </div>
</div>
@endsection
