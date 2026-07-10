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
            <li class="nav-item dropdown me-3">

                <a
                    class="nav-link position-relative"
                    href="#"
                    data-bs-toggle="dropdown"
                >

                    <i class="bi bi-bell-fill fs-4"></i>

                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>
                        <h6 class="dropdown-header">
                            Notifikasi
                        </h6>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            🚢 Shipment SH001 mengalami keterlambatan
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            🌧 Cuaca buruk di Laut China Selatan
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            💵 Kurs USD naik 2%
                        </a>
                    </li>

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