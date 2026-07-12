@extends('layouts.app')

@push('styles')
<style>
    /* ===== WEATHER PAGE STYLES ===== */
    .weather-hero {
        background: linear-gradient(135deg, #9D174D 0%, #DB2777 50%, #EC4899 100%);
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(236,72,153,0.3);
    }
    .weather-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }

    /* Country search & select card */
    .selector-card {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 24px;
        backdrop-filter: blur(10px);
    }
    .selector-title {
        color: rgba(255,255,255,0.65);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .selector-title i { font-size: 0.85rem; }
    
    .custom-select-wrapper {
        position: relative;
    }
    .custom-select-wrapper i.select-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 0.95rem;
    }
    .weather-select {
        background-color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 16px 10px 40px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1f2937;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all .2s;
        cursor: pointer;
    }
    .weather-select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(236,72,153,0.3);
    }

    /* Current weather big display */
    .current-weather {
        display: flex; align-items: flex-start; gap: 24px;
        flex-wrap: wrap;
    }
    .weather-temp-main {
        flex: 1; min-width: 200px;
    }
    .temp-big {
        font-size: 4.5rem; font-weight: 800;
        color: #fff; line-height: 1;
        display: flex; align-items: flex-start;
    }
    .temp-big .unit { font-size: 1.5rem; margin-top: 12px; font-weight: 400; opacity: .7; }
    .weather-desc { color: rgba(255,255,255,0.75); font-size: 1rem; margin-top: 6px; }
    .weather-icon-big { font-size: 3rem; }
    .weather-location { color: rgba(255,255,255,0.5); font-size: 0.78rem; margin-top: 4px; }

    .weather-stats {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;
        min-width: 220px;
    }
    .stat-item {
        background: rgba(255,255,255,0.08);
        border-radius: 12px; padding: 12px 14px;
        display: flex; flex-direction: column; gap: 4px;
    }
    .stat-label { font-size: 0.68rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: .05em; }
    .stat-value { font-size: 1rem; font-weight: 700; color: #fff; }
    .stat-icon { font-size: 1rem; color: rgba(255,255,255,0.4); }

    /* Risk badge */
    .risk-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700;
        margin-top: 8px;
    }
    .risk-low    { background: rgba(16,185,129,0.2); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.3); }
    .risk-medium { background: rgba(245,158,11,0.2); color: #fcd34d; border: 1px solid rgba(245,158,11,0.3); }
    .risk-high   { background: rgba(239,68,68,0.2);  color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }

    /* Sections */
    .section-title {
        font-size: 0.75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: #9ca3af; margin-bottom: 12px;
    }

    /* Forecast cards */
    .forecast-scroll { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 4px; }
    .forecast-scroll::-webkit-scrollbar { height: 4px; }
    .forecast-scroll::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 4px; }
    .forecast-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

    .forecast-card {
        flex: 0 0 100px;
        background: #fff;
        border-radius: 14px;
        padding: 14px 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1.5px solid #f3f4f6;
        transition: all .2s;
    }
    .forecast-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(236,72,153,0.12); border-color: #fce7f3; }
    .forecast-day  { font-size: 0.72rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; }
    .forecast-icon { font-size: 1.6rem; margin: 8px 0; display: block; }
    .forecast-max  { font-size: 0.9rem; font-weight: 700; color: #1f2937; }
    .forecast-min  { font-size: 0.75rem; color: #9ca3af; }
    .forecast-rain { font-size: 0.68rem; color: #3b82f6; margin-top: 4px; }

    /* Hourly chart wrapper */
    .chart-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1.5px solid #f3f4f6;
    }
    .chart-card canvas { max-height: 180px; }

    /* Info grid */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
    .info-item {
        background: #fff;
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1.5px solid #f3f4f6;
        display: flex; flex-direction: column; gap: 6px;
    }
    .info-item-label { font-size: 0.68rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; }
    .info-item-val   { font-size: 1.1rem; font-weight: 700; color: #1f2937; }
    .info-item-icon  { font-size: 1.2rem; }

    /* Sunrise/sunset */
    .sun-bar {
        background: linear-gradient(90deg, #f59e0b, #fb923c, #7c3aed);
        height: 4px; border-radius: 4px; margin: 8px 0;
        position: relative;
    }

    .alert-api-err {
        background: #fff1f2; border-left: 4px solid #ef4444;
        border-radius: 12px; padding: 14px 18px;
        color: #7f1d1d; font-size: 0.83rem;
        display: flex; align-items: center; gap: 10px;
    }
</style>
@endpush

@section('title', 'Weather Monitor — SCM')

@section('content')

@php
    use App\Http\Controllers\WeatherController;
    $cur   = $weather['current']  ?? null;
    $daily = $weather['daily']    ?? null;
    $hourly= $weather['hourly']   ?? null;

    $code     = $cur ? (int)$cur['weather_code'] : 0;
    $codeInfo = WeatherController::weatherCodeInfo($code);

    $windSpeed = $cur['wind_speed_10m'] ?? 0;
    $precip    = $cur['precipitation']  ?? 0;

    // Tentukan risk level
    if ($windSpeed > 50 || $precip > 10) {
        $risk = 'high';  $riskLabel = '⚠ Risiko Tinggi';
    } elseif ($windSpeed > 25 || $precip > 3) {
        $risk = 'medium'; $riskLabel = '⚡ Risiko Sedang';
    } else {
        $risk = 'low';  $riskLabel = '✓ Kondisi Aman';
    }

    $days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
@endphp

{{-- ===== HERO SECTION ===== --}}
<div class="weather-hero">

    {{-- Country Search & Selector --}}
    <div class="selector-card">
        <div class="selector-title">
            <i class="bi bi-globe"></i> Pilih Negara Pemantauan
        </div>
        <form action="{{ route('weather.index') }}" method="GET" id="countryWeatherForm">
            <div class="custom-select-wrapper">
                <i class="bi bi-search select-icon"></i>
                <select name="country" class="form-select weather-select" onchange="document.getElementById('countryWeatherForm').submit();">
                    @foreach($countries as $c)
                        @php
                            $cFlag = WeatherController::getFlagEmoji($c->country_code);
                        @endphp
                        <option value="{{ $c->country_code }}" {{ $selectedCountry && $selectedCountry->country_code === $c->country_code ? 'selected' : '' }}>
                            {{ $cFlag }} {{ $c->country_name }} ({{ $c->capital ?: 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    @if($error)
        <div class="alert-api-err">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}
        </div>
    @elseif($cur && $selectedCountry)
        <div class="current-weather">
            {{-- Suhu utama --}}
            <div class="weather-temp-main">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
                    <i class="bi {{ $codeInfo['icon'] }} weather-icon-big" style="color:{{ $codeInfo['color'] }};"></i>
                </div>
                <div class="temp-big">
                    {{ number_format($cur['temperature_2m'], 1) }}<span class="unit">°C</span>
                </div>
                <div class="weather-desc">{{ $codeInfo['label'] }}</div>
                <div class="weather-location">
                    <i class="bi bi-geo-alt"></i>
                    Ibukota: <strong>{{ $selectedCountry->capital ?: '-' }}</strong> &nbsp;·&nbsp; {{ $selectedCountry->country_name }}
                    &nbsp;·&nbsp; Lat/Lon: {{ number_format($selectedCountry->latitude, 4) }}, {{ number_format($selectedCountry->longitude, 4) }}
                </div>
                <span class="risk-badge risk-{{ $risk }}">{{ $riskLabel }}</span>
            </div>

            {{-- Stats grid --}}
            <div class="weather-stats">
                <div class="stat-item">
                    <span class="stat-icon"><i class="bi bi-thermometer-half"></i></span>
                    <span class="stat-label">Terasa</span>
                    <span class="stat-value">{{ number_format($cur['apparent_temperature'] ?? $cur['temperature_2m'], 1) }}°C</span>
                </div>
                <div class="stat-item">
                    <span class="stat-icon"><i class="bi bi-droplet"></i></span>
                    <span class="stat-label">Kelembaban</span>
                    <span class="stat-value">{{ $cur['relative_humidity_2m'] ?? '-' }}%</span>
                </div>
                <div class="stat-item">
                    <span class="stat-icon"><i class="bi bi-wind"></i></span>
                    <span class="stat-label">Angin</span>
                    <span class="stat-value">{{ number_format($windSpeed, 1) }} km/h</span>
                </div>
                <div class="stat-item">
                    <span class="stat-icon"><i class="bi bi-cloud-rain"></i></span>
                    <span class="stat-label">Curah Hujan</span>
                    <span class="stat-value">{{ number_format($precip, 1) }} mm</span>
                </div>
                <div class="stat-item" style="grid-column:span 2;">
                    <span class="stat-icon"><i class="bi bi-speedometer"></i></span>
                    <span class="stat-label">Tekanan Udara</span>
                    <span class="stat-value">{{ number_format($cur['surface_pressure'] ?? 0, 1) }} hPa</span>
                </div>
            </div>
        </div>
    @endif
</div>

@if($weather && !$error && $selectedCountry)

{{-- ===== 7-DAY FORECAST ===== --}}
<div class="mb-4">
    <p class="section-title"><i class="bi bi-calendar3 me-1"></i> Prakiraan 7 Hari</p>
    <div class="forecast-scroll">
        @foreach($daily['time'] as $i => $date)
            @php
                $dCode = (int)($daily['weather_code'][$i] ?? 0);
                $dInfo = WeatherController::weatherCodeInfo($dCode);
                $dow   = $days[date('w', strtotime($date))];
                $isToday = $i === 0;
            @endphp
            <div class="forecast-card {{ $isToday ? 'border-primary' : '' }}" style="{{ $isToday ? 'border-color:#667eea!important;' : '' }}">
                <div class="forecast-day" style="{{ $isToday ? 'color:#667eea;' : '' }}">{{ $isToday ? 'Hari ini' : $dow }}</div>
                <span class="forecast-icon">
                    <i class="bi {{ $dInfo['icon'] }}" style="color:{{ $dInfo['color'] }};"></i>
                </span>
                <div class="forecast-max">{{ number_format($daily['temperature_2m_max'][$i], 0) }}°</div>
                <div class="forecast-min">{{ number_format($daily['temperature_2m_min'][$i], 0) }}°</div>
                @if(isset($daily['precipitation_sum'][$i]) && $daily['precipitation_sum'][$i] > 0)
                    <div class="forecast-rain"><i class="bi bi-droplet-fill"></i> {{ number_format($daily['precipitation_sum'][$i], 1) }}mm</div>
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- ===== CHART + INFO GRID ===== --}}
<div class="row g-3 mb-4">

    {{-- Hourly temperature chart --}}
    <div class="col-lg-8">
        <div class="chart-card">
            <p class="section-title"><i class="bi bi-graph-up me-1"></i> Suhu & Probabilitas Hujan (24 Jam)</p>
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>

    {{-- Sunrise / Sunset --}}
    <div class="col-lg-4">
        <div class="chart-card h-100">
            <p class="section-title"><i class="bi bi-sunrise me-1"></i> Matahari Hari Ini</p>

            @php
                $sunrise = isset($daily['sunrise'][0]) ? date('H:i', strtotime($daily['sunrise'][0])) : '-';
                $sunset  = isset($daily['sunset'][0])  ? date('H:i', strtotime($daily['sunset'][0]))  : '-';
                $maxWind = $daily['wind_speed_10m_max'][0] ?? 0;
            @endphp

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <div style="text-align:center;">
                    <div style="font-size:1.8rem;">🌅</div>
                    <div style="font-size:0.68rem;color:#9ca3af;margin-top:2px;">Terbit</div>
                    <div style="font-size:1rem;font-weight:700;color:#1f2937;">{{ $sunrise }}</div>
                </div>
                <div class="sun-bar flex-1" style="flex:1;margin:0 16px;"></div>
                <div style="text-align:center;">
                    <div style="font-size:1.8rem;">🌇</div>
                    <div style="font-size:0.68rem;color:#9ca3af;margin-top:2px;">Terbenam</div>
                    <div style="font-size:1rem;font-weight:700;color:#1f2937;">{{ $sunset }}</div>
                </div>
            </div>

            <hr style="border-color:#f3f4f6;margin:16px 0;">

            <p class="section-title"><i class="bi bi-wind me-1"></i> Angin Maks. Hari Ini</p>
            <div style="font-size:1.8rem;font-weight:800;color:#1f2937;">
                {{ number_format($maxWind, 1) }}
                <span style="font-size:0.9rem;font-weight:400;color:#9ca3af;">km/h</span>
            </div>
            @php
                if ($maxWind > 50) { $wLabel='Badai'; $wColor='#ef4444'; }
                elseif ($maxWind > 30) { $wLabel='Kencang'; $wColor='#f59e0b'; }
                elseif ($maxWind > 15) { $wLabel='Sedang'; $wColor='#3b82f6'; }
                else { $wLabel='Tenang'; $wColor='#10b981'; }
            @endphp
            <span style="font-size:0.75rem;font-weight:600;color:{{ $wColor }};">
                <i class="bi bi-circle-fill" style="font-size:.5rem;"></i> {{ $wLabel }}
            </span>
        </div>
    </div>
</div>

{{-- ===== DETAILED CONDITIONS TABLE ===== --}}
<div class="chart-card">
    <p class="section-title"><i class="bi bi-info-circle me-1"></i> Detail Kondisi Saat Ini — Ibukota {{ $selectedCountry->capital ?: 'N/A' }}</p>
    <div class="info-grid">
        <div class="info-item">
            <span class="info-item-icon">🌡</span>
            <span class="info-item-label">Suhu</span>
            <span class="info-item-val">{{ number_format($cur['temperature_2m'], 1) }}°C</span>
        </div>
        <div class="info-item">
            <span class="info-item-icon">🌡</span>
            <span class="info-item-label">Terasa Seperti</span>
            <span class="info-item-val">{{ number_format($cur['apparent_temperature'] ?? $cur['temperature_2m'], 1) }}°C</span>
        </div>
        <div class="info-item">
            <span class="info-item-icon">💧</span>
            <span class="info-item-label">Kelembaban</span>
            <span class="info-item-val">{{ $cur['relative_humidity_2m'] ?? '-' }}%</span>
        </div>
        <div class="info-item">
            <span class="info-item-icon">💨</span>
            <span class="info-item-label">Kecepatan Angin</span>
            <span class="info-item-val">{{ number_format($windSpeed, 1) }} km/h</span>
        </div>
        <div class="info-item">
            <span class="info-item-icon">🌧</span>
            <span class="info-item-label">Curah Hujan</span>
            <span class="info-item-val">{{ number_format($precip, 1) }} mm</span>
        </div>
        <div class="info-item">
            <span class="info-item-icon">🔵</span>
            <span class="info-item-label">Tekanan Udara</span>
            <span class="info-item-val">{{ number_format($cur['surface_pressure'] ?? 0, 0) }} hPa</span>
        </div>
        <div class="info-item">
            <span class="info-item-icon">📦</span>
            <span class="info-item-label">Status Logistik</span>
            <span class="info-item-val" style="font-size:0.85rem;color:{{ $risk === 'low' ? '#10b981' : ($risk === 'medium' ? '#f59e0b' : '#ef4444') }};">
                {{ $riskLabel }}
            </span>
        </div>
        <div class="info-item">
            <span class="info-item-icon">🕐</span>
            <span class="info-item-label">Data Diperbarui</span>
            <span class="info-item-val" style="font-size:0.78rem;">
                {{ isset($weather['current']['time']) ? date('d M, H:i', strtotime($weather['current']['time'])) : 'N/A' }}
            </span>
        </div>
    </div>
</div>

@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($weather && !$error && isset($hourly))
(function () {
    const hours  = @json(array_slice($hourly['time'], 0, 24));
    const temps  = @json(array_slice($hourly['temperature_2m'], 0, 24));
    const probs  = @json(array_slice($hourly['precipitation_probability'], 0, 24));
    const winds  = @json(array_slice($hourly['wind_speed_10m'], 0, 24));

    const labels = hours.map(h => h.slice(11, 16)); // HH:MM

    const ctx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(ctx, {
        data: {
            labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Suhu (°C)',
                    data: temps,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2,
                    yAxisID: 'yTemp',
                    order: 1,
                },
                {
                    type: 'bar',
                    label: 'Prob. Hujan (%)',
                    data: probs,
                    backgroundColor: 'rgba(59,130,246,0.25)',
                    borderColor: 'rgba(59,130,246,0.5)',
                    borderWidth: 1,
                    borderRadius: 3,
                    yAxisID: 'yRain',
                    order: 2,
                },
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { labels: { font: { size: 11 }, boxWidth: 12 } },
                tooltip: { bodyFont: { size: 11 } }
            },
            scales: {
                x: {
                    ticks: { font: { size: 10 }, maxTicksLimit: 12 },
                    grid: { color: '#f3f4f6' }
                },
                yTemp: {
                    position: 'left',
                    ticks: { font: { size: 10 }, callback: v => v + '°C' },
                    grid: { color: '#f3f4f6' }
                },
                yRain: {
                    position: 'right',
                    min: 0, max: 100,
                    ticks: { font: { size: 10 }, callback: v => v + '%' },
                    grid: { display: false }
                }
            }
        }
    });
})();
@endif
</script>
@endpush
