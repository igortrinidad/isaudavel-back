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
        <link rel="stylesheet" href="{{ elixir('build/prelaunch/css/build_vendors_custom.css') }}">

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('landing.hotjar')

    </head>

    <body id="body">

        <div id="app">

        @include('landing.home.navbar')
        @include('landing.home.header')
        @include('landing.home.about')
        @include('landing.home.footer')

        </div>


        <!-- Js -->
        <script src="{{ elixir('build/prelaunch/js/build_vendors_custom.js') }}"></script>

        <!-- GOOGLE ANALYTICS -->
        @include('landing.googleanalytics')

        <script type="text/javascript">

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#app',
                data: {
                },
                mounted: function() {

                    console.log('Vue rodando no index');
                },
                methods: {
                }

            })
        </script>



        @section('scripts')
        @show
        
    </body>
</html>
