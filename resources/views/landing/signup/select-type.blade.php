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
    </style>

    <section id="signup" class="section gray p-t-30 p-b-0">

        <div class="container" style="min-height: 700px">

            <h2 class="text-center m-t-30">Quem bom ver você novamente, estamos quase lá!</h2>

            {{-- Form Container --}}
            <div class="row m-t-30">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h3 class="f-300 m-0 m-b-10">Perfil profissional</h3>
                            <p class="f-300 m-0"><small>Isso mesmo, é de graça!</small></p>

                            <span class="payment">
                                <strong class="payment-currency">R$</strong>
                                <span class="payment-value">
                                    <strong>0</strong>
                                    <small>,00</small>
                                </span>

                                {{-- Isso aqui é só um exemplo --}}
                                <span class="payment-duration">
                                    para sempre!
                                </span>

                                <button type="button" class="btn btn-info m-t-20" data-target="#professional-profile" data-toggle="modal">
                                    Saiba mais sobre o perfil profissional
                                </button>
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group text-center">
                                <li class="list-group-item">First item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Second item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Third item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">First item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Second item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item not-supported">Third item <i class="f-20 ion-ios-close-empty"></i></li>
                                <li class="list-group-item not-supported">First item <i class="f-20 ion-ios-close-empty"></i></li>
                                <li class="list-group-item not-supported">Second item <i class="f-20 ion-ios-close-empty"></i></li>
                                <li class="list-group-item not-supported">Third item <i class="f-20 ion-ios-close-empty"></i></li>
                            </ul>
                            <a href="#" class="btn btn-xs btn-block btn-secondary p-10 f-16" title="Escolhi o perfil profissional">Escolhi o perfil profissional</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h3 class="f-300 m-0 m-b-10">Perfil empresarial</h3>
                            <p class="f-300 m-0"><small>Vale a pena conferir as vantagens!</small></p>
                            <span class="payment">
                                <strong class="payment-currency">R$</strong>
                                <span class="payment-value">
                                    <strong>99</strong>
                                    <small>,99</small>
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
                                <li class="list-group-item">First item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Second item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Third item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">First item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Second item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Third item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">First item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Second item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                                <li class="list-group-item">Third item <i class="f-20 ion-ios-checkmark-empty"></i></li>
                            </ul>

                            <a href="#" class="btn btn-xs btn-block btn-success p-10 f-16" title="Escolhi o perfil empresarial">Escolhi o perfil empresarial</a>

                        </div>
                    </div>
                </div>
            </div>
            {{-- End Form Container --}}

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
