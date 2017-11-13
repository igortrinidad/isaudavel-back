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

        .bg-pattern{
            background-image: url("/images/pattern-isaudavel-5-300.png");
        }

        .link{
            color: #247CAE !important;
        }

    </style>

    <section id="signup" class="section gray p-t-30 p-b-0 bg-pattern">

        <div class="container">

            <h2 class="text-center m-t-30">Cadastre-se</h2>

            <h4 class="text-center f-300">Cadastre-se e comece a divulgar seu perfil profissional e serviços agora mesmo! <b>É gratuíto!</b></h4>

            {{-- Form Container --}}
            <div class="form-container m-t-30">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body card-padding">

                                {{--Alert display--}}
                                @include('flash::message')

                                <form id="signup-form" method="POST" action="{{route('landing.professionals.send-signup-form')}}">

                                    {{csrf_field()}}

                                    <legend class="f-300 p-b-10">Seus dados</legend>

                                    <div class="form-group m-t-0">
                                        <label for="signup-name" class="cursor-pointer">Nome*</label>
                                        <input id="signup-name" class="form-control" type="text" v-model="professional.name" placeholder="Nome">
                                    </div>

                                    <div class="form-group">
                                        <label for="signup-last-name" class="cursor-pointer">Sobrenome*</label>
                                        <input id="signup-last-name" class="form-control" type="text" v-model="professional.last_name" placeholder="Sobrenome">
                                    </div>

                                    <div class="form-group">
                                        <label for="signup-email" class="cursor-pointer">Email*</label>
                                        <input id="signup-email" class="form-control" type="email" v-model="professional.email" placeholder="Email">
                                    </div>

                                    <div class="form-group m-b-0">
                                        <label for="signup-phone" class="cursor-pointer">Telefone*</label>
                                        <input id="signup-phone" class="form-control" type="text" v-model="professional.phone" placeholder="Telefone" value="{{old('phone')}}">
                                    </div>

                                    <div class="form-group m-b-0">
                                        <label class="cursor-pointer">Senha*</label>
                                        <input class="form-control" type="password" v-model="professional.password" placeholder="Senha" value="{{old('password')}}">
                                    </div>

                                    <div class="form-group m-b-0">
                                        <label class="cursor-pointer">Confirmação de senha*</label>
                                        <input class="form-control" type="password" v-model="professional.password_confirmation" placeholder="Digite a senha novamente" value="{{old('password')}}">
                                    </div>

                                    <div class="form-group m-b-0">
                                        <label for="signup-categories" class="cursor-pointer">Especialidades</label>
                                        <multiselect
                                            id="signup-categories"
                                            v-model="categories_selected"
                                            :options="categories"
                                            :label="'name'"
                                            :multiple="true"
                                            placeholder="Selecione ao menos uma especialidade"
                                            @input="toggleCategories"
                                        >
                                        </multiselect>
                                    </div>

                                    <div class="checkbox-group m-t-20">
                                        <label class="checkbox">
                                            <input type="checkbox" class="wp-checkbox-reset wp-checkbox-input" v-model="terms_checkbox" @click="handleTerms">
                                            <div class="wp-checkbox-reset wp-checkbox-inline wp-checkbox">
                                            </div>
                                            <span class="wp-checkbox-text">
                                                Aceito os <a class="link" href="{{ route('landing.terms')}}" target="blank">Termos de Uso</a> e <a class="link" href="{{ route('landing.privacy')}}" target="blank">Política de privacidade</a>
                                            </span>
                                        </label>
                                    </div>

                                    <input type="hidden" name="professional" v-model="professional_parsed">

                                    <button type="submit" class="btn btn-sm btn-block btn-success m-t-20 f-16" title="Cadastrar" @click.prevent="submit" :disabled="!professional.name || !professional.last_name || !professional.email || !professional.phone || !professional.categories_selected.length || !professional.terms_accepted || professional.password != professional.password_confirmation">Cadastrar</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Form Container --}}
        </div>

    </section>

@stop

@section('scripts')
    <script>

        Vue.config.debug = true;

        var vm = new Vue({
                el: '#signup',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                data(){
                    return {
                        categoryFromParams: '',
                        categories: [],
                        categories_selected: [],
                        category: null,
                        terms_checkbox: false,
                        professional: {
                            name: '',
                            last_name: '',
                            email: '',
                            phone: '',
                            categories_selected: [],
                            terms_accepted: false,
                            terms_accepted_at: '',
                            password: '',
                            password_confirmation: ''
                        },
                        professional_parsed: ''
                    }
                },
                mounted: function () {

                    this.getCategories();
                    console.log('Vue rodando no signup');
                },
                methods: {

                    toggleCategories: function(){
                        let that = this
                    
                        that.professional.categories_selected = [];

                        that.categories_selected.map((category) => {
                            that.professional.categories_selected.push(category.id)
                        })
                        
                    },
                    getCategories: function(){
                        let that = this

                        this.$http.get('/api/company/category/list').then(response => {

                            that.categories = response.body;

                        }, response => {
                            // error callback
                        });
                    },

                    handleTerms: function () {
                        var that = this;

                        setTimeout( function(){
                            if (that.terms_checkbox == true) {
                                that.professional.terms_accepted = true;
                                that.professional.terms_accepted_at = moment().format('YYYY-MM-DD HH:mm:ss');
                            } else {
                                that.professional.terms_accepted = false;
                                that.professional.terms_accepted_at = '';
                            }
                            
                        },100)

                    },

                    submit (){
                        this.professional_parsed = JSON.stringify(this.professional)
                        setTimeout(() =>{
                            document.getElementById("signup-form").submit();
                        }, 100)
                    }
                }
            })

    </script>
@endsection
