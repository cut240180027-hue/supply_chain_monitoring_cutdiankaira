@extends('layouts.app')

@push('styles')
<style>
    .ports-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 50%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .ports-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ports-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .ports-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }
    .btn-sync {
        background: linear-gradient(135deg, #F472B6, #EC4899);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all .25s;
        display: flex; align-items: center; gap: 6px;
    }
    .btn-sync:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(236,72,153,0.35); color:#fff; }
    .btn-add {
        background: linear-gradient(135deg, #EC4899, #BE185D);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all .25s;
        display: flex; align-items: center; gap: 6px;
    }
    .btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(236,72,153,0.4); color:#fff; }

    /* Search bar */
    .search-wrap {
        position: relative;
        max-width: 300px;
    }
    .search-wrap i {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: #aaa; font-size: 0.85rem;
    }
    .search-wrap input {
        padding-left: 34px;
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        font-size: 0.82rem;
        height: 36px;
        transition: border-color .2s;
    }
    .search-wrap input:focus { border-color: #EC4899; box-shadow: 0 0 0 3px rgba(236,72,153,.12); }

    /* Table */
    .ports-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .table-ports {
        margin: 0;
        font-size: 0.82rem;
    }
    .table-ports thead th {
        background: #f8f9fe;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1.5px solid #ebebf0;
        padding: 10px 14px;
        white-space: nowrap;
    }
    .table-ports tbody tr {
        transition: background .15s;
        border-bottom: 1px solid #f3f4f6;
    }
    .table-ports tbody tr:hover { background: #fff0f6; }
    .table-ports tbody td {
        padding: 8px 14px;
        vertical-align: middle;
        color: #374151;
    }
    .table-ports tbody tr:last-child { border-bottom: none; }

    /* Flag + name cell */
    .country-flag { font-size: 1.3rem; line-height: 1; }
    .country-cell { display: flex; align-items: center; gap: 8px; }
    .country-name { font-weight: 600; color: #1f2937; font-size: 0.82rem; }

    /* Coordinate badges */
    .badge-latlon {
        background: #f3f4f6;
        color: #4b5563;
        font-family: monospace;
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 4px;
    }

    /* Action buttons */
    .action-group { display: flex; gap: 5px; align-items: center; }
    .btn-act {
        border: none; border-radius: 8px;
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-act-detail { background: #eff6ff; color: #2563eb; }
    .btn-act-detail:hover { background: #2563eb; color: #fff; transform: translateY(-1px); }
    .btn-act-edit   { background: #fffbeb; color: #d97706; }
    .btn-act-edit:hover   { background: #d97706; color: #fff; transform: translateY(-1px); }
    .btn-act-del    { background: #fff1f2; color: #e11d48; }
    .btn-act-del:hover    { background: #e11d48; color: #fff; transform: translateY(-1px); }

    /* Custom alerts */
    .alert-success-custom {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: none;
        border-left: 4px solid #10b981;
        border-radius: 10px;
        color: #065f46;
        font-size: 0.82rem;
        font-weight: 500;
        padding: 10px 16px;
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .alert-error-custom {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: none;
        border-left: 4px solid #ef4444;
        border-radius: 10px;
        color: #7f1d1d;
        font-size: 0.82rem;
        font-weight: 500;
        padding: 10px 16px;
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }

    /* Pagination */
    .pagination { margin: 0; }
    .pagination .page-link {
        border-radius: 8px !important;
        border: 1.5px solid #e5e7eb;
        font-size: 0.78rem;
        color: #374151;
        padding: 4px 10px;
        margin: 0 2px;
        line-height: 1.4;
    }
    .pagination .page-link:hover { background: #EC4899; color: #fff; border-color: #EC4899; }
    .pagination .page-item.active .page-link { background: #EC4899; border-color: #EC4899; }
    .pagination .page-link span[aria-hidden] { font-size: 0.7rem; line-height: 1; }
    .pagination .page-link svg,
    .pagination .page-link span[aria-hidden="true"] {
        width: 10px !important;
        height: 10px !important;
        display: inline-block;
        vertical-align: middle;
    }

    .empty-state {
        padding: 48px 0;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; }
    .empty-state p { font-size: 0.85rem; margin: 0; }

    .table-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px;
        background: #f8f9fe;
        border-top: 1px solid #ebebf0;
        font-size: 0.78rem;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="ports-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-anchor"></i></span>
            <span>
                Data Ports
                <div class="subtitle">Kelola & sinkronkan data pelabuhan dunia</div>
            </span>
        </h4>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('ports.sync') }}" class="btn-sync">
            <i class="bi bi-arrow-repeat"></i> Sync API
        </a>
        <a href="{{ route('ports.create') }}" class="btn-add">
            <i class="bi bi-plus-lg"></i> Tambah
        </a>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert-success-custom">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert-error-custom">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
@endif

{{-- Card Table --}}
<div class="ports-card">

    {{-- Toolbar --}}
    <div class="d-flex align-items-center justify-content-between px-3 py-2" style="border-bottom:1px solid #ebebf0;">
        <span style="font-size:.78rem;color:#9ca3af;">
            <b style="color:#374151;">{{ $ports->total() }}</b> pelabuhan
        </span>
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari pelabuhan..." class="form-control">
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-ports" id="portsTable">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Nama Pelabuhan</th>
                    <th>Negara</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($ports as $port)
                <tr>
                    <td style="color:#9ca3af;font-size:.75rem;">{{ $loop->iteration + ($ports->currentPage()-1) * $ports->perPage() }}</td>
                    
                    <td><strong style="color: #1f2937;">{{ $port->port_name }}</strong></td>
                    
                    <td>
                        <div class="country-cell">
                            @php
                                $flag = '';
                                if ($port->country && strlen($port->country->country_code) === 2) {
                                    $chars = str_split(strtoupper($port->country->country_code));
                                    $flag = mb_chr(ord($chars[0]) - 65 + 0x1F1E6) . mb_chr(ord($chars[1]) - 65 + 0x1F1E6);
                                }
                            @endphp
                            @if($flag)
                                <span class="country-flag" title="{{ $port->country->country_name }}">{{ $flag }}</span>
                            @endif
                            <span class="country-name">{{ $port->country ? $port->country->country_name : '-' }}</span>
                        </div>
                    </td>

                    <td><span class="badge-latlon">{{ number_format($port->latitude, 4) }}</span></td>
                    <td><span class="badge-latlon">{{ number_format($port->longitude, 4) }}</span></td>

                    <td>
                        <div class="action-group justify-content-center">
                            <a href="{{ route('ports.show', $port) }}"
                               class="btn-act btn-act-detail" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('ports.edit', $port) }}"
                               class="btn-act btn-act-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('ports.destroy', $port) }}"
                                  method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn-act btn-act-del"
                                        title="Hapus"
                                        onclick="return confirm('Hapus {{ $port->port_name }}?')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-anchor"></i>
                            <p>Belum ada data pelabuhan.<br>Klik <b>Sync API</b> untuk mengambil data.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer pagination --}}
    <div class="table-footer">
        <span>
            Menampilkan {{ $ports->firstItem() ?: 0 }}–{{ $ports->lastItem() ?: 0 }}
            dari {{ $ports->total() }} pelabuhan
        </span>
        <div>{{ $ports->links() }}</div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Client-side search (filter baris yang tampil)
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#portsTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endpush
