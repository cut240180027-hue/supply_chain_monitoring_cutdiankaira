@extends('layouts.admin')
@section('title','Artikel Analisis — Admin')
@section('breadcrumb','Artikel Analisis')

@section('content')
<div class="section-header">
    <h4>Artikel Analisis</h4>
    <p>Kelola konten artikel dan laporan analisis rantai pasok.</p>
</div>

<div class="row g-3">
    {{-- Write Article Form --}}
    <div class="col-lg-4">
        <div class="admin-table-card">
            <div class="admin-table-header"><h6><i class="bi bi-pencil-square" style="color:#c084fc;"></i> Tulis Artikel Baru</h6></div>
            <div style="padding:20px;">
                <form method="POST" action="{{ route('admin.article.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label style="font-size:.7rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#64748b;display:block;margin-bottom:7px;">Judul Artikel</label>
                        <input type="text" name="title" class="admin-input w-100" placeholder="Judul artikel…" value="{{ old('title') }}" required>
                        @error('title')<div style="font-size:.7rem;color:#f87171;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label style="font-size:.7rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#64748b;display:block;margin-bottom:7px;">Isi / Konten</label>
                        <textarea name="content" class="admin-input w-100" rows="8" placeholder="Tulis isi analisis…" required style="resize:vertical;">{{ old('content') }}</textarea>
                        @error('content')<div style="font-size:.7rem;color:#f87171;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn-admin-primary w-100">
                        <i class="bi bi-send me-1"></i> Terbitkan Artikel
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Article Table --}}
    <div class="col-lg-8">
        <div class="admin-table-card">
            <div class="admin-table-header">
                <h6><i class="bi bi-journal-text" style="color:#c084fc;"></i> Daftar Artikel ({{ $articles->total() }})</h6>
                <form method="GET" action="{{ route('admin.articles') }}">
                    <input type="text" name="search" class="admin-input" style="width:200px;" placeholder="Cari judul…" value="{{ $search }}">
                </form>
            </div>
            <table class="admin-table">
                <thead>
                    <tr><th>Judul</th><th>Penulis</th><th>Diterbitkan</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>
                            <strong>{{ Str::limit($article->title, 50) }}</strong>
                            <br>
                            <span style="font-size:.65rem;color:#64748b;">{{ Str::limit($article->content, 80) }}</span>
                        </td>
                        <td style="color:#64748b;font-size:.75rem;white-space:nowrap;">
                            {{ $article->author->name ?? '—' }}
                        </td>
                        <td style="color:#64748b;font-size:.72rem;white-space:nowrap;">
                            {{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.article.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-admin-danger"><i class="bi bi-trash3"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:#64748b;padding:30px;">Belum ada artikel.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @if($articles->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--admin-border);">
                {{ $articles->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
