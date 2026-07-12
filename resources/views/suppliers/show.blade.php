@extends('layouts.app')

@section('title', 'Detail Supplier — ' . $supplier->company_name)

@push('styles')
<style>
    .detail-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .detail-header h4 {
        color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .detail-header .icon-wrap {
        width: 36px; height: 36px; background: rgba(255,255,255,0.12);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
    }
    .detail-header .subtitle { color: rgba(255,255,255,0.55); font-size: 0.75rem; margin-top: 2px; }
    .detail-actions { display: flex; gap: 8px; }
    .btn-header-edit {
        background: rgba(255,255,255,0.15); color: #fff; border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 10px; padding: 7px 16px; font-size: 0.8rem; font-weight: 600;
        text-decoration: none; display: flex; align-items: center; gap: 5px;
        transition: all .2s;
    }
    .btn-header-edit:hover { background: rgba(255,255,255,0.25); color: #fff; }
    .btn-header-back {
        background: transparent; color: rgba(255,255,255,0.7); border: 1.5px solid rgba(255,255,255,0.2);
        border-radius: 10px; padding: 7px 14px; font-size: 0.8rem; font-weight: 600;
        text-decoration: none; display: flex; align-items: center; gap: 5px;
        transition: all .2s;
    }
    .btn-header-back:hover { color: #fff; border-color: rgba(255,255,255,0.4); }

    /* Profile card */
    .profile-card {
        background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 16px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 20px;
    }
    .profile-banner {
        height: 80px;
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        position: relative;
    }
    .profile-avatar {
        width: 72px; height: 72px;
        background: linear-gradient(135deg, #EC4899, #DB2777);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: 800; color: #fff;
        position: absolute; bottom: -28px; left: 24px;
        box-shadow: 0 6px 20px rgba(236,72,153,0.3);
        border: 4px solid #fff;
    }
    .profile-body { padding: 44px 24px 24px; }
    .profile-name { font-size: 1.2rem; font-weight: 800; color: #1f2937; margin-bottom: 4px; }
    .profile-country {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.82rem; color: #64748b; font-weight: 600;
    }
    .profile-flag { font-size: 1.2rem; }

    /* Info grid */
    .info-section { padding: 20px; }
    .info-section-title {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: #EC4899; margin-bottom: 16px;
        padding-bottom: 8px; border-bottom: 1.5px solid #fce7f3;
        display: flex; align-items: center; gap: 6px;
    }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
    .info-item {
        background: #fdf2f8; border-radius: 12px; padding: 14px 16px;
        border: 1px solid #fce7f3;
    }
    .info-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #DB2777; margin-bottom: 4px; }
    .info-value { font-size: 0.9rem; font-weight: 600; color: #1f2937; }
    .info-value.empty { color: #9ca3af; font-weight: 400; font-style: italic; }

    /* Shipment table */
    .shipments-card {
        background: #fff; border-radius: 16px; border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 16px rgba(0,0,0,0.05); overflow: hidden;
    }
    .table-sm-supplier { font-size: 0.8rem; margin: 0; }
    .table-sm-supplier thead th {
        background: linear-gradient(90deg, #EC4899, #DB2777);
        color: #fff; font-weight: 700; font-size: 0.7rem;
        text-transform: uppercase; letter-spacing: .05em;
        border: none; padding: 10px 14px;
    }
    .table-sm-supplier tbody td { padding: 10px 14px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    .table-sm-supplier tbody tr:hover { background: #fff0f6; }
    .badge-risk-low  { background: #d1fae5; color: #065f46; font-size: 0.68rem; font-weight: 700; padding: 3px 8px; border-radius: 10px; }
    .badge-risk-med  { background: #fef3c7; color: #92400e; font-size: 0.68rem; font-weight: 700; padding: 3px 8px; border-radius: 10px; }
    .badge-risk-high { background: #fee2e2; color: #7f1d1d; font-size: 0.68rem; font-weight: 700; padding: 3px 8px; border-radius: 10px; }
    .badge-status { font-size: 0.68rem; font-weight: 700; padding: 3px 8px; border-radius: 10px; }

    /* Delete confirm */
    .btn-delete-supplier {
        background: #fee2e2; color: #ef4444; border: none; border-radius: 10px;
        padding: 7px 16px; font-size: 0.8rem; font-weight: 600;
        display: flex; align-items: center; gap: 5px; cursor: pointer;
        transition: all .2s;
    }
    .btn-delete-supplier:hover { background: #ef4444; color: #fff; }
</style>
@endpush

@section('content')

@php
    $code = $supplier->country->country_code ?? '';
    $flag = '';
    if ($code && strlen($code) === 2) {
        $flag = mb_convert_encoding('&#' . (0x1F1E6 + ord($code[0]) - 65) . ';', 'UTF-8', 'HTML-ENTITIES')
              . mb_convert_encoding('&#' . (0x1F1E6 + ord($code[1]) - 65) . ';', 'UTF-8', 'HTML-ENTITIES');
    }
@endphp

{{-- Header --}}
<div class="detail-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-building"></i></span>
            <span>
                {{ $supplier->company_name }}
                <div class="subtitle">Detail informasi supplier &amp; riwayat pengiriman</div>
            </span>
        </h4>
    </div>
    <div class="detail-actions">
        <a href="{{ route('suppliers.index') }}" class="btn-header-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-header-edit">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="profile-card">
            <div class="profile-banner">
                <div class="profile-avatar">{{ strtoupper(substr($supplier->company_name, 0, 1)) }}</div>
            </div>
            <div class="profile-body">
                <div class="profile-name">{{ $supplier->company_name }}</div>
                @if($supplier->country)
                    <div class="profile-country mt-1">
                        <span class="profile-flag">{{ $flag }}</span>
                        <span>{{ $supplier->country->country_name }}</span>
                    </div>
                @endif

                <hr style="border-color:#fce7f3;margin:16px 0;">

                <div class="d-flex flex-column gap-2" style="font-size:0.82rem;">
                    @if($supplier->email)
                        <div style="display:flex;align-items:center;gap:8px;color:#374151;">
                            <i class="bi bi-envelope-fill" style="color:#EC4899;width:16px;"></i>
                            {{ $supplier->email }}
                        </div>
                    @endif
                    @if($supplier->phone)
                        <div style="display:flex;align-items:center;gap:8px;color:#374151;">
                            <i class="bi bi-telephone-fill" style="color:#EC4899;width:16px;"></i>
                            {{ $supplier->phone }}
                        </div>
                    @endif
                    @if($supplier->address)
                        <div style="display:flex;align-items:flex-start;gap:8px;color:#374151;">
                            <i class="bi bi-geo-alt-fill" style="color:#EC4899;width:16px;margin-top:2px;"></i>
                            <span>{{ $supplier->address }}</span>
                        </div>
                    @endif
                    @if($supplier->country)
                        <div style="display:flex;align-items:center;gap:8px;color:#374151;">
                            <i class="bi bi-currency-exchange" style="color:#EC4899;width:16px;"></i>
                            Mata Uang: <strong>{{ $supplier->country->currency_code }}</strong>
                        </div>
                    @endif
                </div>

                <hr style="border-color:#fce7f3;margin:16px 0;">

                {{-- Stats --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div style="background:#fdf2f8;border-radius:10px;padding:12px;text-align:center;border:1px solid #fce7f3;">
                        <div style="font-size:1.4rem;font-weight:800;color:#DB2777;">{{ $supplier->shipments->count() }}</div>
                        <div style="font-size:0.68rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Shipments</div>
                    </div>
                    <div style="background:#fdf2f8;border-radius:10px;padding:12px;text-align:center;border:1px solid #fce7f3;">
                        <div style="font-size:1.4rem;font-weight:800;color:#DB2777;">
                            {{ $supplier->shipments->where('status', 'On Shipping')->count() }}
                        </div>
                        <div style="font-size:0.68rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Aktif</div>
                    </div>
                </div>

                <hr style="border-color:#fce7f3;margin:16px 0;">

                {{-- Delete --}}
                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->company_name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete-supplier w-100 justify-content-center">
                        <i class="bi bi-trash"></i> Hapus Supplier Ini
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Shipment History --}}
    <div class="col-lg-8">
        <div class="shipments-card">
            <div style="padding:14px 18px;background:#fff;border-bottom:1.5px solid #fce7f3;display:flex;align-items:center;justify-content:space-between;">
                <h6 style="margin:0;font-weight:700;font-size:0.85rem;color:#374151;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-truck" style="color:#EC4899;"></i> Riwayat Shipment
                </h6>
                <span style="font-size:0.72rem;color:#9ca3af;">{{ $supplier->shipments->count() }} pengiriman</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm-supplier">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tujuan</th>
                            <th>ETA</th>
                            <th>Status</th>
                            <th>Risiko</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->shipments->sortByDesc('created_at') as $shipment)
                            <tr>
                                <td><strong style="color:#1e293b;">{{ $shipment->shipment_code }}</strong></td>
                                <td style="font-size:0.78rem;">
                                    {{ $shipment->destinationCountry->country_name ?? '-' }}
                                    @if($shipment->destinationPort)
                                        <br><span style="color:#9ca3af;font-size:0.7rem;">{{ $shipment->destinationPort->port_name }}</span>
                                    @endif
                                </td>
                                <td style="font-size:0.78rem;color:#64748b;">
                                    {{ $shipment->estimated_arrival ? \Carbon\Carbon::parse($shipment->estimated_arrival)->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'Pending'     => 'background:#fef3c7;color:#92400e;',
                                            'On Shipping' => 'background:#dbeafe;color:#1e40af;',
                                            'Arrived'     => 'background:#d1fae5;color:#065f46;',
                                            'Delivered'   => 'background:#d1fae5;color:#065f46;',
                                            'Delayed'     => 'background:#fee2e2;color:#7f1d1d;',
                                        ][$shipment->status] ?? 'background:#f3f4f6;color:#374151;';
                                    @endphp
                                    <span class="badge-status" style="{{ $statusClass }}">{{ $shipment->status }}</span>
                                </td>
                                <td>
                                    @if($shipment->risk_level === 'High')
                                        <span class="badge-risk-high">High</span>
                                    @elseif($shipment->risk_level === 'Medium')
                                        <span class="badge-risk-med">Medium</span>
                                    @else
                                        <span class="badge-risk-low">Low</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color:#9ca3af;font-size:0.82rem;">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                    Belum ada riwayat shipment
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
