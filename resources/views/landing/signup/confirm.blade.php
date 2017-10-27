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
            margin: 0
        }
    </style>

    <section id="signup" class="section gray p-t-30 p-b-0">

        <div class="container" style="min-height: 700px">

            <h2 class="text-center m-t-30">Verifique seu email</h2>

            {{-- Form Container --}}
            <div class="form-container m-t-30">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body card-padding ">
                                <p class="f-300 m-0 f-18">
                                    Parabéns, seu cadastro foi efetivado com sucesso!
                                </p>
                                <p class="f-300 m-t-30 f-18">
                                    Nós enviamos uma mensagem para que você possa confirmar sua conta e todas as informações necessárias para que possamos prosseguir.
                                </p>
                                <p class="f-700 m-t-30 f-14">
                                    <img class="confirm-image" src="https://isaudavel.com/logos/LOGO-1-01.png" alt="Isaudavel">
                                    Atenciosamente, Equipe Isadavel
                                </p>
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
