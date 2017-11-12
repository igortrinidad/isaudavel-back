@extends('landing.events.index')

@section('landing-content')
    <style media="screen">
        .form-container {
            max-width: 768px;
            margin: 0 auto;
        }

        .form-control:focus {
            box-shadow: 0 0 4px rgba(110, 192, 88, .4);
            border-color: #6ec058;
        }

        .bg-pattern {
            background-image: url("/images/pattern-isaudavel-5-300.png");
        }

        .link {
            color: #247CAE !important;
        }

        .container-signup{
            min-height: 800px;
        }

    </style>

    <section id="signup" class="section gray p-t-30 p-b-0 bg-pattern">


        <div class="container-signup">

            <h2 class="text-center m-t-30">Cadastrar empresa</h2>
            @if(!empty(request()->query('id')) || !\Auth::guard('professional_web')->guest())

                {{-- Form Container --}}
                <div class="form-container m-t-30">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body card-padding">

                                    {{--Alert display--}}
                                    @include('flash::message')

                                    <form id="signup-form" method="POST"
                                          action="{{route('landing.signup.company.store')}}">

                                        {{csrf_field()}}

                                        <legend class="f-300 p-b-10">Dados da empresa</legend>

                                        <div class="form-group m-t-0">
                                            <label for="signup-name" class="cursor-pointer">Nome*</label>
                                            <input id="signup-name" class="form-control" type="text"
                                                   v-model="company.name" placeholder="Nome">
                                        </div>


                                        <div class="form-group m-b-0">
                                            <label for="signup-phone" class="cursor-pointer">Telefone*</label>
                                            <input id="signup-phone" class="form-control" type="text"
                                                   v-model="company.phone" placeholder="Telefone">
                                        </div>

                                        <div class="form-group m-b-0">
                                            <label for="signup-phone" class="cursor-pointer">Website</label>
                                            <input id="signup-phone" class="form-control" type="text"
                                                   v-model="company.website" placeholder="Website">
                                        </div>


                                        <div class="form-group m-b-0">
                                            <label>Endereço *</label>
                                            <input class="form-control" id="autocomplete"
                                                   placeholder="Informe o endereço da empresa"/>
                                        </div>

                                        <div class="checkbox-group m-t-20">
                                            <label class="checkbox">
                                                <input type="checkbox" class="wp-checkbox-reset wp-checkbox-input"
                                                       v-model="terms_checkbox" @click="handleTerms">
                                                <div class="wp-checkbox-reset wp-checkbox-inline wp-checkbox">
                                                </div>
                                                <span class="wp-checkbox-text">
                                                Aceito os <a class="link" href="{{ route('landing.terms')}}"
                                                             target="blank">Termos de Uso</a> e <a class="link"
                                                                                                   href="{{ route('landing.privacy')}}"
                                                                                                   target="blank">Política de privacidade</a>
                                            </span>
                                            </label>
                                        </div>

                                        <input type="hidden" name="company" v-model="company_parsed">

                                        <button type="submit" class="btn btn-sm btn-block btn-success m-t-20 f-16"
                                                title="Cadastrar" @click.prevent="submit"
                                                :disabled="!company.name || !company.phone || !company.address || !company.terms_accepted">
                                            Cadastrar
                                        </button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Form Container --}}

            @else

                <div class="container m-t-20 text-center ">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body card-padding ">
                                    <p>É necessário possuir um cadastro de profissional para cadastar uma empresa no <b>iSaudavel</b>.</p>

                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <p>Ainda não tem cadastro?</p>
                                            <a class="btn btn-primary btn-lg btn-block p-10" href="/cadastro">Cadastre-se aqui!</a>
                                            <br>
                                            <p class="m-t-10">Já tenho cadastro no iSaudavel</p>
                                            <a class="btn btn-success btn-lg btn-block p-10" href="/profissionais/login?redirect=/cadastro/empresa">Fazer login</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </section>

@stop

@section('scripts')


    <script>

        function initAutocomplete() {
            
            //prevent form submit on enter
            document.getElementById('signup-form').onkeypress = function (e) {
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

                    place.address_components.map((current) => {
                        current.types.map((type) => {

                            if (type == 'administrative_area_level_1') {
                                Vue.set(vm.$data.company, 'state', current.short_name)
                            }

                            if (type == 'administrative_area_level_2') {
                                Vue.set(vm.$data.company, 'city', current.short_name)

                            }
                        })
                    })

                    var address_data = {
                        name: place.name,
                        url: place.url,
                        full_address: place.formatted_address,
                    }

                    Vue.set(vm.$data.company, 'lat', place.geometry.location.lat())
                    Vue.set(vm.$data.company, 'lng', place.geometry.location.lng())
                    Vue.set(vm.$data.company, 'address', address_data)

                } else {
                    document.getElementById('autocomplete').placeholder = 'Enter a city';
                }

            });
        }

        Vue.config.debug = true;

        var vm = new Vue({
            el: '#signup',
            components: {
                Multiselect: window.VueMultiselect.default
            },
            data() {
                return {
                    categoryFromParams: '',
                    categories: [],
                    categories_selected: [],
                    category: null,
                    terms_checkbox: false,
                    company: {
                        owner_id: '',
                        name: '',
                        website: '',
                        phone: '',
                        categories_selected: [],
                        terms_accepted: false,
                        terms_accepted_at: '',
                    },
                    company_parsed: ''
                }
            },
            mounted: function () {


                @if(!empty(request()->query('id')))
                    this.company.owner_id = '@php echo request()->query('id') @endphp'
                @endif

                @if(Auth::guard('professional_web')->check())
                    this.company.owner_id = '@php echo  Auth::guard('professional_web')->user()->id @endphp'
                @endif

                this.getCategories();
                console.log('Vue rodando no signup');
            },
            methods: {

                toggleCategories: function () {
                    let that = this

                    that.company.categories_selected = [];

                    that.categories_selected.map((category) => {
                        that.company.categories_selected.push(category.id)
                    })

                },
                getCategories: function () {
                    let that = this

                    this.$http.get('/api/company/category/list').then(response => {

                        that.categories = response.body;

                    }, response => {
                        // error callback
                    });
                },

                handleTerms: function () {
                    var that = this;

                    setTimeout(function () {
                        if (that.terms_checkbox == true) {
                            that.company.terms_accepted = true;
                            that.company.terms_accepted_at = moment().format('YYYY-MM-DD HH:mm:ss');
                        } else {
                            that.company.terms_accepted = false;
                            that.company.terms_accepted_at = '';
                        }

                    }, 100)

                },

                submit() {
                    this.company_parsed = JSON.stringify(this.company)
                    setTimeout(() => {
                        document.getElementById("signup-form").submit();
                    }, 100)
                }
            }
        })
    </script>


    @if(!empty(request()->query('id')) || !\Auth::guard('professional_web')->guest())

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
                async defer></script>
    @endif

@endsection
