@extends('layouts.app')

@section('title', 'Live Shipment Tracking — SCM')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    /* ===== LIVE TRACKING STYLES ===== */
    .tracking-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .tracking-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .tracking-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .tracking-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Layout grid */
    .tracking-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 991px) {
        .tracking-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Left Sidebar: List of Shipments */
    .shipments-list-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        height: 600px;
        overflow: hidden;
    }
    .list-search-wrap {
        padding: 16px;
        border-bottom: 1.5px solid #f3f4f6;
    }
    .list-search-wrap input {
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        font-size: 0.8rem;
        padding-left: 32px;
        height: 36px;
    }
    .list-search-wrap i {
        position: absolute;
        left: 28px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 0.85rem;
    }

    .shipments-scroll-container {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
    }
    
    .shipment-list-item {
        background: #fdf2f8;
        border: 1px solid #fce7f3;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .shipment-list-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(236,72,153,0.1);
        border-color: #fbcfe8;
    }
    .shipment-list-item.active {
        background: #fce7f3;
        border-color: #EC4899;
        box-shadow: 0 4px 12px rgba(236,72,153,0.15);
    }
    
    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    .item-code {
        font-weight: 700;
        color: #1f2937;
        font-size: 0.85rem;
    }
    
    /* Map panel */
    .map-panel {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        overflow: hidden;
        height: 600px;
        position: relative;
    }
    #map {
        width: 100%;
        height: 100%;
    }

    /* Risk indicators */
    .indicator-pill {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .pill-low { background: #d1fae5; color: #065f46; }
    .pill-med { background: #fef3c7; color: #92400e; }
    .pill-high { background: #fee2e2; color: #7f1d1d; }

    /* Custom popup styles */
    .custom-popup-content {
        font-family: 'Poppins', sans-serif;
        font-size: 0.78rem;
        line-height: 1.5;
        min-width: 180px;
    }
    .custom-popup-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Keyframes for pulse */
    @keyframes pulse-low {
        0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
        70% { box-shadow: 0 0 0 10px rgba(16,185,129,0); }
        100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
    }
    @keyframes pulse-med {
        0% { box-shadow: 0 0 0 0 rgba(245,158,11,0.5); }
        70% { box-shadow: 0 0 0 10px rgba(245,158,11,0); }
        100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); }
    }
    @keyframes pulse-high {
        0% { box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
        70% { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
        100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="tracking-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-geo-alt"></i></span>
            <span>
                Live Shipment Tracking
                <div class="subtitle">Monitoring rute, posisi, status, dan level risiko pengiriman secara real-time</div>
            </span>
        </h4>
    </div>
</div>

<div class="tracking-layout">
    {{-- Sidebar --}}
    <div class="shipments-list-card">
        <div class="list-search-wrap position-relative">
            <i class="bi bi-search"></i>
            <input type="text" id="shipmentSearch" class="form-control w-100" placeholder="Cari kode/supplier/vessel...">
        </div>

        <div class="shipments-scroll-container" id="shipmentsScroll">
            @forelse($shipments as $shipment)
                @if($shipment->latitude && $shipment->longitude)
                    <div class="shipment-list-item" 
                         data-id="{{ $shipment->id }}"
                         data-lat="{{ $shipment->latitude }}"
                         data-lng="{{ $shipment->longitude }}"
                         data-code="{{ $shipment->shipment_code }}">
                        <div class="item-header">
                            <span class="item-code">🚢 {{ $shipment->shipment_code }}</span>
                            @php
                                $rClass = $shipment->risk_level === 'High' ? 'pill-high' : ($shipment->risk_level === 'Medium' ? 'pill-med' : 'pill-low');
                            @endphp
                            <span class="indicator-pill {{ $rClass }}">{{ $shipment->risk_level }}</span>
                        </div>
                        <div style="font-size:0.72rem;color:#4b5563;margin-bottom:4px;">
                            <strong>Vessel:</strong> {{ $shipment->vessel_name ?: '-' }}
                        </div>
                        <div style="font-size:0.72rem;color:#4b5563;margin-bottom:4px;">
                            <strong>Supplier:</strong> {{ $shipment->supplier->company_name ?? '-' }}
                        </div>
                        <div style="font-size:0.68rem;color:#9ca3af;display:flex;justify-content:space-between;margin-top:6px;">
                            <span>{{ $shipment->status }}</span>
                            <span>📍 {{ number_format($shipment->latitude, 3) }}, {{ number_format($shipment->longitude, 3) }}</span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-5 text-muted" style="font-size: 0.8rem;">
                    <i class="bi bi-box-seam d-block fs-2 mb-2 text-pink-300"></i>
                    Belum ada data shipment aktif
                </div>
            @endforelse
        </div>
    </div>

    {{-- Map --}}
    <div class="map-panel">
        <div id="map"></div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize map
    let map = L.map('map', { zoomControl: true }).setView([15, 115], 3);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
    }).addTo(map);

    // Marker container map to focus on selection
    const markers = {};
    const shipmentData = @json($shipments);

    shipmentData.forEach(function (shipment) {
        if (!shipment.latitude || !shipment.longitude) return;

        let color = "#10b981"; // Low (Green)
        let pulseName = "pulse-low";
        if (shipment.risk_level === 'Medium') {
            color = "#f59e0b"; // Medium (Amber)
            pulseName = "pulse-med";
        } else if (shipment.risk_level === 'High') {
            color = "#ef4444"; // High (Red)
            pulseName = "pulse-high";
        }

        // Custom pulsing divIcon
        const icon = L.divIcon({
            className: '',
            html: `<div style="
                width: 20px;
                height: 20px;
                background: ${color};
                border: 3px solid #fff;
                border-radius: 50%;
                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                animation: ${pulseName} 2s infinite;
            "></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        // Popup content layout
        const popupContent = `
            <div class="custom-popup-content">
                <div class="custom-popup-title">
                    <span>🚢 ${shipment.shipment_code}</span>
                </div>
                <div style="margin-bottom:4px;">
                    <strong>Kapal:</strong> ${shipment.vessel_name || '-'}
                </div>
                <div style="margin-bottom:4px;">
                    <strong>Supplier:</strong> ${shipment.supplier ? shipment.supplier.company_name : '-'}
                </div>
                <div style="margin-bottom:4px;">
                    <strong>Rute:</strong> ${shipment.origin_country ? shipment.origin_country.country_name : '-'} ➔ ${shipment.destination_country ? shipment.destination_country.country_name : '-'}
                </div>
                <div style="margin-bottom:4px;">
                    <strong>Status:</strong> <span class="badge bg-light text-dark border">${shipment.status}</span>
                </div>
                <div style="color:${color};font-weight:700;margin-top:6px;">
                    ⚠ Risiko: ${shipment.risk_level}
                </div>
            </div>
        `;

        const marker = L.marker([shipment.latitude, shipment.longitude], { icon })
            .addTo(map)
            .bindPopup(popupContent, { maxWidth: 220 });

        // Add to global dictionary
        markers[shipment.id] = marker;
    });

    // Handle list item click
    const listItems = document.querySelectorAll('.shipment-list-item');
    listItems.forEach(function (item) {
        item.addEventListener('click', function () {
            // Remove active classes
            listItems.forEach(el => el.classList.remove('active'));
            this.classList.add('active');

            const id = this.getAttribute('data-id');
            const lat = parseFloat(this.getAttribute('data-lat'));
            const lng = parseFloat(this.getAttribute('data-lng'));

            if (markers[id]) {
                map.setView([lat, lng], 6);
                markers[id].openPopup();
            }
        });
    });

    // Search function
    document.getElementById('shipmentSearch').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        listItems.forEach(function (item) {
            const text = item.textContent.toLowerCase();
            if (text.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Invalidate size on load
    setTimeout(function () {
        map.invalidateSize();
    }, 200);
});
</script>
@endpush