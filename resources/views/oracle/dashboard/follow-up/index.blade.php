@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="notifications">

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>Follow-up</h2>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading cursor-pointer" data-toggle="collapse" data-target="#companies">Últimas empresas cadastadas</div>
                    <div class="panel-body collapse in" id="companies">
                        @unless($latest_companies->count())
                            <div class="alert alert-info">
                                Nenhuma empresa cadastada.
                            </div>
                        @else
                            <div class="table-responsive m-t-20">
                                <table class="table table-striped table-hover table-vmiddle">
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Especialidades</th>
                                        <th>Profissionais</th>
                                        <th>Status</th>
                                        <th>Assinatura</th>
                                        <th>Criado em:</th>
                                        <th>Ações:</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($latest_companies as $company)
                                        <tr>
                                            <td>{{$company->name}}</td>
                                            <td>{{$company->categories->count()}}</td>
                                            <td>{{$company->professionals->count()}}</td>
                                            <td>
                                                @if($company->is_active)
                                                    <span class="label label-success">Ativa</span>
                                                @else()
                                                    <span class="label label-danger">Inativa</span>
                                                @endif
                                            </td>
                                            <td>

                                                @if(!$company->subscription)
                                                    <span class="label label-danger">Sem assinatura</span>
                                                @endif

                                                @if($company->subscription && $company->subscription->is_active)
                                                    <span class="label label-success">Ativa</span>
                                                @endif

                                                @if($company->subscription && !$company->subscription->is_active)
                                                    <span class="label label-default">Inativa</span>
                                                @endif
                                            </td>
                                            <td>{{$company->created_at->format('d/m/Y H:i:s')}}</td>
                                            <td class="text-center">
                                                <a class="btn btn-default btn-sm" href="{!! route('landing.companies.show', $company->slug) !!}"><i class="ion-search"></i></a>
                                                <a class="btn btn-primary btn-sm" href="{{route('oracle.dashboard.companies.edit', ['id'=> $company->id])}}" title="Editar empresa"><i class="ion-edit"></i></a>
                                                <a class="btn btn-primary btn-sm" href="{{route('oracle.dashboard.companies.professional.list', $company->id)}}" title="Profissionais"><i class="ion-person fa-lg"></i></a>
                                                @if($company->subscription)
                                                    <a class="btn btn-success btn-sm" href="{{route('oracle.dashboard.companies.subscription', ['id'=> $company->id])}}" title="Gerenciar assinatura"><i class="ion-gear-b fa-lg"></i></a>
                                                @endif
                                                @if(!$company->subscription)
                                                    <a class="btn btn-success btn-sm" href="{{route('oracle.dashboard.companies.subscription.create', ['id'=> $company->id])}}" title="Criar assinatura"><i class="ion-document-text fa-lg"></i></a>
                                                @endif
                                                <a class="btn btn-info btn-sm" href="{{route('oracle.dashboard.companies.invoices', ['id'=> $company->id])}}" title="Visualizar faturas"><i class="ion-social-usd fa-lg"></i></a>


                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endunless
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading cursor-pointer" data-toggle="collapse" data-target="#professionals">Últimos profissionais cadastrados</div>
                    <div class="panel-body collapse" id="professionals">
                        @unless($latest_professionals->count())
                            <div class="alert alert-info">
                                Nenhuma profissional cadastado.
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
                                    @foreach($latest_professionals as $professional)
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
                            </div>
                        @endunless
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection