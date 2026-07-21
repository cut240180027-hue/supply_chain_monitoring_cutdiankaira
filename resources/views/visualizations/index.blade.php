@extends('layouts.app')

@section('title', 'Data Visualizations — SCM')

@push('styles')
<style>
    .vis-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        color: #fff;
    }
    .vis-header h4 { margin: 0; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .vis-header .subtitle { color: rgba(255,255,255,0.6); font-size: 0.75rem; }

    .selector-panel {
        background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6;
        padding: 16px 20px; margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .chart-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 14px rgba(0,0,0,0.03);
        padding: 20px;
        margin-bottom: 24px;
        height: 100%;
    }
    .chart-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #475569;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>
@endpush

@section('content')

@php
    use App\Http\Controllers\WeatherController;
@endphp

<div class="vis-header">
    <h4>
        <i class="bi bi-bar-chart-line text-warning"></i>
        <span>
            Data Visualization Dashboard
            <div class="subtitle">Analisis tren tren makroekonomi, fluktuasi kurs mata uang, dan tren risiko rantai pasok global</div>
        </span>
    </h4>
</div>

{{-- Selector --}}
<div class="selector-panel">
    <form action="{{ route('visualizations.index') }}" method="GET" class="row align-items-center">
        <div class="col-md-8">
            <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Pilih Negara Analisis</label>
            <select name="country" onchange="this.form.submit();" class="form-select" style="border-radius:8px;font-size:0.85rem;font-weight:600;">
                @foreach($countries as $c)
                    <option value="{{ $c->country_code }}" {{ $selectedCountry && $selectedCountry->country_code === $c->country_code ? 'selected' : '' }}>
                        {{ WeatherController::getFlagEmoji($c->country_code) }} {{ $c->country_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 text-end text-muted small pt-3">
            Analisis Komparasi Tren Historis & Real-time
        </div>
    </form>
</div>

<div class="row g-4">
    {{-- Chart 1: GDP Trend --}}
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-bank text-primary"></i> Tren Produk Domestik Bruto (GDP USD)
            </div>
            <div style="height: 250px;">
                <canvas id="gdpChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart 2: Inflation Trend --}}
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-graph-up-arrow text-danger"></i> Tren Tingkat Inflasi (%)
            </div>
            <div style="height: 250px;">
                <canvas id="inflationChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart 3: Currency Trend --}}
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-currency-exchange text-warning"></i> Fluktuasi Kurs ({{ $currencyCode }}/USD - Past 7 Days)
            </div>
            <div style="height: 250px;">
                <canvas id="currencyChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart 4: Risk Score Trend --}}
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-shield-exclamation text-success"></i> Tren Tingkat Risiko Rantai Pasok (Past 7 Days)
            </div>
            <div style="height: 250px;">
                <canvas id="riskChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. GDP Chart
    const gdpCtx = document.getElementById('gdpChart').getContext('2d');
    new Chart(gdpCtx, {
        type: 'line',
        data: {
            labels: @json($gdpData['labels']),
            datasets: [{
                label: 'GDP Nominal ($)',
                data: @json($gdpData['values']),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // 2. Inflation Chart
    const inflationCtx = document.getElementById('inflationChart').getContext('2d');
    new Chart(inflationCtx, {
        type: 'bar',
        data: {
            labels: @json($inflationData['labels']),
            datasets: [{
                label: 'Tingkat Inflasi (%)',
                data: @json($inflationData['values']),
                backgroundColor: 'rgba(239, 68, 68, 0.85)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // 3. Currency Chart
    const currencyCtx = document.getElementById('currencyChart').getContext('2d');
    new Chart(currencyCtx, {
        type: 'line',
        data: {
            labels: @json($currencyData['labels']),
            datasets: [{
                label: 'Kurs per USD',
                data: @json($currencyData['values']),
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // 4. Risk Chart
    const riskCtx = document.getElementById('riskChart').getContext('2d');
    new Chart(riskCtx, {
        type: 'line',
        data: {
            labels: @json($riskData['labels']),
            datasets: [{
                label: 'Skor Risiko Rata-rata',
                data: @json($riskData['values']),
                borderColor: '#ec4899',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { min: 0, max: 100 }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
