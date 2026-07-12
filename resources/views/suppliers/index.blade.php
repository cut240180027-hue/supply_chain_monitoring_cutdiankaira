@extends('layouts.app')

@section('title', 'Supplier Management')

@push('styles')
<style>
    .suppliers-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .suppliers-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .suppliers-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .suppliers-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }
    .btn-add {
        background: linear-gradient(135deg, #fff, #fce7f3);
        color: #DB2777;
        border: none;
        border-radius: 10px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 700;
        transition: all .25s;
        display: flex; align-items: center; gap: 6px;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(236,72,153,0.3); color: #9D174D; }

    /* Search */
    .search-wrap { position: relative; max-width: 300px; }
    .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 0.85rem; }
    .search-wrap input {
        padding-left: 34px;
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        font-size: 0.82rem;
        height: 36px;
        transition: border-color .2s;
    }
    .search-wrap input:focus { border-color: #EC4899; box-shadow: 0 0 0 3px rgba(236,72,153,.12); outline: none; }

    /* Table Card */
    .suppliers-card { border-radius: 16px; border: none; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; }
    .table-suppliers { margin: 0; font-size: 0.82rem; }
    .table-suppliers thead th {
        background: linear-gradient(90deg, #EC4899, #DB2777);
        color: #fff;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: none;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .table-suppliers tbody tr { transition: background .15s; border-bottom: 1px solid #f3f4f6; }
    .table-suppliers tbody tr:hover { background: #fff0f6; }
    .table-suppliers tbody td { padding: 12px 16px; vertical-align: middle; color: #374151; }
    .table-suppliers tbody tr:last-child { border-bottom: none; }

    /* Company badge */
    .company-cell { display: flex; align-items: center; gap: 10px; }
    .company-avatar {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #fce7f3, #fbcfe8);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        font-weight: 800;
        color: #DB2777;
        flex-shrink: 0;
    }
    .company-name { font-weight: 700; color: #1f2937; font-size: 0.85rem; }
    .company-email { font-size: 0.72rem; color: #9ca3af; }

    /* Country flag */
    .country-flag { font-size: 1.2rem; }
    .country-name-sm { font-size: 0.8rem; font-weight: 600; color: #374151; }

    /* Contact */
    .contact-info { font-size: 0.78rem; color: #64748b; }
    .contact-info i { color: #EC4899; margin-right: 3px; }

    /* Action buttons */
    .action-group { display: flex; gap: 5px; align-items: center; }
    .btn-act { border: none; border-radius: 8px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; transition: all .2s; text-decoration: none; }
    .btn-act-detail { background: #fce7f3; color: #DB2777; }
    .btn-act-detail:hover { background: #EC4899; color: #fff; transform: translateY(-1px); }
    .btn-act-edit   { background: #fffbeb; color: #d97706; }
    .btn-act-edit:hover   { background: #d97706; color: #fff; transform: translateY(-1px); }
    .btn-act-del    { background: #fff1f2; color: #e11d48; }
    .btn-act-del:hover    { background: #e11d48; color: #fff; transform: translateY(-1px); }

    /* Alert */
    .alert-success-custom {
        background: linear-gradient(135deg, #fce7f3, #fbcfe8);
        border: none; border-left: 4px solid #EC4899; border-radius: 10px;
        color: #9D174D; font-size: 0.82rem; font-weight: 500;
        padding: 10px 16px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }

    /* Pagination */
    .pagination .page-link { border-radius: 8px !important; border: 1.5px solid #e5e7eb; font-size: 0.78rem; color: #374151; padding: 4px 10px; margin: 0 2px; }
    .pagination .page-link:hover { background: #EC4899; color: #fff; border-color: #EC4899; }
    .pagination .page-item.active .page-link { background: #EC4899; border-color: #EC4899; }

    .empty-state { padding: 48px 0; text-align: center; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; color: #fce7f3; margin-bottom: 12px; display: block; }

    .table-footer { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #fdf2f8; border-top: 1px solid #fce7f3; font-size: 0.78rem; color: #9ca3af; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="suppliers-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-building"></i></span>
            <span>
                Supplier Management
                <div class="subtitle">Kelola data supplier & mitra rantai pasokan global</div>
            </span>
        </h4>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn-add">
        <i class="bi bi-plus-circle"></i> Tambah Supplier
    </a>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert-success-custom">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

{{-- Search + Table Card --}}
<div class="card suppliers-card">
    {{-- Toolbar --}}
    <div style="padding: 14px 16px; background: #fff; border-bottom: 1.5px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchSupplier" placeholder="Cari supplier..." />
        </div>
        <span class="text-muted" style="font-size:0.75rem;">
            Total: <strong>{{ $suppliers->total() }}</strong> supplier
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-suppliers" id="suppliersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Perusahaan</th>
                    <th>Negara</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Shipments</th>
                    <th style="width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $i => $supplier)
                    <tr>
                        <td class="text-muted fw-semibold" style="font-size:0.75rem;">
                            {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $i + 1 }}
                        </td>
                        <td>
                            <div class="company-cell">
                                <div class="company-avatar">{{ strtoupper(substr($supplier->company_name, 0, 1)) }}</div>
                                <div>
                                    <div class="company-name">{{ $supplier->company_name }}</div>
                                    <div class="company-email">{{ $supplier->email ?: '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($supplier->country)
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <span class="country-flag">
                                        @php
                                            $code = $supplier->country->country_code;
                                            if ($code && strlen($code) === 2) {
                                                $flag = mb_convert_encoding('&#' . (0x1F1E6 + ord($code[0]) - 65) . ';', 'UTF-8', 'HTML-ENTITIES')
                                                      . mb_convert_encoding('&#' . (0x1F1E6 + ord($code[1]) - 65) . ';', 'UTF-8', 'HTML-ENTITIES');
                                                echo $flag;
                                            }
                                        @endphp
                                    </span>
                                    <span class="country-name-sm">{{ $supplier->country->country_name }}</span>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="contact-info">
                                @if($supplier->phone)
                                    <div><i class="bi bi-telephone"></i>{{ $supplier->phone }}</div>
                                @endif
                                @if($supplier->email)
                                    <div><i class="bi bi-envelope"></i>{{ $supplier->email }}</div>
                                @endif
                                @if(!$supplier->phone && !$supplier->email)
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </td>
                        <td style="max-width:180px;">
                            <span style="font-size:0.78rem;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">
                                {{ $supplier->address ?: '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span style="background:#fce7f3;color:#DB2777;font-weight:700;font-size:0.72rem;padding:3px 10px;border-radius:12px;">
                                {{ $supplier->shipments->count() ?? 0 }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('suppliers.show', $supplier) }}" class="btn-act btn-act-detail" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-act btn-act-edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-act btn-act-del" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-building"></i>
                                <p class="fw-semibold">Belum ada supplier terdaftar</p>
                                <a href="{{ route('suppliers.create') }}" class="btn-add mx-auto mt-2 d-inline-flex">
                                    <i class="bi bi-plus-circle"></i> Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Pagination --}}
    <div class="table-footer">
        <span>Menampilkan {{ $suppliers->firstItem() ?? 0 }}–{{ $suppliers->lastItem() ?? 0 }} dari {{ $suppliers->total() }} supplier</span>
        <div>{{ $suppliers->links() }}</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchSupplier').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#suppliersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
