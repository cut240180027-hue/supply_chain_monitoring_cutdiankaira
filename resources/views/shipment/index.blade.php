@extends('layouts.app')

@section('title','Shipment Management')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-box-seam-fill text-danger"></i>
                Shipment Management
            </h2>

            <p class="text-muted mb-0">
                Manage all global shipment data
            </p>
        </div>

        <a href="{{ route('shipments.create') }}" class="btn btn-pink rounded-pill px-4">

            <i class="bi bi-plus-circle me-2"></i>

            Add Shipment

        </a>

    </div>

    @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="bi bi-check-circle-fill"></i>

        {{ session('success') }}

        <button class="btn-close" data-bs-dismiss="alert"></button>

    </div>

    @endif

    <!-- Search -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-lg-6">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search Shipment..."
                            id="searchShipment">

                    </div>

                </div>

                <div class="col-lg-3">

                    <select class="form-select" id="statusFilter">

                        <option value="">All Status</option>
                        <option>Pending</option>
                        <option>On Shipping</option>
                        <option>Arrived</option>
                        <option>Delayed</option>

                    </select>

                </div>

                <div class="col-lg-3">

                    <select class="form-select" id="riskFilter">

                        <option value="">All Risk</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>

                    </select>

                </div>

            </div>

        </div>

    </div>

    <!-- Table -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th class="ps-4">Code</th>

                        <th>Supplier</th>

                        <th>Origin</th>

                        <th>Destination</th>

                        <th>ETA</th>

                        <th>Status</th>

                        <th>Risk</th>

                        <th width="180">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($shipments as $shipment)

                    <tr>

                        <td class="ps-4 fw-bold">

                            {{ $shipment->shipment_code }}

                        </td>

                        <td>

                            {{ $shipment->supplier->company_name ?? '-' }}

                        </td>

                        <td>

                            <strong>
                                {{ $shipment->originCountry->country_name ?? '-' }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $shipment->originPort->port_name ?? '-' }}
                            </small>

                        </td>

                        <td>

                            <strong>
                                {{ $shipment->destinationCountry->country_name ?? '-' }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $shipment->destinationPort->port_name ?? '-' }}
                            </small>

                        </td>

                        <td>

                            {{ \Carbon\Carbon::parse($shipment->estimated_arrival)->format('d M Y') }}

                        </td>

                        <td>

                            @if($shipment->status == 'Pending')

                                <span class="badge bg-warning text-dark px-3 py-2">
                                    Pending
                                </span>

                            @elseif($shipment->status == 'On Shipping')

                                <span class="badge bg-primary px-3 py-2">
                                    On Shipping
                                </span>

                            @elseif($shipment->status == 'Arrived')

                                <span class="badge bg-success px-3 py-2">
                                    Arrived
                                </span>

                            @else

                                <span class="badge bg-danger px-3 py-2">
                                    Delayed
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($shipment->risk_level == 'Low')

                                <span class="badge bg-success px-3 py-2">
                                    Low
                                </span>

                            @elseif($shipment->risk_level == 'Medium')

                                <span class="badge bg-warning text-dark px-3 py-2">
                                    Medium
                                </span>

                            @else

                                <span class="badge bg-danger px-3 py-2">
                                    High
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="btn-group">

                                <a href="{{ route('shipments.show',$shipment->id) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="bi bi-eye"></i>

                                </a>

                                <a href="{{ route('shipments.edit',$shipment->id) }}"
                                   class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <form action="{{ route('shipments.destroy',$shipment->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm btn-delete">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8">

                            <div class="text-center py-5">

                                <i class="bi bi-box display-1 text-secondary"></i>

                                <h4 class="mt-3">

                                    No Shipment Data

                                </h4>

                                <p class="text-muted">

                                    Click Add Shipment to create data.

                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer bg-white">

            {{ $shipments->links() }}

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$('.btn-delete').click(function(e){

    e.preventDefault();

    let form=$(this).closest('form');

    Swal.fire({

        title:'Delete Shipment?',

        text:'Data cannot be restored.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonColor:'#EC4899',

        cancelButtonColor:'#6c757d',

        confirmButtonText:'Delete'

    }).then((result)=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

});

</script>

@endpush