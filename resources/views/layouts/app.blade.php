<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>@yield('title', 'Supply Chain Monitoring')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS Project -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- CSS dari setiap halaman -->
    @stack('styles')

</head>

<body>

<div class="wrapper">

    @include('layouts.sidebar')

    <div class="main-content">

        @include('layouts.navbar')

        <div class="container-fluid mt-4">

            @yield('content')

        </div>

    </div>

</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script dari setiap halaman -->
@stack('scripts')

</body>
</html>