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

        <!-- Live Tracking -->
        <li class="nav-item mb-2">

            <a href="{{ route('tracking.index') }}"
               class="nav-link {{ request()->routeIs('tracking.*') ? 'active' : '' }}">

                <i class="bi bi-geo-alt me-2"></i>

                Live Tracking

            </a>

        </li>

        <!-- Weather -->
        <li class="nav-item mb-2">

            <a href="{{ route('weather.index') }}"
               class="nav-link {{ request()->routeIs('weather.*') ? 'active' : '' }}">

                <i class="bi bi-cloud-sun me-2"></i>

                Weather

            </a>

        </li>

        <!-- Currency -->
        <li class="nav-item mb-2">

            <a href="{{ route('currency.index') }}"
               class="nav-link {{ request()->routeIs('currency.*') ? 'active' : '' }}">

                <i class="bi bi-currency-dollar me-2"></i>

                Currency

            </a>

        </li>

        <!-- Countries -->
        <li class="nav-item mb-2">

            <a href="{{ route('countries.index') }}"
               class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}">

                <i class="bi bi-globe2 me-2"></i>

                Countries

            </a>

        </li>

        <!-- Ports -->
        <li class="nav-item mb-2">

            <a href="{{ route('ports.index') }}"
               class="nav-link {{ request()->routeIs('ports.*') ? 'active' : '' }}">

                <i class="bi bi-anchor me-2"></i>

                Ports

            </a>

        </li>

        <!-- Suppliers -->
        <li class="nav-item mb-2">

            <a href="{{ route('suppliers.index') }}"
               class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">

                <i class="bi bi-building me-2"></i>

                Suppliers

            </a>

        </li>

        <!-- Economy -->
        <li class="nav-item mb-2">

            <a href="{{ route('economy.index') }}"
               class="nav-link {{ request()->routeIs('economy.*') ? 'active' : '' }}">

                <i class="bi bi-bar-chart-line me-2"></i>

                Economy

            </a>

        </li>

        <!-- News -->
        <li class="nav-item mb-2">

            <a href="{{ route('news.index') }}"
               class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">

                <i class="bi bi-newspaper me-2"></i>

                News

            </a>

        </li>

        <!-- Risk Score -->
        <li class="nav-item mb-2">

            <a href="{{ route('risk.index') }}"
               class="nav-link {{ request()->routeIs('risk.*') ? 'active' : '' }}">

                <i class="bi bi-shield-exclamation me-2"></i>

                Risk Score

            </a>

        </li>

        <!-- Country Dashboard -->
        <li class="nav-item mb-2">
            <a href="{{ route('country-dashboard.index') }}"
               class="nav-link {{ request()->routeIs('country-dashboard.*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>
                Country Dashboard
            </a>
        </li>

        <!-- Country Comparison -->
        <li class="nav-item mb-2">
            <a href="{{ route('comparison.index') }}"
               class="nav-link {{ request()->routeIs('comparison.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right me-2"></i>
                Country Comparison
            </a>
        </li>

        <!-- Data Visualization -->
        <li class="nav-item mb-2">
            <a href="{{ route('visualizations.index') }}"
               class="nav-link {{ request()->routeIs('visualizations.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up me-2"></i>
                Data Visualization
            </a>
        </li>

        <!-- Watchlist -->
        <li class="nav-item mb-2">
            <a href="{{ route('watchlist.index') }}"
               class="nav-link {{ request()->routeIs('watchlist.*') ? 'active' : '' }}">
                <i class="bi bi-star-fill text-warning me-2"></i>
                Watchlist (Favorit)
            </a>
        </li>

        <!-- Admin Control Panel -->
        <li class="nav-item mb-2">
            <a href="{{ route('admin.login') }}"
               class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill me-2"></i>
                Admin Panel
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