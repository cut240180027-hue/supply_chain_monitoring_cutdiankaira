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

                    <div>{{ $shipment->supplier }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Origin Country</label>

                    <div>{{ $shipment->origin_country }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Destination Country</label>

                    <div>{{ $shipment->destination_country }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Origin Port</label>

                    <div>{{ $shipment->origin_port }}</div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold">Destination Port</label>

                    <div>{{ $shipment->destination_port }}</div>

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

</div>

@endsection