@extends('layouts.app')
@section('title', 'Notifikasi WhatsApp')

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
    
    /* Dark Theme Support */
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

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    .template-badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .preview-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 12px;
        font-size: 0.85rem;
        white-space: pre-wrap;
        word-break: break-word;
        color: #1e293b;
    }
    body.dark-theme .preview-box {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-whatsapp text-success"></i>
            <span>{{ __('Pusat Notifikasi WhatsApp') }}</span>
        </h3>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">
            {{ __('Otomasi notifikasi kerusakan armada, pengingat servis, update keluhan driver, dan pengiriman pesan gateway API.') }}
        </p>
    </div>
</div>

{{-- Flash Alert Notifikasi --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
            <i class="bi bi-x-circle-fill fs-5"></i>
            <span>Terdapat kesalahan pengisian:</span>
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-success-subtle text-success">
                <i class="bi bi-check-all"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Pesan Sukses') }}</span>
                <h4 class="fw-bold text-dark mb-0">{{ $logs->where('status', 'success')->count() }} <small class="text-muted fs-6 fw-normal">Pesan</small></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-danger-subtle text-danger">
                <i class="bi bi-exclamation-octagon-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Pesan Gagal') }}</span>
                <h4 class="fw-bold text-dark mb-0">{{ $logs->where('status', 'failed')->count() }} <small class="text-muted fs-6 fw-normal">Pesan</small></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-warning-subtle text-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Pending Antrean') }}</span>
                <h4 class="fw-bold text-dark mb-0">{{ $logs->where('status', 'pending')->count() }} <small class="text-muted fs-6 fw-normal">Pesan</small></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="icon-box bg-primary-subtle text-primary">
                <i class="bi bi-chat-dots-fill"></i>
            </div>
            <div>
                <span class="text-muted fw-medium d-block text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ __('Total Log Terdata') }}</span>
                <h4 class="fw-bold text-dark mb-0">{{ $logs->total() }} <small class="text-muted fs-6 fw-normal">Log</small></h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Form Kirim Pesan WhatsApp -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-send-fill text-success fs-5"></i>
                    <span class="fw-bold text-dark">{{ __('Kirim Pesan WhatsApp') }}</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnUseAdminNumber" title="Isi dengan Nomor WA Admin">
                    <i class="bi bi-shield-lock me-1"></i>{{ __('Admin') }}
                </button>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('whatsapp.send') }}" method="POST" id="waSendForm">
                    @csrf
                    
                    {{-- Quick Picker Kontak Pengguna --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark d-flex justify-content-between align-items-center" style="font-size: 0.88rem;">
                            <span>{{ __('Pilih Penerima Cepat (Driver / Staff)') }}</span>
                            <span class="text-muted small fw-normal">{{ __('Opsional') }}</span>
                        </label>
                        <select id="quickUserSelect" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="">-- Pilih dari Kontak Pengguna --</option>
                            @php
                                $driverUsers = $users->where('role', 'user');
                                $teknisiUsers = $users->where('role', 'teknisi');
                                $adminUsers = $users->where('role', 'admin');
                                $otherUsers = $users->whereNotIn('role', ['admin', 'teknisi', 'user']);
                            @endphp

                            @if($driverUsers->count() > 0)
                                <optgroup label="🚚 Pengemudi / Driver Armada">
                                    @foreach ($driverUsers as $user)
                                        @php
                                            $userPhone = $user->no_wa ?: $user->no_telepon;
                                            $assignedVehicle = $vehicles->first(function($v) use ($user) {
                                                return $v->driver_id === $user->id || strtolower($v->supir_utama) === strtolower($user->name);
                                            });
                                        @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="{{ $user->role }}"
                                                data-vehicle-plat="{{ $assignedVehicle ? $assignedVehicle->plat_nomor : '' }}"
                                                data-vehicle-merk="{{ $assignedVehicle ? trim(($assignedVehicle->merek ?? '') . ' ' . ($assignedVehicle->tipe ?? '')) : '' }}">
                                            {{ $user->name }} {{ $assignedVehicle ? '— [' . $assignedVehicle->plat_nomor . ']' : '' }} ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if($teknisiUsers->count() > 0)
                                <optgroup label="🔧 Tim Teknisi Armada">
                                    @foreach ($teknisiUsers as $user)
                                        @php $userPhone = $user->no_wa ?: $user->no_telepon; @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="{{ $user->role }}">
                                            {{ $user->name }} ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if($adminUsers->count() > 0)
                                <optgroup label="👑 Admin & Manajemen">
                                    @foreach ($adminUsers as $user)
                                        @php $userPhone = $user->no_wa ?: $user->no_telepon; @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="{{ $user->role }}">
                                            {{ $user->name }} ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if($otherUsers->count() > 0)
                                <optgroup label="👥 Pengguna Lainnya">
                                    @foreach ($otherUsers as $user)
                                        @php $userPhone = $user->no_wa ?: $user->no_telepon; @endphp
                                        <option value="{{ $userPhone ?: '' }}" 
                                                data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}"
                                                data-user-role="{{ $user->role }}">
                                            {{ $user->name }} [{{ ucfirst($user->role) }}] ({{ $userPhone ?: 'Belum ada WA' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    {{-- Input Nomor Telepon --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">
                            {{ __('Nomor WhatsApp Tujuan') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-telephone-fill text-success"></i>
                            </span>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   class="form-control border-start-0" 
                                   value="{{ old('phone') }}" 
                                   placeholder="Contoh: 08123456789 atau 628123456789" 
                                   required 
                                   style="border-radius: 0 8px 8px 0;">
                        </div>
                        <input type="hidden" name="user_id" id="selectedUserId" value="{{ old('user_id') }}">
                    </div>

                    {{-- Quick Picker Template Sistem --}}
                    <div class="mb-3">
                        <label for="template_code" class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">
                            {{ __('Pilih Template Sistem Armada') }}
                        </label>
                        <select name="template_code" id="template_code" class="form-select" style="border-radius: 8px;">
                            <option value="">-- Pesan Manual (Tulis Teks Bebas) --</option>
                            @foreach ($templates as $tmpl)
                                <option value="{{ $tmpl->code }}" 
                                        data-content="{{ $tmpl->content }}"
                                        data-vars="{{ json_encode($tmpl->variables) }}"
                                        @selected(old('template_code') == $tmpl->code)>
                                    {{ $tmpl->name }} ({{ $tmpl->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quick Picker Kendaraan Armada (Untuk isi variabel cepat) --}}
                    <div class="mb-3 d-none" id="vehiclePickerContainer">
                        <label class="form-label fw-semibold text-dark d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                            <span><i class="bi bi-truck text-primary me-1"></i>{{ __('Pilih Kendaraan Armada') }}</span>
                            <span class="text-muted small fw-normal">{{ __('Otomatis isi plat & merk') }}</span>
                        </label>
                        <select id="quickVehicleSelect" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="">-- Pilih Kendaraan Terdaftar --</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->plat_nomor }}" 
                                        data-plat="{{ $v->plat_nomor }}"
                                        data-merk="{{ trim(($v->merek ?? '') . ' ' . ($v->tipe ?? '')) }}"
                                        data-odo="{{ $v->odometer_awal ?? 0 }}"
                                        data-driver="{{ $v->supir_utama ?: ($v->driver?->name ?: 'Belum ada driver') }}">
                                    {{ $v->plat_nomor }} - {{ $v->merek }} {{ $v->tipe }} ({{ $v->supir_utama ?: 'Driver: —' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dynamic Variable Inputs for Selected Template --}}
                    <div id="dynamicVariablesContainer" class="p-3 bg-light rounded-3 border mb-3 d-none">
                        <span class="fw-bold text-dark d-block mb-2" style="font-size: 0.85rem;">
                            <i class="bi bi-sliders text-primary me-1"></i>{{ __('Parameter Variabel Template') }}
                        </span>
                        <div id="dynamicFieldsList" class="d-flex flex-column gap-2"></div>
                    </div>

                    {{-- Live Preview Box --}}
                    <div class="mb-3 d-none" id="previewContainer">
                        <label class="form-label fw-semibold text-dark mb-1" style="font-size: 0.85rem;">
                            <i class="bi bi-eye text-info me-1"></i>{{ __('Pratinjau Pesan yang Akan Dikirim') }}
                        </label>
                        <div id="livePreviewBox" class="preview-box"></div>
                    </div>

                    {{-- Manual Message Textarea --}}
                    <div class="mb-4" id="manualMessageContainer">
                        <label for="message" class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">
                            {{ __('Isi Pesan') }} <span id="msgRequiredAsterisk" class="text-danger">*</span>
                        </label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="5" 
                                  class="form-control" 
                                  placeholder="Tuliskan isi pesan yang ingin dikirim..." 
                                  style="border-radius: 8px;">{{ old('message') }}</textarea>
                        <div class="form-text text-muted d-flex justify-content-between align-items-center" style="font-size: 0.8rem;">
                            <span>Maksimal 2.000 karakter.</span>
                            <span id="charCounter" class="font-monospace">0 karakter</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-send-check-fill fs-6"></i>
                        <span>{{ __('Kirim Pesan WhatsApp') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Pengiriman WhatsApp -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary fs-5"></i>
                    <span class="fw-bold text-dark">{{ __('Riwayat Pengiriman Pesan') }}</span>
                </div>
                
                {{-- Form Filter Pencarian & Status --}}
                <form action="{{ route('whatsapp.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                    <select name="status" class="form-select form-select-sm" style="width: 120px; border-radius: 6px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="success" @selected(request('status') === 'success')>Sukses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="failed" @selected(request('status') === 'failed')>Gagal</option>
                    </select>
                    
                    <div class="input-group input-group-sm" style="width: 170px;">
                        <input type="text" name="search" class="form-control" placeholder="Cari pelapor/plat..." value="{{ request('search') }}" style="border-radius: 6px 0 0 6px;">
                        <button class="btn btn-outline-secondary" type="submit" style="border-radius: 0 6px 6px 0;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    @if(request()->filled('status') || request()->filled('search'))
                        <a href="{{ route('whatsapp.index') }}" class="btn btn-sm btn-light text-muted" title="Reset Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-responsive flex-grow-1">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 40px;">No</th>
                            <th style="width: 115px;">{{ __('Waktu') }}</th>
                            <th>{{ __('Pelapor & Kontak') }}</th>
                            <th>{{ __('Kendaraan Armada') }}</th>
                            <th>{{ __('Kategori & Kendala') }}</th>
                            <th style="width: 85px;">{{ __('Status') }}</th>
                            <th class="pe-3 text-end" style="width: 90px;">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td class="ps-3 text-muted font-monospace" style="font-size: 0.85rem;">
                                {{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                            </td>
                            <td>
                                <span class="fw-semibold text-dark d-block" style="font-size: 0.82rem;">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y') : '—' }}
                                </span>
                                <span class="text-muted font-monospace" style="font-size: 0.75rem;">
                                    {{ $log->created_at ? $log->created_at->format('H:i:s') : '' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1.5 mb-0.5">
                                    <i class="bi bi-person-circle text-secondary" style="font-size: 0.95rem;"></i>
                                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $log->pelapor }}</span>
                                </div>
                                <div class="text-muted font-monospace" style="font-size: 0.78rem;">
                                    <i class="bi bi-whatsapp text-success me-1"></i>{{ $log->phone }}
                                </div>
                            </td>
                            <td>
                                @if ($log->kendaraan)
                                    <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 bg-light text-dark border rounded-2" style="font-size: 0.82rem;">
                                        <i class="bi bi-truck text-primary"></i>
                                        <span class="fw-bold">{{ $log->kendaraan }}</span>
                                    </div>
                                @else
                                    <span class="text-muted font-monospace" style="font-size: 0.8rem;">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if ($log->template)
                                        @php
                                            $tmplClass = match($log->template->code) {
                                                'keluhan_baru' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                                'keluhan_status' => 'bg-info-subtle text-info border border-info-subtle',
                                                'servis_reminder' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                                'checklist_peringatan' => 'bg-warning-subtle text-dark border border-warning',
                                                default => 'bg-light text-primary border',
                                            };
                                        @endphp
                                        <span class="badge {{ $tmplClass }} align-self-start px-2 py-0.5" style="font-size: 0.7rem;">
                                            {{ $log->template->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-secondary border align-self-start px-2 py-0.5" style="font-size: 0.7rem;">
                                            {{ __('Pesan Manual') }}
                                        </span>
                                    @endif
                                    <span class="text-dark fw-medium d-inline-block text-truncate" style="max-width: 220px; font-size: 0.82rem;" title="{{ $log->message }}">
                                        {{ $log->kendala }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $log->statusBadge() }} px-2 py-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="pe-3 text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    {{-- Tombol Kirim Ulang jika Status Gagal --}}
                                    @if ($log->status === 'failed')
                                        <form action="{{ route('whatsapp.resend', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim ulang pesan WhatsApp ini ke {{ $log->phone }}?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-0.5" style="border-radius: 6px; font-size: 0.75rem;" title="Kirim Ulang Pesan">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Detail Log --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-light border px-2 py-0.5 btn-detail-log" 
                                            style="border-radius: 6px; font-size: 0.75rem;" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailLogModal"
                                            data-log="{{ json_encode(array_merge($log->load(['template', 'user'])->toArray(), [
                                                'parsed_pelapor' => $log->pelapor,
                                                'parsed_kendaraan' => $log->kendaraan,
                                                'parsed_kendala' => $log->kendala,
                                            ])) }}"
                                            title="Lihat Rincian Laporan & Pesan">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-square-dots fs-1 d-block mb-2 opacity-50"></i>
                                <span class="fw-semibold d-block">Belum ada riwayat pesan WhatsApp</span>
                                <small>Kirim pesan baru menggunakan formulir di sebelah kiri.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Laravel Built-in Pagination --}}
            @if ($logs->hasPages())
                <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} log
                    </span>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail Log WhatsApp & Laporan Armada -->
<div class="modal fade" id="detailLogModal" tabindex="-1" aria-labelledby="detailLogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="detailLogModalLabel">
                    <i class="bi bi-info-circle text-primary"></i>
                    <span>Detail Laporan Armada & Pesan WhatsApp</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Info Ringkas Pelapor, Kendaraan, dan Status --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pelapor / Pengirim</span>
                            <div id="modalPelapor" class="fw-bold text-dark fs-6 mt-1"></div>
                            <div id="modalPhone" class="text-muted font-monospace small"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Kendaraan Terkait</span>
                            <div id="modalKendaraan" class="fw-bold text-primary fs-6 mt-1"></div>
                            <div id="modalTemplateName" class="text-muted small"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Status Gateway</span>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span id="modalStatusBadge" class="badge"></span>
                                <span id="modalSentAt" class="text-dark small font-monospace"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kendala / Bagian Rusak --}}
                <div class="mb-3">
                    <label class="fw-semibold text-dark small d-block mb-1">
                        <i class="bi bi-wrench-adjustable text-danger me-1"></i>Kendala / Bagian yang Dilaporkan
                    </label>
                    <div id="modalKendala" class="p-2.5 bg-danger-subtle text-danger rounded border border-danger-subtle fw-semibold" style="font-size: 0.88rem;"></div>
                </div>

                {{-- Teks Pesan WhatsApp Lengkap --}}
                <div class="mb-3">
                    <label class="fw-semibold text-muted small d-block mb-1">
                        <i class="bi bi-whatsapp text-success me-1"></i>Format Isi Pesan WhatsApp
                    </label>
                    <div id="modalMessage" class="p-3 bg-light rounded border text-dark font-monospace" style="font-size: 0.85rem; white-space: pre-wrap; word-break: break-word;"></div>
                </div>

                <div id="modalErrorSection" class="mb-3 d-none">
                    <label class="fw-semibold text-danger small d-block mb-1">Keterangan Error Provider</label>
                    <div id="modalError" class="p-2 bg-danger-subtle text-danger rounded border border-danger-subtle small font-monospace"></div>
                </div>

                <div>
                    <label class="fw-semibold text-muted small d-block mb-1">Response JSON (Ervelia Gateway)</label>
                    <pre id="modalResponse" class="p-2 bg-dark text-light rounded small mb-0" style="max-height: 120px; overflow-y: auto; font-size: 0.75rem;"></pre>
                </div>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 6px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');
        const selectedUserId = document.getElementById('selectedUserId');
        const quickUserSelect = document.getElementById('quickUserSelect');
        const quickVehicleSelect = document.getElementById('quickVehicleSelect');
        const vehiclePickerContainer = document.getElementById('vehiclePickerContainer');
        const templateSelect = document.getElementById('template_code');
        const messageTextarea = document.getElementById('message');
        const manualMessageContainer = document.getElementById('manualMessageContainer');
        const dynamicVariablesContainer = document.getElementById('dynamicVariablesContainer');
        const dynamicFieldsList = document.getElementById('dynamicFieldsList');
        const previewContainer = document.getElementById('previewContainer');
        const livePreviewBox = document.getElementById('livePreviewBox');
        const charCounter = document.getElementById('charCounter');
        const msgAsterisk = document.getElementById('msgRequiredAsterisk');
        const btnUseAdminNumber = document.getElementById('btnUseAdminNumber');

        const adminNumber = @json(config('services.whatsapp.admin_number', ''));

        // Tombol cepat nomor admin
        if (btnUseAdminNumber) {
            btnUseAdminNumber.addEventListener('click', function () {
                if (adminNumber) {
                    phoneInput.value = adminNumber;
                    if (selectedUserId) selectedUserId.value = '';
                } else {
                    alert('Nomor admin belum diisi di .env (WHATSAPP_ADMIN_NUMBER)');
                }
            });
        }

        // Quick User Picker
        if (quickUserSelect) {
            quickUserSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const phone = this.value;
                const userId = selected.getAttribute('data-user-id');
                const userName = selected.getAttribute('data-user-name');
                const vehiclePlat = selected.getAttribute('data-vehicle-plat');
                const vehicleMerk = selected.getAttribute('data-vehicle-merk');

                if (phone) {
                    phoneInput.value = phone;
                }
                if (selectedUserId) {
                    selectedUserId.value = userId || '';
                }

                // Jika ada field nama_driver / driver / nama_pemeriksa pada variabel template yang aktif, isi otomatis
                if (userName) {
                    const driverInputs = document.querySelectorAll('input[name="data[nama_driver]"], input[name="data[driver]"], input[name="data[nama_pemeriksa]"]');
                    driverInputs.forEach(input => {
                        input.value = userName;
                    });
                }

                // Jika driver memiliki kendaraan terkait, otomatis sinkronkan ke template & picker kendaraan
                if (vehiclePlat) {
                    const platInput = document.querySelector('input[name="data[plat_nomor]"]');
                    const merkInput = document.querySelector('input[name="data[merk_tipe]"]');
                    if (platInput && !platInput.value) platInput.value = vehiclePlat;
                    if (merkInput && !merkInput.value && vehicleMerk) merkInput.value = vehicleMerk;

                    if (quickVehicleSelect) {
                        for (let i = 0; i < quickVehicleSelect.options.length; i++) {
                            if (quickVehicleSelect.options[i].value === vehiclePlat) {
                                quickVehicleSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                }

                updateLivePreview();
            });
        }

        // Quick Vehicle Picker
        if (quickVehicleSelect) {
            quickVehicleSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const plat = selected.getAttribute('data-plat');
                const merk = selected.getAttribute('data-merk');
                const odo = selected.getAttribute('data-odo');
                const driver = selected.getAttribute('data-driver');

                if (plat) {
                    const platInput = document.querySelector('input[name="data[plat_nomor]"]');
                    const merkInput = document.querySelector('input[name="data[merk_tipe]"]');
                    const odoInput = document.querySelector('input[name="data[odometer]"]');
                    const driverInput = document.querySelector('input[name="data[driver]"]') || document.querySelector('input[name="data[nama_driver]"]');

                    if (platInput) platInput.value = plat;
                    if (merkInput) merkInput.value = merk || '';
                    if (odoInput) odoInput.value = odo || '0';
                    if (driverInput && driver && driver !== 'Belum ada driver') driverInput.value = driver;

                    updateLivePreview();
                }
            });
        }

        // Update counter pesan manual
        function updateCounter() {
            if (messageTextarea && charCounter) {
                charCounter.textContent = `${messageTextarea.value.length} karakter`;
            }
        }

        if (messageTextarea) {
            messageTextarea.addEventListener('input', updateCounter);
            updateCounter();
        }

        // Render live preview untuk template
        function updateLivePreview() {
            const selectedOpt = templateSelect.options[templateSelect.selectedIndex];
            let content = selectedOpt.getAttribute('data-content');

            if (!content) {
                livePreviewBox.textContent = messageTextarea.value;
                return;
            }

            const varInputs = dynamicFieldsList.querySelectorAll('input, textarea');
            varInputs.forEach(input => {
                const varName = input.getAttribute('data-var');
                const val = input.value || `{${varName}}`;
                const regex = new RegExp(`\\{\\{\\s*${varName}\\s*\\}\\}`, 'g');
                content = content.replace(regex, val);
            });

            livePreviewBox.textContent = content;
        }

        // Dynamic Template Selection Handler
        if (templateSelect) {
            templateSelect.addEventListener('change', function () {
                const selectedOpt = this.options[this.selectedIndex];
                const content = selectedOpt.getAttribute('data-content');
                const rawVars = selectedOpt.getAttribute('data-vars');

                dynamicFieldsList.innerHTML = '';

                if (content) {
                    // Sembunyikan textarea manual & tampilkan kontainer dinamis + preview
                    manualMessageContainer.classList.add('d-none');
                    dynamicVariablesContainer.classList.remove('d-none');
                    previewContainer.classList.remove('d-none');
                    vehiclePickerContainer.classList.remove('d-none');
                    if (msgAsterisk) msgAsterisk.classList.add('d-none');

                    let vars = [];
                    try {
                        vars = JSON.parse(rawVars) || [];
                    } catch (e) {
                        vars = [];
                    }

                    // Buat input field untuk tiap variabel template
                    vars.forEach(varName => {
                        const formGroup = document.createElement('div');
                        formGroup.className = 'mb-2';

                        const label = document.createElement('label');
                        label.className = 'form-label text-muted small fw-semibold mb-1 text-capitalize';
                        label.textContent = varName.replace(/_/g, ' ');

                        let inputField;
                        if (varName.includes('deskripsi') || varName.includes('catatan') || varName.includes('komponen')) {
                            inputField = document.createElement('textarea');
                            inputField.rows = 2;
                        } else {
                            inputField = document.createElement('input');
                            inputField.type = 'text';
                        }

                        inputField.name = `data[${varName}]`;
                        inputField.className = 'form-control form-control-sm';
                        inputField.setAttribute('data-var', varName);
                        inputField.placeholder = `Isi nilai untuk ${varName.replace(/_/g, ' ')}...`;
                        inputField.style.borderRadius = '6px';

                        // Nilai default untuk tanggal/waktu jika ada
                        if (varName === 'tanggal') {
                            const now = new Date();
                            inputField.value = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()}`;
                        } else if (varName === 'waktu') {
                            const now = new Date();
                            inputField.value = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
                        }

                        inputField.addEventListener('input', updateLivePreview);

                        formGroup.appendChild(label);
                        formGroup.appendChild(inputField);
                        dynamicFieldsList.appendChild(formGroup);
                    });

                    updateLivePreview();
                } else {
                    // Tampilkan kembali textarea manual
                    manualMessageContainer.classList.remove('d-none');
                    dynamicVariablesContainer.classList.add('d-none');
                    previewContainer.classList.add('d-none');
                    vehiclePickerContainer.classList.add('d-none');
                    if (msgAsterisk) msgAsterisk.classList.remove('d-none');
                    updateCounter();
                }
            });
        }

        // Handle modal detail log
        const detailButtons = document.querySelectorAll('.btn-detail-log');
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                try {
                    const logData = JSON.parse(this.getAttribute('data-log'));
                    
                    document.getElementById('modalPelapor').textContent = logData.parsed_pelapor || (logData.user ? logData.user.name : 'Pengemudi / Staff');
                    document.getElementById('modalPhone').textContent = logData.phone ? `No. WA: ${logData.phone}` : '—';
                    document.getElementById('modalKendaraan').textContent = logData.parsed_kendaraan || 'Armada Umum / Belum Ditentukan';
                    document.getElementById('modalTemplateName').textContent = logData.template ? logData.template.name : 'Pesan Manual';
                    document.getElementById('modalKendala').textContent = logData.parsed_kendala || 'Tidak ada kendala tercatat.';

                    const statusBadge = document.getElementById('modalStatusBadge');
                    statusBadge.textContent = (logData.status || 'PENDING').toUpperCase();
                    statusBadge.className = `badge bg-${logData.status === 'success' ? 'success' : (logData.status === 'failed' ? 'danger' : 'secondary')}`;

                    document.getElementById('modalSentAt').textContent = logData.sent_at || logData.created_at || '—';
                    document.getElementById('modalMessage').textContent = logData.message || '—';

                    const errorSection = document.getElementById('modalErrorSection');
                    const modalError = document.getElementById('modalError');
                    if (logData.error_message) {
                        errorSection.classList.remove('d-none');
                        modalError.textContent = logData.error_message;
                    } else {
                        errorSection.classList.add('d-none');
                    }

                    document.getElementById('modalResponse').textContent = logData.response ? JSON.stringify(logData.response, null, 2) : 'Tidak ada payload response';
                } catch (e) {
                    console.error('Error parsing log data', e);
                }
            });
        });
    });
</script>
@endsection
