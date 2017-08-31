<!DOCTYPE html>
<html class="no-js">
    <head>

        @include('components.seo-opengraph')

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!-- Fonts -->
        <!-- Lato -->
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ elixir('build/landing/css/build_vendors_custom.css') }}">
        <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('components.hotjar')

    </head>

    <body id="body">

        <style media="screen">
            .contact-signup {
                background-image: url('https://weplaces.com.br/build/landing/weplaces/road_california.jpg');
                min-height: 100vh;
                padding: 0;
            }

            .entry-field label{
                color: #fff;
                font-weight: 400;
                display: block;
                margin-top: 10px;
            }
            .entry-field input{
                color: #757575;
                font-weight: 400;
            }
           .contact-signup {
                position: relative;
                background-position: center center;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                background-size: cover;
           }
           .contact.gradient-overlay-black .overlay-inner {
                min-height: 100vh;
                background: -webkit-linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
                background: -o-linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
                background: -moz-linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
                background: linear-gradient(rgba(0, 0, 0, 0.6) 90%, #212121);
           }

           .contact-form input, .contact-form textarea,
           .contact-form { width: 100%; }

           .block-center { margin: 0 auto !important; float: none; }

           .contact-form input, .contact-form textarea{ background-color: #fff; }
           .form-control{ height: 46px; }
           option{ padding: 0px 2px 1px 10px; }

        </style>


        <section id="company-signup" class="contact contact-signup section gradient-overlay-black">
          <div class="overlay-inner">
            <div class="container">
              <!--|Section Header|-->
              <header class="section-header text-center wow flipInX" data-wow-delay=".15s">
                <div class="row">
                  <div class="col-6 block-center">
                    <a href="/">
                      <img src="/logos/LOGO-1-01.PNG" width="200px" style="padding-top: 70px; "/>
                    </a>
                    <h1 class="section-title" >Obrigado por se cadastrar!</h1>
                    <h4 class="f-300 m-t-10 p-20" style="color: #fff; margin-bottom: 40px;">Você receberá um e-mail com as informações necessárias.</h4>
                  </div>
                </div>
              </header> <!--|End Section Header|-->

            </div>
          </div>
        </section>

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

    </body>
</html>
