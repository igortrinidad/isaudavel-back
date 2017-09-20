@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="profile-update">

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>Meus dados</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.companies.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <form class="m-b-25" action="{{route('oracle.dashboard.oracles.update')}}" method="post" role="form">

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome" value="{{$oracle->name}}" required>
                    </div>

                    <div class="form-group">
                        <label>Sobrenome</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Sobrenome" value="{{$oracle->last_name}}" required>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="text" class="form-control" name="email" placeholder="E-mail" value="{{$oracle->email}}" required>
                    </div>

                    <div class="alert alert-info m-t-20 m-b-20">
                       <i class="ion-ios-information-outline fa-lg"></i> <strong>Dica:</strong> para alterar a senha atual preencha os campos abaixo.
                    </div>

                    <div class="form-group">
                        <label>Senha atual</label>
                        <input type="password" class="form-control" name="current_password" placeholder="Informe a senha atual">
                    </div>

                    <div class="form-group">
                        <label>Nova senha</label>
                        <input type="password" class="form-control" name="new_password" placeholder="Informe a nova senha atual">
                    </div>

                    <div class="form-group">
                        <label>Confirmar nova senha</label>
                        <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirmar a nova senha atual">
                    </div>


                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}

                    <input type="hidden" id="id" name="id" value="{{ $oracle->id  }}">

                    <button type="submit" class="btn btn-success btn-lg btn-block m-t-20">Salvar alterações</button>
                </form>
            </div>
        </div>
    </div>
@endsection
