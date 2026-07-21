@extends('layouts.admin')

@section('title', 'Dashboard — Admin Panel')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
    .chart-box {
        background: var(--admin-card);
        border: 1px solid var(--admin-border);
        border-radius: 14px;
        padding: 22px;
    }
    .chart-box h6 {
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .activity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid var(--admin-border);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .activity-label {
        font-size: 0.76rem;
        color: var(--admin-text);
        flex: 1;
    }
    .activity-sub {
        font-size: 0.65rem;
        color: var(--admin-muted);
    }
    .activity-time {
        font-size: 0.65rem;
        color: var(--admin-muted);
        white-space: nowrap;
    }
    .donut-legend { list-style: none; padding: 0; margin: 0; font-size: 0.75rem; }
    .donut-legend li {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 0;
        color: var(--admin-muted);
    }
    .donut-legend .dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .donut-legend .val { margin-left: auto; font-weight: 600; color: var(--admin-text); }
</style>
@endpush

@section('content')
<div class="section-header">
    <h4>Selamat Datang, {{ session('admin_user_name', 'Admin') }} 👋</h4>
    <p>Ringkasan sistem Supply Chain Risk Intelligence — {{ now()->format('d F Y') }}</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['icon'=>'bi-people-fill',       'value'=>$stats['users'],     'label'=>'Total User',       'color'=>'rgba(236,72,153,0.2)',  'ic'=>'#f472b6'],
        ['icon'=>'bi-globe',             'value'=>$stats['countries'], 'label'=>'Negara',            'color'=>'rgba(217,70,239,0.2)',  'ic'=>'#e879f9'],
        ['icon'=>'bi-anchor',            'value'=>number_format($stats['ports']), 'label'=>'Pelabuhan', 'color'=>'rgba(244,63,94,0.2)',  'ic'=>'#fb7185'],
        ['icon'=>'bi-truck',             'value'=>$stats['shipments'], 'label'=>'Shipment',          'color'=>'rgba(168,85,247,0.2)',  'ic'=>'#c084fc'],
        ['icon'=>'bi-building',          'value'=>$stats['suppliers'], 'label'=>'Supplier',          'color'=>'rgba(236,72,153,0.2)',  'ic'=>'#f472b6'],
        ['icon'=>'bi-journal-text',      'value'=>$stats['articles'],  'label'=>'Artikel',           'color'=>'rgba(244,63,94,0.2)',  'ic'=>'#fb7185'],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon" style="background: {{ $card['color'] }}; color: {{ $card['ic'] }}">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div class="stat-value">{{ $card['value'] }}</div>
            <div class="stat-label">{{ $card['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts + Activity Row --}}
<div class="row g-3 mb-4">
    {{-- Shipment Status Donut --}}
    <div class="col-lg-4">
        <div class="chart-box h-100">
            <h6><i class="bi bi-pie-chart" style="color:#818cf8;"></i> Status Shipment</h6>
            <canvas id="statusChart" height="200"></canvas>
            <ul class="donut-legend mt-3">
                @foreach($shipmentByStatus as $status => $total)
                <li>
                    <span class="dot" style="background:
                        @if($status=='On Shipping') #ec4899
                        @elseif($status=='Delayed') #f43f5e
                        @elseif($status=='Pending') #d946ef
                        @else #a855f7
                        @endif
                    ;"></span>
                    {{ $status }}
                    <span class="val">{{ $total }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Risk Level Donut --}}
    <div class="col-lg-4">
        <div class="chart-box h-100">
            <h6><i class="bi bi-shield-exclamation" style="color:#f59e0b;"></i> Level Risiko</h6>
            <canvas id="riskChart" height="200"></canvas>
            <ul class="donut-legend mt-3">
                @foreach($shipmentByRisk as $risk => $total)
                <li>
                    <span class="dot" style="background:
                        @if($risk=='High') #ef4444
                        @elseif($risk=='Medium') #f59e0b
                        @else #10b981
                        @endif
                    ;"></span>
                    {{ $risk }} Risk
                    <span class="val">{{ $total }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Recent Shipments --}}
    <div class="col-lg-4">
        <div class="chart-box h-100">
            <h6><i class="bi bi-truck" style="color:#34d399;"></i> Shipment Terbaru</h6>
            @foreach($recentShipments as $shipment)
            <div class="activity-item">
                <div class="activity-dot" style="background:
                    @if($shipment->risk_level=='High') #ef4444
                    @elseif($shipment->risk_level=='Medium') #f59e0b
                    @else #10b981
                    @endif
                ;"></div>
                <div class="flex-fill">
                    <div class="activity-label">{{ $shipment->shipment_code }} — {{ $shipment->vessel_name }}</div>
                    <div class="activity-sub">
                        {{ $shipment->originCountry->country_name ?? '—' }} →
                        {{ $shipment->destinationCountry->country_name ?? '—' }}
                    </div>
                </div>
                <span class="risk-badge risk-{{ strtolower($shipment->risk_level) }}">{{ $shipment->risk_level }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Users + Articles --}}
<div class="row g-3">
    <div class="col-lg-6">
        <div class="admin-table-card">
            <div class="admin-table-header">
                <h6><i class="bi bi-people" style="color:#ec4899;"></i> User Terbaru</h6>
                <a href="{{ route('admin.users') }}" style="font-size:0.72rem;color:#f472b6;text-decoration:none;">Lihat Semua</a>
            </div>
            <table class="admin-table">
                <thead><tr><th>Nama</th><th>Email</th><th>Bergabung</th></tr></thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;background:linear-gradient(135deg,#ec4899,#f43f5e);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td style="color:#64748b;">{{ $user->email }}</td>
                        <td style="color:#64748b;">{{ $user->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="admin-table-card">
            <div class="admin-table-header">
                <h6><i class="bi bi-journal-text" style="color:#c084fc;"></i> Artikel Terbaru</h6>
                <a href="{{ route('admin.articles') }}" style="font-size:0.72rem;color:#f472b6;text-decoration:none;">Lihat Semua</a>
            </div>
            <table class="admin-table">
                <thead><tr><th>Judul</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @forelse($recentArticles as $article)
                    <tr>
                        <td>{{ Str::limit($article->title, 45) }}</td>
                        <td style="color:#64748b;white-space:nowrap;">{{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;color:#64748b;padding:24px;">Belum ada artikel.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartDefaults = {
    plugins: { legend: { display: false } },
    cutout: '68%',
};

// Status chart
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($shipmentByStatus->keys()) !!},
        datasets: [{
            data: {!! json_encode($shipmentByStatus->values()) !!},
            backgroundColor: ['#ec4899','#f43f5e','#d946ef','#a855f7'],
            borderWidth: 0,
        }]
    },
    options: { ...chartDefaults, maintainAspectRatio: true }
});

// Risk chart
new Chart(document.getElementById('riskChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($shipmentByRisk->keys()) !!},
        datasets: [{
            data: {!! json_encode($shipmentByRisk->values()) !!},
            backgroundColor: ['#ef4444','#f59e0b','#10b981'],
            borderWidth: 0,
        }]
    },
    options: { ...chartDefaults, maintainAspectRatio: true }
});
</script>
@endpush
