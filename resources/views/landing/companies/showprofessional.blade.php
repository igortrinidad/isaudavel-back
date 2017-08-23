

@extends('landing.companies.index')

@section('landing-content')
    <style>

    h1, h2, h3, h4, h5{
        color: #383938;
    }

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
 </style>

    <header>
        <div class="bg-picture" style="background-image:url({{$professional->avatar}})">
        </div>
        <div class="bg-header-company">
            <h1 class="bg-header-company-name">{{$professional->full_name}}</h1>
        </div>
    </header>
     <section class="m-t-20">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-20">Informações</h2>
                </div>
                <div class="col-md-12 col-xs-12 text-center">
                    <h4 class="m-b-15">Especialidades</h4>
                    @foreach($professional->categories as $category)
                        <a href="/new-landing/buscar?category={{$category->name}}">
                            <span class="label label-primary p-5 f-16">{{$category->name}}</span>
                        </a>
                    @endforeach                    
                </div>
            </div>

        </div>

        <div class="container">
            <div class="row m-t-20">
                <div class="col-md-12 col-xs-12 text-center">
                    <hr>
                    <h2>Avaliações</h2>
                    <p class="f-14 m-t-10">{{$professional->current_rating}} de {{$professional->total_rating}} avaliações</p>
                    <?php $rating_to_loop = $professional->current_rating; ?>
                    @include('components.rating', ['size' => '35'])
                </div>
            </div>

            <div class="row m-t-20">

                @foreach($professional->last_ratings as $rating)
                <div class="col-md-12 col-xs-12 m-t-20">
                    <div class="col-md-12 col-xs-12 text-center">
                        <div class="picture-circle picture-circle-p" style="background-image:url({{$rating->client->avatar}})"></div>
                        <h4>{{$rating->client->full_name}}</h4>
                    </div>
                    <div class="col-md-12 col-xs-12 m-t-10 text-center">
                        <?php $rating_to_loop = $rating->rating; ?>
                        @include('components.rating', ['size' => '22'])
                        <p>{{$rating->content}}</p>
                    </div>
                </div>
                @endforeach

            </div>

        <div class="container">
            <div class="row m-t-20">

                <div class="col-md-12 col-xs-12 text-center">
                    <hr>
                    <h2>Empresas</h2>
                </div>

                @foreach($professional->companies as $company)
                <div class="col-md-12 col-xs-12 text-center">
                    <div class="picture-circle  picture-circle-p m-t-10" style="background-image:url({{$company->avatar}})"></div>
                    <h3><a class="f-400" href="/new-landing/empresas/{{$company->slug}}"> {{$company->name}}</a></h3>
                </div>
                <div class="col-md-12 col-xs-12 text-center">
                    <?php $rating_to_loop = $company->current_rating; ?>
                    @include('components.rating', ['size' => '22'])
                </div>
                <div class="col-md-12 col-xs-12 text-center p-t-10 m-b-30">
                    @foreach($company->categories as $category)
                        <a href="/new-landing/buscar?category={{$category->name}}">
                            <span class="label label-primary p-5 f-12">{{$category->name}}</span>
                        </a>
                    @endforeach
                </div>
                @endforeach

            </div>

        </div>

        </div>
    </section>

    @section('scripts')
        @parent

        <script>

        </script>
    @stop
@stop
