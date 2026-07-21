@extends('layouts.admin')
@section('title','Kelola Negara — Admin')
@section('breadcrumb','Kelola Negara')

@section('content')
<div class="section-header">
    <h4>Kelola Negara</h4>
    <p>Daftar seluruh negara yang tersinkronisasi dari REST Countries API.</p>
</div>

<div class="admin-table-card">
    <div class="admin-table-header">
        <h6><i class="bi bi-globe" style="color:#22d3ee;"></i> {{ $countries->total() }} Negara</h6>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <form method="GET" action="{{ route('admin.countries') }}">
                <input type="text" name="search" class="admin-input" style="width:220px;" placeholder="Cari nama / kode negara…" value="{{ $search }}">
            </form>
            <a href="{{ route('countries.sync') }}" class="btn-admin-primary" style="display:flex;align-items:center;gap:6px;text-decoration:none;">
                <i class="bi bi-arrow-clockwise"></i> Sync dari API
            </a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Kode</th><th>Nama Negara</th><th>Ibu Kota</th><th>Kawasan</th>
                <th>Mata Uang</th><th>Timezone</th><th>Koordinat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($countries as $country)
            <tr>
                <td>
                    <span style="background:rgba(99,102,241,0.15);color:#818cf8;padding:3px 8px;border-radius:6px;font-size:.7rem;font-weight:700;">
                        {{ $country->country_code }}
                    </span>
                </td>
                <td><strong>{{ $country->country_name }}</strong></td>
                <td style="color:#64748b;">{{ $country->capital ?? '—' }}</td>
                <td>
                    <span style="font-size:.65rem;color:#94a3b8;">{{ $country->region }}</span>
                    @if($country->subregion && $country->subregion !== '-')
                    <br><span style="font-size:.6rem;color:#475569;">{{ $country->subregion }}</span>
                    @endif
                </td>
                <td>
                    <span style="font-size:.72rem;color:#34d399;">{{ $country->currency_code }}</span>
                    @if($country->currency && $country->currency !== '-')
                    <br><span style="font-size:.62rem;color:#475569;">{{ $country->currency }}</span>
                    @endif
                </td>
                <td style="color:#64748b;font-size:.7rem;">{{ $country->timezone }}</td>
                <td style="color:#64748b;font-size:.68rem;">
                    @if($country->latitude && $country->longitude)
                    {{ number_format($country->latitude,2) }}, {{ number_format($country->longitude,2) }}
                    @else —
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#64748b;padding:30px;">Tidak ada data negara. Klik "Sync dari API".</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($countries->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--admin-border);">
        {{ $countries->links() }}
    </div>
    @endif
</div>
@endsection
