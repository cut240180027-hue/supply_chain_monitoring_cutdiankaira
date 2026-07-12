@extends('layouts.app')

@section('title','Live Tracking')

@section('content')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="bi bi-geo-alt-fill text-danger"></i>

                Live Shipment Tracking

            </h2>

            <p class="text-muted">

                Monitoring posisi seluruh shipment secara realtime

            </p>

        </div>

    </div>

    <div class="card shadow border-0 rounded-4">

        <div class="card-body">

            <div id="map"></div>

        </div>

    </div>

</div>

@endsection

<style>

#map{
    width:100%;
    height:600px;
    border-radius:12px;
}

.leaflet-container{
    cursor:grab;
}

.leaflet-container:active{
    cursor:grabbing;
}

</style>

@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    var map = L.map('map',{
        dragging:true,
        touchZoom:true,
        doubleClickZoom:true,
        scrollWheelZoom:true,
        boxZoom:true,
        keyboard:true
    }).setView([20,110],4);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{

        maxZoom:18,

        attribution:'© OpenStreetMap'

    }).addTo(map);

    @foreach($shipments as $shipment)

    @if($shipment->latitude && $shipment->longitude)

    L.marker([
        {{ $shipment->latitude }},
        {{ $shipment->longitude }}
    ]).addTo(map)
    .bindPopup(`
        <b>{{ $shipment->shipment_code }}</b><br>
        Supplier : {{ $shipment->supplier }}<br>
        Status : {{ $shipment->status }}<br>
        Risk : {{ $shipment->risk_level }}
    `);

    @endif

    @endforeach

    setTimeout(function () {
        map.invalidateSize();
    }, 300);

});

</script>

@endpush