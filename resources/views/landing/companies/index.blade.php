<!DOCTYPE html>
<html class="no-js" lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>iSaudavel - A sua saúde em boas mãos</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        @include('components.opengraph')

        <!-- Fonts -->
        <!-- Lato -->
        <!-- <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'> -->
        <link rel="stylesheet" href="/css/lato.css">

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

        </style>

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('components.hotjar')

    </head>

    <body id="body">

        <div id="app">

            @include('landing.home.navbar')
            @if($header_with_search)
                @include('landing.companies.header-with-search')
            @else
                @include('landing.companies.header-blank')
            @endif

            @section('landing-content')
            @show

            @include('landing.home.footer')

        </div>

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

        <!-- GOOGLE ANALYTICS -->
        @include('components.googleanalytics')


        @section('scripts')
        @show

    </body>
</html>
