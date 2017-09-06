@extends('dashboard.layout.index')

@section('content')
    <div class="container">
        <div class="row m-t-20">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <label> Empresas</label>
                        @foreach(\Auth::user()->companies as $company)
                            <p>{{$company->name}}</p>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
