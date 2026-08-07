@extends('layouts.app')
@section('title', 'Keluhan Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="page-title mb-0">Daftar Keluhan / Kendala Kendaraan</h5>
    <a href="{{ route('complaints.create') }}" class="btn btn-brand"><i class="bi bi-megaphone"></i> Lapor Keluhan Baru</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table id="tabelKeluhan" class="table table-hover mb-0 w-100" data-count="{{ $complaints->count() }}" data-lastcol="{{ in_array(auth()->user()->role, ['admin', 'teknisi']) ? 5 : -1 }}">
            <thead>
                <tr>
                    <th>Tanggal</th><th>Plat Nomor</th>
                    <th>Pelapor</th><th>Keluhan</th><th>Status</th>
                    @if (in_array(auth()->user()->role, ['admin', 'teknisi']))<th data-orderable="false">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($complaints as $c)
                <tr>
                    <td data-order="{{ $c->tanggal->format('Y-m-d') }}">{{ $c->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $c->vehicle->plat_nomor }}</td>
                    <td>{{ $c->user->name }}</td>
                    <td>{{ $c->keluhan }}</td>
                    <td>
                        @if ($c->status === 'Baru')
                            <span class="badge bg-danger">Baru</span>
                        @elseif ($c->status === 'Diproses')
                            <span class="badge bg-warning text-dark">Diproses</span>
                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>
                    @if (in_array(auth()->user()->role, ['admin', 'teknisi']))
                    <td>
                        <form action="{{ route('complaints.updateStatus', $c) }}" method="POST" class="d-flex gap-1">
                            @csrf @method('PUT')
                            <select name="status" class="form-select form-select-sm" style="width:120px">
                                <option value="Baru" @selected($c->status === 'Baru')>Baru</option>
                                <option value="Diproses" @selected($c->status === 'Diproses')>Diproses</option>
                                <option value="Selesai" @selected($c->status === 'Selesai')>Selesai</option>
                            </select>
                            <button class="btn btn-sm btn-brand">Update</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-3">Belum ada keluhan yang dilaporkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.11/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.11/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive@2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        var jumlahKeluhan = parseInt($('#tabelKeluhan').data('count'), 10);
        if (jumlahKeluhan > 0) {
            var lastCol = parseInt($('#tabelKeluhan').data('lastcol'), 10);
            $('#tabelKeluhan').DataTable({
                responsive: true,
                paging: true,
                pageLength: 10,
                lengthChange: false,
                order: [[0, 'desc']],
                language: {
                    search: "Cari cepat:",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ keluhan",
                    infoEmpty: "Tidak ada data",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" }
                },
                columnDefs: [
                    { orderable: false, targets: lastCol }
                ]
            });
        }
    });
</script>
@endsection