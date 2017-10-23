@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container">

    	<h2>{{$company->name}}: lista de profissionais</h2>

    	<hr>

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">

                <div class="page-title m-b-10">

                    <table class="table table-bordered table-hover table-striped m-t-30">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>É proprietário?</th>
                                <th>Categorias</th>
                                <th>Admin?</th>
                                <th>Confirmado?</th>
                                <th>Público?</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@foreach($company->professionals as $professional)
                            <tr>
                                <td>{{$professional->full_name}}</td>
                                <td>{{$professional->email}}</td>
                                <td class="text-center">
                                	@if($professional->id == $company->owner_id)
                                		Sim
                                	@endif
                                </td>
                                <td class="text-center">
                                	@foreach($professional->categories as $cat)
                                	<span  class="label label-success m-r-10">
	                                    {{$cat->name}}
	                                </span>
	                                @endforeach
	                            </td>
                                <td class="text-center">{{$professional->pivot->is_admin}}</td>
                                <td class="text-center">{{$professional->pivot->is_confirmed}}</td>
                                <td class="text-center">{{$professional->pivot->is_public}}</td>
                                <td class="text-center">
                                	@if($professional->id != $company->owner_id)
                                		<form method="POST" action="{{route('oracle.dashboard.companies.professional.remove_from_company') }}">
                                			{{csrf_field()}}
                                			<input type="hidden" name="company_id" value="{{$company->id}}">
                                			<input type="hidden" name="professional_id" value="{{$professional->id}}">
	                                		<button type="submit" class="btn btn-danger btn-sm">Excluir</button>
	                                	</form>
                                	@endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        <div class="row">
        	<div class="col-md-12 col-xs-12">

        		<hr>


        		<h3>Adicionar profissional existente à empresa</h3>

        		<form method="POST" action="{{route('oracle.dashboard.companies.professional.add_to_company')}}">

        			{{csrf_field()}}
        			<input type="hidden" name="company_id" value="{{$company->id}}">

	        		<div class="form-group">
	        			<label>Email</label>
	        			<input class="form-control" name="email">
	        		</div>

	        		<div class="form-group">
	        			<label>Admin</label>
	        			<input type="checkbox" class="form-control" name="is_admin">
	        		</div>

	        		<div class="form-group">
	        			<label>Perfil público</label>
	        			<input type="checkbox" class="form-control" name="is_public">
	        		</div>

	        		<div class="form-group">
	        			<label>Confirmado</label>
	        			<input type="checkbox" class="form-control" name="is_confirmed">
	        		</div>

	        		<div class="form-group">
	        			<button class="btn btn-block btn-primary" type="submit">Adicionar profissional à empresa</button>
	        		</div>

	        	</form>
        	</div>
        </div>
    </div>
@stop