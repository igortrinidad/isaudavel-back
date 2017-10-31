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

            <h2 class="text-center m-t-30">Verifique seu email</h2>

            {{-- Form Container --}}
            <div class="row m-t-30">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body card-custom-padding">
                            <p class="f-300 m-0 f-18">
                                Parabéns, seu cadastro foi efetivado com sucesso!
                            </p>

                            <p class="f-300 m-t-30 f-18">
                                Seja muito bem-vindo ao <b style="color: #8cc63f">iSaudavel</b>.
                            </p>

                            <p class="f-300 m-t-30 f-18">
                                Nós enviamos uma mensagem para o seu email para que você possa fazer a confirmação dos seus dados e com as informações necessárias para que possamos prosseguir.
                            </p>
                            <p class="f-700 m-t-30 f-14">
                                Atenciosamente,
                                <img class="confirm-image" src="https://isaudavel.com/logos/LOGO-1-01.png" alt="Isaudavel">
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body card-padding">
                            <p class="f-300 m-0 f-16 text-justify">
                                O <b style="color: #8cc63f">iSaudavel</b> foi criado para você profissional da área da saúde e seu cliente
                                economizarem o mais importante da vida: <b style="color: #8cc63f">TEMPO</b>.
                                Uma rede social dedicada para você divulgar seus serviços e organizar o atendimento a seu cliente de forma
                                simplificada e objetiva, integrando outros profissionais que assim como você estão comprometidos à promover a
                                saúde e bem estar de seus clientes.
                            </p>
                            <div class="text-center m-t-30">
                                <a href="{!! route('landing.professionals.about') !!}" class="btn btn-sm btn-secondary f-14">Saiba mais</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Form Container --}}


            @if(request()->query('company') != 'true' )
                <div class="row m-b-30">
                    <div class="text-center">
                        <h2>Possui uma empresa?</h2>
                        <span class="f-300">Cadastre sua empresa e deixe que os usuários do iSaudavel encontrem você.</span>
                    </div>

                    <div class="text-center wow m-t-30">
                        <a class="btn btn-primary btn-lg" href="{{route('landing.signup.company', ['id' => request()->query('id')])}}">Cadastrar minha empresa</a>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="card">
                    <div class="card-body card-padding">
                        <div class="text-center">
                            <h2>Download</h2>
                            <span class="f-300">Faça o download do iSaudavel, disponível para Iphone, Android.</span>
                        </div>

                        <div class="text-center wow m-t-30">
                            <a href="https://itunes.apple.com/us/app/isaudavel/id1277115133?mt=8" target="_blank" class="m-r-5" title="Download para Iphone"><img src="/images/app_store_btn.png" alt=""></a>
                            <a href="https://play.google.com/store/apps/details?id=com.isaudavel" target="_blank" title="Download para Android"><img src="/images/play_store_btn.png" alt=""></a>
                        </div>
                    </div>
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
