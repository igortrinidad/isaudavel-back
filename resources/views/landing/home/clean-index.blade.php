<!DOCTYPE html>
<html class="no-js">
    <head>
    
        @include('components.seo-opengraph')

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!-- Styles -->
        <link rel="stylesheet" href="{{ elixir('build/landing/css/build_vendors_custom.css') }}">

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('components.hotjar')

    </head>

    <body id="body">

        @include('landing.home.navbar')
        @include('landing.companies.header-blank')
        
        @section('content')
        @show
        
        @include('landing.home.footer')

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

        <!-- GOOGLE ANALYTICS -->
        @include('components.googleanalytics')


        @section('scripts')
        @show

    </body>
</html>
