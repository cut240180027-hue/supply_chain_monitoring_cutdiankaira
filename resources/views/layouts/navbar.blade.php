<!-- ===========================
        NAVBAR
============================ -->

<nav class="navbar navbar-expand-lg navbar-light custom-navbar shadow-sm">

    <div class="container-fluid">

        <!-- Tombol Sidebar (Mobile) -->
        <button class="btn d-lg-none me-3" id="toggleSidebar">
            <i class="bi bi-list fs-3"></i>
        </button>

        <!-- Judul -->
        <div>
            <h4 class="fw-bold mb-0 text-dark">
                Global Supply Chain Monitoring
            </h4>

            <small class="text-muted">
                Dashboard Monitoring Risiko Ekspor & Impor
            </small>
        </div>

        <!-- Menu Kanan -->
        <ul class="navbar-nav ms-auto align-items-center">

            <!-- Search -->
            <li class="nav-item me-3">

                <div class="input-group">

                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control border-start-0"
                        placeholder="Cari shipment..."
                    >

                </div>

            </li>

            <!-- Notifikasi -->
            @php
                $unreadNotifications = \App\Models\Notification::where('is_read', false)->latest()->take(6)->get();
                $unreadCount = \App\Models\Notification::where('is_read', false)->count();
            @endphp
            <li class="nav-item dropdown me-3">

                <a
                    class="nav-link position-relative"
                    href="#"
                    data-bs-toggle="dropdown"
                >

                    <i class="bi bi-bell-fill fs-4" style="color: #EC4899;"></i>

                    @if($unreadCount > 0)
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;padding:3px 6px;">
                        {{ $unreadCount }}
                    </span>
                    @endif

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4" style="min-width: 290px; padding: 0; overflow:hidden;">

                    <li class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark" style="font-size:0.8rem;">Notifikasi</span>
                        @if($unreadCount > 0)
                            <span class="badge bg-danger-subtle text-danger" style="font-size:0.65rem;">{{ $unreadCount }} Baru</span>
                        @endif
                    </li>

                    <div style="max-height: 280px; overflow-y: auto;">
                        @forelse($unreadNotifications as $notif)
                            @php
                                $icon = 'bi-info-circle-fill text-primary';
                                if($notif->type === 'weather') $icon = 'bi-cloud-rain-fill text-info';
                                elseif($notif->type === 'port') $icon = 'bi-anchor-fill text-warning';
                                elseif($notif->type === 'geopolitical') $icon = 'bi-shield-fill-exclamation text-danger';
                                elseif($notif->type === 'delay') $icon = 'bi-exclamation-triangle-fill text-danger';
                            @endphp
                            <li>
                                <a class="dropdown-item py-2 px-3 border-bottom d-flex align-items-start gap-2" href="{{ $notif->shipment_id ? route('risk.show', $notif->shipment_id) : '#' }}" style="white-space: normal;">
                                    <span style="font-size:1.1rem; flex-shrink: 0;"><i class="bi {{ $icon }}"></i></span>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size:0.75rem;">{{ $notif->title }}</div>
                                        <div class="text-muted" style="font-size:0.68rem; margin-top:2px;">{{ $notif->message }}</div>
                                        <small class="text-muted" style="font-size:0.6rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <div class="text-center py-4 text-muted" style="font-size:0.75rem;">
                                <i class="bi bi-bell-slash d-block fs-4 mb-1" style="color: #fbcfe8;"></i>
                                Tidak ada notifikasi baru
                            </div>
                        @endforelse
                    </div>

                    @if($unreadCount > 0)
                        <li class="bg-light p-2 text-center border-top">
                            <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-decoration-none p-0 text-pink fw-bold" style="font-size:0.7rem; color:#DB2777;">
                                    Tandai Semua Dibaca
                                </button>
                            </form>
                        </li>
                    @endif

                </ul>

            </li>

            <!-- Profil -->
            <li class="nav-item dropdown">

                <a
                    class="nav-link dropdown-toggle d-flex align-items-center"
                    href="#"
                    data-bs-toggle="dropdown"
                >

                    <img
                        src="https://ui-avatars.com/api/?name=Guest&background=EC4899&color=fff"
                        width="45"
                        height="45"
                        class="rounded-circle shadow"
                    >

                    <span class="ms-2 fw-semibold">
                        Guest User
                    </span>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person-circle me-2"></i>
                            Profil
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear me-2"></i>
                            Pengaturan
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item text-danger" href="#">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>