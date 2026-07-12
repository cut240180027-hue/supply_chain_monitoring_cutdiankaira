@extends('layouts.app')

@section('title', 'Edit Supplier — ' . $supplier->company_name)

@push('styles')
<style>
    .form-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .form-header h4 {
        color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .form-header .icon-wrap {
        width: 36px; height: 36px; background: rgba(255,255,255,0.12);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
    }
    .form-header .subtitle { color: rgba(255,255,255,0.55); font-size: 0.75rem; margin-top: 2px; }
    .form-header-actions { display: flex; gap: 8px; }
    .btn-header-back {
        background: transparent; color: rgba(255,255,255,0.7); border: 1.5px solid rgba(255,255,255,0.2);
        border-radius: 10px; padding: 7px 14px; font-size: 0.8rem; font-weight: 600;
        text-decoration: none; display: flex; align-items: center; gap: 5px;
        transition: all .2s;
    }
    .btn-header-back:hover { color: #fff; border-color: rgba(255,255,255,0.4); }

    .form-card {
        background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 16px rgba(0,0,0,0.05); overflow: hidden;
    }
    .form-section-title {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: #EC4899; margin-bottom: 16px;
        padding-bottom: 8px; border-bottom: 1.5px solid #fce7f3;
        display: flex; align-items: center; gap: 6px;
    }
    .form-label { font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 4px; }
    .form-control, .form-select {
        border-radius: 10px; border: 1.5px solid #e5e7eb;
        font-size: 0.85rem; padding: 9px 12px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #EC4899; box-shadow: 0 0 0 3px rgba(236,72,153,.12); outline: none;
    }
    .btn-save {
        background: linear-gradient(135deg, #EC4899, #DB2777);
        color: #fff; border: none; border-radius: 10px;
        padding: 10px 28px; font-size: 0.85rem; font-weight: 700;
        transition: all .25s; display: inline-flex; align-items: center; gap: 6px;
        cursor: pointer;
    }
    .btn-save:hover { background: linear-gradient(135deg, #DB2777, #9D174D); box-shadow: 0 4px 14px rgba(236,72,153,.35); color:#fff; }
    .btn-cancel {
        background: #f3f4f6; color: #6b7280; border: none; border-radius: 10px;
        padding: 10px 20px; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-cancel:hover { background: #e5e7eb; color: #374151; }
    .btn-view {
        background: #fce7f3; color: #DB2777; border: none; border-radius: 10px;
        padding: 10px 20px; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-view:hover { background: #EC4899; color: #fff; }

    /* Current info badge */
    .current-info {
        background: linear-gradient(135deg, #fdf2f8, #fce7f3);
        border: 1.5px solid #fbcfe8;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.82rem;
        color: #9D174D;
    }
    .current-avatar {
        width: 44px; height: 44px;
        background: linear-gradient(135deg, #EC4899, #DB2777);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; font-weight: 800; color: #fff;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="form-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-pencil-square"></i></span>
            <span>
                Edit Supplier
                <div class="subtitle">Perbarui informasi supplier: {{ $supplier->company_name }}</div>
            </span>
        </h4>
    </div>
    <div class="form-header-actions">
        <a href="{{ route('suppliers.show', $supplier) }}" class="btn-header-back">
            <i class="bi bi-eye"></i> Lihat Detail
        </a>
        <a href="{{ route('suppliers.index') }}" class="btn-header-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if ($errors->any())
    <div style="background:#fee2e2;border-left:4px solid #ef4444;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:0.82rem;color:#7f1d1d;">
        <strong><i class="bi bi-exclamation-triangle me-1"></i>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Current info banner --}}
<div class="current-info">
    <div class="current-avatar">{{ strtoupper(substr($supplier->company_name, 0, 1)) }}</div>
    <div>
        <strong style="font-size:0.9rem;">{{ $supplier->company_name }}</strong><br>
        <span style="color:#DB2777;font-size:0.75rem;">
            ID #{{ $supplier->id }} &nbsp;·&nbsp;
            Dibuat: {{ $supplier->created_at->format('d M Y') }} &nbsp;·&nbsp;
            {{ $supplier->shipments->count() }} shipment terdaftar
        </span>
    </div>
</div>

<div class="form-card">
    <div class="p-4">
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                {{-- Informasi Perusahaan --}}
                <div class="col-12">
                    <div class="form-section-title">
                        <i class="bi bi-building"></i> Informasi Perusahaan
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control"
                        value="{{ old('company_name', $supplier->company_name) }}"
                        placeholder="Nama perusahaan supplier" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Negara Asal <span class="text-danger">*</span></label>
                    <select name="country_id" class="form-select" required>
                        <option value="">-- Pilih Negara --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country_id', $supplier->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->country_name }} ({{ $country->currency_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kontak --}}
                <div class="col-12" style="padding-top:8px;">
                    <div class="form-section-title">
                        <i class="bi bi-telephone"></i> Informasi Kontak
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <div style="position:relative;">
                        <i class="bi bi-envelope" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#EC4899;font-size:0.85rem;"></i>
                        <input type="email" name="email" class="form-control" style="padding-left:34px;"
                            value="{{ old('email', $supplier->email) }}"
                            placeholder="supplier@company.com">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nomor Telepon</label>
                    <div style="position:relative;">
                        <i class="bi bi-telephone" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#EC4899;font-size:0.85rem;"></i>
                        <input type="text" name="phone" class="form-control" style="padding-left:34px;"
                            value="{{ old('phone', $supplier->phone) }}"
                            placeholder="+62 21 1234567">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="3"
                        placeholder="Alamat lengkap supplier...">{{ old('address', $supplier->address) }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="col-12 d-flex gap-2 justify-content-between align-items-center"
                    style="padding-top:8px;border-top:1.5px solid #f3f4f6;">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn-view">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('suppliers.index') }}" class="btn-cancel">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
