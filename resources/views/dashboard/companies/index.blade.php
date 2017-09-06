@extends('dashboard.layout')

@section('content')
    <div class="container">
        <div class="row m-t-20">
            <h3>Empresas</h3>
            @foreach($companies as $company)
                <p>{{$company->name}}</p>
            @endforeach

            
        </div>
    </div>
@endsection
