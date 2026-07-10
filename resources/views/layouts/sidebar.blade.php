<!-- Sidebar -->
<div class="sidebar">

    <!-- Logo -->
    <div class="logo text-center py-4">

        <i class="bi bi-globe-americas fs-1 text-white"></i>

        <h4 class="text-white fw-bold mt-2">
            SCM
        </h4>

        <small class="text-white-50">
            Supply Chain Monitoring
        </small>

    </div>

    <!-- Menu -->
    <ul class="nav flex-column mt-4">

        <!-- Dashboard -->
        <li class="nav-item mb-2">

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                <i class="bi bi-speedometer2 me-2"></i>

                Dashboard

            </a>

        </li>

        <!-- Shipment -->
        <li class="nav-item mb-2">

            <a href="{{ route('shipments.index') }}"
               class="nav-link {{ request()->routeIs('shipments.*') ? 'active' : '' }}">

                <i class="bi bi-box-seam me-2"></i>

                Shipment

            </a>

        </li>

        <!-- Menu lainnya (sementara belum aktif) -->

        <li class="nav-item mb-2">

            <a href="#" class="nav-link">

                <i class="bi bi-geo-alt me-2"></i>

                Live Tracking

                <span class="badge bg-light text-dark float-end">
                    Soon
                </span>

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="#" class="nav-link">

                <i class="bi bi-cloud-sun me-2"></i>

                Weather

                <span class="badge bg-light text-dark float-end">
                    Soon
                </span>

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="#" class="nav-link">

                <i class="bi bi-currency-dollar me-2"></i>

                Currency

                <span class="badge bg-light text-dark float-end">
                    Soon
                </span>

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="#" class="nav-link">

                <i class="bi bi-globe2 me-2"></i>

                Countries

                <span class="badge bg-light text-dark float-end">
                    Soon
                </span>

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="#" class="nav-link">

                <i class="bi bi-bar-chart-line me-2"></i>

                Economy

                <span class="badge bg-light text-dark float-end">
                    Soon
                </span>

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="#" class="nav-link">

                <i class="bi bi-newspaper me-2"></i>

                News

                <span class="badge bg-light text-dark float-end">
                    Soon
                </span>

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="#" class="nav-link">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Risk Score

                <span class="badge bg-light text-dark float-end">
                    Soon
                </span>

            </a>

        </li>

    </ul>

    <!-- Footer -->

    <div class="sidebar-footer text-center py-4">

        <small class="text-white-50">

            Version 1.0

        </small>

    </div>

</div>