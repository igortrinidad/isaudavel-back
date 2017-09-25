@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container m-b-30">
        <div class="row">
            <div class="col-md-12">
                <h3><strong>Receitas</strong></h3>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="m-t-20">
                    <form class="m-b-25" action="{{route('oracle.dashboard.recipes.list')}}" method="get" role="form" id="company-edit-form">
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
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Cadastrado por</th>
                                <th class="text-center">Rating</th>
                                <th class="text-center">Comentários</th>
                                <th>Visualizar</th>
                                <th>Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recipes as $recipe)
                                <tr>
                                    <td>{{$recipe->title}}</td>
                                    <td>{{$recipe->type->name}}</td>
                                    <td>{{$recipe->from->full_name}}</td>
                                    <td class="text-center">{{$recipe->current_rating}}</td>
                                    <td class="text-center">{{$recipe->total_comments}}</td>
                                    <td><a href="{{route('landing.recipes.show', $recipe->slug)}}" target="_blank" class="btn btn-sm btn-primary">Visualizar</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {!! $recipes->render() !!}
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
@endsection
