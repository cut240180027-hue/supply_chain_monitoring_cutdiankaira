@extends('layouts.app')

@section('title', 'Risk Score Monitor — SCM')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js">
<style>
    /* ===== RISK PAGE STYLES ===== */
    .risk-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.25);
    }
    .risk-header h4 {
        color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .risk-header .icon-wrap {
        width: 36px; height: 36px; background: rgba(255,255,255,0.15);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
    }
    .risk-header .subtitle { color: rgba(255,255,255,0.6); font-size: 0.75rem; margin-top: 2px; }

    .btn-recalc-all {
        background: rgba(255,255,255,0.15); color: #fff;
        border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 10px; padding: 8px 16px; font-size: 0.8rem; font-weight: 700;
        text-decoration: none; display: flex; align-items: center; gap: 6px;
        transition: all .2s;
    }
    .btn-recalc-all:hover { background: rgba(255,255,255,0.25); color: #fff; }

    /* Stats cards */
    .risk-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .risk-stat-card {
        background: #fff; border-radius: 14px; padding: 18px 20px;
        border: 1.5px solid #f3f4f6; box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        display: flex; align-items: center; gap: 14px; transition: transform .2s;
    }
    .risk-stat-card:hover { transform: translateY(-2px); }
    .risk-stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }
    .risk-stat-card h3 { font-size: 1.6rem; font-weight: 800; color: #1f2937; margin: 0; }
    .risk-stat-card span { font-size: 0.72rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; }

    /* Chart card */
    .chart-section { background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6; box-shadow: 0 2px 10px rgba(0,0,0,0.03); padding: 18px; margin-bottom: 24px; }
    .chart-section-title { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #EC4899; margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }

    /* Table */
    .risk-table-card { background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow: hidden; }
    .table-risk { margin: 0; font-size: 0.82rem; }
    .table-risk thead th {
        background: linear-gradient(90deg, #EC4899, #DB2777);
        color: #fff; font-weight: 700; font-size: 0.7rem;
        text-transform: uppercase; letter-spacing: .05em;
        border: none; padding: 12px 14px; white-space: nowrap;
    }
    .table-risk tbody tr { transition: background .15s; border-bottom: 1px solid #f3f4f6; }
    .table-risk tbody tr:hover { background: #fff0f6; }
    .table-risk tbody td { padding: 12px 14px; vertical-align: middle; }
    .table-risk tbody tr:last-child { border-bottom: none; }

    /* Score bar */
    .score-bar-wrap { display: flex; align-items: center; gap: 8px; }
    .score-bar { flex: 1; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
    .score-bar-fill { height: 100%; border-radius: 4px; transition: width .5s ease; }
    .score-num { font-weight: 800; font-size: 0.85rem; min-width: 28px; text-align: right; }

    /* Risk badge */
    .risk-badge-high   { background: #fee2e2; color: #7f1d1d; font-weight: 700; font-size: 0.68rem; padding: 4px 10px; border-radius: 20px; }
    .risk-badge-medium { background: #fef3c7; color: #92400e; font-weight: 700; font-size: 0.68rem; padding: 4px 10px; border-radius: 20px; }
    .risk-badge-low    { background: #d1fae5; color: #065f46; font-weight: 700; font-size: 0.68rem; padding: 4px 10px; border-radius: 20px; }

    /* Factor dots */
    .factor-dots { display: flex; gap: 5px; }
    .factor-dot { width: 10px; height: 10px; border-radius: 50%; cursor: help; }

    /* Action buttons */
    .btn-act { border: none; border-radius: 7px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; transition: all .2s; text-decoration: none; cursor: pointer; }
    .btn-act-detail { background: #fce7f3; color: #DB2777; }
    .btn-act-detail:hover { background: #EC4899; color: #fff; }
    .btn-act-refresh { background: #eff6ff; color: #2563eb; }
    .btn-act-refresh:hover { background: #2563eb; color: #fff; }

    /* Alert */
    .alert-success-custom {
        background: linear-gradient(135deg, #fce7f3, #fbcfe8);
        border: none; border-left: 4px solid #EC4899; border-radius: 10px;
        color: #9D174D; font-size: 0.82rem; font-weight: 500;
        padding: 10px 16px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }

    /* Manual Calculator */
    .calc-card {
        background: #fff; border-radius: 16px; border: 1.5px solid #fce7f3;
        box-shadow: 0 2px 10px rgba(236,72,153,0.08); padding: 20px;
    }
    .calc-result {
        background: linear-gradient(135deg, #fdf2f8, #fce7f3);
        border-radius: 12px; padding: 16px;
        text-align: center; margin-top: 16px;
        border: 1.5px solid #fbcfe8;
        display: none;
    }
    .calc-score { font-size: 2.5rem; font-weight: 800; color: #DB2777; line-height: 1; }
    .calc-level { font-size: 0.8rem; font-weight: 700; margin-top: 4px; }

    /* Table footer */
    .table-footer { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #fdf2f8; border-top: 1px solid #fce7f3; font-size: 0.78rem; color: #9ca3af; }
    .pagination .page-link { border-radius: 8px !important; border: 1.5px solid #e5e7eb; font-size: 0.78rem; color: #374151; padding: 4px 10px; margin: 0 2px; }
    .pagination .page-link:hover { background: #EC4899; color: #fff; border-color: #EC4899; }
    .pagination .page-item.active .page-link { background: #EC4899; border-color: #EC4899; }

    @media (max-width: 768px) {
        .risk-stats { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="risk-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-shield-check"></i></span>
            <span>
                Risk Score Monitor
                <div class="subtitle">Kalkulasi otomatis: Cuaca 30% + Kurs 25% + Pelabuhan 20% + Geopolitik 25%</div>
            </span>
        </h4>
    </div>
    <a href="{{ route('risk.recalculate-all') }}" class="btn-recalc-all">
        <i class="bi bi-arrow-repeat"></i> Hitung Ulang Semua
    </a>
</div>

@if(session('success'))
    <div class="alert-success-custom">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
<div class="risk-stats">
    <div class="risk-stat-card">
        <div class="risk-stat-icon" style="background:#fee2e2;color:#ef4444;">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <h3>{{ $stats['high'] }}</h3>
            <span>Risiko Tinggi</span>
        </div>
    </div>
    <div class="risk-stat-card">
        <div class="risk-stat-icon" style="background:#fef3c7;color:#d97706;">
            <i class="bi bi-exclamation-circle-fill"></i>
        </div>
        <div>
            <h3>{{ $stats['medium'] }}</h3>
            <span>Risiko Sedang</span>
        </div>
    </div>
    <div class="risk-stat-card">
        <div class="risk-stat-icon" style="background:#d1fae5;color:#10b981;">
            <i class="bi bi-shield-check-fill"></i>
        </div>
        <div>
            <h3>{{ $stats['low'] }}</h3>
            <span>Risiko Rendah</span>
        </div>
    </div>
    <div class="risk-stat-card">
        <div class="risk-stat-icon" style="background:#fce7f3;color:#EC4899;">
            <i class="bi bi-bar-chart-fill"></i>
        </div>
        <div>
            <h3>{{ $stats['avg'] }}</h3>
            <span>Rata-rata Skor</span>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Main Risk Table --}}
    <div class="col-lg-8">
        <div class="risk-table-card">
            <div style="padding:14px 16px;border-bottom:1.5px solid #fce7f3;display:flex;align-items:center;justify-content:space-between;">
                <h6 style="margin:0;font-weight:700;font-size:0.85rem;color:#374151;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-table" style="color:#EC4899;"></i> Skor Risiko Per Shipment
                </h6>
                <span style="font-size:0.72rem;color:#9ca3af;">{{ $riskScores->total() }} record</span>
            </div>
            <div class="table-responsive">
                <table class="table table-risk">
                    <thead>
                        <tr>
                            <th>Shipment</th>
                            <th>Rute</th>
                            <th>Breakdown (C|K|P|G)</th>
                            <th>Skor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riskScores as $rs)
                            @php
                                $s = $rs->shipment;
                                $barColor = $rs->risk_level === 'High' ? '#ef4444' : ($rs->risk_level === 'Medium' ? '#f59e0b' : '#10b981');
                                $badgeClass = 'risk-badge-' . strtolower($rs->risk_level);
                                $dotW = $rs->weather_score >= 70 ? '#ef4444' : ($rs->weather_score >= 40 ? '#f59e0b' : '#10b981');
                                $dotK = $rs->currency_score >= 70 ? '#ef4444' : ($rs->currency_score >= 40 ? '#f59e0b' : '#10b981');
                                $dotP = $rs->port_score >= 70 ? '#ef4444' : ($rs->port_score >= 40 ? '#f59e0b' : '#10b981');
                                $dotG = $rs->geopolitical_score >= 70 ? '#ef4444' : ($rs->geopolitical_score >= 40 ? '#f59e0b' : '#10b981');
                            @endphp
                            <tr>
                                <td>
                                    <strong style="color:#1e293b;font-size:0.82rem;">{{ $s->shipment_code ?? '-' }}</strong>
                                    <br><span style="font-size:0.68rem;color:#9ca3af;">{{ $s->status ?? '' }}</span>
                                </td>
                                <td style="font-size:0.75rem;color:#64748b;">
                                    {{ $s->originPort->port_name ?? ($s->originCountry->country_name ?? '-') }}
                                    <i class="bi bi-arrow-right text-muted mx-1" style="font-size:0.6rem;"></i>
                                    {{ $s->destinationPort->port_name ?? ($s->destinationCountry->country_name ?? '-') }}
                                </td>
                                <td>
                                    <div class="factor-dots">
                                        <div class="factor-dot" style="background:{{ $dotW }};" title="Cuaca: {{ $rs->weather_score }}/100 (30%)"></div>
                                        <div class="factor-dot" style="background:{{ $dotK }};" title="Kurs: {{ $rs->currency_score }}/100 (25%)"></div>
                                        <div class="factor-dot" style="background:{{ $dotP }};" title="Pelabuhan: {{ $rs->port_score }}/100 (20%)"></div>
                                        <div class="factor-dot" style="background:{{ $dotG }};" title="Geopolitik: {{ $rs->geopolitical_score }}/100 (25%)"></div>
                                    </div>
                                    <div style="font-size:0.65rem;color:#9ca3af;margin-top:3px;">
                                        {{ $rs->weather_score }} | {{ $rs->currency_score }} | {{ $rs->port_score }} | {{ $rs->geopolitical_score }}
                                    </div>
                                </td>
                                <td>
                                    <div class="score-bar-wrap">
                                        <div class="score-bar">
                                            <div class="score-bar-fill" style="width:{{ $rs->total_score }}%;background:{{ $barColor }};"></div>
                                        </div>
                                        <span class="score-num" style="color:{{ $barColor }};">{{ $rs->total_score }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="{{ $badgeClass }}">{{ $rs->risk_level }}</span>
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px;">
                                        @if($s)
                                        <a href="{{ route('risk.show', $s) }}" class="btn-act btn-act-detail" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('risk.recalculate', $s) }}" class="btn-act btn-act-refresh" title="Hitung Ulang">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color:#9ca3af;">
                                    <i class="bi bi-shield-exclamation" style="font-size:2rem;display:block;margin-bottom:8px;color:#fce7f3;"></i>
                                    Belum ada data risk score.<br>
                                    <a href="{{ route('risk.recalculate-all') }}" class="btn-recalc-all mx-auto mt-2 d-inline-flex" style="font-size:0.78rem;">
                                        <i class="bi bi-arrow-repeat"></i> Hitung Sekarang
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <span>{{ $riskScores->firstItem() ?? 0 }}–{{ $riskScores->lastItem() ?? 0 }} dari {{ $riskScores->total() }}</span>
                <div>{{ $riskScores->links() }}</div>
            </div>
        </div>
    </div>

    {{-- Right: Chart + Manual Calculator --}}
    <div class="col-lg-4">
        {{-- Risk Distribution Chart --}}
        <div class="chart-section mb-3">
            <div class="chart-section-title">
                <i class="bi bi-pie-chart-fill"></i> Distribusi Level Risiko
            </div>
            <canvas id="riskDistChart" style="max-height:180px;"></canvas>
        </div>

        {{-- Weight breakdown card --}}
        <div class="chart-section mb-3" style="padding:16px 18px;">
            <div class="chart-section-title">
                <i class="bi bi-sliders"></i> Bobot Formula Risiko
            </div>
            @php
                $weights = [
                    ['label' => 'Cuaca & Alam', 'weight' => 30, 'color' => '#3b82f6', 'icon' => 'bi-cloud-rain'],
                    ['label' => 'Kurs & Valuta', 'weight' => 25, 'color' => '#8b5cf6', 'icon' => 'bi-currency-exchange'],
                    ['label' => 'Pelabuhan', 'weight' => 20, 'color' => '#f59e0b', 'icon' => 'bi-anchor'],
                    ['label' => 'Geopolitik', 'weight' => 25, 'color' => '#ef4444', 'icon' => 'bi-shield-exclamation'],
                ];
            @endphp
            @foreach($weights as $w)
                <div style="margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.75rem;margin-bottom:4px;">
                        <span style="font-weight:600;color:#374151;"><i class="bi {{ $w['icon'] }}" style="color:{{ $w['color'] }};margin-right:4px;"></i>{{ $w['label'] }}</span>
                        <span style="font-weight:800;color:{{ $w['color'] }};">{{ $w['weight'] }}%</span>
                    </div>
                    <div style="height:6px;background:#f1f5f9;border-radius:4px;">
                        <div style="height:100%;width:{{ $w['weight'] }}%;background:{{ $w['color'] }};border-radius:4px;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Manual Calculator --}}
        <div class="calc-card">
            <div class="chart-section-title">
                <i class="bi bi-calculator"></i> Kalkulator Risiko Manual
            </div>
            <div id="calcForm">
                @foreach([
                    ['id' => 'calcWeather', 'label' => 'Cuaca (0–100)', 'icon' => 'bi-cloud-rain', 'color' => '#3b82f6'],
                    ['id' => 'calcCurrency', 'label' => 'Kurs (0–100)', 'icon' => 'bi-currency-exchange', 'color' => '#8b5cf6'],
                    ['id' => 'calcPort', 'label' => 'Pelabuhan (0–100)', 'icon' => 'bi-anchor', 'color' => '#f59e0b'],
                    ['id' => 'calcGeo', 'label' => 'Geopolitik (0–100)', 'icon' => 'bi-shield-exclamation', 'color' => '#ef4444'],
                ] as $input)
                <div style="margin-bottom:10px;">
                    <label style="font-size:0.75rem;font-weight:600;color:#374151;display:flex;align-items:center;gap:5px;margin-bottom:4px;">
                        <i class="bi {{ $input['icon'] }}" style="color:{{ $input['color'] }};"></i>
                        {{ $input['label'] }}
                    </label>
                    <input type="range" id="{{ $input['id'] }}" min="0" max="100" value="30"
                        style="width:100%;accent-color:{{ $input['color'] }};" oninput="updateCalc()">
                    <div style="display:flex;justify-content:space-between;font-size:0.68rem;color:#9ca3af;">
                        <span>0</span>
                        <span id="{{ $input['id'] }}Val" style="color:{{ $input['color'] }};font-weight:700;">30</span>
                        <span>100</span>
                    </div>
                </div>
                @endforeach

                <div class="calc-result" id="calcResult">
                    <div class="calc-score" id="calcScoreNum">—</div>
                    <div class="calc-level" id="calcScoreLevel"></div>
                    <div style="font-size:0.7rem;color:#9ca3af;margin-top:4px;">Skor Total Tertimbang</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Risk Distribution Donut Chart
const riskCtx = document.getElementById('riskDistChart').getContext('2d');
new Chart(riskCtx, {
    type: 'doughnut',
    data: {
        labels: ['High', 'Medium', 'Low'],
        datasets: [{
            data: [{{ $stats['high'] }}, {{ $stats['medium'] }}, {{ $stats['low'] }}],
            backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 6
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 11, family: 'Poppins' }, padding: 14, usePointStyle: true }
            }
        }
    }
});

// Manual Calculator
function updateCalc() {
    const w = parseInt(document.getElementById('calcWeather').value);
    const k = parseInt(document.getElementById('calcCurrency').value);
    const p = parseInt(document.getElementById('calcPort').value);
    const g = parseInt(document.getElementById('calcGeo').value);

    document.getElementById('calcWeatherVal').textContent = w;
    document.getElementById('calcCurrencyVal').textContent = k;
    document.getElementById('calcPortVal').textContent = p;
    document.getElementById('calcGeoVal').textContent = g;

    const score = Math.round((w * 0.30) + (k * 0.25) + (p * 0.20) + (g * 0.25));
    let level = 'Low', levelColor = '#10b981';
    if (score >= 70) { level = 'High'; levelColor = '#ef4444'; }
    else if (score >= 40) { level = 'Medium'; levelColor = '#f59e0b'; }

    document.getElementById('calcScoreNum').textContent = score;
    document.getElementById('calcScoreNum').style.color = levelColor;
    document.getElementById('calcScoreLevel').textContent = '⚠ Risiko ' + level;
    document.getElementById('calcScoreLevel').style.color = levelColor;
    document.getElementById('calcResult').style.display = 'block';
}
updateCalc();
</script>
@endpush
