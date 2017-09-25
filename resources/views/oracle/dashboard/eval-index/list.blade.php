@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container m-b-30">
        <div class="row">
            <div class="col-md-12">
                <h3><strong>Indíces de avaliações</strong></h3>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="m-t-20">
                    <form class="m-b-25" action="{{route('oracle.dashboard.eval-index.list')}}" method="get" role="form" id="company-edit-form">
                        <label>Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Digite o que procura" value="{{request()->query('search')}}"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success" id="submit">Buscar</button>
                        </span>
                        </div>
                    </form>
                </div>

                    <div class="table-responsive m-t-20">
                        <table class="table table-striped table-hover table-vmiddle">
                            <thead>
                            <tr>
                                <th>Label</th>
                                <th>Criado por</th>
                                <th>Criado em</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($eval_index as $index)
                                <tr>
                                    <td>{{$index->label}}</td>
                                    <td>{{$index->from->full_name}}</td>
                                    <td>{{$index->created_at}}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.eval-index.edit', $index->id )}}">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {!! $eval_index->render() !!}
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
@endsection
