@extends('layouts.app')

@push('styles')
<style>
    /* ===== ECONOMY MONITOR STYLES ===== */
    .economy-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .economy-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .economy-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        color: #f59e0b;
    }
    .economy-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Filter Card */
    .filter-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    /* Grid layout */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .metric-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .metric-card::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    }
    .card-gdp::before { background: #4f46e5; }
    .card-inflation::before { background: #ef4444; }
    .card-population::before { background: #10b981; }
    .card-balance::before { background: #f59e0b; }

    .metric-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #9ca3af;
        letter-spacing: .05em;
        margin-bottom: 6px;
    }
    .metric-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .metric-desc {
        font-size: 0.75rem;
        color: #6b7280;
    }
    .metric-icon-bg {
        position: absolute; right: 16px; bottom: 12px;
        font-size: 2.2rem;
        color: #f3f4f6;
        pointer-events: none;
        line-height: 1;
    }

    /* Comparison card for imports/exports */
    .trade-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    }
    .trade-bar-wrap {
        display: flex;
        height: 12px;
        border-radius: 6px;
        overflow: hidden;
        margin: 16px 0;
        background: #f3f4f6;
    }
    .trade-bar-export { background: #3b82f6; }
    .trade-bar-import { background: #ec4899; }

    .trade-legend {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
    }
    .legend-item { display: flex; align-items: center; gap: 6px; font-weight: 600; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; }

    /* Custom alert */
    .alert-custom-err {
        background: #fff1f2;
        border-left: 4px solid #ef4444;
        border-radius: 10px;
        color: #7f1d1d;
        font-size: 0.82rem;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex; align-items: center; gap: 8px;
    }
</style>
@endpush

@section('title', 'Economic Indicators — SCM')

@section('content')

@php
    use App\Http\Controllers\WeatherController; // Helper bendera negara
    
    // Helper format mata uang
    function formatCurrency($num) {
        if ($num === null) return 'N/A';
        if ($num >= 1000000000000) {
            return '$' . number_format($num / 1000000000000, 2) . ' T';
        }
        if ($num >= 1000000000) {
            return '$' . number_format($num / 1000000000, 2) . ' B';
        }
        if ($num >= 1000000) {
            return '$' . number_format($num / 1000000, 2) . ' M';
        }
        return '$' . number_format($num, 2);
    }

    // Helper format populasi
    function formatPop($num) {
        if ($num === null) return 'N/A';
        if ($num >= 1000000000) {
            return number_format($num / 1000000000, 2) . ' Miliar';
        }
        if ($num >= 1000000) {
            return number_format($num / 1000000, 2) . ' Juta';
        }
        return number_format($num);
    }

    $gdp = $indicator->gdp ?? null;
    $inflation = $indicator->inflation ?? null;
    $population = $indicator->population ?? null;
    $export = $indicator->export_value ?? null;
    $import = $indicator->import_value ?? null;

    $balance = ($export !== null && $import !== null) ? ($export - $import) : null;
    
    $flag = $selectedCountry ? WeatherController::getFlagEmoji($selectedCountry->country_code) : '';
@endphp

{{-- Header --}}
<div class="economy-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-bar-chart-line"></i></span>
            <span>
                Economic Indicators
                <div class="subtitle">Indikator makroekonomi terintegrasi API World Bank</div>
            </span>
        </h4>
    </div>
</div>

{{-- Alert Error --}}
@if($error)
    <div class="alert-custom-err">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}
    </div>
@endif

{{-- Filter Card --}}
<div class="filter-card">
    <form action="{{ route('economy.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Negara</label>
            <select name="country" class="form-select" style="font-size:0.85rem;border-radius:8px;">
                @foreach($countries as $c)
                    <option value="{{ $c->country_code }}" {{ $selectedCountry && $selectedCountry->country_code === $c->country_code ? 'selected' : '' }}>
                        {{ WeatherController::getFlagEmoji($c->country_code) }} {{ $c->country_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Tahun</label>
            <select name="year" class="form-select" style="font-size:0.85rem;border-radius:8px;">
                @for($y = date('Y') - 1; $y >= 2015; $y--)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2 align-self-end">
            <button type="submit" class="btn btn-primary w-100 fw-bold" style="font-size:0.82rem;border-radius:8px;height:38px;">
                <i class="bi bi-search"></i> Tampilkan
            </button>
            <button type="submit" name="refresh" value="1" class="btn btn-outline-success fw-bold" style="font-size:0.82rem;border-radius:8px;height:38px;min-width:110px;" title="Ambil ulang data terbaru dari API World Bank">
                <i class="bi bi-arrow-clockwise"></i> Sync API
            </button>
        </div>
    </form>
</div>

@if($indicator)
    {{-- Grid Metrics --}}
    <div class="metrics-grid">
        {{-- GDP --}}
        <div class="metric-card card-gdp">
            <div>
                <div class="metric-label">Gross Domestic Product (GDP)</div>
                <div class="metric-value">{{ formatCurrency($gdp) }}</div>
            </div>
            <div class="metric-desc">PDB nominal tahun {{ $selectedYear }}.</div>
            <div class="metric-icon-bg">🏦</div>
        </div>

        {{-- Inflation --}}
        <div class="metric-card card-inflation">
            <div>
                <div class="metric-label">Inflasi (Tahunan)</div>
                <div class="metric-value" style="color: {{ $inflation > 5 ? '#ef4444' : '#1f2937' }};">
                    {{ $inflation !== null ? number_format($inflation, 2) . '%' : 'N/A' }}
                </div>
            </div>
            <div class="metric-desc">Indeks Harga Konsumen (IHK).</div>
            <div class="metric-icon-bg">📈</div>
        </div>

        {{-- Population --}}
        <div class="metric-card card-population">
            <div>
                <div class="metric-label">Jumlah Populasi</div>
                <div class="metric-value">{{ formatPop($population) }}</div>
            </div>
            <div class="metric-desc">Total populasi penduduk negara.</div>
            <div class="metric-icon-bg">👥</div>
        </div>

        {{-- Trade Balance --}}
        <div class="metric-card card-balance">
            <div>
                <div class="metric-label">Neraca Perdagangan</div>
                @if($balance !== null)
                    <div class="metric-value" style="color: {{ $balance >= 0 ? '#10b981' : '#ef4444' }};">
                        {{ formatCurrency($balance) }}
                    </div>
                @else
                    <div class="metric-value">N/A</div>
                @endif
            </div>
            <div class="metric-desc">
                @if($balance !== null)
                    {{ $balance >= 0 ? 'Surplus Perdagangan (Ekspor > Impor)' : 'Defisit Perdagangan (Impor > Ekspor)' }}
                @else
                    Selisih nilai ekspor dan impor.
                @endif
            </div>
            <div class="metric-icon-bg">⚖️</div>
        </div>
    </div>

    {{-- Row Chart & Trade Summary --}}
    <div class="row g-3">
        {{-- Trade Analysis --}}
        <div class="col-lg-6">
            <div class="trade-card h-100">
                <p class="section-title fw-bold" style="font-size:0.75rem;"><i class="bi bi-arrow-left-right me-1"></i> Analisis Perdagangan Internasional (Ekspor vs Impor)</p>
                
                <div style="font-size: 0.85rem;color:#4b5563;" class="mb-3">
                    Nilai transaksi perdagangan ekspor dan impor barang & jasa untuk negara <strong>{{ $selectedCountry->country_name }} {{ $flag }}</strong> pada tahun <strong>{{ $selectedYear }}</strong>.
                </div>

                @if($export !== null || $import !== null)
                    @php
                        $totalTrade = ($export ?? 0) + ($import ?? 0);
                        $exportPct = $totalTrade > 0 ? (($export ?? 0) / $totalTrade) * 100 : 0;
                        $importPct = $totalTrade > 0 ? (($import ?? 0) / $totalTrade) * 100 : 0;
                    @endphp
                    
                    <div class="trade-bar-wrap">
                        <div class="trade-bar-export" style="width: {{ $exportPct }}%;"></div>
                        <div class="trade-bar-import" style="width: {{ $importPct }}%;"></div>
                    </div>

                    <div class="trade-legend">
                        <div class="legend-item" style="color: #3b82f6;">
                            <div class="legend-dot" style="background:#3b82f6;"></div>
                            Ekspor: {{ number_format($exportPct, 1) }}% ({{ formatCurrency($export) }})
                        </div>
                        <div class="legend-item" style="color: #ec4899;">
                            <div class="legend-dot" style="background:#ec4899;"></div>
                            Impor: {{ number_format($importPct, 1) }}% ({{ formatCurrency($import) }})
                        </div>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-info-circle fs-3 mb-2 display-block"></i>
                        <p class="mb-0">Data ekspor/impor tidak tersedia dari API untuk tahun ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- World Bank Data Status --}}
        <div class="col-lg-6">
            <div class="trade-card h-100">
                <p class="section-title fw-bold" style="font-size:0.75rem;"><i class="bi bi-database-check me-1"></i> Detail Informasi Database</p>
                <table class="table table-borderless table-sm mb-0" style="font-size:0.8rem;">
                    <tbody>
                        <tr>
                            <td class="text-muted" width="160">Negara Terpilih:</td>
                            <td class="fw-bold">{{ $selectedCountry->country_name }} ({{ $selectedCountry->country_code }})</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ibukota:</td>
                            <td>{{ $selectedCountry->capital ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tahun Indikator:</td>
                            <td>{{ $selectedYear }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Sumber Asal:</td>
                            <td>World Bank Databank (API v2)</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Cache Lokal:</td>
                            <td><span class="badge bg-success">Tersimpan di DB</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terakhir Diupdate:</td>
                            <td>{{ $indicator->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="card p-5 text-center text-secondary border-0 shadow-sm" style="border-radius:16px;">
        <i class="bi bi-bar-chart fs-1 mb-2 text-muted"></i>
        <p class="mb-0 fw-bold">Belum ada data indikator ekonomi untuk {{ $selectedCountry->country_name }} pada tahun {{ $selectedYear }}.</p>
        <p class="small text-muted">Klik tombol <strong>Sync API</strong> di atas untuk mengambil data dari World Bank secara online.</p>
    </div>
@endif

@endsection
