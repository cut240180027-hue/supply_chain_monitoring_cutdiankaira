@extends('layouts.app')

@section('title', 'Country Comparison — SCM')

@push('styles')
<style>
    .comp-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        color: #fff;
    }
    .comp-header h4 { margin: 0; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .comp-header .subtitle { color: rgba(255,255,255,0.6); font-size: 0.75rem; }

    .selector-panel {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }

    .compare-grid {
        display: grid;
        grid-template-columns: 1fr 120px 1fr;
        gap: 20px;
        align-items: stretch;
    }

    @media (max-width: 768px) {
        .compare-grid {
            grid-template-columns: 1fr;
        }
        .vs-divider { display: none; }
    }

    .country-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 14px rgba(0,0,0,0.03);
        padding: 24px;
        text-align: center;
        height: 100%;
    }
    
    .vs-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.6rem;
        color: #db2777;
        background: #fdf2f8;
        border-radius: 16px;
        border: 2px dashed #fbcfe8;
    }

    .flag-huge {
        font-size: 3.5rem;
        line-height: 1;
        margin-bottom: 12px;
    }
    
    .comp-table {
        margin-top: 24px;
        font-size: 0.85rem;
    }
    .comp-table tr {
        border-bottom: 1px solid #f3f4f6;
    }
    .comp-table tr:last-child { border-bottom: none; }
    .comp-table td {
        padding: 12px 8px;
        vertical-align: middle;
    }

    .comp-row-label {
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: .05em;
        text-align: center;
        background: #f8fafc;
        border-radius: 8px;
        padding: 8px !important;
    }

    .risk-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .risk-low { background: #d1fae5; color: #065f46; }
    .risk-medium { background: #fef3c7; color: #92400e; }
    .risk-high { background: #fee2e2; color: #7f1d1d; }
</style>
@endpush

@section('content')

@php
    use App\Http\Controllers\WeatherController;
    $flagA = $countryA ? WeatherController::getFlagEmoji($countryA->country_code) : '🌐';
    $flagB = $countryB ? WeatherController::getFlagEmoji($countryB->country_code) : '🌐';
@endphp

<div class="comp-header">
    <h4>
        <i class="bi bi-arrow-left-right text-warning"></i>
        <span>
            Country Comparison Engine
            <div class="subtitle">Bandingkan indikator makro, risiko, cuaca, dan sentimen antar 2 negara secara real-time</div>
        </span>
    </h4>
</div>

{{-- Selector Panel --}}
<div class="selector-panel">
    <form action="{{ route('comparison.index') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Negara Pertama (A)</label>
                <select name="country_a" class="form-select" style="border-radius:8px;font-size:0.85rem;font-weight:600;">
                    @foreach($countries as $c)
                        <option value="{{ $c->country_code }}" {{ $countryA->country_code === $c->country_code ? 'selected' : '' }}>
                            {{ WeatherController::getFlagEmoji($c->country_code) }} {{ $c->country_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 text-center text-muted fw-bold pb-2" style="font-size: 1.2rem;">
                VS
            </div>
            <div class="col-md-5">
                <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Negara Kedua (B)</label>
                <select name="country_b" class="form-select" style="border-radius:8px;font-size:0.85rem;font-weight:600;">
                    @foreach($countries as $c)
                        <option value="{{ $c->country_code }}" {{ $countryB->country_code === $c->country_code ? 'selected' : '' }}>
                            {{ WeatherController::getFlagEmoji($c->country_code) }} {{ $c->country_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:8px;font-size:0.85rem;height:40px;">
                    <i class="bi bi-arrow-left-right"></i> Bandingkan Negara
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Comparison Layout --}}
<div class="compare-grid">
    {{-- Country A --}}
    <div>
        <div class="country-card">
            <div class="flag-huge">{{ $flagA }}</div>
            <h3 class="fw-bold text-dark mb-0">{{ $countryA->country_name }}</h3>
            <span class="text-muted small">Ibukota: {{ $countryA->capital ?: '-' }} · Region: {{ $countryA->region }}</span>

            <table class="table comp-table table-borderless text-start">
                <tbody>
                    <tr>
                        <td width="150" class="text-muted fw-bold">GDP (PDB)</td>
                        <td class="fw-bold">
                            @if($dataA['economy'] && $dataA['economy']->gdp)
                                ${{ number_format($dataA['economy']->gdp / 1e9, 2) }} Miliar USD
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Inflation Rate</td>
                        <td class="fw-bold">
                            {{ ($dataA['economy'] && $dataA['economy']->inflation) ? number_format($dataA['economy']->inflation, 2) . '%' : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Population</td>
                        <td class="fw-bold">
                            {{ ($dataA['economy'] && $dataA['economy']->population) ? number_format($dataA['economy']->population / 1e6, 2) . ' Juta' : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Currency Exchange</td>
                        <td class="fw-bold">
                            {{ $dataA['exchangeRate'] ? number_format($dataA['exchangeRate'], 2) . ' ' . $countryA->currency_code . '/USD' : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Weather Temp</td>
                        <td class="fw-bold">
                            @if($dataA['weather'])
                                @php $wA = WeatherController::weatherCodeInfo($dataA['weather']['weather_code']); @endphp
                                <i class="bi {{ $wA['icon'] }}" style="color: {{ $wA['color'] }};"></i>
                                {{ number_format($dataA['weather']['temperature_2m'], 1) }}°C ({{ $wA['label'] }})
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">News Sentiment</td>
                        <td class="fw-bold">
                            <span class="text-success">P: {{ $dataA['sentimentStats']['Positive'] }}%</span> · 
                            <span class="text-danger">N: {{ $dataA['sentimentStats']['Negative'] }}%</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Estimated Risk</td>
                        <td>
                            @php
                                $rLvlA = $dataA['riskScore'] >= 70 ? 'High' : ($dataA['riskScore'] >= 40 ? 'Medium' : 'Low');
                                $rClassA = 'risk-' . strtolower($rLvlA);
                            @endphp
                            <span class="risk-badge {{ $rClassA }}">{{ $dataA['riskScore'] }} - {{ $rLvlA }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- VS Divider --}}
    <div class="vs-divider">
        VS
    </div>

    {{-- Country B --}}
    <div>
        <div class="country-card">
            <div class="flag-huge">{{ $flagB }}</div>
            <h3 class="fw-bold text-dark mb-0">{{ $countryB->country_name }}</h3>
            <span class="text-muted small">Ibukota: {{ $countryB->capital ?: '-' }} · Region: {{ $countryB->region }}</span>

            <table class="table comp-table table-borderless text-start">
                <tbody>
                    <tr>
                        <td width="150" class="text-muted fw-bold">GDP (PDB)</td>
                        <td class="fw-bold">
                            @if($dataB['economy'] && $dataB['economy']->gdp)
                                ${{ number_format($dataB['economy']->gdp / 1e9, 2) }} Miliar USD
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Inflation Rate</td>
                        <td class="fw-bold">
                            {{ ($dataB['economy'] && $dataB['economy']->inflation) ? number_format($dataB['economy']->inflation, 2) . '%' : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Population</td>
                        <td class="fw-bold">
                            {{ ($dataB['economy'] && $dataB['economy']->population) ? number_format($dataB['economy']->population / 1e6, 2) . ' Juta' : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Currency Exchange</td>
                        <td class="fw-bold">
                            {{ $dataB['exchangeRate'] ? number_format($dataB['exchangeRate'], 2) . ' ' . $countryB->currency_code . '/USD' : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Weather Temp</td>
                        <td class="fw-bold">
                            @if($dataB['weather'])
                                @php $wB = WeatherController::weatherCodeInfo($dataB['weather']['weather_code']); @endphp
                                <i class="bi {{ $wB['icon'] }}" style="color: {{ $wB['color'] }};"></i>
                                {{ number_format($dataB['weather']['temperature_2m'], 1) }}°C ({{ $wB['label'] }})
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">News Sentiment</td>
                        <td class="fw-bold">
                            <span class="text-success">P: {{ $dataB['sentimentStats']['Positive'] }}%</span> · 
                            <span class="text-danger">N: {{ $dataB['sentimentStats']['Negative'] }}%</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Estimated Risk</td>
                        <td>
                            @php
                                $rLvlB = $dataB['riskScore'] >= 70 ? 'High' : ($dataB['riskScore'] >= 40 ? 'Medium' : 'Low');
                                $rClassB = 'risk-' . strtolower($rLvlB);
                            @endphp
                            <span class="risk-badge {{ $rClassB }}">{{ $dataB['riskScore'] }} - {{ $rLvlB }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
