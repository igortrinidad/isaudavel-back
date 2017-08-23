 

@extends('landing.companies.index')

@section('landing-content')
 <style>

 	.teste{
 		color: red;
 	}

    h1, h2, h3, h4, h5{
        color: #383938;
    }
 </style>

     <section class="section" id="companies-list">
        <div class="container">

            <div class="row">
                <div class="col-md-12 col-xs-12">

                <img class="img-circle" src="{{$company->avatar}}" width="120px"/>
                    <h1>{{$company->name}}</h1>
                    <h1>Avaliação: {{$company->current_rating}}</h1>

                    <hr>

                    @foreach($company->professionals as $professional)
                        <img class="img-circle" src="{{$professional->avatar}}" width="70px"/>
                        <h3><a href="/new-landing/profissionais/{{$professional->id}}"> {{$professional->full_name}}</a></h3>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @section('scripts')
        @parent

        <script>
            

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#companies-list',
                data: {
                },
                mounted: function() {

                    console.log('Vue rodando no companies-list');
                },
                methods: {
                }

            })

        </script>


    @stop
@stop