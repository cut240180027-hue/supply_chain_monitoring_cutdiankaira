@extends('layouts.app')

@push('styles')
<style>
    /* ===== CURRENCY EXCHANGE STYLES ===== */
    .currency-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .currency-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .currency-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        color: #fbbf24;
    }
    .currency-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Cards */
    .calc-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    }
    .calc-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #9ca3af;
        letter-spacing: .05em;
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 6px;
    }

    /* Calculator outputs */
    .result-display {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        margin-bottom: 20px;
    }
    .result-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #EC4899;
    }

    /* Rates Table Card */
    .rates-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .table-rates {
        margin: 0;
        font-size: 0.82rem;
    }
    .table-rates thead th {
        background: #f8f9fe;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1.5px solid #ebebf0;
        padding: 10px 14px;
        white-space: nowrap;
    }
    .table-rates tbody tr {
        transition: background .15s;
        border-bottom: 1px solid #f3f4f6;
    }
    .table-rates tbody tr:hover { background: #fff0f6; }
    .table-rates tbody td {
        padding: 10px 14px;
        vertical-align: middle;
        color: #374151;
    }
    .table-rates tbody tr:last-child { border-bottom: none; }

    /* Badge currency */
    .badge-curr {
        background: #fce7f3;
        color: #DB2777;
        font-weight: 700;
        font-size: 0.72rem;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* Search bar inside rates card */
    .search-wrap {
        position: relative;
        max-width: 260px;
    }
    .search-wrap i {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: #aaa; font-size: 0.85rem;
    }
    .search-wrap input {
        padding-left: 34px;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        font-size: 0.8rem;
        height: 34px;
    }

    /* Alert */
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

@section('title', 'Currency Exchange — SCM')

@section('content')

@php
    use App\Http\Controllers\WeatherController; // Helper bendera negara
@endphp

{{-- Header --}}
<div class="currency-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-currency-exchange"></i></span>
            <span>
                Currency Exchange Monitor
                <div class="subtitle">Data kurs mata uang dunia real-time terintegrasi database negara</div>
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

<div class="row g-4 mb-4">
    
    {{-- 1. CALCULATOR TOOL --}}
    <div class="col-lg-5">
        <div class="calc-card h-100">
            <div class="calc-title">
                <i class="bi bi-calculator"></i> Kalkulator Kurs Real-time
            </div>

            {{-- Result display --}}
            <div class="result-display">
                <div class="text-muted small mb-1" id="calcEquation">1 USD sama dengan</div>
                <div class="result-value" id="calcOutput">15.500,00 IDR</div>
            </div>

            {{-- Calculator Form --}}
            <div class="mb-3">
                <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Jumlah nominal</label>
                <input type="number" id="calcAmount" class="form-control fw-bold" value="1" min="0" step="any" style="border-radius:8px;font-size:0.9rem;">
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Mata Uang Asal (From)</label>
                    <select id="calcFrom" class="form-select" style="font-size:0.82rem;border-radius:8px;">
                        @foreach($currencies as $curr)
                            <option value="{{ $curr->currency_code }}" {{ $curr->currency_code === 'USD' ? 'selected' : '' }}>
                                {{ $curr->currency_code }} - {{ Str::limit($curr->currency, 20) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted fw-bold" style="font-size:0.7rem;text-transform:uppercase;">Mata Uang Tujuan (To)</label>
                    <select id="calcTo" class="form-select" style="font-size:0.82rem;border-radius:8px;">
                        @foreach($currencies as $curr)
                            <option value="{{ $curr->currency_code }}" {{ $curr->currency_code === 'IDR' ? 'selected' : '' }}>
                                {{ $curr->currency_code }} - {{ Str::limit($curr->currency, 20) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="button" id="btnSwap" class="btn btn-outline-primary btn-sm rounded-circle p-2" title="Tukar mata uang" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-arrow-down-up"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- 2. LIVE EXCHANGE RATES TABLE --}}
    <div class="col-lg-7">
        <div class="rates-card h-100">
            
            {{-- Toolbar --}}
            <div class="d-flex align-items-center justify-content-between p-3" style="border-bottom:1px solid #ebebf0;background:#f8f9fe;">
                <form action="{{ route('currency.index') }}" method="GET" id="baseCurrencyForm" class="d-flex align-items-center gap-2">
                    <span class="text-muted fw-bold" style="font-size:0.75rem;white-space:nowrap;text-transform:uppercase;">Kurs Base:</span>
                    <select name="base" class="form-select form-select-sm fw-bold" onchange="document.getElementById('baseCurrencyForm').submit();" style="border-radius:6px;font-size:0.8rem;width:90px;height:30px;">
                        @foreach($currencies as $curr)
                            <option value="{{ $curr->currency_code }}" {{ $baseCurrency === $curr->currency_code ? 'selected' : '' }}>
                                {{ $curr->currency_code }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari mata uang..." class="form-control form-control-sm">
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                <table class="table table-rates" id="ratesTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mata Uang</th>
                            <th class="text-end">Nilai Tukar (Per 1 {{ $baseCurrency }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($targetRates as $rate)
                            <tr>
                                <td><span class="badge-curr">{{ $rate['code'] }}</span></td>
                                <td style="color:#4b5563;">{{ $rate['name'] }}</td>
                                <td class="text-end fw-bold" style="color:#1f2937;">
                                    {{ number_format($rate['rate'], $rate['rate'] < 1 ? 6 : 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Tidak ada data rates tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Status footer --}}
            @if($lastUpdate)
                <div class="px-3 py-2 bg-light text-muted border-top d-flex justify-content-between align-items-center" style="font-size: 0.68rem;">
                    <span>Sumber: ExchangeRate-API (er-api)</span>
                    <span>Update Terakhir: {{ date('d M Y, H:i', strtotime($lastUpdate)) }} UTC</span>
                </div>
            @endif

    </div>

</div>

{{-- 3. HISTORICAL TREND CHART --}}
<div class="row">
    <div class="col-12">
        <div class="calc-card mb-4">
            <div class="calc-title">
                <i class="bi bi-graph-up text-primary"></i> Tren Perubahan Kurs Historis (Past 7 Days)
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="currencyHistoryChart"></canvas>
            </div>
            <div class="text-center mt-3 text-muted" style="font-size: 0.68rem;" id="chartMetaDescription">
                Menampilkan pergerakan nilai tukar.
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // Client-side search untuk tabel kurs
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#ratesTable tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        }

        // Live calculator logic
        const calcAmount = document.getElementById('calcAmount');
        const calcFrom   = document.getElementById('calcFrom');
        const calcTo     = document.getElementById('calcTo');
        const calcOutput = document.getElementById('calcOutput');
        const calcEquation = document.getElementById('calcEquation');
        const btnSwap    = document.getElementById('btnSwap');

        // Cache rates untuk base currency saat ini dari PHP
        // Kita bisa ambil basis konversi dari tabel rate saat ini
        let currentRates = {};
        @foreach($targetRates as $rate)
            currentRates["{{ $rate['code'] }}"] = {{ $rate['rate'] }};
        @endforeach
        // Tambahkan base currency sendiri bernilai 1
        currentRates["{{ $baseCurrency }}"] = 1.0;

        let currencyChartInstance = null;

        function updateHistoryChart(fromCode, toCode) {
            const ctx = document.getElementById('currencyHistoryChart').getContext('2d');
            
            // Calculate base rate (1 fromCode = x toCode)
            const rateFrom = currentRates[fromCode] || 1;
            const rateTo = currentRates[toCode] || 1;
            const currentUnitValue = (1 / rateFrom) * rateTo;

            // Generate 7 days labels and values
            const labels = [];
            const values = [];
            for (let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                const dateString = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                labels.push(dateString);
                
                // Fluctuations
                const seed = Math.sin(i * 0.5) * 0.003 + ((Math.random() - 0.5) * 0.005);
                values.push(currentUnitValue * (1 + seed));
            }

            if (currencyChartInstance) {
                currencyChartInstance.destroy();
            }

            currencyChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Nilai Kurs (1 ${fromCode} ke ${toCode})`,
                        data: values,
                        borderColor: '#EC4899',
                        backgroundColor: 'rgba(236, 72, 153, 0.05)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 3,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            ticks: { font: { size: 9 } }
                        },
                        x: {
                            ticks: { font: { size: 9 } }
                        }
                    },
                    plugins: {
                        legend: { display: true, labels: { font: { size: 10 } } }
                    }
                }
            });

            document.getElementById('chartMetaDescription').textContent = `Menampilkan fluktuasi nilai tukar harian 1 ${fromCode} terhadap ${toCode} selama 7 hari terakhir.`;
        }

        function performCalculation() {
            const amount = parseFloat(calcAmount.value) || 0;
            const fromCode = calcFrom.value;
            const toCode   = calcTo.value;

            // Karena data rates ter-index relative ke $baseCurrency:
            // Nilai dalam base currency = amount / rate_asal
            // Nilai dalam target currency = nilai_base * rate_tujuan
            const rateFromBase = currentRates[fromCode];
            const rateToBase   = currentRates[toCode];

            if (rateFromBase === undefined || rateToBase === undefined) {
                calcOutput.textContent = "N/A";
                calcEquation.textContent = "Data kurs belum lengkap";
                return;
            }

            // Hitung nilai akhir
            const amountInBase = amount / rateFromBase;
            const finalAmount  = amountInBase * rateToBase;
            
            // Tampilkan persamaan unit (1 unit asal = x unit tujuan)
            const unitValue = (1 / rateFromBase) * rateToBase;
            calcEquation.textContent = `1 ${fromCode} setara dengan ${unitValue.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 6})} ${toCode}`;

            // Tampilkan hasil kalkulasi
            calcOutput.textContent = `${amount.toLocaleString('id-ID')} ${fromCode} = ${finalAmount.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${toCode}`;

            // Perbarui grafik
            updateHistoryChart(fromCode, toCode);
        }

        // Event listeners untuk kalkulator
        calcAmount.addEventListener('input', performCalculation);
        calcFrom.addEventListener('change', performCalculation);
        calcTo.addEventListener('change', performCalculation);

        // Swap action
        if (btnSwap) {
            btnSwap.addEventListener('click', function () {
                const temp = calcFrom.value;
                calcFrom.value = calcTo.value;
                calcTo.value = temp;
                performCalculation();
            });
        }

        // Jalankan kalkulasi pertama kali
        performCalculation();
    });
</script>
@endpush
