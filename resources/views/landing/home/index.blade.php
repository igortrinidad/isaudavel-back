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

            /* Isaudavel Swiper Arrow Default */
            .is-swiper-button-default.swiper-button-prev,
            .is-swiper-button-default.swiper-button-next {
                background-image: none;
                color: #f1562b;
                padding: 0 !important;
                display: inline-block;
                width: auto; height: auto;
            }

            .is-swiper-button-default.arrow-top { top: 50%; margin-top: -10px; }

            .is-swiper-button-default.swiper-button-disabled { opacity: .2; }

            .is-swiper-button-default.arrow-ls { font-size: 50px; }
            .is-swiper-button-default.arrow-ms { font-size: 30px; }
            .is-swiper-button-default.arrow-xs { font-size: 20px; }

            .is-swiper-button-default.swiper-button-prev { left: 10px; }
            .is-swiper-button-default.swiper-button-next { right: 10px; }

            .is-swiper-button-default.arrow-ms.swiper-button-prev { left: 20px; }
            .is-swiper-button-default.arrow-ms.swiper-button-next { right: 20px; }
            /* Isaudavel Icons */
            .is-icon {
                background-repeat: no-repeat;
                display: inline-block;
                background-size: cover;
                background-color: transparent;
                width: 40px; height: 36px;
                margin-left: 10px;
            }
            .is-icon.is-icon-events  { background-image: url("/icons/icon_events.png"); }
            .is-icon.is-icon-recipes { background-image: url("/icons/icon_recipes.png"); }

            /* Card With Background Pattern */
            .card.card-pattern {
                background-image: url("/images/pattern-isaudavel-5-300.png");
                min-height: 350px;
            }
            /* Card Activities */
            .card-activities {
                position: relative;
                min-height: 180px;
                max-height: 180px;
                padding: 0 !important
            }
            .card-activities .picture-circle.picture-circle-p {
                width: 60px; height: 60px;
            }
            .card-activities .card-body {
                padding-left: 50px;
                padding-right: 0;
            }
            .store-badge { margin: 0 auto; }
            /* Default Isaudavel Title */

            .is-title {
                border-left: 4px solid;
                border-bottom: 4px solid;
                display: inline-flex;
                align-items: center;
                width: auto;
                border-radius: 4px;
                padding: 5px 10px 5px 10px;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 2rem !important;
                position: relative;
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
                top: 66px; left: -66px;
                color: #fff;
                border-radius: 4px 4px 0 0;
                text-transform: uppercase;
                font-size: 16px;
                padding: 5px 10px 7px 10px;
                letter-spacing: .2rem;
                text-align: center;
                font-weight: 300;
                width: 180px;
            }

            .is-activities-title small {
                font-size: 12px;
                display: block;
                color: inherit;
                font-weight: 700 !important;
            }

            .is-activities-title span { display: block; }

            /* Isaudavel Box */
            .is-box {
                border: 1px solid #efefef;
                border-radius: 4px;
                padding: 10px 30px;
                height: 160px;
            }
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
