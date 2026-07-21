@extends('layouts.app')

@section('title', 'Admin Panel — SCM')

@push('styles')
<style>
    .admin-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        color: #fff;
    }
    .admin-header h4 { margin: 0; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .admin-header .subtitle { color: rgba(255,255,255,0.6); font-size: 0.75rem; }

    /* Tabs styling */
    .admin-tabs {
        display: flex; gap: 10px; margin-bottom: 24px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;
    }
    .admin-tab-btn {
        padding: 8px 18px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
        border: 1.5px solid #e5e7eb; background: #fff; color: #4b5563; text-decoration: none;
        transition: all .2s; display: inline-flex; align-items: center; gap: 6px;
    }
    .admin-tab-btn:hover { background: #f3f4f6; color: #1f2937; }
    .admin-tab-btn.active {
        background: #EC4899; color: #fff; border-color: #EC4899;
        box-shadow: 0 4px 12px rgba(236,72,153,0.3);
    }

    .alert-success-custom {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: none; border-left: 4px solid #10b981; border-radius: 10px;
        color: #065f46; font-size: 0.82rem; font-weight: 500;
        padding: 10px 16px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .alert-error-custom {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: none; border-left: 4px solid #ef4444; border-radius: 10px;
        color: #7f1d1d; font-size: 0.82rem; font-weight: 500;
        padding: 10px 16px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
</style>
@endpush

@section('content')

<div class="admin-header">
    <h4>
        <i class="bi bi-gear-fill text-warning"></i>
        <span>
            SCM Control Center (Admin Panel)
            <div class="subtitle">Kelola otorisasi user, koordinat pelabuhan utama, dan artikel analisis geopolitik</div>
        </span>
    </h4>
</div>

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

{{-- Tabs --}}
<div class="admin-tabs">
    <a href="{{ route('admin.index', ['tab' => 'users']) }}" class="admin-tab-btn {{ $tab === 'users' ? 'active' : '' }}">
        <i class="bi bi-people"></i> Kelola User
    </a>
    <a href="{{ route('admin.index', ['tab' => 'ports']) }}" class="admin-tab-btn {{ $tab === 'ports' ? 'active' : '' }}">
        <i class="bi bi-anchor"></i> Dataset Pelabuhan
    </a>
    <a href="{{ route('admin.index', ['tab' => 'articles']) }}" class="admin-tab-btn {{ $tab === 'articles' ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> Artikel Analisis
    </a>
</div>

{{-- Content Tabs --}}
@if($tab === 'users')
    <div class="row g-4">
        {{-- Add User Form --}}
        <div class="col-lg-4">
            <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-plus text-primary me-1"></i> Tambah User Admin</h6>
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">NAMA LENGKAP</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama lengkap..." required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">EMAIL</label>
                        <input type="email" name="email" class="form-control" placeholder="Email..." required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">PASSWORD</label>
                        <input type="password" name="password" class="form-control" placeholder="Password..." required style="border-radius: 8px;">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; font-size:0.82rem; height:38px;">
                        <i class="bi bi-plus-lg"></i> Simpan User
                    </button>
                </form>
            </div>
        </div>

        {{-- Users List --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <table class="table mb-0" style="font-size:0.8rem; vertical-align: middle;">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Registrasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="fw-bold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0" style="border-radius:6px;" title="Hapus User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3 bg-light">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

@elseif($tab === 'ports')
    {{-- Ports Dataset --}}
    <div class="row">
        <div class="col-12">
            <div class="card p-3 border-0 shadow-sm mb-4" style="border-radius:16px;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Kelola seluruh pelabuhan aktif yang tersimpan di dalam database lokal.</span>
                    <a href="{{ route('ports.sync') }}" class="btn btn-sm btn-outline-primary fw-bold" style="border-radius:8px;">
                        <i class="bi bi-arrow-repeat"></i> Sinkronkan Ulang Pelabuhan via API
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <table class="table mb-0" style="font-size:0.8rem; vertical-align: middle;">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pelabuhan</th>
                            <th>Negara</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ports as $port)
                            <tr>
                                <td class="fw-bold">{{ $port->port_name }}</td>
                                <td>{{ $port->country->country_name ?? '-' }}</td>
                                <td class="font-monospace text-muted">{{ number_format($port->latitude, 4) }}</td>
                                <td class="font-monospace text-muted">{{ number_format($port->longitude, 4) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('ports.show', $port->id) }}" class="btn btn-sm btn-outline-primary border-0" style="border-radius:6px;" title="Lihat Peta"><i class="bi bi-geo-alt"></i></a>
                                        <a href="{{ route('ports.edit', $port->id) }}" class="btn btn-sm btn-outline-warning border-0" style="border-radius:6px;" title="Edit"><i class="bi bi-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3 bg-light">
                    {{ $ports->links() }}
                </div>
            </div>
        </div>
    </div>

@elseif($tab === 'articles')
    <div class="row g-4">
        {{-- Add Article Form --}}
        <div class="col-lg-4">
            <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle text-primary me-1"></i> Tulis Artikel Analisis</h6>
                <form action="{{ route('admin.article.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">JUDUL ARTIKEL</label>
                        <input type="text" name="title" class="form-control" placeholder="Judul artikel..." required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">KONTEN / ISI ANALISIS</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Isi konten analisis..." required style="border-radius: 8px; font-size: 0.8rem;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; font-size:0.82rem; height:38px;">
                        <i class="bi bi-check-lg"></i> Publish Artikel
                    </button>
                </form>
            </div>
        </div>

        {{-- Articles List --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <table class="table mb-0" style="font-size:0.8rem; vertical-align: middle;">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Author</th>
                            <th>Tanggal Terbit</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $art)
                            <tr>
                                <td class="fw-bold">{{ Str::limit($art->title, 45) }}</td>
                                <td>{{ $art->author->name ?? 'Admin' }}</td>
                                <td>{{ $art->published_at ? $art->published_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.article.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0" style="border-radius:6px;" title="Hapus Artikel">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada artikel analisis yang diterbitkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-3 bg-light">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
