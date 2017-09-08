<!DOCTYPE html>
<html class="no-js" lang="pt-br">
    <head>
    
        @include('components.seo-opengraph')

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

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


        @section('scripts')
        @show

        <script type="text/javascript">
            $(document).ready(function(){
                //animated header class
                $(window).scroll(function () {
                    if ($(window).scrollTop() > 100) {
                        $(".navbar-default").addClass("animated");
                    } else {
                        $(".navbar-default").removeClass('animated');
                    }
                });
            });
        </script>

    </body>
</html>
