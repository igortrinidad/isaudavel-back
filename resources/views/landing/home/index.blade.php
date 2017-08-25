<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>iSaudavel - A sua saúde em boas mãos</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!-- OPENGGRAPH -->
        @include('landing.opengraph')

        <!-- Fonts -->
        <!-- Lato -->
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ elixir('build/landing/css/build_vendors_custom.css') }}">

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('landing.hotjar')

    </head>

    <body id="body">

        @include('landing.home.video')
        @include('landing.home.navbar')
        @include('landing.companies.header-with-search')
        @include('landing.home.about')
        @include('landing.home.featured')
        @include('landing.home.footer')

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

        <!-- GOOGLE ANALYTICS -->
        @include('landing.googleanalytics')


        @section('scripts')
        @show

    </body>
</html>
