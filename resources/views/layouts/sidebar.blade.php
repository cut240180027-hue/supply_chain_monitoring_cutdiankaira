<!-- Sidebar -->
<div class="sidebar">

    <!-- Logo -->
    <div class="logo text-center py-4">
        <i class="bi bi-globe-americas fs-1 text-white"></i>
        <h4 class="text-white mt-2 fw-bold">SCM</h4>
        <small class="text-white-50">
            Supply Chain Monitoring
        </small>
    </div>

    <!-- Menu -->
    <ul class="nav flex-column mt-4">

        <li class="nav-item mb-2">
            <a href="{{ route('dashboard') }}" class="nav-link active">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('shipment.index') }}" class="nav-link">
                <i class="bi bi-box-seam me-2"></i>
                Shipment
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('tracking.index') }}" class="nav-link">
                <i class="bi bi-geo-alt me-2"></i>
                Live Tracking
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('weather.index') }}" class="nav-link">
                <i class="bi bi-cloud-sun me-2"></i>
                Weather
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('currency.index') }}" class="nav-link">
                <i class="bi bi-currency-dollar me-2"></i>
                Currency
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('countries.index') }}" class="nav-link">
                <i class="bi bi-globe2 me-2"></i>
                Countries
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('economy.index') }}" class="nav-link">
                <i class="bi bi-bar-chart-line me-2"></i>
                Economy
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('news.index') }}" class="nav-link">
                <i class="bi bi-newspaper me-2"></i>
                News
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('risk.index') }}" class="nav-link">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Risk Score
            </a>
        </li>

    </ul>

    <!-- Footer Sidebar -->
    <div class="sidebar-footer text-center mt-auto py-3">

        <small class="text-white-50">
            Version 1.0
        </small>

    </div>

</div>