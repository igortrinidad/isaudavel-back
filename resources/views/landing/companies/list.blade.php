@extends('landing.companies.index', ['header_with_search' => true])

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
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="block">
                        <div class="">
                            <h2>Empresas</h2>
                            <p class="teste">Encontre a empresa ou profissional para ajudar você a cuidar da sua saúde.</p>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    
                    @foreach($companies as $company)

                        <h1><a href="/new-landing/empresas/{{$company->slug}}"> {{$company->name}}</a></h1>
                    @endforeach

                    {!! $companies->render() !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h4 class="m-b-10">Não achou a empresa ou profissional que você procura?</h4>
                    <button class="btn btn-primary">Indique uma empresa ou profissional</button>
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