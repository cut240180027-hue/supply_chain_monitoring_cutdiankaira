@extends('layouts.app')

@section('title', 'Watchlist — SCM')

@push('styles')
<style>
    .wl-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        color: #fff;
    }
    .wl-header h4 { margin: 0; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .wl-header .subtitle { color: rgba(255,255,255,0.6); font-size: 0.75rem; }

    .wl-card {
        background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03); padding: 18px 20px;
        display: flex; justify-content: space-between; align-items: center;
        transition: transform .2s; margin-bottom: 16px;
    }
    .wl-card:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .wl-details h5 { margin: 0; font-weight: 700; color: #1f2937; }

    .btn-remove {
        background: #fff1f2; color: #e11d48; border: none; border-radius: 8px;
        padding: 6px 12px; font-size: 0.78rem; font-weight: 600;
        transition: background .2s;
    }
    .btn-remove:hover { background: #e11d48; color: #fff; }

    .alert-success-custom {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: none; border-left: 4px solid #10b981; border-radius: 10px;
        color: #065f46; font-size: 0.82rem; font-weight: 500;
        padding: 10px 16px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .alert-error-custom {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: none; border-left: 4px solid #ef4444; border-radius: 10px;
        color: #7f1d1d; font-size: 0.82rem; font-weight: 500;
        padding: 10px 16px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
</style>
@endpush

@section('content')

@php
    use App\Http\Controllers\WeatherController;
@endphp

<div class="wl-header">
    <h4>
        <i class="bi bi-star-fill text-warning"></i>
        <span>
            Favorite Monitoring List
            <div class="subtitle">Simpan dan pantau negara-negara prioritas impor Anda secara berkala</div>
        </span>
    </h4>
</div>

@if(session('success'))
    <div class="alert-success-custom">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert-error-custom">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
@endif

{{-- Add country form --}}
<div class="card p-4 border-0 shadow-sm mb-4" style="border-radius:16px;">
    <h6 class="fw-bold text-dark mb-3" style="font-size:0.85rem;"><i class="bi bi-plus-circle me-1 text-primary"></i> Tambah Negara ke Watchlist</h6>
    <form action="{{ route('watchlist.store') }}" method="POST" class="row g-2 align-items-center">
        @csrf
        <div class="col-md-9">
            <select name="country_id" class="form-select" style="border-radius:8px;font-size:0.85rem;">
                <option value="">-- Pilih Negara --</option>
                @foreach($countries as $c)
                    <option value="{{ $c->id }}">
                        {{ WeatherController::getFlagEmoji($c->country_code) }} {{ $c->country_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:8px;font-size:0.85rem;height:38px;">
                <i class="bi bi-star"></i> Simpan
            </button>
        </div>
    </form>
</div>

{{-- Watchlist Items --}}
<div class="row">
    <div class="col-12">
        @forelse($watchlists as $wl)
            @php
                $c = $wl->country;
                $flag = $c ? WeatherController::getFlagEmoji($c->country_code) : '';
            @endphp
            <div class="wl-card">
                <div class="wl-details d-flex align-items-center gap-3">
                    <span style="font-size:2.5rem;line-height:1;">{{ $flag }}</span>
                    <div>
                        <h5>{{ $c->country_name }}</h5>
                        <span class="text-muted small">Capital: {{ $c->capital ?: '-' }} · Region: {{ $c->region }} · Currency: {{ $c->currency_code }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('country-dashboard.index', ['country' => $c->country_code]) }}" class="btn btn-sm btn-outline-primary fw-bold" style="border-radius:8px;font-size:0.78rem;">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <form action="{{ route('watchlist.destroy', $wl->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-remove">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card p-5 text-center text-muted border-0 shadow-sm" style="border-radius:16px;">
                <i class="bi bi-star fs-1 mb-2 text-pink-300"></i>
                <p class="mb-0 fw-bold">Belum ada negara yang dipantau.</p>
                <p class="small">Pilih negara di atas untuk menyimpannya ke daftar pantau favorit Anda.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
