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
        {{--@include('components.hotjar')--}}

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
                      <img src="/logos/LOGO-1-01.png" width="200px" style="padding-top: 70px; "/>
                    </a>
                    <h1 class="section-title" >Você esta quase lá!</h1>
                    <h4 class="f-300 m-t-10 p-20" style="color: #fff; margin-bottom: 40px;">Falta pouco para você utilizar o melhor da tecnologia para promover a saúde de seus clientes e ajudá-los a atingir seus objetivos!</h4>
                  </div>
                </div>
              </header> <!--|End Section Header|-->

              <div class="row">
                <div class="col-md-8 block-center">

                {{--Alert display--}}
                @include('flash::message')

                  <!--|Contact Form|-->
                  <form class="contact-form" id="signup-form" method="POST" action="{{route('landing.professionals.send-signup-form')}}">
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
                       <label>CPF</label>
                       <input class="form-control" name="cpf"  value="{{ old('cpf') }}" placeholder="CPF" required type="text" >
                    </div>
                   <div class="entry-field">
                       <label>Email</label>
                       <input class="form-control" name="email" value="{{ old('email') }}" placeholder="email@exemplo.com" required type="email">
                    </div>
                    <div class="entry-field">
                       <label>Telefone</label>
                       <input class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Telefone com ddd" required type="text">
                    </div>
                    <div class="entry-field">
                       <label>Empresa</label>
                       <input class="form-control" name="company_name" value="{{ old('company_name') }}" placeholder="Nome da empresa" required type="text">
                    </div>
                    <div class="entry-field">
                       <label>Website</label>
                        <input class="form-control" name="website" value="{{ old('website') }}" placeholder="Informe o website da empresa" />
                   </div>
                    <div class="entry-field">
                       <label>Endereço</label>
                        <input class="form-control" id="autocomplete" placeholder="Informe o endereço da empresa" />
                   </div>

                    <div class="entry-field">
                       <label>Especialidades (R$37,90 / especialidade)</label>
                       <multiselect
                        v-model="category"
                        :options="categories"
                        :label="'name'"
                        :multiple="true"
                        placeholder="Selecione ao menos uma categoria"
                        @input="calcValue"
                        >
                      </multiselect>
                    </div>

                    <div class="entry-field">
                       <label>Quantidade de profissionais (R$17,90 / profissional)</label>
                       <input class="form-control" name="professionals" placeholder="" v-model="professionals" required type="number" @blur="calcValue()">
                    </div>

                    <hr class="m-t-30">

                    <div class="entry-field text-center">
                       <label>Valor total</label>
                       <h1 class="text-center">@{{total | formatCurrency}}</h1>
                    </div>

                      {{--Hidden inputs to send vue data on request--}}
                      <input type="hidden" id="categories" name="categories" v-model="categories_parsed">
                      <input type="hidden" id="city" name="city" value="{{ old('city') }}">
                      <input type="hidden" id="state" name="state" value="{{ old('state') }}">
                      <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
                      <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">
                      <input type="hidden" id="address" name="address" value="{{ old('address') }}">
                      <input type="hidden" id="total" name="total" value="{{ old('total') }}" v-model="total">

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

        <!-- Js -->
        <script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

        <script>



            accounting.settings = {
    currency: {
        symbol : "R$ ",   // default currency symbol is '$'
        format: "%s%v", // controls output: %s = symbol, %v = value/number (can be object: see below)
        decimal : ",",  // decimal point separator
        thousand: ".",  // thousands separator
        precision : 2   // decimal places
    },
    number: {
        precision: 2,  // default precision on numbers is 0
        thousand: ".",
        decimal : ","
    }
}

            function initAutocomplete() {

                //prevent form submit on enter
                document.getElementById('signup-form').onkeypress = function(e) {
                    var key = e.charCode || e.keyCode || 0;
                    if (key == 13) {
                        e.preventDefault();
                    }
                }

                var autocomplete = new google.maps.places.Autocomplete(
                    (
                        document.getElementById('autocomplete')), {
                        language: 'pt-BR',
                        componentRestrictions: {'country': 'br'}
                    });


                autocomplete.addListener('place_changed', function () {
                    var place = autocomplete.getPlace();
                    if (place.geometry) {

                        var city = document.getElementById('city');
                        var state = document.getElementById('state');

                        place.address_components.map((current) =>{
                            current.types.map((type) => {

                                if(type == 'administrative_area_level_1'){
                                    state.setAttribute('value', current.short_name)
                                }

                                if(type == 'administrative_area_level_2'){

                                    city.setAttribute('value', current.short_name)

                                }
                            })
                        })

                        var lat = document.getElementById('lat');
                        lat.setAttribute('value', place.geometry.location.lat())

                        var lng = document.getElementById('lng');
                        lng.setAttribute('value', place.geometry.location.lng())


                        var address = document.getElementById('address');

                        var address_data = {
                            name:  place.name,
                            url:  place.url,
                            full_address: place.formatted_address,
                        }

                        address.setAttribute('value', JSON.stringify(address_data))

                    } else {
                        document.getElementById('autocomplete').placeholder = 'Enter a city';
                    }

                });
            }

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
                    professionals: 0,
                    categories_parsed: [],
                },
                mounted: function() {

                    var url = new URL(window.location.href);
                    var category = url.searchParams.get("category");
                    var city = url.searchParams.get("city");
                    this.category = category
                    this.city = city
                    if (window.location.pathname === '/buscar') {
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

                    calcValue: function(ev){

                        var cost_categories = this.category.length * 37.90;


                        if(isNaN(this.professionals) || this.professionals == 0){
                            this.professionals = 1;
                        }

                        if(this.professionals == 1){
                            var cost_professional = 0;
                        } else {
                            var cost_professional = 17.90 * (this.professionals - 1);
                        }


                        var total = cost_professional + cost_categories;

                        this.total = total.toFixed(2);

                        this.categories_parsed = []
                        var categories_parsed = []

                        this.category.map((category) => {
                            categories_parsed.push(category.id)
                        })

                        this.categories_parsed = JSON.stringify(categories_parsed);

                    }
                }

            })

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
             async defer></script>

    </body>
</html>
