@extends('layouts.app')

@push('styles')
{{-- Load Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* ===== PREMIUM DASHBOARD STYLES ===== */
    .dashboard-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.25);
    }
    .dashboard-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.15rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dashboard-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        color: #38ef7d;
    }
    .dashboard-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Stats Grid */
    .stats-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform .2s;
    }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .stats-details h3 { font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0; }
    .stats-details span { font-size: 0.75rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; }

    /* Map Card */
    .section-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 14px rgba(0,0,0,0.03);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .section-card-header {
        padding: 16px 20px;
        border-bottom: 1.5px solid #f3f4f6;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-card-header h5 {
        margin: 0; font-size: 0.85rem; font-weight: 700; color: #475569;
        text-transform: uppercase; letter-spacing: .05em;
        display: flex; align-items: center; gap: 8px;
    }
    #map {
        height: 400px;
        width: 100%;
    }

    /* Risk Score Badges */
    .badge-risk {
        font-size: 0.68rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;
    }
    .risk-high { background: #fee2e2; color: #ef4444; }
    .risk-medium { background: #fef3c7; color: #d97706; }
    .risk-low { background: #d1fae5; color: #10b981; }

    /* Weather details list */
    .weather-table-wrap {
        height: 400px;
        overflow-y: auto;
    }
    .weather-row {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
    }
    .weather-row:last-child { border-bottom: none; }
    .weather-hub-name { font-weight: 700; color: #334155; }
    .weather-hub-meta { font-size: 0.72rem; color: #64748b; }

    /* Tables */
    .dashboard-table {
        margin: 0;
        font-size: 0.82rem;
    }
    .dashboard-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        border-bottom: 1.5px solid #f1f5f9;
        padding: 12px 16px;
    }
    .dashboard-table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        color: #334155;
    }

    /* Risk breakdowns */
    .breakdown-dots {
        display: flex;
        gap: 6px;
    }
    .breakdown-dot {
        width: 8px; height: 8px; border-radius: 50%;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="dashboard-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-speedometer2"></i></span>
            <span>
                SCM Risk Control Room
                <div class="subtitle">Dashboard Risiko All-in-One Real-time & Pemantauan Rantai Pasok</div>
            </span>
        </h4>
    </div>
    <form action="{{ route('dashboard') }}" method="GET">
        <button type="submit" class="btn btn-sm btn-light fw-bold" style="border-radius:8px;padding:6px 14px;font-size:0.78rem;">
            <i class="bi bi-arrow-clockwise text-primary"></i> Recalculate Risk
        </button>
    </form>
</div>

{{-- Statistic Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stats-card">
            <div class="stats-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-truck"></i></div>
            <div class="stats-details">
                <h3>{{ $stats['shipments'] }}</h3>
                <span>Shipments</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card">
            <div class="stats-icon" style="background:#d1fae5;color:#10b981;"><i class="bi bi-building"></i></div>
            <div class="stats-details">
                <h3>{{ $stats['suppliers'] }}</h3>
                <span>Suppliers</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card">
            <div class="stats-icon" style="background:#ffe4e6;color:#e11d48;"><i class="bi bi-anchor"></i></div>
            <div class="stats-details">
                <h3>{{ $stats['ports'] }}</h3>
                <span>Ports</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-card">
            <div class="stats-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-globe2"></i></div>
            <div class="stats-details">
                <h3>{{ $stats['countries'] }}</h3>
                <span>Countries</span>
            </div>
        </div>
    </div>
</div>

{{-- Row 1: Live Tracking Map & Port Weather --}}
<div class="row g-3 mb-4">
    {{-- Leaflet Map --}}
    <div class="col-lg-8">
        <div class="section-card">
            <div class="section-card-header">
                <h5><i class="bi bi-geo-alt-fill text-danger"></i> Live Shipment Risk Tracking</h5>
                <span class="badge bg-light text-dark font-monospace" style="font-size:0.68rem;">Interactive Leaflet Map</span>
            </div>
            <div class="card-body p-0">
                <div id="map"></div>
            </div>
        </div>
    </div>

    {{-- Weather Hubs --}}
    <div class="col-lg-4">
        <div class="section-card">
            <div class="section-card-header">
                <h5><i class="bi bi-cloud-sun text-primary"></i> Live Port Weather Monitor</h5>
                <span class="badge bg-primary-subtle text-primary font-monospace" style="font-size:0.68rem;">Open-Meteo API</span>
            </div>
            <div class="weather-table-wrap">
                @php
                    use App\Http\Controllers\WeatherController;
                @endphp
                @foreach($weatherHubs as $hub)
                    @php
                        $wInfo = WeatherController::weatherCodeInfo($hub['code']);
                        // Hitung weather risk untuk pelabuhan
                        $wRisk = 'Low'; $wRiskClass = 'risk-low';
                        if ($hub['wind'] > 30 || $hub['rain'] > 5) {
                            $wRisk = 'High'; $wRiskClass = 'risk-high';
                        } elseif ($hub['wind'] > 15 || $hub['rain'] > 1) {
                            $wRisk = 'Medium'; $wRiskClass = 'risk-medium';
                        }
                    @endphp
                    <div class="weather-row">
                        <div>
                            <div class="weather-hub-name">{{ $hub['name'] }}</div>
                            <div class="weather-hub-meta">
                                Angin: {{ number_format($hub['wind'], 1) }} km/h · Hujan: {{ number_format($hub['rain'], 1) }} mm
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-dark" style="font-size:0.9rem;">
                                <i class="bi {{ $wInfo['icon'] }}" style="color: {{ $wInfo['color'] }};"></i> {{ number_format($hub['temp'], 1) }}°C
                            </div>
                            <span class="badge-risk {{ $wRiskClass }}" style="font-size:0.6rem;padding:2px 6px;">{{ $wRisk }} Risk</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Row 2: Recent Shipments & Exchange Rates --}}
<div class="row g-3">
    {{-- Shipment Risk Breakdown --}}
    <div class="col-lg-8">
        <div class="section-card">
            <div class="section-card-header">
                <h5><i class="bi bi-shield-alert text-warning"></i> Shipment Risk Score Breakdown</h5>
                <span class="badge bg-warning-subtle text-warning font-monospace" style="font-size:0.68rem;">Cuaca 30% + Kurs 25% + Port 20% + Geo 25%</span>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Rute Pelabuhan</th>
                            <th>Status</th>
                            <th>Breakdown (C | K | P | G)</th>
                            <th class="text-center">Skor Total</th>
                            <th class="text-center">Risiko</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentShipments as $shipment)
                            @php
                                $rScore = $shipment->riskScore;
                                $scoreVal = $rScore ? $rScore->total_score : 20;
                                $rLevel = $shipment->risk_level;
                                
                                $riskClass = 'risk-low';
                                if ($rLevel === 'High') $riskClass = 'risk-high';
                                elseif ($rLevel === 'Medium') $riskClass = 'risk-medium';
                                
                                // Color dots for breakdown
                                $dotW = ($rScore && $rScore->weather_score >= 70) ? '#ef4444' : (($rScore && $rScore->weather_score >= 40) ? '#f59e0b' : '#10b981');
                                $dotK = ($rScore && $rScore->currency_score >= 70) ? '#ef4444' : (($rScore && $rScore->currency_score >= 40) ? '#f59e0b' : '#10b981');
                                $dotP = ($rScore && $rScore->port_score >= 70) ? '#ef4444' : (($rScore && $rScore->port_score >= 40) ? '#f59e0b' : '#10b981');
                                $dotG = ($rScore && $rScore->geopolitical_score >= 70) ? '#ef4444' : (($rScore && $rScore->geopolitical_score >= 40) ? '#f59e0b' : '#10b981');
                            @endphp
                            <tr>
                                <td><strong style="color:#1e293b;">{{ $shipment->shipment_code }}</strong></td>
                                <td style="font-size:0.78rem;">
                                    {{ $shipment->originPort ? $shipment->originPort->port_name : '-' }} 
                                    <i class="bi bi-arrow-right text-muted mx-1"></i> 
                                    {{ $shipment->destinationPort ? $shipment->destinationPort->port_name : '-' }}
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $shipment->status }}</span></td>
                                <td>
                                    <div class="breakdown-dots" title="Cuaca, Kurs, Pelabuhan, Geopolitik">
                                        <div class="breakdown-dot" style="background-color: {{ $dotW }};" title="Cuaca: {{ $rScore ? $rScore->weather_score : 'N/A' }}"></div>
                                        <div class="breakdown-dot" style="background-color: {{ $dotK }};" title="Kurs: {{ $rScore ? $rScore->currency_score : 'N/A' }}"></div>
                                        <div class="breakdown-dot" style="background-color: {{ $dotP }};" title="Pelabuhan: {{ $rScore ? $rScore->port_score : 'N/A' }}"></div>
                                        <div class="breakdown-dot" style="background-color: {{ $dotG }};" title="Geopolitik: {{ $rScore ? $rScore->geopolitical_score : 'N/A' }}"></div>
                                    </div>
                                </td>
                                <td class="text-center font-monospace fw-bold" style="font-size:0.9rem;">
                                    {{ $scoreVal }}<span style="font-size:0.65rem;color:#9ca3af;">/100</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-risk {{ $riskClass }}">{{ $rLevel }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada shipment yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Exchange Rates Chart --}}
    <div class="col-lg-4">
        <div class="section-card">
            <div class="section-card-header">
                <h5><i class="bi bi-graph-up-arrow text-success"></i> Grafik Kurs Valuta (USD)</h5>
                <span class="badge bg-success-subtle text-success font-monospace" style="font-size:0.68rem;">ER-API Live</span>
            </div>
            <div class="card-body">
                <canvas id="currencyRatesChart" style="max-height: 250px;"></canvas>
                <div class="text-center mt-3 text-muted" style="font-size:0.68rem;">
                    Menampilkan nilai tukar relatif per 1 USD secara real-time.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Load Leaflet JS & Chart.js --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ===== 1. Leaflet Map setup =====
    let map = L.map('map', { zoomControl: true }).setView([20, 15], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
    }).addTo(map);

    // ===== 2. Region color palette =====
    const regionColors = {
        'Asia':     { fill: '#EC4899', border: '#BE185D' },   // Pink
        'Europe':   { fill: '#3b82f6', border: '#1d4ed8' },   // Blue
        'Americas': { fill: '#10b981', border: '#065f46' },   // Green
        'Africa':   { fill: '#f59e0b', border: '#b45309' },   // Amber
        'Oceania':  { fill: '#8b5cf6', border: '#6d28d9' },   // Purple
        'Antarctic':{ fill: '#64748b', border: '#334155' },   // Slate
    };

    function getRegionColor(region) {
        for (const key in regionColors) {
            if (region && region.toLowerCase().includes(key.toLowerCase())) {
                return regionColors[key];
            }
        }
        return { fill: '#DB2777', border: '#9D174D' }; // default pink
    }

    // ===== 3. Countries Layer =====
    const countriesLayer = L.layerGroup();
    const countries = @json($countriesMap ?? []);

    countries.forEach(function(c) {
        if (!c.latitude || !c.longitude) return;
        const col = getRegionColor(c.region);

        const marker = L.circleMarker([c.latitude, c.longitude], {
            radius: 5,
            color: col.border,
            fillColor: col.fill,
            fillOpacity: 0.75,
            weight: 1.5
        });

        marker.bindPopup(`
            <div style="font-family:'Poppins',sans-serif;font-size:0.8rem;line-height:1.6;min-width:160px;">
                <div style="font-size:1.2rem;margin-bottom:4px;">${getFlagEmoji(c.country_code)}</div>
                <strong style="font-size:0.9rem;color:#1e293b;">${c.country_name}</strong><br>
                <span style="color:#64748b;font-size:0.72rem;">
                    🏙️ ${c.capital || '-'} &nbsp;|&nbsp; ${c.region || '-'}
                </span><br>
                <span style="font-size:0.72rem;color:#DB2777;font-weight:700;">
                    💱 ${c.currency_code || '-'}
                </span><br>
                <span style="font-size:0.68rem;color:#94a3b8;">
                    📍 ${parseFloat(c.latitude).toFixed(2)}, ${parseFloat(c.longitude).toFixed(2)}
                </span>
            </div>
        `, { maxWidth: 220 });

        marker.on('mouseover', function() { this.openPopup(); });
        countriesLayer.addLayer(marker);
    });

    countriesLayer.addTo(map);

    // ===== 4. Shipments Layer (Risk Markers) =====
    const shipmentsLayer = L.layerGroup();
    const shipments = @json($shipmentsMap ?? []);

    shipments.forEach(function(item) {
        if (!item.latitude || !item.longitude) return;

        let color = "#10b981";   // Low  - Green
        let label = "Low";
        if (item.risk_level === "Medium") { color = "#f59e0b"; label = "Sedang"; }
        if (item.risk_level === "High")   { color = "#ef4444"; label = "Tinggi"; }

        // Pulsing icon for shipments
        const icon = L.divIcon({
            className: '',
            html: `<div style="
                width:18px;height:18px;
                background:${color};
                border:3px solid #fff;
                border-radius:50%;
                box-shadow:0 0 0 3px ${color}55, 0 2px 8px rgba(0,0,0,0.25);
                animation: pulse-marker 2s infinite;
            "></div>`,
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });

        L.marker([item.latitude, item.longitude], { icon })
            .addTo(shipmentsLayer)
            .bindPopup(`
                <div style="font-family:'Poppins',sans-serif;font-size:0.8rem;line-height:1.6;min-width:170px;">
                    <strong style="font-size:0.9rem;color:#1e293b;">🚢 ${item.shipment_code}</strong><br>
                    <span style="color:#64748b;font-size:0.72rem;">Status: <b>${item.status}</b></span><br>
                    <span style="color:${color};font-weight:700;font-size:0.75rem;">
                        ⚠️ Risiko: ${label}
                    </span>
                </div>
            `, { maxWidth: 220 });
    });

    shipmentsLayer.addTo(map);

    // ===== 5. Layer Control =====
    const overlays = {
        "🌍 Semua Negara (Database)": countriesLayer,
        "🚢 Shipment Aktif (Risiko)": shipmentsLayer
    };
    L.control.layers(null, overlays, { collapsed: false, position: 'topright' }).addTo(map);

    // ===== 6. Legend =====
    const legend = L.control({ position: 'bottomleft' });
    legend.onAdd = function() {
        const div = L.DomUtil.create('div');
        div.style.cssText = 'background:#fff;padding:10px 14px;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.1);font-size:0.72rem;font-family:Poppins,sans-serif;line-height:2;';
        div.innerHTML = `
            <div style="font-weight:700;color:#374151;margin-bottom:4px;">📍 Legenda Negara</div>
            ${Object.entries(regionColors).map(([region, col]) =>
                `<div><span style="display:inline-block;width:12px;height:12px;background:${col.fill};border-radius:50%;margin-right:6px;vertical-align:middle;border:2px solid ${col.border};"></span>${region}</div>`
            ).join('')}
            <div style="border-top:1px solid #f1f5f9;margin-top:6px;padding-top:6px;font-weight:700;color:#374151;">🚢 Shipment Risk</div>
            <div><span style="display:inline-block;width:12px;height:12px;background:#10b981;border-radius:50%;margin-right:6px;vertical-align:middle;"></span>Low Risk</div>
            <div><span style="display:inline-block;width:12px;height:12px;background:#f59e0b;border-radius:50%;margin-right:6px;vertical-align:middle;"></span>Medium Risk</div>
            <div><span style="display:inline-block;width:12px;height:12px;background:#ef4444;border-radius:50%;margin-right:6px;vertical-align:middle;"></span>High Risk</div>
        `;
        return div;
    };
    legend.addTo(map);

    // ===== Helper: Flag Emoji from country code =====
    function getFlagEmoji(code) {
        if (!code || code.length !== 2) return '🌐';
        const offset = 0x1F1E6;
        const A = 0x41;
        return String.fromCodePoint(
            offset + code.toUpperCase().charCodeAt(0) - A,
            offset + code.toUpperCase().charCodeAt(1) - A
        );
    }

    // ===== 7. Chart.js Exchange Rates =====
    const ratesData = @json($ratesData ?? []);
    const labels = Object.keys(ratesData);
    const values = Object.values(ratesData);

    const ctx = document.getElementById('currencyRatesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Kurs (per 1 USD)',
                data: values,
                backgroundColor: [
                    'rgba(236, 72, 153, 0.75)',  // IDR - Pink
                    'rgba(59, 130, 246, 0.75)',  // EUR - Blue
                    'rgba(16, 185, 129, 0.75)',  // SGD - Green
                    'rgba(245, 158, 11, 0.75)',  // CNY - Amber
                    'rgba(139, 92, 246, 0.75)'   // JPY - Purple
                ],
                borderColor: [
                    'rgba(219, 39, 119, 1)',
                    'rgba(37, 99, 235, 1)',
                    'rgba(5, 150, 105, 1)',
                    'rgba(180, 83, 9, 1)',
                    'rgba(109, 40, 217, 1)'
                ],
                borderWidth: 1.5,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    type: 'logarithmic',
                    ticks: { font: { size: 9 } },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    ticks: { font: { size: 10, weight: 'bold' } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y.toLocaleString()} ${ctx.label}/USD`
                    }
                }
            }
        }
    });
});
</script>
<style>
@keyframes pulse-marker {
    0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
    70%  { box-shadow: 0 0 0 8px rgba(239,68,68,0); }
    100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
}
.leaflet-control-layers {
    border-radius: 12px !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1) !important;
    font-family: 'Poppins', sans-serif !important;
    font-size: 0.78rem !important;
    border: none !important;
}
.leaflet-control-layers-expanded {
    padding: 10px 14px !important;
}
.leaflet-popup-content-wrapper {
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}
.leaflet-popup-tip { display: none; }
</style>
@endpush