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
        <meta property="fb:app_id" content="1854829291449231" />
        <meta property="og:locale" content="pt_BR">
        <meta property="og:url" content="https://weskd.com">
        <meta property="og:title" content="iSaudavel">
        <meta property="og:site_name" content="iSaudavel">
        <meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
        <meta property="og:image" content="https://isaudavel.com/logos/LOGO-1-02.png">
        <meta property="og:image:type" content="image/png">

        
        <!-- Fonts -->
        <!-- Lato -->
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ elixir('build/prelaunch/css/build_vendors_custom.css') }}">

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:583813,hjsv:5};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

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
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-70761422-7', 'auto');
          ga('send', 'pageview');


        </script>

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
