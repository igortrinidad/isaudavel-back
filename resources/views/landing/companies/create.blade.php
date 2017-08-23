@extends('landing.companies.index', ['header_with_search' => false])

 @section('landing-content')

    <style>

    </style>

     <section class="section" id="companies-list">


        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <div class="block">
                        <div class="">
                            <h2>Cadastrar empresa</h2>
                            <p>Cadastre a sua empresa, seus cliente merecem essa comodidade.</p>

                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="block">

                        <div class="form-group">
                            <label>Nome</label>
                            <input class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Endereço</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <hr>
        <p>Remover e colocar verificação se esta logado depois..</p>

        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <p>Você precisa estar logado para cadastrar uma nova empresa.</p>
                    <a href="{!! route('landing.professionals.auth.login')!!}" class="btn btn-primary m-t-10">Fazer login</a>
                </div>
                <div class="col-md-12 col-xs-12 text-center m-t-20">
                    <p>Não possui login ainda?</p>
                    <button class="btn btn-primary m-t-10">Cadastre-se aqui</button>
                </div>
            </div>

        </div>




    </section>

    @section('scripts')
        @parent

        <script>
            

        </script>


    @stop
@stop