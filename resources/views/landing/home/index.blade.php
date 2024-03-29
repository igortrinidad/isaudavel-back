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

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('components.hotjar')

    </head>

    <body id="body">

        @include('landing.home.video')
        @include('landing.home.navbar')

        @section('content')
        @show

        @include('landing.home.featured')
        @include('landing.home.articles')
        @include('landing.home.recipes')
        @include('landing.home.events')
        @include('landing.home.download')
        @include('landing.home.contact')
        @include('landing.home.footer')

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

        @section('scripts')
        @show

    </body>
</html>
