@extends('layouts.app')

@push('styles')
<style>
    .countries-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
    }
    .countries-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .countries-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .countries-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }
    .btn-sync {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all .25s;
        display: flex; align-items: center; gap: 6px;
    }
    .btn-sync:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(56,239,125,0.35); color:#fff; }
    .btn-add {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all .25s;
        display: flex; align-items: center; gap: 6px;
    }
    .btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(102,126,234,0.4); color:#fff; }

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
    .search-wrap input:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.12); }

    /* Table */
    .countries-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .table-countries {
        margin: 0;
        font-size: 0.82rem;
    }
    .table-countries thead th {
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
    .table-countries tbody tr {
        transition: background .15s;
        border-bottom: 1px solid #f3f4f6;
    }
    .table-countries tbody tr:hover { background: #f5f6ff; }
    .table-countries tbody td {
        padding: 8px 14px;
        vertical-align: middle;
        color: #374151;
    }
    .table-countries tbody tr:last-child { border-bottom: none; }

    /* Flag + name cell */
    .country-flag { font-size: 1.3rem; line-height: 1; }
    .country-name-cell { display: flex; align-items: center; gap: 10px; }
    .country-name-text { font-weight: 600; color: #1f2937; font-size: 0.82rem; }

    /* Code badge */
    .badge-code {
        background: #f0f0f8;
        color: #4f46e5;
        font-weight: 700;
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 6px;
        letter-spacing: .04em;
    }

    /* Region badge */
    .badge-region {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
    }
    .region-americas  { background:#fef3c7; color:#92400e; }
    .region-europe    { background:#dbeafe; color:#1e40af; }
    .region-asia      { background:#d1fae5; color:#065f46; }
    .region-africa    { background:#ffe4e6; color:#9f1239; }
    .region-oceania   { background:#ede9fe; color:#5b21b6; }
    .region-antarctic { background:#f3f4f6; color:#374151; }

    /* Currency */
    .currency-text { color: #6b7280; font-size: 0.78rem; }
    .currency-code { font-weight: 600; color: #374151; font-size: 0.78rem; }

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

    /* Alert */
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
    .pagination .page-link:hover { background: #667eea; color: #fff; border-color: #667eea; }
    .pagination .page-item.active .page-link { background: #667eea; border-color: #667eea; }
    /* Kecilkan icon SVG panah prev/next bawaan Bootstrap */
    .pagination .page-link span[aria-hidden] {
        font-size: 0.7rem;
        line-height: 1;
    }
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
<div class="countries-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-globe2"></i></span>
            <span>
                Data Countries
                <div class="subtitle">Kelola & sinkronkan data negara</div>
            </span>
        </h4>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('countries.sync') }}" class="btn-sync">
            <i class="bi bi-arrow-repeat"></i> Sync API
        </a>
        <a href="{{ route('countries.create') }}" class="btn-add">
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
<div class="countries-card">

    {{-- Toolbar --}}
    <div class="d-flex align-items-center justify-content-between px-3 py-2" style="border-bottom:1px solid #ebebf0;">
        <span style="font-size:.78rem;color:#9ca3af;">
            <b style="color:#374151;">{{ $countries->total() }}</b> negara
        </span>
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari negara..." class="form-control">
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-countries" id="countriesTable">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Negara</th>
                    <th>Kode</th>
                    <th>Ibukota</th>
                    <th>Region</th>
                    <th>Mata Uang</th>
                    <th width="100" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($countries as $country)
                <tr>
                    <td style="color:#9ca3af;font-size:.75rem;">{{ $loop->iteration + ($countries->currentPage()-1) * $countries->perPage() }}</td>

                    <td>
                        <div class="country-name-cell">
                            {{-- flag emoji dari kode negara (Unicode regional indicator) --}}
                            @php
                                $flag = '';
                                if (strlen($country->country_code) === 2) {
                                    $chars = str_split(strtoupper($country->country_code));
                                    $flag = mb_chr(ord($chars[0]) - 65 + 0x1F1E6) . mb_chr(ord($chars[1]) - 65 + 0x1F1E6);
                                }
                            @endphp
                            <span class="country-flag">{{ $flag }}</span>
                            <span class="country-name-text">{{ $country->country_name }}</span>
                        </div>
                    </td>

                    <td><span class="badge-code">{{ $country->country_code }}</span></td>

                    <td style="color:#6b7280;font-size:.78rem;">{{ $country->capital ?: '-' }}</td>

                    <td>
                        @php
                            $regionMap = [
                                'Americas' => 'region-americas',
                                'Europe'   => 'region-europe',
                                'Asia'     => 'region-asia',
                                'Africa'   => 'region-africa',
                                'Oceania'  => 'region-oceania',
                                'Antarctic'=> 'region-antarctic',
                            ];
                            $regionClass = $regionMap[$country->region] ?? 'region-antarctic';
                        @endphp
                        <span class="badge-region {{ $regionClass }}">{{ $country->region ?: '-' }}</span>
                    </td>

                    <td>
                        <span class="currency-code">{{ $country->currency_code }}</span>
                        <span class="currency-text"> · {{ Str::limit($country->currency, 20) }}</span>
                    </td>

                    <td>
                        <div class="action-group justify-content-center">
                            <a href="{{ route('countries.show', $country) }}"
                               class="btn-act btn-act-detail" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('countries.edit', $country) }}"
                               class="btn-act btn-act-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('countries.destroy', $country) }}"
                                  method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn-act btn-act-del"
                                        title="Hapus"
                                        onclick="return confirm('Hapus {{ $country->country_name }}?')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-globe"></i>
                            <p>Belum ada data negara.<br>Klik <b>Sync API</b> untuk mengambil data.</p>
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
            Menampilkan {{ $countries->firstItem() }}–{{ $countries->lastItem() }}
            dari {{ $countries->total() }} negara
        </span>
        <div>{{ $countries->links() }}</div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Client-side search (filter baris yang tampil)
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#countriesTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endpush