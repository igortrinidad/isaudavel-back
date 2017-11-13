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

                                @include('landing.professionals.plans-for-professional')

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

                                @include('landing.professionals.plans-for-companies')
                                
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
