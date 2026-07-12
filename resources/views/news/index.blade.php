@extends('layouts.app')

@push('styles')
<style>
    /* ===== NEWS PORTAL STYLES ===== */
    .news-header {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 60%, #9D174D 100%);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 24px rgba(236,72,153,0.2);
    }
    .news-header h4 {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .news-header h4 .icon-wrap {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .news-header .subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Source badge */
    .badge-source-type {
        font-size: 0.65rem;
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.7);
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: .02em;
    }

    /* Category Navigation */
    .category-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .category-btn {
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        color: #4b5563;
        text-decoration: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .category-btn:hover {
        background: #f3f4f6;
        color: #1f2937;
        border-color: #d1d5db;
    }
    .category-btn.active {
        background: #EC4899;
        color: #fff;
        border-color: #EC4899;
        box-shadow: 0 4px 12px rgba(236,72,153,0.3);
    }

    /* News Grid Layout */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .news-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all .25s;
        height: 100%;
    }
    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border-color: #e5e7eb;
    }

    .news-card-img-wrap {
        height: 160px;
        position: relative;
        background: linear-gradient(135deg, #EC4899 0%, #BE185D 100%);
        overflow: hidden;
    }
    .news-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.85;
        transition: transform .3s;
    }
    .news-card:hover .news-card-img {
        transform: scale(1.04);
    }

    /* Placeholder Gradient cards */
    .news-placeholder-icon {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.2);
        font-size: 4rem;
    }

    /* Risk badge over image */
    .news-risk-badge {
        position: absolute; top: 12px; right: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 800;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: .04em;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .news-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .news-source-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.7rem;
        color: #9ca3af;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .news-source {
        color: #DB2777;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .news-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.4;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
    }

    .news-desc {
        font-size: 0.78rem;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 4.5em;
        flex: 1;
    }

    .news-footer {
        padding-top: 12px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .btn-read {
        font-size: 0.75rem;
        font-weight: 700;
        color: #EC4899;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: transform .2s;
    }
    .btn-read:hover {
        color: #9D174D;
    }
    .btn-read:hover i {
        transform: translateX(2px);
    }
</style>
@endpush

@section('title', 'Supply Chain News — SCM')

@section('content')

{{-- Header --}}
<div class="news-header">
    <div>
        <h4>
            <span class="icon-wrap"><i class="bi bi-newspaper"></i></span>
            <span>
                Global Supply Chain News
                <div class="subtitle">Berita ekonomi, geopolitik, dan logistik dunia</div>
            </span>
        </h4>
    </div>
    <div>
        <span class="badge-source-type">
            Sumber: {{ $sourceType }}
        </span>
    </div>
</div>

{{-- Category filter pills --}}
<div class="category-nav">
    <a href="{{ route('news.index', ['category' => 'all']) }}" 
       class="category-btn {{ $category === 'all' ? 'active' : '' }}">
        <i class="bi bi-collection"></i> Semua Berita
    </a>
    <a href="{{ route('news.index', ['category' => 'economics']) }}" 
       class="category-btn {{ $category === 'economics' ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i> Ekonomi & Keuangan
    </a>
    <a href="{{ route('news.index', ['category' => 'logistics']) }}" 
       class="category-btn {{ $category === 'logistics' ? 'active' : '' }}">
        <i class="bi bi-truck"></i> Logistik & Pelabuhan
    </a>
    <a href="{{ route('news.index', ['category' => 'geopolitics']) }}" 
       class="category-btn {{ $category === 'geopolitics' ? 'active' : '' }}">
        <i class="bi bi-shield-alert"></i> Geopolitik & Keamanan
    </a>
</div>

{{-- News Grid --}}
@if(empty($articles))
    <div class="card p-5 text-center text-secondary border-0 shadow-sm" style="border-radius:16px;">
        <i class="bi bi-journal-x fs-1 mb-2"></i>
        <p class="mb-0">Tidak ada berita yang ditemukan untuk kategori ini.</p>
        <small class="text-muted">Pastikan Anda memiliki koneksi internet atau periksa kembali API Key Anda.</small>
    </div>
@else
    <div class="news-grid">
        @foreach($articles as $art)
            <div class="news-card">
                
                {{-- Card Image --}}
                <div class="news-card-img-wrap">
                    @if($art['image'])
                        <img src="{{ $art['image'] }}" class="news-card-img" alt="{{ $art['title'] }}" onerror="this.style.display='none';">
                    @endif
                    
                    {{-- Generic placeholder gradient fallback --}}
                    <div class="news-placeholder-icon">
                        @if($category === 'economics')
                            <i class="bi bi-graph-up-arrow"></i>
                        @elseif($category === 'logistics')
                            <i class="bi bi-box-seam"></i>
                        @elseif($category === 'geopolitics')
                            <i class="bi bi-globe-americas"></i>
                        @else
                            <i class="bi bi-newspaper"></i>
                        @endif
                    </div>

                    {{-- Risk Level Badge --}}
                    <span class="news-risk-badge" style="background-color: {{ $art['risk_color'] }};">
                        {{ $art['risk_level'] }} Risk
                    </span>
                </div>

                {{-- Card Body --}}
                <div class="news-body">
                    <div class="news-source-row">
                        <span class="news-source">{{ $art['source'] }}</span>
                        <span>
                            @php
                                $timeStr = $art['published_at'];
                                $time = strtotime($timeStr);
                                echo $time ? date('d M Y, H:i', $time) : $timeStr;
                            @endphp
                        </span>
                    </div>

                    <h5 class="news-title" title="{{ $art['title'] }}">
                        {{ $art['title'] }}
                    </h5>

                    <p class="news-desc">
                        {{ $art['description'] ?: 'Tidak ada deskripsi singkat untuk berita ini. Klik Baca Selengkapnya untuk membuka artikel asli.' }}
                    </p>

                    <div class="news-footer">
                        <a href="{{ $art['url'] }}" target="_blank" class="btn-read">
                            Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                        </a>
                        
                        <span class="text-muted" style="font-size:0.65rem;">
                            <i class="bi bi-shield-check"></i> Dinilai
                        </span>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@endif

@endsection
