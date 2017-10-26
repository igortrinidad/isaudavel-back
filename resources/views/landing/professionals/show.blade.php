@extends('landing.companies.index', ['header_with_search' => false])

@section('landing-content')
    <style>
        .bg-picture {
            display: block;
            box-sizing: border-box;
            margin: 0 auto;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            width: 100%;
            height: 350px;
        }

        .picture-circle{
            box-sizing: border-box;
            margin: 0 auto;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        .picture-circle-p{
            width: 68px;
            height: 68px;
        }

        .picture-circle-m{
            width: 86px;
            height: 86px;
        }

        .bg-header-company{
            display: block;
            background-color: #000000;
            opacity: 0.8;
            width: 100%;
            height: auto;
            padding: 10px 10px 10px 25px;
        }

        .bg-header-company-name{
            color: #fff;
            font-weight:400;
        }
        .hr-style {
            border: 0;
            height: 1px;
            background-image: -webkit-linear-gradient(left, #F4F5F5, rgba(56, 57, 56, .2), #F4F5F5);
            background-image: -moz-linear-gradient(left, #F4F5F5, rgba(56, 57, 56, .2), #F4F5F5);
            background-image: -ms-linear-gradient(left, #F4F5F5, rgba(56, 57, 56, .2), #F4F5F5);
            background-image: -o-linear-gradient(left, #F4F5F5, rgba(56, 57, 56, .2), #F4F5F5);
        }
    </style>

    <header>
        <div class="bg-picture" style="background-image:url({{$professional_fetched->avatar}})"></div>
        <div class="bg-header-company">
            <h1 class="bg-header-company-name">{{$professional_fetched->full_name}}</h1>
        </div>
    </header>

    <section class="m-0 p-t-30" style="background-color: #F4F5F5;">

        <!-- Base Informations -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Informações</h2>
                </div>
                <div class="col-md-12 col-xs-12 text-center">
                    <h4 class="m-b-15">Especialidades</h4>
                    @foreach($professional_fetched->categories as $category)
                    <a href="{!! route('landing.search.index', ['category' => $category->slug]) !!}"><button class="btn btn-success btn-sm m-b-5">{{ $category->name }}</button></a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /Base Informations -->

        <div class="container">
            <hr class="hr-style m-30 m-r-0 m-l-0">
        </div>

        <!-- Ratings -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="">Avaliações</h2>
                    <p class="f-14 m-t-10">{{$professional_fetched->current_rating}} de {{$professional_fetched->total_rating}} avaliações</p>
                    <?php $rating_to_loop = $professional_fetched->current_rating; ?>
                    <h2 class="m-b-30">{{$professional_fetched->current_rating}}</h2>
                    @include('components.rating', ['size' => '35'])
                </div>
            </div>

            <div class="row m-t-30">
                @foreach($professional_fetched->last_ratings as $rating)
                    <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                        <div class="card" style="min-height: 370px;">
                            <div class="card-header ch-alt">
                                <div class="picture-circle picture-circle-p" style="background-image:url({{$rating->client->avatar}})"></div>
                                <h4>{{$rating->client->full_name}}</h4>
                                <?php $rating_to_loop = $rating->rating; ?>
                                @include('components.rating', ['size' => '22'])
                                <h3>{{$rating->rating}}</h3>
                                <p>{{$rating->created_at->format('d/m/Y')}}</p>
                            </div>
                            <div class="card-body p-10">
                                <p>{{$rating->content}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(!$professional_fetched->last_ratings->count())
                <p class="text-center">Este profissional ainda não possui avaliações</p>
                @endif
            </div>
        </div>
        <!-- /Ratings  -->

        <div class="container">
            <hr class="hr-style m-30 m-r-0 m-l-0">
        </div>

        <!-- Courses & Certifications -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Cursos e certificados</h2>
                </div>
            </div>

            <div class="row">
                @foreach($professional_fetched->certifications as $certification)
                <div class="col-md-4  col-sm-6 col-xs-12 text-center">
                    <div class="card">
                        <div class="bg-picture" style="background-image:url({{$certification->photo_url}}); height: 200px"></div>
                        <div class="card-header ch-alt">
                            <h4>{{$certification->name}}</h4>
                        </div>
                        <div class="card-body p-10 text-center">
                            <p class="f-13">Instituição</p>
                            <p class="f-16">{{$certification->institution}}</p>

                            <p class="f-13">Descrição</p>
                            <p class="f-16">{{$certification->description}}</p>

                            <p class="f-13">Concluído em</p>
                            <p class="f-16">{{$certification->granted_at}}</p>

                        </div>
                    </div>
                </div>
                @endforeach

                @if(!$professional_fetched->certifications->count())
                    <p class="text-center">Este profissional ainda não possui cursos e certificados cadastrados</p>
                @endif
            </div>
        </div>
        <!-- /Courses & Certifications -->

        <div class="container">
            <hr class="hr-style m-30 m-r-0 m-l-0">
        </div>

        <!-- Companies -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Empresas</h2>
                </div>
            </div>
            <div class="row">
                @foreach($professional_fetched->companies as $company)
                    <div class="col-md-4  col-sm-6 col-xs-12 text-center">
                        <div class="card">
                                <a class="f-400"  href="{!! route('landing.companies.show', $company->slug) !!}">
                                    <div class="card-header ch-alt">
                                        <div class="picture-circle picture-circle-p" style="background-image:url({{$company->avatar}})"></div>
                                        <h4 class="m-t-20 m-0">
                                            <a class="f-300"  href="{!! route('landing.companies.show', $company->slug) !!}"> {{$company->name}}</a>
                                        </h4>
                                    </div>
                                </a>
                            <div class="card-body p-10">

                                @unless ($company->current_rating == 0)
                                    <?php $rating_to_loop = $company->current_rating; ?>
                                    @include('components.rating', ['size' => '24'])
                                @else
                                    <p class="m-t-10 m-b-10 f-300">Essa empresa ainda não foi avaliada</p>
                                @endunless

                                <p class="f-300 f-18 m-t-10 m-b-20">
                                    <i class="ion-ios-location-outline m-r-5"></i>
                                    {{ $company->city }} -  {{ $company->state }}
                                </p>

                                @foreach($company->categories as $category)
                                    <a href="{!! route('landing.search.index', ['category' => $category->slug]) !!}"><button class="btn btn-success btn-sm m-b-5 m-t-5">{{ $category->name }}</button></a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                @if(!$professional_fetched->companies->count())
                    <p class="text-center">Este profissional ainda não possui empresas que atua</p>
                @endif
            </div>
        </div>
        <!-- /Companies -->

    </section>

    @section('scripts')
        @parent

        <script>

        </script>
    @stop
@stop
