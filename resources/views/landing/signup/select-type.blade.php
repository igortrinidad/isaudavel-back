@extends('landing.events.index')

@section('landing-content')
    <style media="screen">
        .form-control:focus {
            box-shadow: 0 0 4px rgba(110, 192, 88, .4);
            border-color: #6ec058;
        }

        .card-body.card-custom-padding{
            padding: 43px 14px;
        }
        /* Payment */
        .payment {
            margin-top: 20px;
            display: block
        }

        .payment .payment-currency,
        .payment .payment-value {
            font-size: 30px;
            color: #8cc63f;
        }

        .payment .payment-value small {
            font-size: 15px;
            font-weight: 300;
            color: #383938;
            margin-left: -7px
        }
        .payment .payment-duration {
            display: block;
            font-weight: 300;
            text-transform: uppercase;
            border: 1px solid #383938;
            border-radius: 4px;
            padding: 4px 10px;
            width: 150px;
            margin: 10px auto 0 auto;
        }
        .list-group { margin: 4px 0 !important; }

        .list-group{ color: #00A369; }
        .list-group-item i {
            position: relative;
            top: 3px;
            margin-left: 5px;
        }

        .list-group-item.not-supported {
            text-decoration: line-through;
            color: #E14A45;
        }

        .text-primary{
            color: #8cc63f;
        }
    </style>

    <section id="plan-chooser" class="section white p-t-30 p-b-0">

        <div class="container" style="min-height: 700px">

            <h2 class="text-center m-t-30">Quem bom ver você novamente, estamos quase lá!</h2>

            {{-- Form Container --}}
            <div class="row m-t-30">

                <!-- Company -->
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h3 class="f-300 m-0 m-b-10">Plano empresarial</h3>
                            <p class="f-300 m-0"><small>Uma ferramenta completa para sua empresa!</small></p>
                            <span class="payment">
                                a partir de
                                <strong class="payment-currency">R$</strong>
                                <span class="payment-value">
                                    <strong>57</strong>
                                    <small>,90</small>
                                </span>

                                {{-- Isso aqui é só um exemplo --}}
                                <span class="payment-duration">
                                    Mensal
                                </span>

                                <button type="button" class="btn btn-info m-t-20" data-target="#company-profile" data-toggle="modal">Saiba mais sobre o perfil empresarial</button>
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group m-0 text-center">
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    1 especialidade
                                    <br>
                                    <span class="f-12">R$57,90 / especialidade adicional / mês</span>
                                </li>
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    1 profissional
                                    <br>
                                    <span class="f-12">R$17,90 / profissional adicional / mês</span>
                                </li>
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    Acesso aos Dashboard's dos clientes
                                    <br>
                                    <span class="f-12">*Sujeito à permissões do cliente</span>
                                </li>
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    Perfil da empresa na plataforma
                                </li>
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    Agenda online
                                </li>
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    Planos e preços online
                                </li>
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    Controle de planos e aulas de clientes
                                </li>
                                <li class="list-group-item">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                    Galeria de fotos
                                </li>
                            </ul>

                           @if(!empty(request()->query('professional')) || !empty(request()->query('company')) || !\Auth::guard('professional_web')->guest())
                                <button class="btn btn-xs btn-block btn-success p-10 f-16" title="Quero este!" @click.prevent="planChooser('company')">Quero este!</button>
                               @else
                                <p class="text-center m-t-10 m-b-10">Faça login para selecionar um plano</p>
                                <a class="btn btn-xs btn-block btn-primary p-10 f-16" @click.prevent="goToLogin()">Fazer login</a>
                           @endif
                        </div>
                    </div>
                </div>
                <!-- Company -->
            </div>
            {{-- End Form Container --}}

        </div>


        <div>
            <div class="container text-center m-t-30 m-b-30">
                <h2 class="f-300">Ficou com alguma dúvida?</h2>
                <p>Se você tem alguma dúvida entre em contato conosco através do e-mail <a class="text-primary" href="mailto:contato@isaudavel.com">contato@isaudavel.com</a> ou através do formulário abaixo.</p>
                <p>Estamos sempre prontos para te atender.</p>
            </div>
        </div>


        {{-- Modal Professional Profile --}}
        <div class="modal" id="professional-profile" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Perfil profissional</h3>
                    </div>
                    <div class="modal-body">
                        <p>Uma idéia de como mostrar detalhadamente o que cada item pode interferir, se quiser pode ser numa rota ;)</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Modal Professional Profile --}}

        {{-- Modal Company Profile --}}
        <div class="modal" id="company-profile" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Perfil empresarial</h3>
                    </div>
                    <div class="modal-body">
                        <p>Uma idéia de como mostrar detalhadamente o que cada item pode interferir, se quiser pode ser numa rota ;)</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Modal Company Profile --}}

    </section>

    @include('landing.home.contact')

@stop

@section('scripts')
    <script>

        Vue.config.debug = true;

        var vm = new Vue({
                el: '#plan-chooser',
                data: {
                    tracking: {
                        professional_id: null,
                        action: '',
                        description: ''
                    },
                },
                mounted: function () {
                    @if(Auth::guard('professional_web')->check())
                        this.tracking.professional_id = '@php echo  Auth::guard('professional_web')->user()->id @endphp'
                    @endif

                    @if(Auth::guard('professional_web')->guest())
                       this.handleUnauthenticated()
                    @endif
                },
                methods: {

                    planChooser(plan){
                        this.tracking.action = 'click'
                        this.tracking.description = `Clicou para selecionar o plano ${plan}`

                        this.submitTracking()
                    },

                    submitTracking: function(){
                        let that = this

                        this.$http.post('/api/tools/information/collect', that.tracking).then(response => {

                            that.tracking.action = ''
                            that.tracking.description = ''

                            sessionStorage.setItem('is_tracking_id', that.tracking.professional_id);

                        }, response => {
                            // error callback
                        });
                    },

                    handleUnauthenticated(){
                        let that = this

                        let hasTracking = sessionStorage.getItem('is_tracking_id')

                        if(!_.isEmpty(hasTracking)){
                            that.tracking.professional_id = hasTracking,
                            that.tracking.action = 'view'
                            that.tracking.description = `Visitou a página de plano deslogado`

                            that.submitTracking()
                        }
                    },

                    goToLogin() {
                        let that = this
                        let hasTracking = sessionStorage.getItem('is_tracking_id')

                        if (!_.isEmpty(hasTracking)) {
                            that.tracking.professional_id = hasTracking,
                            that.tracking.action = 'click'
                            that.tracking.description = `Clicou para fazer login na página de planos`
                            that.submitTracking()
                        }


                        setTimeout(() => {
                            window.location = '/profissionais/login?redirect=/cadastro/finalizar'
                        }, 100)

                    }

                }
            })

    </script>
@endsection
