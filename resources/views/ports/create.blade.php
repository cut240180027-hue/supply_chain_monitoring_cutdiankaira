@extends('layouts.app')

@push('styles')
<style>
    .form-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .form-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 18px 24px;
        color: #fff;
    }
    .form-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.05rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-save {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all .2s;
    }
    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(102,126,234,0.3); color:#fff; }
    .btn-back {
        border-radius: 8px;
        font-size: 0.82rem;
        padding: 8px 16px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

<div class="card form-card">

    <div class="form-card-header">
        <h4>
            <i class="bi bi-plus-circle"></i> Tambah Pelabuhan Baru
        </h4>
    </div>

    <div class="card-body p-4">

        @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 10px; font-size: 0.82rem;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ports.store') }}" method="POST">
            @csrf

            @include('ports.form')

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn-save">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('ports.index') }}" class="btn btn-secondary btn-back">
                    Batal
                </a>
            </div>
        </form>

    </div>

</div>

@endsection
