@extends('layouts.app')

@section('title','Edit Shipment')

@section('content')

<div class="container-fluid">

    <div class="card shadow border-0 rounded-4">

        <div class="card-header bg-white">

            <h3 class="mb-0">
                <i class="bi bi-pencil-square text-warning"></i>
                Edit Shipment
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

            <form action="{{ route('shipments.update',$shipment->id) }}" method="POST">

                @csrf
                @method('PUT')

                @include('shipment.form')

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('shipments.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>
                        Back

                    </a>

                    <button
                        type="submit"
                        class="btn btn-warning">

                        <i class="bi bi-check-circle"></i>
                        Update Shipment

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

document.querySelector("form").addEventListener("submit",function(e){

    e.preventDefault();

    let form=this;

    Swal.fire({

        title:"Update Shipment?",

        text:"Data shipment akan diperbarui.",

        icon:"question",

        showCancelButton:true,

        confirmButtonText:"Update",

        cancelButtonText:"Batal",

        confirmButtonColor:"#f59e0b"

    }).then((result)=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

});

</script>

@endpush