@extends('landing.companies.index')

 @section('landing-content')

    <style>

        html, body,
        body a,
        body a:hover,
        body a:active {
            color: #383938;
            text-decoration: none;
        }

        h1, h2, h3, h4, h5{
            color: #383938;
            font-weight: 300;
        }
        .section {
            background-color: #F4F5F5;
        }
        .wp-rating-div {
            color: #FFCC5F;
            font-size: 25px;
        }
    </style>

     <section class="section" id="companies-list">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="block">
                        <div class="f-300 text-center">
                            <h2>Empresas</h2>
                            <p class="teste">Encontre a empresa ou profissional para ajudar você a cuidar da sua saúde.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-t-20">
                @foreach($companies as $company)
                <div class="col-md-4 col-xs-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <img class="img-circle m-b-10" src="{{ $company->avatar }}" width="64">
                            <h3 class="m-b-0 t-overflow">
                                <a href="/new-landing/empresas/{{$company->slug}}" title="{{ $company->name }}">{{ $company->name }}</a>
                            </h3>
                        </div>
                        <div class="card-body card-padding text-center">
                            <h4>Avaliação</h4>
                            <div class="wp-rating-div">
                                @for ($i = 1; $i <= $company->current_rating; $i++ )
                                    <i class="ion-ios-star"></i>
                                @endfor
                                @for ($i = $company->current_rating; $i < 5; $i++ )
                                    <i class="ion-ios-star-outline"></i>
                                @endfor
                            </div>
                            <div class="m-t-20">
                                <span class="f-300 f-18 ">
                                    <i class="ion-ios-location-outline m-r-5"></i>
                                    {{ $company->address['full_address'] }}
                                </span>
                            </div>
                            <hr class="m-t-20">
                            <a href="/new-landing/empresas/{{$company->slug}}" title="{{ $company->name }}">
                                <button class="btn btn-primary f-300 f-16" href="/new-landing/empresas/{{$company->slug}}">
                                    <i class="ion-ios-plus-outline m-r-5 f-20"></i>Mais informações
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                {!! $companies->render() !!}
            </div>
        </div>

        <hr>

        <div class="container">
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
