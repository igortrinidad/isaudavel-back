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

        /* Swiper */
        .swiper-wrapper { padding-top: 30px; }
        .swiper-slide .card {
            transform: scale(.9);
            z-index: 99999;
            transition: ease .3s;
        }

        .swiper-slide-active .card {
            transform: scale(1);
            transition: ease .3s;
        }

        /* List & Cards With Swiper */
        .swiper-pagination-bullet,
        .swiper-pagination-bullet { border-color: #88C657 !important }

        .swiper-pagination-bullet.swiper-pagination-bullet-active,
        .swiper-pagination-bullet.swiper-pagination-bullet-active { background-color: #88C657 !important }

        /* Swiper Buttons Prev & Next */
        .swiper-button-prev { right: auto; left: 20px; }
        .swiper-button-next { right: 20px; left: auto; }

        .swiper-button-prev,
        .swiper-button-next {
            background-image: none;
            top: 0;
            margin-top: 0;
            background-color: rgba(255, 255, 255, 1);
            height: 40px;
            width: 40px;
            border-radius: 4px;
            text-align: center;
            color: #383938;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 1px rgba(0,0,0,.15);
            font-size: 25px;
        }
        .swiper-button-prev.swiper-button-disabled,
        .swiper-button-next.swiper-button-disabled { opacity: 0; }

        @media (max-width: 768px) {
            .swiper-wrapper { padding-top: 50px; }
            .swiper-button-prev { right: auto; left: 0; }
            .swiper-button-next { right: 0; left: auto; }
            .swiper-button-prev,
            .swiper-button-next { height: 30px ; width: 30px ; font-size: 16px; }
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
                            <h3 class="f-300">Level <small class="">(algum dado para colocar aqui)</small></h3>
                            <span class="f-300">{{ $client_fetched->level }}</span>
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
                @if(count($client_fetched->activities) === 0)
                    <div class="col-sm-12 text-center">
                        <span class="f-300">Nenhuma atividade recente.</span>
                    </div>
                @endif
                @foreach($client_fetched->activities as $indexActivities => $activity)

                    @if($indexActivities < 3)
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header ch-alt">
                                    <div class="picture-circle picture-circle-p" style="background-image:url('{{ $activity->user->avatar }}')">
                                    </div>
                                    <div class="m-t-10 m-b-10 text-center">
                                        <h4 class="f-300 t-overflow m-t-10 m-b-10">{{ $activity->user->full_name }},
                                            <small class="f-300">{{ $activity->content }}</small>
                                        </h4>
                                        <small class="label label-success from-now p-5 f-300 f-10">
                                            {{ $activity->created_at }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- / activities -->

        <div class="container">
            <hr class="hr-style m-30 m-r-0 m-l-0">
        </div>

        <!-- companies -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Empresas</h2>
                </div>

                @if(count($client_fetched->companies) === 0)
                    <div class="col-sm-12 text-center">
                        <span class="f-300">Nenhuma empresa recente.</span>
                    </div>
                @endif
                <div class="col-sm-12">
                    <div class="swiper-container swiper-featureds wow fadeInUp">
                        <div class="swiper-wrapper">
                            @foreach($client_fetched->companies as $index_company => $company)
                                <div class="swiper-slide text-center">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <div class="picture-circle  picture-circle-p m-b-10" style="background-image:url({{$company->avatar}})">
                                            </div>
                                            <h3 class="m-b-0 t-overflow">
                                                <a  href="{!! route('landing.companies.show', $company->slug) !!}" title="{{ $company->name }}">{{ $company->name }}</a>
                                            </h3>
                                        </div>
                                        <div class="card-body card-padding text-center">
                                            <h4 class="f-300">Avaliação</h4>
                                            <div class="wp-rating-div">
                                                <?php $rating_to_loop = $company->current_rating; ?>
                                                @include('components.rating', ['size' => '22'])
                                            </div>

                                            <div class="m-t-20">
                                                @foreach($company->categories as $index_category => $category)
                                                    <a href="{!! route('landing.search.index', ['category' => $category->slug]) !!}"><button class="btn btn-success btn-xs m-b-5">{{ $category->name }}</button></a>
                                                @endforeach
                                            </div>

                                            <div class="m-t-20">
                                                <span class="f-300 f-18 t-overflow">
                                                    <i class="ion-ios-location-outline m-r-5"></i>
                                                    {{ $company->address['full_address'] }}
                                                </span>
                                            </div>
                                            <hr class="m-t-20">
                                            <a  href="{!! route('landing.companies.show', $company->slug) !!}" title="{{ $company->name }}">
                                                <button class="btn btn-primary f-300 f-16">
                                                    Mais informações
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-prev"><i class="ion-ios-arrow-back"></i></div>
                        <div class="swiper-button-next"><i class="ion-ios-arrow-forward"></i></div>
                        <div style="height: 50px;"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / companies -->

        <div class="container">
            <hr class="hr-style m-30 m-r-0 m-l-0">
        </div>

        <!-- Photos -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-center">
                    <h2 class="m-b-30">Fotos</h2>
                </div>
                @if(count($client_fetched->photos) === 0)
                    <div class="col-sm-12 text-center">
                        <span class="f-300">Nenhuma foto publicada.</span>
                    </div>
                @endif
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

                var fromNowValues = $('.from-now')
                fromNowValues.each(function() {
                    var date = $.trim($(this).text())
                    console.log(date);
                    $(this).text(moment(date, "YYYYMMDD, h:mm:ss").fromNow())
                });

                var swiperFeatureds = new Swiper('.swiper-featureds', {
                    initialSlide: 0,
                    centeredSlides: true,
                    spaceBetween: 15,
                    loop: true,
                    slidesPerView: 3,
                    slideToClickedSlide: true,
                    paginationClickable: true,
                    pagination: '.swiper-pagination',
                    prevButton: '.swiper-button-prev',
                    nextButton: '.swiper-button-next',
                    breakpoints: {
                        768: {
                            slidesPerView: 1
                        }
                    }
                })
            });
        </script>
    @stop
@stop
