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

    <section id="signup" class="section gray p-t-30 p-b-0 bg-pattern">

        <div class="container" style="min-height: 700px">

            <h2 class="text-center m-t-30">Você está quase lá!</h2>

            {{-- Form Container --}}
            <div class="row m-t-30 text-center">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-custom-padding">
                            <p class="f-300 m-0 f-18">
                                Seu cadastro foi realizado com sucesso. Agora você só precisa acessar seu email para confirmar seu cadastro.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Form Container --}}

            <div class="row">
                <div class="col-sm-12">
                    @include('landing.components.card-to-download')
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
