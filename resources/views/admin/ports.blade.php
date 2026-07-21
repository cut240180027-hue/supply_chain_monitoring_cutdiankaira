@extends('layouts.admin')
@section('title','Kelola Pelabuhan — Admin')
@section('breadcrumb','Kelola Pelabuhan')

@section('content')
<div class="section-header">
    <h4>Kelola Pelabuhan</h4>
    <p>Daftar pelabuhan tersinkronisasi dari World Port Index API.</p>
</div>

<div class="admin-table-card">
    <div class="admin-table-header">
        <h6><i class="bi bi-anchor" style="color:#fbbf24;"></i> {{ $ports->total() }} Pelabuhan</h6>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <form method="GET" action="{{ route('admin.ports') }}" style="display:flex;gap:8px;">
                <input type="text" name="search" class="admin-input" style="width:180px;" placeholder="Cari nama pelabuhan…" value="{{ $search }}">
                <select name="country_id" class="admin-input" style="width:160px;">
                    <option value="">Semua Negara</option>
                    @foreach($countries as $c)
                    <option value="{{ $c->id }}" @selected($countryFilter == $c->id)>{{ $c->country_name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-admin-primary">Filter</button>
            </form>
            <a href="{{ route('ports.sync') }}" class="btn-admin-primary" style="display:flex;align-items:center;gap:6px;text-decoration:none;">
                <i class="bi bi-arrow-clockwise"></i> Sync API
            </a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr><th>ID</th><th>Nama Pelabuhan</th><th>Negara</th><th>Latitude</th><th>Longitude</th></tr>
        </thead>
        <tbody>
            @forelse($ports as $port)
            <tr>
                <td style="color:#64748b;">{{ $port->id }}</td>
                <td><strong>{{ $port->port_name }}</strong></td>
                <td>
                    @if($port->country)
                    <span style="display:flex;align-items:center;gap:6px;">
                        <span style="background:rgba(99,102,241,0.15);color:#818cf8;padding:2px 7px;border-radius:5px;font-size:.65rem;font-weight:700;">{{ $port->country->country_code }}</span>
                        {{ $port->country->country_name }}
                    </span>
                    @else —
                    @endif
                </td>
                <td style="color:#64748b;font-size:.75rem;">{{ $port->latitude }}</td>
                <td style="color:#64748b;font-size:.75rem;">{{ $port->longitude }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#64748b;padding:30px;">Belum ada data pelabuhan.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($ports->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--admin-border);">
        {{ $ports->links() }}
    </div>
    @endif
</div>
@endsection
