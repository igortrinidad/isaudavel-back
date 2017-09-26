@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container m-b-30">
        <div class="row">
            <div class="col-md-12">
                <h3><strong>Eventos</strong></h3>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="m-t-20">
                    <form class="m-b-25" action="{{route('oracle.dashboard.events.list')}}" method="get" role="form" id="company-edit-form">
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
                                <th>Valor</th>
                                <th>Organizador</th>
                                <th>Confirmados</th>
                                <th>Comentários</th>
                                <th>Status</th>
                                <th>Visualizar</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td>{{$event->name}}</td>
                                    <td>R${{$event->value}}</td>
                                    <td>{{$event->from->full_name}}</td>
                                    <td>{{$event->total_participants}}</td>
                                    <td>{{$event->total_comments}}</td>
                                    <td>
                                       @if($event->is_published)
                                           <span class="label label-success">Publicado</span>
                                           @else
                                            <span class="label label-default">Aguardando</span>
                                        @endif
                                    </td>
                                    <td><a href="{{route('landing.events.show', $event->slug)}}" target="_blank" class="btn btn-sm btn-primary">Visualizar</a></td>
                                    <td><a href="{{route('oracle.dashboard.events.edit', $event->id)}}" class="btn btn-sm btn-info">Editar</a></td>
                                    <td><a class="btn btn-sm btn-danger">Excluir</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {!! $events->render() !!}
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
@endsection
