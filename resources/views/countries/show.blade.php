@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Detail Country</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>Kode</th>
                <td>{{ $country->country_code }}</td>
            </tr>

            <tr>
                <th>Negara</th>
                <td>{{ $country->country_name }}</td>
            </tr>

            <tr>
                <th>Mata Uang</th>
                <td>{{ $country->currency }}</td>
            </tr>

            <tr>
                <th>Kode Mata Uang</th>
                <td>{{ $country->currency_code }}</td>
            </tr>

            <tr>
                <th>Ibukota</th>
                <td>{{ $country->capital }}</td>
            </tr>

            <tr>
                <th>Region</th>
                <td>{{ $country->region }}</td>
            </tr>

        </table>

        <a href="{{ route('countries.index') }}" class="btn btn-secondary">
            Kembali
        </a>

    </div>

</div>

@endsection