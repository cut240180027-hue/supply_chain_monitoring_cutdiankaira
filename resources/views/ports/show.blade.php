@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .detail-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .detail-card-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        padding: 18px 24px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .detail-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.05rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-edit {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-edit:hover { background: rgba(255,255,255,0.3); color: #fff; }
    
    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #9ca3af;
        letter-spacing: .05em;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
    }
    .flag-large {
        font-size: 2rem;
        line-height: 1;
        margin-bottom: 4px;
    }
    #map {
        height: 320px;
        border-radius: 12px;
        border: 1.5px solid #e5e7eb;
    }
</style>
@endpush

@section('content')

<div class="card detail-card">
    <div class="detail-card-header">
        <h4>
            <i class="bi bi-anchor"></i> Detail Pelabuhan: {{ $port->port_name }}
        </h4>
        <a href="{{ route('ports.edit', $port) }}" class="btn-edit">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>

    <div class="card-body p-4">
        <div class="row">
            {{-- Info pelabuhan --}}
            <div class="col-md-5">
                <div class="info-label">Nama Pelabuhan</div>
                <div class="info-value">{{ $port->port_name }}</div>

                <div class="info-label">Negara</div>
                <div class="info-value d-flex align-items-center gap-2">
                    @php
                        $flag = '';
                        if ($port->country && strlen($port->country->country_code) === 2) {
                            $chars = str_split(strtoupper($port->country->country_code));
                            $flag = mb_chr(ord($chars[0]) - 65 + 0x1F1E6) . mb_chr(ord($chars[1]) - 65 + 0x1F1E6);
                        }
                    @endphp
                    @if($flag)
                        <span class="flag-large" title="{{ $port->country->country_name }}">{{ $flag }}</span>
                    @endif
                    <span>{{ $port->country ? $port->country->country_name : '-' }} ({{ $port->country ? $port->country->country_code : '-' }})</span>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="info-label">Latitude</div>
                        <div class="info-value font-monospace">{{ number_format($port->latitude, 6) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Longitude</div>
                        <div class="info-value font-monospace">{{ number_format($port->longitude, 6) }}</div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('ports.index') }}" class="btn btn-secondary px-4" style="border-radius: 8px; font-size:0.82rem; font-weight:600;">
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Peta Lokasi --}}
            <div class="col-md-7 mt-4 mt-md-0">
                <div class="info-label mb-2">Peta Lokasi Pelabuhan</div>
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const lat = {{ $port->latitude }};
        const lon = {{ $port->longitude }};
        const portName = "{{ $port->port_name }}";

        // Inisialisasi peta
        const map = L.map('map').setView([lat, lon], 12);

        // Tambahkan tile layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker untuk pelabuhan
        const marker = L.marker([lat, lon]).addTo(map);
        marker.bindPopup(`<b>${portName}</b><br>Lat: ${lat}<br>Lon: ${lon}`).openPopup();
    });
</script>
@endpush
