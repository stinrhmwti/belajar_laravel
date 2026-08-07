@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<h5 class="page-title mb-3">Tambah Pengguna Baru</h5>
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin Fleet</option>
                        <option value="teknisi">Mekanik / Teknisi</option>
                        <option value="user">Driver / User</option>
                        <option value="pimpinan">Pimpinan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" placeholder="Contoh: NIP-12345">
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-brand">Simpan</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection