@extends('layouts.app')

@section('title', 'Risk Detail — ' . ($shipment->shipment_code ?? ''))

@push('styles')
<style>
    .risk-detail-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px; padding: 20px 24px; margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.25);
    }
    .risk-detail-header h4 { color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0; display: flex; align-items: center; gap: 10px; }
    .risk-detail-header .icon-wrap { width: 36px; height: 36px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .risk-detail-header .subtitle { color: rgba(255,255,255,0.6); font-size: 0.75rem; margin-top: 2px; }
    .btn-header-action {
        background: rgba(255,255,255,0.15); color: #fff; border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 10px; padding: 7px 14px; font-size: 0.8rem; font-weight: 600;
        text-decoration: none; display: flex; align-items: center; gap: 5px; transition: all .2s;
    }
    .btn-header-action:hover { background: rgba(255,255,255,0.25); color: #fff; }

    /* Score gauge card */
    .gauge-card {
        background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 14px rgba(0,0,0,0.05); padding: 24px; text-align: center; margin-bottom: 20px;
    }
    .gauge-circle {
        width: 140px; height: 140px; border-radius: 50%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        border: 8px solid;
    }
    .gauge-score { font-size: 2.4rem; font-weight: 900; line-height: 1; }
    .gauge-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-top: 4px; }

    /* Factor breakdown */
    .factor-card {
        background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 14px rgba(0,0,0,0.05); padding: 20px; margin-bottom: 20px;
    }
    .factor-title {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
        color: #EC4899; margin-bottom: 18px; padding-bottom: 8px; border-bottom: 1.5px solid #fce7f3;
        display: flex; align-items: center; gap: 6px;
    }
    .factor-row { margin-bottom: 16px; }
    .factor-row-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 0.8rem; }
    .factor-label { font-weight: 600; color: #374151; display: flex; align-items: center; gap: 6px; }
    .factor-weight { font-size: 0.68rem; color: #9ca3af; }
    .factor-val { font-weight: 800; font-size: 0.9rem; }
    .factor-bar { height: 10px; background: #f1f5f9; border-radius: 5px; overflow: hidden; }
    .factor-bar-fill { height: 100%; border-radius: 5px; transition: width .6s ease; }

    /* Shipment info */
    .info-card { background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6; box-shadow: 0 2px 14px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 20px; }
    .info-card-header { background: linear-gradient(90deg, #fdf2f8, #fce7f3); padding: 14px 18px; border-bottom: 1.5px solid #fce7f3; }
    .info-card-header h6 { margin: 0; font-weight: 700; font-size: 0.85rem; color: #374151; display: flex; align-items: center; gap: 6px; }
    .info-row { display: flex; padding: 12px 18px; border-bottom: 1px solid #f9f0f6; font-size: 0.82rem; }
    .info-row:last-child { border-bottom: none; }
    .info-row-label { font-weight: 600; color: #9ca3af; width: 140px; flex-shrink: 0; font-size: 0.75rem; text-transform: uppercase; letter-spacing: .03em; }
    .info-row-val { color: #1f2937; font-weight: 500; }

    /* Recalculate button */
    .btn-recalc {
        background: linear-gradient(135deg, #EC4899, #DB2777); color: #fff; border: none;
        border-radius: 10px; padding: 10px 20px; font-size: 0.85rem; font-weight: 700;
        display: flex; align-items: center; gap: 6px; text-decoration: none; width: 100%; justify-content: center;
        transition: all .25s;
    }
    .btn-recalc:hover { background: linear-gradient(135deg, #DB2777, #9D174D); color: #fff; box-shadow: 0 4px 14px rgba(236,72,153,.35); }

    /* Updated at badge */
    .updated-badge { font-size: 0.68rem; color: #9ca3af; text-align: center; margin-top: 10px; }
</style>
@endpush

@section('content')

@php
    $rs = $shipment->riskScore;
    $riskLevel = $shipment->risk_level ?? 'Low';
    $score = $rs ? $rs->total_score : 0;

    $gaugeColor  = '#10b981';
    $gaugeBg     = '#d1fae5';
    if ($riskLevel === 'High')   { $gaugeColor = '#ef4444'; $gaugeBg = '#fee2e2'; }
    elseif ($riskLevel === 'Medium') { $gaugeColor = '#f59e0b'; $gaugeBg = '#fef3c7'; }

    $factors = [
        ['label' => 'Cuaca & Keadaan Alam', 'weight' => '30%', 'score' => $rs?->weather_score ?? 0, 'color' => '#3b82f6', 'icon' => 'bi-cloud-rain'],
        ['label' => 'Kurs & Volatilitas Valuta', 'weight' => '25%', 'score' => $rs?->currency_score ?? 0, 'color' => '#8b5cf6', 'icon' => 'bi-currency-exchange'],
        ['label' => 'Kepadatan Pelabuhan', 'weight' => '20%', 'score' => $rs?->port_score ?? 0, 'color' => '#f59e0b', 'icon' => 'bi-anchor'],
        ['label' => 'Geopolitik & Keamanan', 'weight' => '25%', 'score' => $rs?->geopolitical_score ?? 0, 'color' => '#ef4444', 'icon' => 'bi-shield-exclamation'],
    ];
@endphp

{{-- Header --}}
<div class="risk-detail-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-shield-fill-exclamation"></i></span>
            <span>
                Risk Detail: {{ $shipment->shipment_code }}
                <div class="subtitle">Analisis skor risiko terperinci per faktor pembobotan</div>
            </span>
        </h4>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('risk.recalculate', $shipment) }}" class="btn-header-action">
            <i class="bi bi-arrow-repeat"></i> Hitung Ulang
        </a>
        <a href="{{ route('risk.index') }}" class="btn-header-action">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Left: Gauge + Recalculate --}}
    <div class="col-lg-4">
        {{-- Score Gauge --}}
        <div class="gauge-card">
            <div class="gauge-circle" style="border-color:{{ $gaugeColor }};background:{{ $gaugeBg }};">
                <div class="gauge-score" style="color:{{ $gaugeColor }};">{{ $score }}</div>
                <div class="gauge-label" style="color:{{ $gaugeColor }};">/100</div>
            </div>
            <div style="font-size:1.1rem;font-weight:800;color:{{ $gaugeColor }};margin-bottom:4px;">
                {{ $riskLevel }} Risk
            </div>
            <div style="font-size:0.75rem;color:#9ca3af;margin-bottom:16px;">
                Skor Risiko Total Tertimbang
            </div>

            {{-- Mini breakdown bars --}}
            <div style="text-align:left;">
                @foreach($factors as $f)
                <div style="margin-bottom:8px;">
                    <div style="display:flex;justify-content:space-between;font-size:0.7rem;margin-bottom:3px;">
                        <span style="color:#374151;font-weight:600;"><i class="bi {{ $f['icon'] }}" style="color:{{ $f['color'] }};"></i> {{ $f['label'] }}</span>
                        <span style="font-weight:700;color:{{ $f['color'] }};">{{ $f['score'] }}</span>
                    </div>
                    <div style="height:5px;background:#f1f5f9;border-radius:3px;">
                        <div style="height:100%;width:{{ $f['score'] }}%;background:{{ $f['color'] }};border-radius:3px;"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('risk.recalculate', $shipment) }}" class="btn-recalc mt-3">
                <i class="bi bi-arrow-repeat"></i> Hitung Ulang Sekarang
            </a>
            @if($rs)
            <div class="updated-badge">
                Terakhir diperbarui: {{ $rs->updated_at->diffForHumans() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Right: Factor breakdown + Shipment info --}}
    <div class="col-lg-8">
        {{-- Factor Breakdown Detail --}}
        <div class="factor-card">
            <div class="factor-title">
                <i class="bi bi-sliders"></i> Breakdown Faktor Risiko
            </div>
            @foreach($factors as $f)
            <div class="factor-row">
                <div class="factor-row-header">
                    <span class="factor-label">
                        <i class="bi {{ $f['icon'] }}" style="color:{{ $f['color'] }};font-size:1rem;"></i>
                        {{ $f['label'] }}
                        <span class="factor-weight">(Bobot: {{ $f['weight'] }})</span>
                    </span>
                    <span class="factor-val" style="color:{{ $f['color'] }};">{{ $f['score'] }}<span style="font-size:0.65rem;color:#9ca3af;">/100</span></span>
                </div>
                <div class="factor-bar">
                    <div class="factor-bar-fill" style="width:{{ $f['score'] }}%;background:{{ $f['color'] }};"></div>
                </div>
            </div>
            @endforeach

            {{-- Total calculation display --}}
            <div style="margin-top:16px;padding:14px;background:linear-gradient(135deg,#fdf2f8,#fce7f3);border-radius:12px;border:1px solid #fbcfe8;">
                <div style="font-size:0.72rem;color:#9ca3af;font-weight:600;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em;">Formula Kalkulasi</div>
                <div style="font-size:0.8rem;color:#374151;font-family:monospace;line-height:1.8;">
                    @if($rs)
                    ({{ $rs->weather_score }} × 30%) + ({{ $rs->currency_score }} × 25%) + ({{ $rs->port_score }} × 20%) + ({{ $rs->geopolitical_score }} × 25%)
                    <br>= <strong style="color:#DB2777;font-size:1rem;">{{ $rs->total_score }}</strong>
                    @else
                    <span class="text-muted">Belum ada data. Klik "Hitung Ulang" untuk kalkulasi.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Shipment Info --}}
        <div class="info-card">
            <div class="info-card-header">
                <h6><i class="bi bi-truck" style="color:#EC4899;"></i> Informasi Shipment</h6>
            </div>
            <div class="info-row">
                <div class="info-row-label">Kode</div>
                <div class="info-row-val"><strong>{{ $shipment->shipment_code }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-row-label">Supplier</div>
                <div class="info-row-val">{{ $shipment->supplier->company_name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-row-label">Asal</div>
                <div class="info-row-val">
                    {{ $shipment->originCountry->country_name ?? '-' }}
                    @if($shipment->originPort)
                        <span style="color:#9ca3af;font-size:0.75rem;"> — {{ $shipment->originPort->port_name }}</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-label">Tujuan</div>
                <div class="info-row-val">
                    {{ $shipment->destinationCountry->country_name ?? '-' }}
                    @if($shipment->destinationPort)
                        <span style="color:#9ca3af;font-size:0.75rem;"> — {{ $shipment->destinationPort->port_name }}</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-label">Kapal</div>
                <div class="info-row-val">{{ $shipment->vessel_name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-row-label">Berangkat</div>
                <div class="info-row-val">{{ $shipment->departure_date ? \Carbon\Carbon::parse($shipment->departure_date)->format('d M Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-row-label">ETA</div>
                <div class="info-row-val">{{ $shipment->estimated_arrival ? \Carbon\Carbon::parse($shipment->estimated_arrival)->format('d M Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-row-label">Status</div>
                <div class="info-row-val">
                    @php
                        $sc = ['Pending'=>'#fef3c7|#92400e','On Shipping'=>'#dbeafe|#1e40af','Arrived'=>'#d1fae5|#065f46','Delivered'=>'#d1fae5|#065f46','Delayed'=>'#fee2e2|#7f1d1d'][$shipment->status] ?? '#f3f4f6|#374151';
                        [$bg, $fc] = explode('|', $sc);
                    @endphp
                    <span style="background:{{ $bg }};color:{{ $fc }};font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:10px;">{{ $shipment->status }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-label">Koordinat</div>
                <div class="info-row-val" style="font-family:monospace;font-size:0.78rem;">
                    {{ $shipment->latitude }}, {{ $shipment->longitude }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
