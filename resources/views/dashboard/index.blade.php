@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold">Dashboard</h1>
        <p class="text-muted">
            Welcome to Global Supply Chain Monitoring Dashboard
        </p>
    </div>

    <button class="btn btn-primary">
        <i class="bi bi-arrow-clockwise"></i>
        Refresh Data
    </button>

</div>

{{-- =======================
    STATISTIC CARD
======================= --}}

<div class="row g-4 mb-4">

    <div class="col-lg-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-globe2 fs-1 text-primary"></i>
                <h6 class="mt-3">Countries</h6>
                <h2>{{ $stats['countries'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-geo-alt-fill fs-1 text-success"></i>
                <h6 class="mt-3">Ports</h6>
                <h2>{{ $stats['ports'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-building fs-1 text-warning"></i>
                <h6 class="mt-3">Suppliers</h6>
                <h2>{{ $stats['suppliers'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-truck fs-1 text-danger"></i>
                <h6 class="mt-3">Shipments</h6>
                <h2>{{ $stats['shipments'] }}</h2>
            </div>
        </div>
    </div>

</div>

{{-- =======================
    MAP & WEATHER
======================= --}}

<div class="row mb-4">

    <div class="col-lg-8">

        <div class="card shadow">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Live Shipment Tracking
                </h5>

            </div>

            <div class="card-body">

                <div id="map" style="height:500px;"></div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card shadow">

            <div class="card-header bg-white">

                Weather Monitoring

            </div>

            <div class="card-body text-center">

                <i class="bi bi-cloud-rain-fill text-primary display-1"></i>

                <h2 class="mt-3">28°C</h2>

                <h5>Shanghai Port</h5>

                <hr>

                <p>Wind : 15 km/h</p>

                <p>Rainfall : 40%</p>

                <span class="badge bg-danger">
                    High Risk
                </span>

            </div>

        </div>

    </div>

</div>

{{-- =======================
    TABLE & CHART
======================= --}}

<div class="row">

    <div class="col-lg-6">

        <div class="card shadow">

            <div class="card-header bg-white">

                Recent Shipments

            </div>

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                    <tr>

                        <th>Code</th>

                        <th>Status</th>

                        <th>Risk</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($recentShipments as $shipment)

                        <tr>

                            <td>{{ $shipment->shipment_code }}</td>

                            <td>{{ $shipment->status }}</td>

                            <td>

                                @if($shipment->risk_level=='High')
                                    <span class="badge bg-danger">High</span>
                                @elseif($shipment->risk_level=='Medium')
                                    <span class="badge bg-warning">Medium</span>
                                @else
                                    <span class="badge bg-success">Low</span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="text-center">

                                No Shipment Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card shadow">

            <div class="card-header bg-white">

                Shipment Statistics

            </div>

            <div class="card-body">

                <canvas id="shipmentChart"></canvas>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

let map = L.map('map').setView([20,110],4);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{

    maxZoom:18,

}).addTo(map);

const shipments = @json($shipmentsMap ?? []);

shipments.forEach(function(item){

    if(item.latitude && item.longitude){

        let color = "green";

        if(item.risk_level=="Medium")
            color="orange";

        if(item.risk_level=="High")
            color="red";

        L.circleMarker(

            [item.latitude,item.longitude],

            {

                radius:8,

                color:color,

                fillColor:color,

                fillOpacity:0.8

            }

        ).addTo(map)

        .bindPopup(

            "<b>"+item.shipment_code+"</b><br>"+

            "Status : "+item.status+"<br>"+

            "Risk : "+item.risk_level

        );

    }

});

new Chart(document.getElementById('shipmentChart'),{

    type:'bar',

    data:{

        labels:[
            'Shipments',
            'Suppliers',
            'Countries',
            'Ports'
        ],

        datasets:[{

            label:'Total Data',

            data:[
                {{ $stats['shipments'] }},
                {{ $stats['suppliers'] }},
                {{ $stats['countries'] }},
                {{ $stats['ports'] }}
            ]

        }]

    }

});

</script>

@endpush