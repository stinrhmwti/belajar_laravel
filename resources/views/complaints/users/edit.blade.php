@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<h5 class="page-title mb-3">Edit Pengguna - {{ $user->name }}</h5>
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                        <option value="teknisi" @selected($user->role === 'teknisi')>Teknisi</option>
                        <option value="user" @selected($user->role === 'user')>User / Driver</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 form-actions-mobile">
                <button class="btn btn-brand">Update</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection