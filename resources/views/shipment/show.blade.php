@extends('layouts.app')

@section('title','Shipment Detail')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                <i class="bi bi-box-seam text-primary"></i>
                Shipment Detail
            </h2>

            <p class="text-muted">
                Detail informasi shipment
            </p>

        </div>

        <a href="{{ route('shipments.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Back

        </a>

    </div>

    <div class="card shadow border-0 rounded-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Shipment Code</label>

                    <div>{{ $shipment->shipment_code }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Supplier</label>

                    <div>{{ $shipment->supplier->company_name ?? '-' }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Origin Country</label>

                    <div>{{ $shipment->originCountry->country_name ?? '-' }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Destination Country</label>

                    <div>{{ $shipment->destinationCountry->country_name ?? '-' }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Origin Port</label>

                    <div>{{ $shipment->originPort->port_name ?? '-' }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Destination Port</label>

                    <div>{{ $shipment->destinationPort->port_name ?? '-' }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Vessel Name</label>

                    <div>{{ $shipment->vessel_name }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Status</label>

                    <div>

                        @if($shipment->status=='Pending')

                            <span class="badge bg-warning text-dark">Pending</span>

                        @elseif($shipment->status=='On Shipping')

                            <span class="badge bg-primary">On Shipping</span>

                        @elseif($shipment->status=='Arrived')

                            <span class="badge bg-success">Arrived</span>

                        @else

                            <span class="badge bg-danger">Delayed</span>

                        @endif

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Departure Date</label>

                    <div>{{ $shipment->departure_date }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Estimated Arrival</label>

                    <div>{{ $shipment->estimated_arrival }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Risk Level</label>

                    <div>

                        @if($shipment->risk_level=='Low')

                            <span class="badge bg-success">Low</span>

                        @elseif($shipment->risk_level=='Medium')

                            <span class="badge bg-warning text-dark">Medium</span>

                        @else

                            <span class="badge bg-danger">High</span>

                        @endif

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Latitude</label>

                    <div>{{ $shipment->latitude }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Longitude</label>

                    <div>{{ $shipment->longitude }}</div>

                </div>

                <div class="col-12">

                    <label class="fw-bold">Description</label>

                    <div class="border rounded p-3 bg-light">

                        {{ $shipment->description }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Risk Score Breakdown Card --}}
    @php
        $rScore = $shipment->riskScore;
        $total = $rScore ? $rScore->total_score : 20;
        
        $riskColor = '#10b981';
        $riskBg = '#d1fae5';
        $riskText = '#065f46';
        if ($shipment->risk_level === 'High') {
            $riskColor = '#ef4444';
            $riskBg = '#fee2e2';
            $riskText = '#7f1d1d';
        } elseif ($shipment->risk_level === 'Medium') {
            $riskColor = '#f59e0b';
            $riskBg = '#fef3c7';
            $riskText = '#92400e';
        }
    @endphp
    <div class="card shadow border-0 rounded-4 mt-4 overflow-hidden">
        <div class="card-header bg-light border-0 py-3 px-4">
            <h5 class="mb-0 fw-bold text-secondary" style="font-size:0.85rem; text-transform:uppercase; letter-spacing:.05em;">
                <i class="bi bi-shield-check text-primary"></i> Analisis Skor Risiko Shipment (Weightage)
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                {{-- Left circle gauge --}}
                <div class="col-md-4 text-center border-end">
                    <div class="d-inline-block rounded-circle p-4 mb-2" style="background: {{ $riskBg }}; width:120px; height:120px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                        <span class="fw-bold" style="font-size:2rem; color: {{ $riskColor }}; line-height:1;">{{ $total }}</span>
                        <span style="font-size:0.65rem; color: {{ $riskText }}; font-weight:700; text-transform:uppercase; margin-top:2px;">{{ $shipment->risk_level }}</span>
                    </div>
                    <div class="small text-muted fw-semibold">Skor Total Risiko SCM</div>
                </div>

                {{-- Right progress bars --}}
                <div class="col-md-8">
                    @php
                        $factors = [
                            [
                                'label' => 'Cuaca & Keadaan Alam',
                                'weight' => '30%',
                                'score' => $rScore ? $rScore->weather_score : 20,
                                'color' => '#3b82f6',
                                'icon' => 'bi-cloud-rain',
                            ],
                            [
                                'label' => 'Kurs & Volatilitas Valuta',
                                'weight' => '25%',
                                'score' => $rScore ? $rScore->currency_score : 20,
                                'color' => '#8b5cf6',
                                'icon' => 'bi-currency-exchange',
                            ],
                            [
                                'label' => 'Kepadatan & Infrastruktur Pelabuhan',
                                'weight' => '20%',
                                'score' => $rScore ? $rScore->port_score : 20,
                                'color' => '#f59e0b',
                                'icon' => 'bi-anchor',
                            ],
                            [
                                'label' => 'Kondisi Geopolitik & Berita Keamanan',
                                'weight' => '25%',
                                'score' => $rScore ? $rScore->geopolitical_score : 20,
                                'color' => '#ef4444',
                                'icon' => 'bi-shield-exclamation',
                            ],
                        ];
                    @endphp

                    @foreach($factors as $f)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.8rem;">
                                <span class="fw-semibold text-dark">
                                    <i class="bi {{ $f['icon'] }}" style="color: {{ $f['color'] }};"></i> {{ $f['label'] }} 
                                    <span class="text-muted" style="font-size:0.68rem; font-weight:normal;">(Bobot: {{ $f['weight'] }})</span>
                                </span>
                                <span class="fw-bold font-monospace">{{ $f['score'] }}/100</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 4px; background:#f1f5f9;">
                                <div class="progress-bar" style="width: {{ $f['score'] }}%; background-color: {{ $f['color'] }}; border-radius:4px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


</div>

@endsection