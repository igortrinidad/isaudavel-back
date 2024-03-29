@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container">
        <div class="row">
            <div class="col-md-12">
                <h3><strong>Empresas</strong></h3>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="m-t-20">
                    <form class="m-b-25" action="{{route('oracle.dashboard.companies.list')}}" method="get" role="form" id="company-edit-form">
                        <label>Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Digite o que procura" value="{{request()->query('search')}}"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success" id="submit">Buscar</button>
                        </span>
                        </div>
                    </form>
                </div>

                @unless($companies->count())
                    <div class="alert alert-info">
                        Nenhuma empresa localizada.
                    </div>
                @else
                    <div class="table-responsive m-t-20">
                        <table class="table table-striped table-hover table-vmiddle">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Especialidades</th>
                                <th>Profissionais</th>
                                <th>Avaliação</th>
                                <th>Status</th>
                                <th>Assinatura</th>
                                <th>Plan</th>
                                <th>Criado em:</th>
                                <th>Ações:</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{$company->name}}</td>
                                    <td>{{$company->categories->count()}}</td>
                                    <td>{{$company->professionals->count()}}</td>
                                    <td>
                                        @if($company->current_rating > 0)
                                            <div class="wp-rating-div">
                                                @php
                                                    $rating_to_loop = $company->current_rating
                                                @endphp
                                                @include('components.rating', ['size' => '16'])
                                            </div>
                                            @else
                                            <span>Sem avaliação</span>
                                        @endif
                                    </td>
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

                                    <td>
                                        @if($company->is_paid)
                                            <span class="label label-info">PLUS</span>
                                        @else
                                            <span class="label label-default">FREE</span>
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
                        <div class="text-center">
                            {{ $companies->links() }}
                        </div>
                    </div>
                @endunless
            </div>
        </div>
    </div>
@endsection
