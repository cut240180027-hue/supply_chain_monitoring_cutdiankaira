@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <!-- ==========================
            PAGE HEADER
    =========================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Dashboard
            </h2>

            <p class="text-muted mb-0">
                Welcome to Global Supply Chain Monitoring Dashboard
            </p>
        </div>

        <button class="btn btn-pink shadow">
            <i class="bi bi-arrow-clockwise"></i>
            Refresh Data
        </button>

    </div>

    <!-- ==========================
            STATISTIC CARD
    =========================== -->

    <div class="row g-4">

        <!-- Total Shipment -->
        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="card dashboard-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Total Shipment
                            </small>

                            <h2 class="fw-bold mt-2 mb-1">
                                120
                            </h2>

                            <span class="text-success">
                                +15 Hari Ini
                            </span>

                        </div>

                        <div class="icon-box bg-primary">

                            <i class="bi bi-box-seam"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Active Shipment -->

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="card dashboard-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Active Shipment
                            </small>

                            <h2 class="fw-bold mt-2 mb-1">
                                97
                            </h2>

                            <span class="text-primary">
                                Live Tracking
                            </span>

                        </div>

                        <div class="icon-box bg-success">

                            <i class="bi bi-truck"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- High Risk -->

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="card dashboard-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                High Risk
                            </small>

                            <h2 class="fw-bold mt-2 mb-1">
                                15
                            </h2>

                            <span class="text-danger">
                                Alert
                            </span>

                        </div>

                        <div class="icon-box bg-danger">

                            <i class="bi bi-exclamation-triangle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Currency -->

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="card dashboard-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                USD / IDR
                            </small>

                            <h2 class="fw-bold mt-2 mb-1">
                                16.430
                            </h2>

                            <span class="text-warning">
                                +0.82%
                            </span>

                        </div>

                        <div class="icon-box bg-warning">

                            <i class="bi bi-currency-dollar"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ==========================
            MAP + WEATHER
    =========================== -->

    <div class="row mt-4 g-4">

        <!-- MAP -->

        <div class="col-xl-8">

            <div class="card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="bi bi-geo-alt-fill text-danger"></i>

                        Live Shipment Tracking

                    </h5>

                </div>

                <div class="card-body p-2">

                    <div id="map"></div>

                </div>

            </div>

        </div>

        <!-- WEATHER -->

        <div class="col-xl-4">

            <div class="card weather-card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="bi bi-cloud-sun-fill text-warning"></i>

                        Weather Monitoring

                    </h5>

                </div>

                <div class="card-body">

                    <div class="text-center">

                        <i class="bi bi-cloud-rain weather-icon"></i>

                        <h1 class="fw-bold mt-3">

                            28°C

                        </h1>

                        <h5>

                            Shanghai Port

                        </h5>

                    </div>

                    <hr>

                    <table class="table table-borderless">

                        <tr>

                            <td>Wind</td>

                            <td class="text-end">

                                15 km/h

                            </td>

                        </tr>

                        <tr>

                            <td>Humidity</td>

                            <td class="text-end">

                                76%

                            </td>

                        </tr>

                        <tr>

                            <td>Rain</td>

                            <td class="text-end">

                                40%

                            </td>

                        </tr>

                        <tr>

                            <td>Storm Risk</td>

                            <td class="text-end">

                                <span class="badge bg-danger">

                                    High

                                </span>

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>
        <!-- ==========================
            CHART
    =========================== -->

    <div class="row mt-4 g-4">

        <!-- Currency Chart -->
        <div class="col-xl-6">

            <div class="card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="bi bi-graph-up-arrow text-success"></i>
                        Currency Exchange Trend
                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="currencyChart" height="120"></canvas>

                </div>

            </div>

        </div>

        <!-- Risk Chart -->
        <div class="col-xl-6">

            <div class="card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart-fill text-danger"></i>
                        Risk Distribution
                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="riskChart" height="120"></canvas>

                </div>

            </div>

        </div>

    </div>

    <!-- ==========================
            NEWS + SHIPMENT
    =========================== -->

    <div class="row mt-4 g-4">

        <!-- NEWS -->

        <div class="col-xl-4">

            <div class="card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="bi bi-newspaper text-primary"></i>
                        Latest News
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <h6 class="fw-bold">
                            🚢 Port Congestion in Singapore
                        </h6>

                        <small class="text-muted">
                            Increased vessel queues may affect shipment schedules.
                        </small>

                    </div>

                    <hr>

                    <div class="mb-3">

                        <h6 class="fw-bold">
                            🌧 Heavy Rain in Shanghai
                        </h6>

                        <small class="text-muted">
                            Severe weather expected during the next 48 hours.
                        </small>

                    </div>

                    <hr>

                    <div>

                        <h6 class="fw-bold">
                            💵 USD Strengthens Against IDR
                        </h6>

                        <small class="text-muted">
                            Exchange rate continues to rise this week.
                        </small>

                    </div>

                </div>

            </div>

        </div>

        <!-- TABLE -->

        <div class="col-xl-8">

            <div class="card">

                <div class="card-header bg-white d-flex justify-content-between">

                    <h5 class="mb-0">
                        <i class="bi bi-box-seam-fill text-success"></i>
                        Latest Shipments
                    </h5>

                    <button class="btn btn-sm btn-pink">

                        View All

                    </button>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>Country</th>
                                    <th>Port</th>
                                    <th>Status</th>
                                    <th>ETA</th>
                                    <th>Risk</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>SH001</td>
                                    <td>China</td>
                                    <td>Shanghai</td>

                                    <td>

                                        <span class="badge bg-success">

                                            On Schedule

                                        </span>

                                    </td>

                                    <td>12 Jul 2026</td>

                                    <td>

                                        <span class="badge bg-warning">

                                            Medium

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <td>SH002</td>
                                    <td>Japan</td>
                                    <td>Tokyo</td>

                                    <td>

                                        <span class="badge bg-primary">

                                            Sailing

                                        </span>

                                    </td>

                                    <td>14 Jul 2026</td>

                                    <td>

                                        <span class="badge bg-success">

                                            Low

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <td>SH003</td>
                                    <td>Germany</td>
                                    <td>Hamburg</td>

                                    <td>

                                        <span class="badge bg-danger">

                                            Delayed

                                        </span>

                                    </td>

                                    <td>18 Jul 2026</td>

                                    <td>

                                        <span class="badge bg-danger">

                                            High

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <td>SH004</td>
                                    <td>USA</td>
                                    <td>Los Angeles</td>

                                    <td>

                                        <span class="badge bg-info">

                                            Loading

                                        </span>

                                    </td>

                                    <td>16 Jul 2026</td>

                                    <td>

                                        <span class="badge bg-warning">

                                            Medium

                                        </span>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
        <!-- ==========================
            SCRIPT
    =========================== -->

