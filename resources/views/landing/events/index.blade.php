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
            a, a:hover{ color: #383939; text-decoration: none; }
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

            .card-picture-header {
                box-sizing: border-box;
                margin: 0 auto;
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
                height: 150px;
                border: solid 1px #EBECEC;
            }
            .card-header { position: relative ;}
            .card-header:hover .hover{
                display: flex;
            }
            .card-header .hover {
                width: 100%; height: 100%;
                position: absolute;
                top: 0; left: 0; bottom: 0; right: 0;
                background-color: rgba(56, 57, 56, .6);
                display: flex;
                justify-content: center;
                align-items: center;
                color: #F4F5F5;
                border-radius: 4px;
                font-size: 50px;
                display: none;
            }
            /* Event Cats */
            .list-cats {
                height: 78px; width: 100%;
                position: relative;
                display: flex;
                align-items: flex-end;
            }

            /* Event */
            .row.row-event { margin: -52px -5px 0 -5px; }

            .row.row-event .event-col { padding: 0 5px !important; }

            /* Event Date */
            .event-date {
            height: 78px; width: 100%;
            border: 2px solid #383938;
            border-radius: 4px;
            }

            .event-date-header,
            .event-date-body {
            width: 100%;
            position: relative;
            }

            .event-date-header {
            background-color: #383938;
            display: block;
            color: #F4F5F5;
            padding: 2px 0;
            }

            .event-date-body {
            text-transform: uppercase;
            padding: 4px;
            }
            .event-date-body span { display: block; }
            .event-date-body span:first-child { border-bottom: 1px solid rgba(56, 57, 56, .6); }


            .divider{
                background-image: url("/images/pattern-isaudavel-5-300.png");
            }

            .gray {
                background-color: #f4f5f4;
            }

        </style>

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('components.hotjar')

    </head>

    <body id="body">

        <div id="app">

            @include('landing.home.navbar')
            @include('landing.companies.header-blank')

            @section('landing-content')
            @show

            @include('landing.home.featured')

            @include('landing.home.footer')

        </div>

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>


        @section('scripts')
        @show

        <script type="text/javascript">
            $(document).ready(function(){
                //animated header class
                // $(window).scroll(function () {
                //     if ($(window).scrollTop() > 100) {
                //         $(".navbar-default").addClass("animated");
                //     } else {
                //         $(".navbar-default").removeClass('animated');
                //     }
                // });
            });
        </script>

    </body>
</html>
