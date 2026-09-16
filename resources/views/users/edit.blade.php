@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid px-0 py-2">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-primary"></i>
                        <span>Edit Data Pengguna - {{ $user->name }}</span>
                    </h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required style="border-radius: 8px;">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">No. Telepon / WhatsApp</label>
                                <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="Contoh: 081234567890" style="border-radius: 8px;">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Password Baru (Kosongkan jika tidak diganti)</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Peran / Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required style="border-radius: 8px;">
                                    <option value="user" @selected($user->role === 'user')>Driver / Pengemudi (User)</option>
                                    <option value="teknisi" @selected($user->role === 'teknisi')>Mekanik / Teknisi</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin Fleet</option>
                                    <option value="pimpinan" @selected($user->role === 'pimpinan')>Pimpinan / Manager</option>
                                    <option value="superadmin" @selected($user->role === 'superadmin')>Super Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-dark">Nomor Induk Pegawai (NIP / NIK)</label>
                                <input type="text" name="nis" class="form-control" value="{{ old('nis', $user->nis) }}" placeholder="Contoh: NIP-2026-001" style="border-radius: 8px;">
                            </div>
                        </div>

                        <!-- Data Khusus Pengemudi (SIM) -->
                        <div class="p-3 bg-light rounded-3 border mb-4" id="driverSimContainer">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-card-heading text-primary fs-5"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Informasi Lisensi Mengemudi (SIM Driver)</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted" style="font-size: 0.82rem;">Jenis SIM</label>
                                    <select name="jenis_sim" class="form-select form-select-sm" style="border-radius: 6px;">
                                        <option value="">-- Pilih Jenis SIM --</option>
                                        <option value="SIM A" @selected(old('jenis_sim', $user->jenis_sim) == 'SIM A')>SIM A (Mobil Penumpang/Pribadi)</option>
                                        <option value="SIM B1" @selected(old('jenis_sim', $user->jenis_sim) == 'SIM B1')>SIM B1 (Mobil Bus/Barang >3.500 kg)</option>
                                        <option value="SIM B2" @selected(old('jenis_sim', $user->jenis_sim) == 'SIM B2')>SIM B2 (Alat Berat/Truk Gandeng)</option>
                                        <option value="SIM C" @selected(old('jenis_sim', $user->jenis_sim) == 'SIM C')>SIM C (Sepeda Motor)</option>
                                        <option value="Lainnya" @selected(old('jenis_sim', $user->jenis_sim) == 'Lainnya')>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted" style="font-size: 0.82rem;">Nomor SIM</label>
                                    <input type="text" name="nomor_sim" class="form-control form-control-sm" value="{{ old('nomor_sim', $user->nomor_sim) }}" placeholder="Contoh: 1234-5678-9012" style="border-radius: 6px;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted" style="font-size: 0.82rem;">Masa Berlaku SIM</label>
                                    <input type="date" name="masa_berlaku_sim" class="form-control form-control-sm" value="{{ old('masa_berlaku_sim', $user->masa_berlaku_sim ? $user->masa_berlaku_sim->format('Y-m-d') : '') }}" style="border-radius: 6px;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-light px-4" style="border-radius: 8px;">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius: 8px;">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection