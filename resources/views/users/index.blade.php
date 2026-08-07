@extends('layouts.app')
@section('title', 'Kelola Pengguna')

@section('content')
<style>
    .stat-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }
    .stat-card .text-muted {
        color: #475569 !important;
    }
    .stat-card .text-dark {
        color: #0f172a !important;
    }
    
    /* Dark Theme Support for Stat Cards */
    body.dark-theme .stat-card {
        background: #111827;
        border-color: #1e293b;
    }
    body.dark-theme .stat-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
    }
    body.dark-theme .stat-card .text-muted {
        color: #94a3b8 !important;
    }
    body.dark-theme .stat-card .text-dark {
        color: #f1f5f9 !important;
    }
    
    /* Dark Theme Support for Icon Boxes */
    body.dark-theme .icon-box.bg-dark {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    body.dark-theme .icon-box.bg-primary-subtle {
        background-color: rgba(79, 70, 229, 0.15) !important;
        color: #a5b4fc !important;
    }
    body.dark-theme .icon-box.bg-info-subtle {
        background-color: rgba(2, 132, 199, 0.15) !important;
        color: #7dd3fc !important;
    }
    body.dark-theme .icon-box.bg-success-subtle {
        background-color: rgba(5, 150, 105, 0.15) !important;
        color: #6ee7b7 !important;
    }
    body.dark-theme .icon-box.bg-warning-subtle {
        background-color: rgba(217, 119, 6, 0.15) !important;
        color: #fde047 !important;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #ffffff;
        flex-shrink: 0;
    }
    .bg-avatar-superadmin { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    .bg-avatar-admin { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
    .bg-avatar-teknisi { background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%); }
    .bg-avatar-user { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .bg-avatar-pimpinan { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }

    .role-badge-superadmin { background-color: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; }
    .role-badge-admin { background-color: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
    .role-badge-teknisi { background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .role-badge-user { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .role-badge-pimpinan { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }

    /* Table text & Datatables Controls Readability */
    #tabelPengguna td {
        font-size: 0.9rem;
        color: #1e293b;
    }
    #tabelPengguna .text-secondary {
        color: #475569 !important;
    }
    #tabelPengguna .text-muted {
        color: #64748b !important;
    }
    .dataTables_wrapper .dataTables_filter label,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        color: #334155 !important;
        font-weight: 600;
        font-size: 0.88rem;
    }
    .dataTables_filter input {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 5px 12px !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-weight: 500;
    }

    /* Dark Theme Controls Readability */
    body.dark-theme #tabelPengguna td {
        color: #f1f5f9;
    }
    body.dark-theme #tabelPengguna .text-secondary {
        color: #cbd5e1 !important;
    }
    body.dark-theme #tabelPengguna .text-muted {
        color: #94a3b8 !important;
    }
    body.dark-theme .dataTables_wrapper .dataTables_filter label,
    body.dark-theme .dataTables_wrapper .dataTables_info,
    body.dark-theme .dataTables_wrapper .dataTables_paginate {
        color: #cbd5e1 !important;
    }
    body.dark-theme .dataTables_filter input {
        border: 1px solid #334155 !important;
        background-color: #1e293b !important;
        color: #f1f5f9 !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Pengguna Sistem Armada</h3>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">Manajemen hak akses &amp; pengguna aplikasi FleetMaintenance.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary px-4 py-2 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-person-plus-fill fs-6"></i>
        <span>Tambah Pengguna Baru</span>
    </a>
</div>

<!-- Summary Metric Cards (Multi-Role Support) -->
<div class="row g-3 mb-4">
    <div class="col-md-2.5 col-sm-6 flex-fill">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-dark text-white">
                <i class="bi bi-shield-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Super Admin</span>
                <h5 class="fw-bold text-dark mb-0">{{ $users->where('role', 'superadmin')->count() }} <small class="text-muted fs-6 font-normal">User</small></h5>
            </div>
        </div>
    </div>
    <div class="col-md-2.5 col-sm-6 flex-fill">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-primary-subtle text-primary">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Admin Fleet</span>
                <h5 class="fw-bold text-dark mb-0">{{ $users->where('role', 'admin')->count() }} <small class="text-muted fs-6 font-normal">User</small></h5>
            </div>
        </div>
    </div>
    <div class="col-md-2.5 col-sm-6 flex-fill">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-info-subtle text-info">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Mekanik / Teknisi</span>
                <h5 class="fw-bold text-dark mb-0">{{ $users->where('role', 'teknisi')->count() }} <small class="text-muted fs-6 font-normal">User</small></h5>
            </div>
        </div>
    </div>
    <div class="col-md-2.5 col-sm-6 flex-fill">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-success-subtle text-success">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Driver / User</span>
                <h5 class="fw-bold text-dark mb-0">{{ $users->where('role', 'user')->count() }} <small class="text-muted fs-6 font-normal">User</small></h5>
            </div>
        </div>
    </div>
    <div class="col-md-2.5 col-sm-6 flex-fill">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-warning-subtle text-warning">
                <i class="bi bi-award-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Pimpinan</span>
                <h5 class="fw-bold text-dark mb-0">{{ $users->where('role', 'pimpinan')->count() }} <small class="text-muted fs-6 font-normal">User</small></h5>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people-fill text-primary fs-5"></i>
            <span class="fw-bold text-dark">Daftar Akun Pengguna</span>
        </div>
        <span class="badge bg-light text-secondary border px-3 py-2">Total {{ $users->count() }} Pengguna Aktif</span>
    </div>
    <div class="table-responsive">
        <table id="tabelPengguna" class="table table-hover align-middle mb-0 w-100" data-count="{{ $users->count() }}">
            <thead>
                <tr>
                    <th class="ps-4">Pengguna</th>
                    <th>NIP / ID Karyawan</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role &amp; Hak Akses</th>
                    <th class="pe-4 text-end" data-orderable="false">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                @php
                    $avatarBg = $u->role === 'superadmin' ? 'bg-avatar-superadmin' : ($u->role === 'admin' ? 'bg-avatar-admin' : ($u->role === 'teknisi' ? 'bg-avatar-teknisi' : ($u->role === 'pimpinan' ? 'bg-avatar-pimpinan' : 'bg-avatar-user')));
                    $roleBadge = $u->role === 'superadmin' ? 'role-badge-superadmin' : ($u->role === 'admin' ? 'role-badge-admin' : ($u->role === 'teknisi' ? 'role-badge-teknisi' : ($u->role === 'pimpinan' ? 'role-badge-pimpinan' : 'role-badge-user')));
                    $roleIcon = $u->role === 'superadmin' ? 'bi-shield-fill' : ($u->role === 'admin' ? 'bi-shield-check' : ($u->role === 'teknisi' ? 'bi-wrench-adjustable' : ($u->role === 'pimpinan' ? 'bi-award-fill' : 'bi-person-check')));
                    
                    // User Friendly Role Name
                    $roleLabel = 'Driver';
                    if ($u->role === 'superadmin') $roleLabel = 'Super Admin';
                    elseif ($u->role === 'admin') $roleLabel = 'Admin Fleet';
                    elseif ($u->role === 'teknisi') $roleLabel = 'Mekanik';
                    elseif ($u->role === 'pimpinan') $roleLabel = 'Pimpinan';
                @endphp
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar {{ $avatarBg }} shadow-sm">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:0.65rem;">Akun Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark font-monospace" style="font-size:0.88rem;">{{ $u->nis ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="font-monospace fw-semibold text-secondary" style="font-size:0.85rem;">@<span>{{ $u->username }}</span></span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1.5 text-secondary" style="font-size:0.875rem;">
                            <i class="bi bi-envelope text-muted"></i>
                            <span>{{ $u->email }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $roleBadge }} px-3 py-1.5 d-inline-flex align-items-center gap-1.5 fw-semibold" style="font-size: 0.78rem;">
                            <i class="bi {{ $roleIcon }}"></i>
                            {{ $roleLabel }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-primary px-2.5 py-1" style="border-radius: 8px;" title="Edit Pengguna">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger px-2.5 py-1" style="border-radius: 8px;" title="Hapus Pengguna">
                                    <i class="bi bi-trash3"></i> Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-people text-muted fs-1 d-block mb-2 opacity-50"></i>
                        <span class="fw-bold text-dark d-block">Belum Ada Pengguna</span>
                    </td>
                </tr>
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
    $.fn.dataTable.ext.errMode = 'none';
    $(document).ready(function () {
        var jumlahPengguna = parseInt($('#tabelPengguna').data('count'), 10);
        if (jumlahPengguna > 0) {
            if ($.fn.DataTable.isDataTable('#tabelPengguna')) {
                $('#tabelPengguna').DataTable().destroy();
            }
            $('#tabelPengguna').DataTable({
                responsive: true,
                paging: true,
                pageLength: 10,
                lengthChange: false,
                language: {
                    search: "Cari cepat:",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ pengguna",
                    infoEmpty: "Tidak ada data",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" }
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ]
            });
        }
    });
</script>
@endsection