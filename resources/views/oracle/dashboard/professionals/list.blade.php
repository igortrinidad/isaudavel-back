@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container">
        <div class="row">
            <div class="col-md-12">
                <h3><strong>Profissionais</strong></h3>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="m-t-20">
                    <form class="m-b-25" action="{{route('oracle.dashboard.professionals.list')}}" method="get" role="form">
                        <label>Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Busca por nome, sobrenome e email" value="{{request()->query('search')}}"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success" id="submit">Buscar</button>
                            </span>
                        </div>
                    </form>
                </div>

                @unless($professionals->count())
                    <div class="alert alert-info">
                        Nenhum cliente localizado.
                    </div>
                @else
                    <div class="table-responsive m-t-20">
                        <table class="table table-striped table-hover table-vmiddle">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Especialidades</th>
                                <th>Empresas</th>
                                <th>Criado em:</th>
                                <th>Ações:</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($professionals as $professional)
                                <tr>
                                    <td>{{$professional->full_name}}</td>
                                    <td>{{$professional->email}}</td>
                                    <td>{{$professional->categories()->count()}}</td>
                                    <td>{{$professional->companies()->count()}}</td>
                                    <td>{{$professional->created_at->format('d/m/Y H:i:s')}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"  href="{{route('oracle.dashboard.professionals.show', ['id'=> $professional->id])}}" ><i class="ion-gear-b fa-lg"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $professionals->links() }}
                        </div>
                    </div>
                @endunless
            </div>
        </div>
    </div>
@endsection