</div>

@endsection

@push('scripts')

<script>

document.addEventListener("DOMContentLoaded", function () {

    /*=================================
        LEAFLET MAP
    =================================*/

    var map = L.map('map').setView([1.3521, 103.8198], 4);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{

        attribution:'© OpenStreetMap'

    }).addTo(map);

    /*==============================
        SAMPLE MARKERS
    ==============================*/

    L.marker([31.2304,121.4737])
        .addTo(map)
        .bindPopup("<b>Shanghai Port</b><br>Shipment SH001");

    L.marker([35.6762,139.6503])
        .addTo(map)
        .bindPopup("<b>Tokyo Port</b><br>Shipment SH002");

    L.marker([53.5511,9.9937])
        .addTo(map)
        .bindPopup("<b>Hamburg Port</b><br>Shipment SH003");

    L.marker([1.3521,103.8198])
        .addTo(map)
        .bindPopup("<b>Singapore Port</b>");



    /*=================================
        CURRENCY CHART
    =================================*/

    const currencyChart = new Chart(

        document.getElementById('currencyChart'),

        {

            type:'line',

            data:{

                labels:[
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul'
                ],

                datasets:[{

                    label:'USD / IDR',

                    data:[
                        15800,
                        15950,
                        16020,
                        16100,
                        16250,
                        16320,
                        16430
                    ],

                    borderColor:'#EC4899',

                    backgroundColor:'rgba(236,72,153,.15)',

                    fill:true,

                    tension:.4

                }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false

            }

        }

    );



    /*=================================
        RISK CHART
    =================================*/

    const riskChart = new Chart(

        document.getElementById('riskChart'),

        {

            type:'doughnut',

            data:{

                labels:[

                    'Low',

                    'Medium',

                    'High'

                ],

                datasets:[{

                    data:[

                        65,

                        25,

                        10

                    ],

                    backgroundColor:[

                        '#22C55E',

                        '#FACC15',

                        '#EF4444'

                    ]

                }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false

            }

        }

    );

});

</script>

@endpush