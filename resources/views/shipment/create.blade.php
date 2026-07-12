@extends('layouts.app')

@section('title','Add Shipment')

@section('content')

<div class="container-fluid">

    <div class="card shadow border-0 rounded-4">

        <div class="card-header bg-white">

            <h3 class="mb-0">
                <i class="bi bi-plus-circle text-primary"></i>
                Add Shipment
            </h3>

        </div>

        <div class="card-body">

            @if ($errors->any())

            <div class="alert alert-danger">

                <strong>Terjadi kesalahan!</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

            @endif

            @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

            @endif

            <form action="{{ route('shipments.store') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Shipment Code</label>

                        <input
                            type="text"
                            name="shipment_code"
                            class="form-control"
                            value="{{ old('shipment_code') }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Supplier</label>

                        <select
                            name="supplier_id"
                            class="form-select"
                            required>

                            <option value="">Choose Supplier</option>

                            @foreach($suppliers as $supplier)

                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>

                                    {{ $supplier->company_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Origin Country</label>

                        <select
                            name="origin_country_id"
                            class="form-select"
                            required>

                            <option value="">Choose Country</option>

                            @foreach($countries as $country)

                                <option value="{{ $country->id }}" {{ old('origin_country_id') == $country->id ? 'selected' : '' }}>

                                    {{ $country->country_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Destination Country</label>

                        <select
                            name="destination_country_id"
                            class="form-select"
                            required>

                            <option value="">Choose Country</option>

                            @foreach($countries as $country)

                                <option value="{{ $country->id }}" {{ old('destination_country_id') == $country->id ? 'selected' : '' }}>

                                    {{ $country->country_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Origin Port</label>

                        <select
                            name="origin_port_id"
                            class="form-select"
                            required>

                            <option value="">Choose Port</option>

                            @foreach($ports as $port)

                                <option value="{{ $port->id }}" {{ old('origin_port_id') == $port->id ? 'selected' : '' }}>

                                    {{ $port->port_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Destination Port</label>

                        <select
                            name="destination_port_id"
                            class="form-select"
                            required>

                            <option value="">Choose Port</option>

                            @foreach($ports as $port)

                                <option value="{{ $port->id }}" {{ old('destination_port_id') == $port->id ? 'selected' : '' }}>

                                    {{ $port->port_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Vessel Name</label>

                        <input
                            type="text"
                            name="vessel_name"
                            class="form-control"
                            value="{{ old('vessel_name') }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Status</label>

                        <select
                            name="status"
                            class="form-select"
                            required>

                            <option>Pending</option>
                            <option>On Shipping</option>
                            <option>Arrived</option>
                            <option>Delayed</option>

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Departure Date</label>

                        <input
                            type="date"
                            name="departure_date"
                            class="form-control"
                            value="{{ old('departure_date') }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Estimated Arrival</label>

                        <input
                            type="date"
                            name="estimated_arrival"
                            class="form-control"
                            value="{{ old('estimated_arrival') }}"
                            required>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Risk Level</label>

                        <select
                            name="risk_level"
                            class="form-select">

                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>

                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">Latitude</label>

                        <input
                            type="number"
                            step="0.000001"
                            name="latitude"
                            value="{{ old('latitude') }}"
                            class="form-control">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">Longitude</label>

                        <input
                            type="number"
                            step="0.000001"
                            name="longitude"
                            value="{{ old('longitude') }}"
                            class="form-control">

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">Description</label>

                    <textarea
                        name="description"
                        rows="3"
                        class="form-control">{{ old('description') }}</textarea>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('shipments.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>
                        Back

                    </a>

                    <button
                        type="reset"
                        class="btn btn-warning">

                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Save Shipment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.querySelector("form").addEventListener("submit", function(e){

    e.preventDefault();

    let form = this;

    Swal.fire({

        title: "Save Shipment?",

        text: "Shipment data will be saved.",

        icon: "question",

        showCancelButton: true,

        confirmButtonColor: "#EC4899",

        cancelButtonColor: "#6c757d",

        confirmButtonText: "Save",

        cancelButtonText: "Cancel"

    }).then((result)=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

});

</script>

@endpush