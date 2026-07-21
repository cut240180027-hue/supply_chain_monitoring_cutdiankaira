<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — SCM Risk Intelligence</title>
    <meta name="description" content="Admin Control Panel — SCM Risk Intelligence Platform">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --admin-sidebar-w: 260px;
            --admin-bg: #120a14;
            --admin-sidebar-bg: #1c0e1e;
            --admin-card: #251328;
            --admin-border: rgba(236, 72, 153, 0.12);
            --admin-accent: #ec4899;
            --admin-accent2: #f43f5e;
            --admin-danger: #f43f5e;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-text: #fbcfe8;
            --admin-muted: #a27b9c;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
            margin: 0;
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: var(--admin-sidebar-w);
            min-height: 100vh;
            background: var(--admin-sidebar-bg);
            border-right: 1px solid var(--admin-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 18px;
            border-bottom: 1px solid var(--admin-border);
        }
        .sidebar-brand .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--admin-accent), var(--admin-accent2));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
        }
        .brand-text strong {
            display: block;
            color: #fff;
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .brand-text span {
            font-size: 0.67rem;
            color: var(--admin-muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-admin-info {
            padding: 14px 20px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-avatar {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--admin-accent), var(--admin-accent2));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .admin-info-text { flex: 1; min-width: 0; }
        .admin-info-text strong {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-info-text span {
            font-size: 0.62rem;
            color: var(--admin-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--admin-muted);
            padding: 8px 8px 4px;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--admin-muted);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all .18s ease;
        }
        .admin-nav-link:hover {
            color: var(--admin-text);
            background: rgba(255,255,255,0.06);
        }
        .admin-nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(236,72,153,0.25), rgba(244,63,94,0.15));
            border-left: 3px solid var(--admin-accent);
        }
        .admin-nav-link i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }
        .nav-badge {
            margin-left: auto;
            font-size: 0.6rem;
            padding: 2px 7px;
            border-radius: 20px;
            background: rgba(236,72,153,0.2);
            color: var(--admin-accent);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid var(--admin-border);
        }
        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 9px 12px;
            border-radius: 8px;
            background: rgba(239,68,68,0.1);
            color: #ef4444;
            border: 1px solid rgba(239,68,68,0.2);
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .18s;
            cursor: pointer;
        }
        .btn-logout:hover {
            background: rgba(239,68,68,0.2);
            color: #ef4444;
            border-color: rgba(239,68,68,0.4);
        }

        /* ===== MAIN CONTENT ===== */
        .admin-main {
            margin-left: var(--admin-sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .admin-topbar {
            height: 60px;
            background: var(--admin-sidebar-bg);
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title {
            font-size: 0.88rem;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-title .breadcrumb-sep { color: var(--admin-muted); font-weight: 400; }
        .topbar-title .breadcrumb-page { color: var(--admin-accent); }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .topbar-time {
            font-size: 0.72rem;
            color: var(--admin-muted);
        }
        .btn-view-site {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(236,72,153,0.12);
            color: var(--admin-accent);
            border: 1px solid rgba(236,72,153,0.25);
            text-decoration: none;
            transition: all .18s;
        }
        .btn-view-site:hover {
            background: rgba(236,72,153,0.22);
            color: #f472b6;
        }

        /* ===== CONTENT AREA ===== */
        .admin-content {
            padding: 28px;
            flex: 1;
        }

        /* ===== ALERT ===== */
        .admin-alert {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-alert-success {
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.25);
            color: #34d399;
        }
        .admin-alert-error {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.25);
            color: #f87171;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 14px;
            padding: 20px;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 14px;
        }
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card .stat-label {
            font-size: 0.72rem;
            color: var(--admin-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== DATA TABLES ===== */
        .admin-table-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 14px;
            overflow: hidden;
        }
        .admin-table-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .admin-table-header h6 {
            margin: 0;
            font-size: 0.82rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
        }
        .admin-table thead th {
            background: rgba(255,255,255,0.03);
            padding: 12px 18px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--admin-muted);
            border-bottom: 1px solid var(--admin-border);
        }
        .admin-table tbody td {
            padding: 13px 18px;
            border-bottom: 1px solid var(--admin-border);
            color: var(--admin-text);
            vertical-align: middle;
        }
        .admin-table tbody tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover td { background: rgba(255,255,255,0.02); }

        /* ===== BADGES =====  */
        .risk-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .risk-high   { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3);  }
        .risk-medium { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .risk-low    { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }

        /* ===== INPUTS / FORMS ===== */
        .admin-input {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            color: var(--admin-text);
            font-size: 0.8rem;
            padding: 8px 12px;
        }
        .admin-input:focus {
            background: rgba(236,72,153,0.08);
            border-color: rgba(236,72,153,0.4);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(236,72,153,0.1);
            outline: none;
        }
        .admin-input::placeholder { color: var(--admin-muted); }

        .btn-admin-primary {
            background: linear-gradient(135deg, var(--admin-accent), var(--admin-accent2));
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 8px 18px;
            cursor: pointer;
            transition: opacity .18s, transform .18s;
        }
        .btn-admin-primary:hover { opacity: 0.88; transform: translateY(-1px); }

        .btn-admin-danger {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 6px;
            color: #f87171;
            font-size: 0.72rem;
            padding: 5px 10px;
            cursor: pointer;
            transition: all .18s;
        }
        .btn-admin-danger:hover { background: rgba(239,68,68,0.22); }

        .section-header {
            margin-bottom: 24px;
        }
        .section-header h4 {
            margin: 0 0 4px;
            font-size: 1.15rem;
            font-weight: 700;
            color: #fff;
        }
        .section-header p {
            margin: 0;
            font-size: 0.75rem;
            color: var(--admin-muted);
        }

        /* Pagination override */
        .pagination { margin: 0; }
        .page-link {
            background: var(--admin-card);
            border-color: var(--admin-border);
            color: var(--admin-muted);
            font-size: 0.72rem;
        }
        .page-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .page-item.active .page-link {
            background: var(--admin-accent);
            border-color: var(--admin-accent);
        }
    </style>
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="admin-sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                <div class="brand-icon"><i class="bi bi-shield-check"></i></div>
                <div class="brand-text">
                    <strong>SCM Admin</strong>
                    <span>Control Panel</span>
                </div>
            </a>
        </div>

        {{-- Admin Info --}}
        <div class="sidebar-admin-info">
            <div class="admin-avatar">{{ strtoupper(substr(session('admin_user_name','A'), 0, 1)) }}</div>
            <div class="admin-info-text">
                <strong>{{ session('admin_user_name', 'Admin') }}</strong>
                <span>{{ session('admin_user_email', '') }}</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>

            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-section-label mt-3">Manajemen Data</div>

            <a href="{{ route('admin.users') }}" class="admin-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Kelola User
                <span class="nav-badge">{{ \App\Models\User::count() }}</span>
            </a>

            <a href="{{ route('admin.countries') }}" class="admin-nav-link {{ request()->routeIs('admin.countries') ? 'active' : '' }}">
                <i class="bi bi-globe"></i> Kelola Negara
                <span class="nav-badge">{{ \App\Models\Country::count() }}</span>
            </a>

            <a href="{{ route('admin.ports') }}" class="admin-nav-link {{ request()->routeIs('admin.ports') ? 'active' : '' }}">
                <i class="bi bi-anchor"></i> Kelola Pelabuhan
                <span class="nav-badge">{{ \App\Models\Port::count() }}</span>
            </a>

            <a href="{{ route('admin.articles') }}" class="admin-nav-link {{ request()->routeIs('admin.articles') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Artikel Analisis
                <span class="nav-badge">{{ \App\Models\Article::count() }}</span>
            </a>

            <div class="nav-section-label mt-3">Quick Sync</div>

            <a href="{{ route('countries.sync') }}" class="admin-nav-link">
                <i class="bi bi-arrow-clockwise"></i> Sync Data Negara
            </a>
            <a href="{{ route('ports.sync') }}" class="admin-nav-link">
                <i class="bi bi-arrow-clockwise"></i> Sync Data Pelabuhan
            </a>

            <div class="nav-section-label mt-3">Navigasi Utama</div>

            <a href="{{ route('dashboard') }}" target="_blank" class="admin-nav-link">
                <i class="bi bi-box-arrow-up-right"></i> Buka Aplikasi Utama
            </a>
        </nav>

        {{-- Logout --}}
        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-left"></i> Logout Admin
                </button>
            </form>
        </div>

    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="admin-main">

        {{-- Top Bar --}}
        <header class="admin-topbar">
            <div class="topbar-title">
                <i class="bi bi-shield-check text-pink-400" style="color:#f472b6;"></i>
                Admin Panel
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-page">@yield('breadcrumb', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                <span class="topbar-time" id="adminClock"></span>
                <a href="{{ route('dashboard') }}" target="_blank" class="btn-view-site">
                    <i class="bi bi-eye"></i> Lihat Aplikasi
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="admin-content">
            @if(session('success'))
                <div class="admin-alert admin-alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="admin-alert admin-alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Live clock in topbar
        function updateClock() {
            const now = new Date();
            document.getElementById('adminClock').textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            }) + ' WIB';
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
    @stack('scripts')
</body>
</html>
