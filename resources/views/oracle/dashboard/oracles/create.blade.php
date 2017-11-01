@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="oracle-edit">


        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>Adicionar novo administrador</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.oracles.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <form class="m-b-25" action="{{route('oracle.dashboard.oracles.store')}}" method="post" role="form">

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome"  required value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label>Sobrenome</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Sobrenome"  required value="{{ old('last_name') }}">
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="text" class="form-control" name="email" placeholder="E-mail" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="password" placeholder="Informe ou gere uma senha para o usuário" id="client_password">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success" onclick="generate()">Gerar senha</button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirmar senha</label>
                        <input class="form-control" name="confirm_password"  placeholder="Confirme a senha" id="client_confirm_password">
                    </div>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}


                    <button type="submit" class="btn btn-success btn-lg btn-block m-t-20">Salvar</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>


        function randomPassword(length) {
            var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }

        function generate() {

            var password = randomPassword(6);

            $('#client_password').val(password);
            $('#client_confirm_password').val(password);
        }


    </script>
@stop


