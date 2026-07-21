@extends('layouts.app')

@section('title', 'Global Country Dashboard — SCM')

@push('styles')
<style>
    .cd-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .cd-header h4 {
        color: #fff;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cd-header .subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 0.75rem;
    }
    .grid-5 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .metric-box {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        position: relative;
        overflow: hidden;
    }
    .metric-box::after {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0; width: 4px;
    }
    .m-gdp::after { background: #3b82f6; }
    .m-inflation::after { background: #ef4444; }
    .m-pop::after { background: #10b981; }
    .m-currency::after { background: #f59e0b; }
    .m-weather::after { background: #8b5cf6; }

    .m-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .m-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1f2937;
        margin-top: 6px;
    }
    .m-sub {
        font-size: 0.72rem;
        color: #6b7280;
        margin-top: 4px;
    }
    
    .panel-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        margin-bottom: 24px;
    }
    .panel-header {
        padding: 16px 20px;
        border-bottom: 1.5px solid #f3f4f6;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }
    .panel-header h5 {
        margin: 0;
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .05em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .badge-sentiment {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')

@php
    use App\Http\Controllers\WeatherController;
    $flag = $selectedCountry ? WeatherController::getFlagEmoji($selectedCountry->country_code) : '';
@endphp

<div class="cd-header">
    <div>
        <h4>
            <i class="bi bi-globe2 text-warning"></i>
            <span>
                Global Country Dashboard
                <div class="subtitle">Informasi terpadu indikator makro, cuaca, kurs, dan analisis sentimen negara</div>
            </span>
        </h4>
    </div>
    <div>
        <form action="{{ route('country-dashboard.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <select name="country" onchange="this.form.submit();" class="form-select form-select-sm" style="border-radius:8px; font-weight:600; min-width:180px;">
                @foreach($countries as $c)
                    <option value="{{ $c->country_code }}" {{ $selectedCountry && $selectedCountry->country_code === $c->country_code ? 'selected' : '' }}>
                        {{ WeatherController::getFlagEmoji($c->country_code) }} {{ $c->country_name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

@if($selectedCountry)
    {{-- Metric Cards Grid --}}
    <div class="grid-5">
        {{-- GDP --}}
        <div class="metric-box m-gdp">
            <div class="m-label">Gross Domestic Product</div>
            <div class="m-value">
                @if($economy && $economy->gdp)
                    @if($economy->gdp >= 1e12)
                        ${{ number_format($economy->gdp / 1e12, 2) }} T
                    @elseif($economy->gdp >= 1e9)
                        ${{ number_format($economy->gdp / 1e9, 2) }} B
                    @else
                        ${{ number_format($economy->gdp / 1e6, 2) }} M
                    @endif
                @else
                    N/A
                @endif
            </div>
            <div class="m-sub">Tahun: {{ $economy ? $economy->year : 'N/A' }}</div>
        </div>

        {{-- Inflation --}}
        <div class="metric-box m-inflation">
            <div class="m-label">Inflation Rate</div>
            <div class="m-value" style="color: {{ ($economy && $economy->inflation > 5) ? '#ef4444' : '#1f2937' }};">
                {{ ($economy && $economy->inflation) ? number_format($economy->inflation, 2) . '%' : 'N/A' }}
            </div>
            <div class="m-sub">Consumer Price Index</div>
        </div>

        {{-- Population --}}
        <div class="metric-box m-pop">
            <div class="m-label">Population</div>
            <div class="m-value">
                @if($economy && $economy->population)
                    @if($economy->population >= 1e9)
                        {{ number_format($economy->population / 1e9, 2) }} B
                    @elseif($economy->population >= 1e6)
                        {{ number_format($economy->population / 1e6, 2) }} M
                    @else
                        {{ number_format($economy->population) }}
                    @endif
                @else
                    N/A
                @endif
            </div>
            <div class="m-sub">Masyarakat Terdata</div>
        </div>

        {{-- Currency exchange --}}
        <div class="metric-box m-currency">
            <div class="m-label">Mata Uang ({{ $selectedCountry->currency_code }})</div>
            <div class="m-value">
                @if($exchangeRate)
                    {{ number_format($exchangeRate, 2) }} <span style="font-size:0.75rem; font-weight:600; color:#9ca3af;">{{ $selectedCountry->currency_code }}/USD</span>
                @else
                    N/A
                @endif
            </div>
            <div class="m-sub">{{ Str::limit($selectedCountry->currency, 20) }}</div>
        </div>

        {{-- Weather --}}
        <div class="metric-box m-weather">
            <div class="m-label">Cuaca Saat Ini</div>
            <div class="m-value">
                @if($weather)
                    @php
                        $wInfo = WeatherController::weatherCodeInfo($weather['weather_code']);
                    @endphp
                    <i class="bi {{ $wInfo['icon'] }}" style="color: {{ $wInfo['color'] }}; font-size:1.2rem;"></i> 
                    {{ number_format($weather['temperature_2m'], 1) }}°C
                @else
                    N/A
                @endif
            </div>
            <div class="m-sub">
                @if($weather)
                    {{ $wInfo['label'] }} · {{ number_format($weather['wind_speed_10m'], 1) }} km/h
                @else
                    {{ $weatherError ?: 'Detail tidak tersedia.' }}
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left: News Sentiment summary --}}
        <div class="col-lg-6">
            <div class="panel-card h-100">
                <div class="panel-header">
                    <h5><i class="bi bi-shield-check text-success"></i> Analisis Sentimen Leksikon (Kategori: {{ $selectedCountry->country_name }})</h5>
                </div>
                <div class="card-body p-4">
                    <div style="font-size: 0.85rem; color:#4b5563; margin-bottom: 20px;">
                        Hasil analisis leksikon berita global (BBC RSS) yang memuat nama negara <strong>{{ $selectedCountry->country_name }} {{ $flag }}</strong>.
                    </div>

                    <div class="row g-2 mb-4 text-center">
                        <div class="col-4">
                            <div style="background:#d1fae5; color:#065f46; border-radius:10px; padding:12px;">
                                <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase;">Positive</div>
                                <h4 class="fw-bold mb-0 mt-1">{{ $sentimentStats['Positive'] }}%</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="background:#f3f4f6; color:#4b5563; border-radius:10px; padding:12px;">
                                <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase;">Neutral</div>
                                <h4 class="fw-bold mb-0 mt-1">{{ $sentimentStats['Neutral'] }}%</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="background:#fee2e2; color:#7f1d1d; border-radius:10px; padding:12px;">
                                <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase;">Negative</div>
                                <h4 class="fw-bold mb-0 mt-1">{{ $sentimentStats['Negative'] }}%</h4>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #f3f4f6; padding-top: 16px;">
                        <h6 class="fw-bold mb-3" style="font-size:0.8rem; color:#475569;">Berita Terkait Yang Dianalisis:</h6>
                        @forelse($matchedNews as $item)
                            <div class="p-2 border rounded-3 mb-2" style="font-size:0.75rem;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong style="color:#1f2937;">{{ Str::limit($item['title'], 55) }}</strong>
                                    <span class="badge-sentiment bg-{{ $item['sentiment'] === 'Positive' ? 'success' : ($item['sentiment'] === 'Negative' ? 'danger' : 'secondary') }}">
                                        {{ $item['sentiment'] }}
                                    </span>
                                </div>
                                <div class="text-muted" style="font-size:0.68rem;">
                                    Pos Words: {{ $item['pos_count'] }} | Neg Words: {{ $item['neg_count'] }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted" style="font-size:0.78rem;">
                                Tidak ada berita RSS terbaru yang secara spesifik menyebut nama negara ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Map Location & Details --}}
        <div class="col-lg-6">
            <div class="panel-card h-100">
                <div class="panel-header">
                    <h5><i class="bi bi-geo-alt text-danger"></i> Geografis & Detail Wilayah</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-sm table-borderless" style="font-size: 0.82rem; margin-bottom: 20px;">
                        <tr>
                            <td class="text-muted" width="150">Nama Lengkap:</td>
                            <td class="fw-bold">{{ $selectedCountry->country_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ibukota:</td>
                            <td>{{ $selectedCountry->capital ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kode Wilayah:</td>
                            <td class="font-monospace fw-bold">{{ $selectedCountry->country_code }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Benua / Region:</td>
                            <td>{{ $selectedCountry->region }} / {{ $selectedCountry->subregion }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bahasa Utama:</td>
                            <td>{{ $selectedCountry->language ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Timezone:</td>
                            <td>{{ $selectedCountry->timezone ?: '-' }}</td>
                        </tr>
                    </table>

                    @if($selectedCountry->latitude && $selectedCountry->longitude)
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                        <div id="countryMap" style="height: 220px; border-radius:12px; border:1px solid #e5e7eb;"></div>
                        
                        @push('scripts')
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const lat = {{ $selectedCountry->latitude }};
                                const lon = {{ $selectedCountry->longitude }};
                                const map = L.map('countryMap').setView([lat, lon], 5);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 18,
                                    attribution: '© OpenStreetMap'
                                }).addTo(map);
                                L.marker([lat, lon]).addTo(map).bindPopup("<b>{{ $selectedCountry->country_name }}</b>").openPopup();
                            });
                        </script>
                        @endpush
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
