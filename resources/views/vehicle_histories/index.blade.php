@extends('layouts.app')
@section('title', 'Riwayat Servis Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Riwayat Servis Kendaraan</h3>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Manajemen riwayat perawatan, servis berkala, dan perbaikan armada.</p>
    </div>
    <div>
        <a href="{{ route('vehicle-histories.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 10px;">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Riwayat</span>
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="bg-light text-secondary" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="px-4 py-3">Kendaraan</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Teknisi</th>
                        <th class="py-3">Jenis Pekerjaan</th>
                        <th class="py-3">Sparepart</th>
                        <th class="py-3">Biaya</th>
                        <th class="py-3">Keterangan</th>
                        <th class="px-4 py-3 text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $h)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('vehicles.show', $h->vehicle_id) }}" class="fw-bold text-decoration-none text-dark">
                                {{ $h->vehicle->plat_nomor }}
                            </a>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $h->vehicle->merek }} {{ $h->vehicle->tipe }}</small>
                        </td>
                        <td class="py-3">{{ $h->tanggal->format('d/m/Y') }}</td>
                        <td class="py-3">
                            @if ($h->teknisi)
                                <span class="badge bg-secondary-subtle text-secondary-emphasis px-2.5 py-1.5 fw-medium" style="border-radius: 6px; font-size: 0.78rem;">
                                    <i class="bi bi-person me-1"></i> {{ $h->teknisi->name }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="py-3 fw-semibold text-dark">{{ $h->jenis_pekerjaan }}</td>
                        <td class="py-3 text-muted">{{ $h->sparepart_digunakan ?: '-' }}</td>
                        <td class="py-3 fw-bold text-danger">Rp {{ number_format($h->biaya, 0, ',', '.') }}</td>
                        <td class="py-3 text-muted" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $h->keterangan }}">
                            {{ $h->keterangan ?: '-' }}
                        </td>
                        <td class="px-4 py-3 text-end">
                            <div class="d-flex justify-content-end gap-1.5">
                                <a href="{{ route('vehicle-histories.edit', $h) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" title="Ubah">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('vehicle-histories.destroy', $h) }}" method="POST" class="form-confirm-delete m-0 p-0" data-text="Riwayat servis untuk {{ $h->vehicle->plat_nomor }} pada {{ $h->tanggal->format('d/m/Y') }} akan dihapus.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <span class="fw-bold text-dark d-block">Belum Ada Riwayat Servis</span>
                            <small class="text-muted">Gunakan tombol "Tambah Riwayat" untuk mencatat perawatan baru.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
