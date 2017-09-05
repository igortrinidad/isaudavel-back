<!DOCTYPE html>
<html class="no-js">
    <head>

        @include('components.seo-opengraph')

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>
        <!-- Styles -->
        <link rel="stylesheet" href="{{ elixir('build/landing/css/build_vendors_custom.css') }}">

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

           h1, h2, h3, h4 ,h5 {
                color: #fff;
           }

        </style>


        <section id="company-signup" class="contact contact-signup section gradient-overlay-black">
          <div class="overlay-inner">
            <div class="container">
              <!--|Section Header|-->
              <header class="section-header text-center wow flipInX" data-wow-delay=".15s">
                <div class="row">
                  <div class="col-6 block-center">
                    <a href="/">
                      <img src="/logos/LOGO-1-01.png" width="200px" style="padding-top: 70px; "/>
                    </a>
                    <h1 class="section-title" >Você esta quase lá!</h1>
                    <h4 class="f-300 m-t-10 p-20 m-b-30">Falta pouco para você utilizar o melhor da tecnologia para cuidar da sua saúde, bem estar e estética!</h4>
                  </div>
                </div>
              </header> <!--|End Section Header|-->

              <div class="row">
              <div class="col-md-8 block-center">

                {{--Alert display--}}
                @include('flash::message')

                  <!--|Contact Form|-->
                  <form class="contact-form" method="POST" action="{{route('landing.client.send.sigup')}}">
                  {!! csrf_field() !!}
                    <!--|Action Message|-->
                    <div class="entry-field">
                       <label>Nome</label>
                       <input class="form-control" name="name" placeholder="Nome"  value="{{ old('name') }}" required type="text">
                    </div>
                      <div class="entry-field">
                          <label>Sobrenome</label>
                          <input class="form-control" name="last_name" placeholder="Sobrenome" value="{{ old('last_name') }}" required type="text" >
                      </div>

                    <div class="entry-field">
                       <label>Email</label>
                       <input class="form-control" name="email" value="{{ old('email') }}" placeholder="email@exemplo.com" required type="email">
                    </div>

                    <div class="entry-field">
                       <label>Telefone</label>
                        <input class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Telefone com ddd" required type="text">
                    </div>

                    <hr class="m-t-30">

                      {{--Hidden inputs to send vue data on request--}}
                      <input type="hidden" id="terms" name="terms" v-model="terms">

                    <div class="checkbox-group">
                        <label class="checkbox">
                        <input type="checkbox" class="wp-checkbox-reset wp-checkbox-input" v-model="terms_accepted" @change="handleTerms">
                        <div class="wp-checkbox-reset wp-checkbox-inline wp-checkbox">
                        </div>
                        <span class="wp-checkbox-text" style="color: #fff">Aceito os <a href="{{ route('landing.terms')}}" target="blank">Termos de Uso</a> e <a href="{{ route('landing.privacy')}}" target="blank">Política de privacidade</a></span></label>
                    </div>

                    <div class="text-center m-t-30">
                        <button class="btn btn-lg btn-primary btn-block" type="submit" :disabled="!terms_accepted">Cadastrar</button>
                    </div>
                  </form> <!--|End Contact Form|-->

                  <br><br><br><br><br>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

        <script>

            Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#company-signup',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                filters: {
                    'formatCurrency': function(value){
                            return accounting.formatMoney(parseFloat(value))
                        }
                },
                data: {
                    city: '',
                    category: null,
                    categories: [],
                    pathSearch: false,
                    form: {
                    },
                    total: 0,
                    professional_numbers: 0,
                    categories_parsed: [],
                    terms_accepted: false,
                    terms: {}
                },
                mounted: function() {

                },
                methods: {
                    handleTerms: function () {
                        var terms = {}
                        if (this.terms_accepted) {
                            var timestamp = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');

                            terms = {
                                accepted: this.terms_accepted,
                                accepted_at: timestamp
                            }

                        }

                        if (!this.terms_accepted) {
                            terms = {
                                accepted: this.terms_accepted,
                                accepted_at: null
                            }
                        }

                        this.terms = JSON.stringify(terms);
                    }
                }

            })

        </script>

    </body>
</html>
