@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container">
        <div class="row">
            <div class="col-md-12">
                <h3><strong>Versão do aplicativo</strong></h3>

                <p>Note que esta versão deve ser compatível com a versão atualizada dos aplicativos nas stores para não informar o usuário sem necessidade de atualização.</p>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <form method="POST" action="{{route('oracle.dashboard.system.update')}}">

                    {{ csrf_field() }}
                    <input type="hidden" name="actual_version" value="{{$actual_version}}">

                    <div class="form-group">
                        <label>Versão atual do aplicativo</label>
                        <input class="form-control" name="new_version" value="{{$actual_version}}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Alterar versão</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
