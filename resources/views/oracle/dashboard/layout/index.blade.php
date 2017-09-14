<!DOCTYPE html>
<html class="no-js">
<head>

    @include('components.seo-opengraph')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
    <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>
    <meta name="google-site-verification" content="Ed4IGd5eqro1sXT8-Cz5eYyKFThT078JpfWLnQP3-VQ" />

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir('build/landing/css/build_vendors_custom.css') }}">

    <style media="screen">
        html, body {
            overflow-x: hidden !important;
        }

        @media (max-width: 768px) {
            .navbar-default.navbar-fixed-top.animated .navbar-toggle,
            .navbar-default .navbar-toggle:hover,
            .navbar-default .navbar-toggle:focus { background-color: #71c158 !important; }

            .navbar-default .navbar-toggle { border-color: #fff; }
            .navbar-default .navbar-toggle .icon-bar { background-color: #fff; }
            .navbar-default .navbar-collapse { border-color: #71c158; }
            .navbar-default { background-color: #71c158 !important; }
            .navbar-default.navbar-fixed-top.animated { background: #fff !important; }
        }

        .invoice-title h2, .invoice-title h3 {
            display: inline-block;
        }

        .table-vmiddle td {
            vertical-align: middle !important;
        }

        .page-title h2, .page-title h3 {
            display: inline-block;
        }

    </style>

    <!-- Hotjar Tracking Code for https://isaudavel.com -->
   @include('components.hotjar')

</head>

<body id="body">

@include('oracle.dashboard.layout.navbar')

@section('content')
@show

@include('landing.home.footer')

<!-- Js -->
<script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

@section('scripts')
@show

</body>
</html>
