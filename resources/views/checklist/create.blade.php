@extends('layouts.app')
@section('title', __('Input Checklist'))

@section('content')
<h5 class="page-title mb-3">{{ __('Input Checklist Harian') }}</h5>

<style>
    .check-toggle input[type="radio"] { display: none; }
    .check-toggle label {
        cursor: pointer;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        display: block;
        transition: all .15s ease;
        height: 100%;
    }
    .check-toggle label:hover { border-color: #3d7ce0; }
    .check-toggle input[value="OK"]:checked + label {
        background: #e6f7ec; border-color: #1c8a4b; color: #1c8a4b; font-weight: 600;
    }
    .check-toggle input[value="Not OK"]:checked + label {
        background: #fdeaea; border-color: #d9364f; color: #d9364f; font-weight: 600;
    }
    .check-toggle .icon { font-size: 1.6rem; display: block; margin-bottom: .35rem; }
</style>

<div class="card">
    <div class="card-body">
        <form action="{{ route('checklist.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Kendaraan (Plat Nomor)') }}</label>
                    <select name="vehicle_id" class="form-select" required>
                        <option value="">-- {{ __('Pilih Kendaraan') }} --</option>
                        @foreach ($vehicles as $v)
                            <option value="{{ $v->id }}" @selected(request('vehicle_id') == $v->id)>{{ $v->plat_nomor }} - {{ __($v->jenis_kendaraan) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Tanggal') }}</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ auth()->user()->role === 'user' ? __('Nama Pemeriksa') : __('Nama Teknisi') }}</label>
                    <input type="text" name="nama_teknisi" class="form-control" value="{{ auth()->user()->name }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Odometer Terbaru (km)') }}</label>
                    <input type="number" name="odometer" class="form-control" placeholder="{{ __('Contoh: 125000') }}">
                </div>
            </div>

            <hr class="my-4">
            <h6 class="mb-3">{{ __('Parameter Pengecekan Fisik — klik') }} <strong>{{ __('Baik') }}</strong> {{ __('atau') }} <strong>{{ __('Perlu Perhatian') }}</strong> {{ __('untuk tiap item') }}</h6>

            <div class="row g-3">
                @php
                    $params = [
                        'oli_mesin' => ['label' => __('Oli Mesin'), 'icon' => 'bi-droplet-fill'],
                        'air_radiator' => ['label' => __('Air Radiator'), 'icon' => 'bi-thermometer-half'],
                        'minyak_rem' => ['label' => __('Minyak Rem'), 'icon' => 'bi-record-circle'],
                        'ban_rem' => ['label' => __('Ban & Rem'), 'icon' => 'bi-circle-half'],
                        'lampu_klakson' => ['label' => __('Lampu & Klakson'), 'icon' => 'bi-lightbulb-fill'],
                        'kebersihan' => ['label' => __('Kebersihan'), 'icon' => 'bi-stars'],
                    ];
                @endphp
                @foreach ($params as $field => $p)
                <div class="col-md-4">
                    <div class="mb-2 fw-semibold"><i class="bi {{ $p['icon'] }}"></i> {{ $p['label'] }}</div>
                    <div class="row g-2 check-toggle">
                        <div class="col-6">
                            <input type="radio" name="{{ $field }}" id="{{ $field }}_ok" value="OK" checked>
                            <label for="{{ $field }}_ok">
                                <span class="icon"><i class="bi bi-check-circle-fill"></i></span>
                                {{ __('Baik') }}
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" name="{{ $field }}" id="{{ $field }}_notok" value="Not OK">
                            <label for="{{ $field }}_notok">
                                <span class="icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                                {{ __('Perlu Perhatian') }}
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <label class="form-label">{{ __('Catatan Tambahan') }}</label>
                <textarea name="catatan_tambahan" class="form-control" rows="2" placeholder="{{ __('Contoh: Oli perlu dicek & lampu sein redup') }}"></textarea>
            </div>

            <div class="mt-4 form-actions-mobile">
                <button class="btn btn-brand"><i class="bi bi-check2-circle"></i> {{ __('Simpan Checklist') }}</button>
                <a href="{{ route('checklist.index') }}" class="btn btn-secondary">{{ __('Batal') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection