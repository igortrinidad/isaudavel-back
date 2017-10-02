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

            ::selection { background-color: #6ec058; color: #fff; }

             /* Card With Background Pattern */
             .card.card-pattern {
                 background-image: url("/images/pattern-isaudavel-5-300.png");
                 min-height: 376px;
             }
            /* Card Activities */
            .card-activities {
                position: relative;
                min-height: 119.48px;
                max-height: 119.48px;
                padding: 0 !important
            }
            .card-activities .picture-circle.picture-circle-p {
                width: 48px; height: 48px;
            }
            .card-activities .card-body {
                padding-left: 35px;
                padding-right: 0;
            }
            .store-badge { margin: 0 auto; }
            /* Default Isaudavel Title */
            .is-title {
                border-left: 4px solid;
                border-bottom: 4px solid;
                display: inline-block;
                width: auto;
                border-radius: 4px;
                padding: 5px 10px 5px 10px;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 2rem !important;
            }

            .is-title-side {
                text-transform: uppercase;
                font-size: 1.5rem;
                font-weight: 700;
                text-align: center;
            }

            .is-title.default { border-color: #fff; color: #fff; }
            .is-title.primary { border-color: #6ec058; color: #6ec058; }
            .is-title.secondary { border-color: #f1562b; color: #f1562b; }

            /* Default Isaudavel Title For Activites Section */
            .is-activities-title {
                transform: rotate(-90deg);
                background: #f1562b;
                position: absolute;
                top: 41px; left: -42px;
                color: #fff;
                border-radius: 4px 4px 0 0;
                text-transform: uppercase;
                font-size: 14px;
                padding: 5px 5px 7px 5px;
                letter-spacing: .3rem;
                text-align: center;
            }

            .is-activities-title span { display: block; }

        </style>

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('components.hotjar')

    </head>

    <body id="body">

        @include('landing.home.video')
        @include('landing.home.navbar')
        @include('landing.companies.header-with-search')

        @section('content')
        @show

        @include('landing.home.featured')
        @include('landing.events.events', ['has_title' => true])
        @include('landing.home.recipes')
        <!-- @include('landing.home.user-activities') -->
        @include('landing.home.download')
        @include('landing.home.contact')
        @include('landing.home.footer')

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
