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
    </style>

    <section id="signup" class="section gray p-t-30 p-b-0">

        <div class="container">

            <h2 class="text-center m-t-30">Cadastre-se</h2>

            {{-- Form Container --}}
            <div class="form-container m-t-30">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body card-padding">
                                <form class="" action="index.html" method="post">

                                    <div class="form-group m-t-0">
                                        <label for="signup-name" class="cursor-pointer">Nome</label>
                                        <input id="signup-name" class="form-control" type="text" name="" value="" placeholder="Nome">
                                    </div>

                                    <div class="form-group">
                                        <label for="signup-last-name" class="cursor-pointer">Sobrenome</label>
                                        <input id="signup-last-name" class="form-control" type="text" name="" value="" placeholder="Sobrenome">
                                    </div>

                                    <div class="form-group">
                                        <label for="signup-email" class="cursor-pointer">Email</label>
                                        <input id="signup-email" class="form-control" type="text" name="" value="" placeholder="Email">
                                    </div>

                                    <div class="form-group m-b-0">
                                        <label for="signup-phone" class="cursor-pointer">Telefone</label>
                                        <input id="signup-phone" class="form-control" type="text" name="" value="" placeholder="Telefone">
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-block btn-success m-t-20 f-16" name="button" title="Cadastrar">Cadastrar</button>
                                    <a href="submit" class="btn btn-sm btn-block btn-info m-t-20 f-16" title="Ir para login">Ir para login</a>
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
                },
                data: {
                    categoryFromParams: '',
                },
                mounted: function () {
                    console.log('Vue rodando no signup');
                },
                methods: {

                }
            })

    </script>
@endsection
