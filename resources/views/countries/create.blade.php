@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Tambah Country</h4>
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('countries.store') }}" method="POST">

            @csrf

            @include('countries.form')

            <button class="btn btn-primary">
                Simpan
            </button>

            <a href="{{ route('countries.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>

</div>

@endsection