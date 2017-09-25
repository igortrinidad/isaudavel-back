@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container m-b-30">
        <div class="row">
            <div class="col-md-12">
                <h3><strong>Editar índice</strong></h3>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <form method="POST" action="{{route('oracle.dashboard.eval-index.update')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Label antigo</label>
                        <input class="form-control" type="hidden" name="old_eval_index" value="{{$old_eval_index}}">
                        <input class="form-control" disabled name="old_eval_index" value="{{$old_eval_index}}">
                    </div>
                    <div class="form-group">
                        <label>Label novo</label>
                        <input class="form-control" name="new_eval_index" value="{{$old_eval_index}}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Alterar indice em todas as avaliações</button>
                    </div>

                </form>
                
            </div>
        </div>
    </div>
@endsection
