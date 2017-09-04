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
        <div class="bg-picture" style="background-image:url({{$client_fetched->avatar}})">
        </div>
        <div class="bg-header-company">
            <h1 class="bg-header-company-name">{{ $client_fetched->full_name }}</h1>
        </div>
    </header>

    <section class="m-0 p-t-30" style="background-color: #F4F5F5;">
        <!-- Base informations -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Informações</h2>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h3 class="f-300">Total de XP</h3>
                            <span class="f-300">{{ $client_fetched->total_xp }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h3 class="f-300">Objetivo</h3>
                            <span class="f-300">{{ $client_fetched->target }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h3 class="f-300">Objetivo</h3>
                            <span class="f-300">{{ $client_fetched->target }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Base informations -->

        <div class="container">
            <hr class="hr-style m-30 m-r-0 m-l-0">
        </div>

        <!-- activities -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Atividades</h2>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h3 class="f-300">Título</h3>
                            <small class="f-300">Há 3 horas atrás</small>
                        </div>
                        <div class="card-body card-padding">
                            may the force be with you.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / activities -->

        <div class="container">
            <hr class="hr-style m-30 m-r-0 m-l-0">
        </div>

        <!-- Photos -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Fotos</h2>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding" style="min-height: 250px">
                            <div id="gallery" style="display:none;">
                                @foreach($client_fetched->photos as $photo)
                                    <img
                                        class="img-gallery"
                                        alt="{{ $client_fetched->full_name }}" src="{{ $photo->photo_url }}"
                                        data-image="{{ $photo->photo_url }}"
                                        data-description="{{ $client_fetched->full_name }}"
                                    >
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Photos -->

    </section>

    @section('scripts')
        @parent

        <script>
            $(document).ready(function(){
                jQuery("#gallery").unitegallery({
                    tiles_type:"justified"
                });
            });
        </script>
    @stop
@stop
