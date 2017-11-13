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
        .confirm-image {
            max-width: 100px;
            display: block;
            margin-left: -5px;
            position: relative;
            top: 10px;
        }
        .card-body.card-custom-padding{
            padding: 43px 14px;
        }
    </style>

    <section id="signup" class="section gray p-t-30 p-b-0">

        <div class="container" style="min-height: 700px">

            <h2 class="text-center m-t-20">Parabéns!</h2>

            {{-- Form Container --}}
            <div class="row m-t-20">
                <div class="col-sm-12">
                    <div class="card text-center">
                        <div class="card-body card-custom-padding">
                            <p class="m-0 f-18">
                                Email confirmado com sucesso!
                            </p>

                            <p class="m-t-30 f-18">
                                Baixe o aplicativo e complete seu perfil profissional para as pessoas localizarem você através do <b style="color: #8cc63f">iSaudavel</b>.
                            </p>

                        </div>
                    </div>
                </div>

            </div>
            {{-- End Form Container --}}

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h2 class="text-center">Turbine seu perfil profissional</h2>

                    <div class="card text-center m-t-20">
                        <div class="card-body card-custom-padding">
                            <p class="f-18">Você pode turbinar seu perfil profissional divulgando para muito mais pessoas através da plataforma e gerenciar seus clientes com ajuda do aplicativo iSaudavel:</p>

                            <div class="row text-center m-t-30">

                                <!--Free -->
                                <div class="col-sm-4 col-sm-offset-2">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <h1 class="m-0 m-b-10">FREE</h1>
                                            <p class="f-300 m-0"><small>Isso mesmo, é de graça!</small></p>

                                            <span class="f-20 payment">
                                                <strong class="payment-currency">R$</strong>
                                                <span class="payment-value">
                                                    <strong class="m-r-0">0</strong>
                                                    <small style="margin-left: -3px;">,00</small>
                                                </span>

                                                {{-- Isso aqui é só um exemplo --}}
                                                <span class="payment-duration">
                                                    para sempre!
                                                </span>

                                            </span>
                                        </div>
                                        <div class="card-body p-0">
                                            <ul class="list-group m-0 text-center">
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Divulgar perfil profissional
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Vincular à empresas que você trabalha
                                                </li>
                                                <li class="list-group-item not-supported">
                                                    <i class="ion-ios-close-empty m-r-5 f-18 c-red"></i>
                                                    Acesso aos Dashboard's dos clientes
                                                </li>
                                                <li class="list-group-item not-supported">
                                                    <i class="ion-ios-close-empty m-r-5 f-18 c-red"></i>
                                                    Agenda online para clientes
                                                </li>
                                                <li class="list-group-item not-supported">
                                                    <i class="ion-ios-close-empty m-r-5 f-18 c-red"></i>
                                                    Gerencie seus clientes
                                                </li>
                                            </ul>

                                                <button class="btn btn-xs btn-block btn-success p-10 f-16 m-t-10" title="Plano atual" disabled>
                                                    Plano atual
                                                </button>

                                        </div>
                                    </div>
                                </div>
                                <!--Free -->


                                <!--Free -->
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <h1 class="m-0 m-b-10">PLUS</h1>
                                            <p class="f-300 m-0"><small>Apenas</small></p>

                                            <span class="f-20 payment">
                                                <strong class="payment-currency">R$</strong>
                                                <span class="payment-value">
                                                    <strong class="m-r-0">27</strong>
                                                    <small style="margin-left: -3px;">,90 / mês</small>
                                                </span>

                                            </span>
                                        </div>
                                        <div class="card-body p-0">
                                            <ul class="list-group m-0 text-center">
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Divulgar perfil profissional
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Vincular à empresas que você trabalha
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Acesso aos Dashboard's dos clientes
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Agenda online para clientes
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Gerencie seus clientes
                                                </li>
                                            </ul>

                                            @if(!empty(request()->query('professional')) || !empty(request()->query('company')) || !\Auth::guard('professional_web')->guest())
                                                <button class="btn btn-xs btn-block btn-success p-10 f-16" title="Quero este!" @click.prevent="planChooser('free')">Quero este!</button>
                                            @else
                                                <p class="text-center m-t-10 m-b-10">Faça login para selecionar um plano</p>
                                                <a class="btn btn-xs btn-block btn-primary p-10 f-16" @click.prevent="goToLogin()">Fazer login</a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <!--Free -->

                                
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h2 class="text-center">Cadastre suas empresas</h2>

                    <div class="card text-center m-t-20">
                        <div class="card-body card-custom-padding">
                            <p class="f-18">Você também pode cadastrar as empresas que você trabalha</p>

                            <div class="row text-center m-t-30">


                                <!--Free -->
                                <div class="col-sm-4 col-sm-offset-4">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <h1 class="m-0 m-b-10">PARA EMPRESAS</h1>
                                            <p class="f-300 m-0"><small>Apenas</small></p>

                                            <span class="f-20 payment">
                                                <strong class="payment-currency">R$</strong>
                                                <span class="payment-value">
                                                    <strong class="m-r-0">57</strong>
                                                    <small style="margin-left: -3px;">,90 / mês</small>
                                                </span>

                                            </span>
                                        </div>
                                        <div class="card-body p-0">
                                            <ul class="list-group m-0 text-center">
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Divulgar perfil da empresa
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Vincular profissionais
                                                    <p class="f-12">incluso 2 profissionais (R$19,90 / profissional extra)</p>
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Acesso aos Dashboard's dos clientes
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Agenda online para clientes
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="ion-ios-checkmark-empty m-r-5 f-18 c-green"></i>
                                                    Gerencie seus clientes
                                                </li>
                                            </ul>

                                            @if(!empty(request()->query('professional')) || !empty(request()->query('company')) || !\Auth::guard('professional_web')->guest())
                                                <a href="/cadastro/empresa" class="btn btn-xs btn-block btn-success p-10 f-16 m-t-10" title="Quero este!">Cadastrar empresa</a>
                                            @else
                                                <p class="text-center m-t-20 m-b-10">Faça login para cadastrar sua empresa</p>
                                                <a class="btn btn-xs btn-block btn-primary p-10 f-16" @click.prevent="goToLogin()">Fazer login</a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <!--Free -->

                                
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    @include('landing.components.card-to-download', ['card_message' => 'Faça o download agora'])
                </div>
            </div>

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
                data: {
                    categoryFromParams: '',
                    categories: [],
                    category: null
                },
                mounted: function () {

                    this.getCategories();
                    console.log('Vue rodando no signup');
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
                }
            })

    </script>
@endsection
