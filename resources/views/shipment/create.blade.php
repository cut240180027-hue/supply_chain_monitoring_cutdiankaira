@extends('layouts.app')

@section('title','Add Shipment')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold">
                        <i class="bi bi-plus-circle-fill text-danger"></i>
                        Add Shipment
                    </h2>

                    <p class="text-muted mb-0">
                        Create new shipment data
                    </p>

                </div>

                <a href="{{ route('shipments.index') }}" class="btn btn-secondary rounded-pill">

                    <i class="bi bi-arrow-left"></i>

                    Back

                </a>

            </div>

            <div class="card border-0 shadow rounded-4">

                <div class="card-body p-4">

                    <form action="{{ route('shipments.store') }}" method="POST">

                        @include('shipment.form')

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection