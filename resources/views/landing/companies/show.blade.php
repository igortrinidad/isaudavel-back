 

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

                    <h1>{{$company->name}}</h1>

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