<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>iSaudavel - Para Você</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        @include('components.opengraph')

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

        
        <section id="contact" class="contact contact-signup section gradient-overlay-black">
          <div class="overlay-inner">
            <div class="container">
              <!--|Section Header|-->
              <header class="section-header text-center wow flipInX" data-wow-delay=".15s">
                <div class="row">
                  <div class="col-6 block-center">
                    <img src="/logos/LOGO-1-01.PNG" width="200px" style="padding-top: 70px; "/>
                    <h1 class="section-title" >Você esta quase lá!</h1>
                    <h4 class="f-300 m-t-10 p-20" style="color: #fff; margin-bottom: 40px;">Falta pouco para você oferecer uma experiência única para seus clientes e vender mais.</h4>
                  </div>
                </div>
              </header> <!--|End Section Header|-->

              <div class="row">
                <div class="col-md-8 block-center">
                  <!--|Contact Form|-->
                  <form class="contact-form" method="POST" action="/sendSignupForm">
                  {!! csrf_field() !!}
                    <!--|Action Message|-->
                    <div class="entry-field">
                       <label>Nome completo</label>
                       <input class="form-control" name="name" placeholder="Nome completo" required type="text">
                    </div>
                    <div class="entry-field">
                       <label>CPF</label>
                       <input class="form-control" name="cpf" placeholder="CPF" required type="text">
                    </div>
                    <div class="entry-field">
                       <label>Endereço completo</label>
                       <input class="form-control" name="address" placeholder="Endereço completo" required type="text">
                    </div>
                    <div class="entry-field">
                       <label>Telefone</label>
                       <input class="form-control" name="phone" placeholder="Telefone com ddd" required type="text">
                    </div>
                    <div class="entry-field">
                       <label>Email</label>
                       <input class="form-control" name="email" placeholder="email@exemplo.com" required type="email">
                    </div>
                    <div class="entry-field">
                       <label>Empresa</label>
                       <input class="form-control" name="company_name" placeholder="Nome da empresa" required type="text">
                    </div>
                    <div class="entry-field">
                       <label>Plano</label>
                       <select class="form-control" name="plan_selected">
                            <option>Anual R$39,00 / mês - R$468,00 por 12 meses</option>
                            <option>Semestral R$49,00 / mês - R$294,00 por 6 meses</option>
                            <option>Mensal R$79,00 / mês - R$79,00 por mês</option>
                       </select>
                    </div>

                    <div class="text-center m-t-30">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Cadastrar</button>
                    </div>
                  </form> <!--|End Contact Form|-->

                  <br><br><br><br><br>
                </div>
              </div>
            </div>
          </div>
        </section>

        <br><br><br>

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

        <script>

          function initAutocomplete() {


            var autocomplete = new google.maps.places.Autocomplete(
            (
                document.getElementById('autocomplete')), {
              types: ['(cities)'],
              language: 'pt-BR',
              componentRestrictions: {'country': 'br'}
            });


            autocomplete.addListener('place_changed', function() {
              var place = autocomplete.getPlace();
                if (place.geometry) {

                    var city = document.getElementById('city');
                    city.setAttribute('value', place.name)

                    var lat = document.getElementById('lat');
                    lat.setAttribute('value', place.geometry.location.lat())

                    var lng = document.getElementById('lng');
                    lng.setAttribute('value', place.geometry.location.lng())

                } else {
                    document.getElementById('autocomplete').placeholder = 'Enter a city';
                }

            });
          }

            Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#search-area',
                data: {
                    city: '',
                    category: null,
                    categories: [],
                    pathSearch: false
                },
                mounted: function() {

                    var url = new URL(window.location.href);
                    var category = url.searchParams.get("category");
                    var city = url.searchParams.get("city");
                    this.category = category
                    this.city = city
                    if (window.location.pathname === '/new-landing/buscar') {
                        this.pathSearch = true
                    }

                    this.getCategories();

                },
                methods: {
                  getCategories: function(){
                      let that = this
                
                        this.$http.get('/api/company/category/list').then(response => {

                            that.categories = response.body;

                        }, response => {
                            // error callback
                        });
                  },

                  setCategory: function(ev){

                    var city = document.getElementById('category');
                    city.setAttribute('value', ev.target.value)
                      
                  },
                }

            })

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
             async defer></script>

        <!-- GOOGLE ANALYTICS -->
        @include('components.googleanalytics')

    </body>
</html>
