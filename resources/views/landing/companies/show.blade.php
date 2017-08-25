

@extends('landing.companies.index', ['header_with_search' => false])

@section('landing-content')
 <style>

    html, body,
    body a,
    body a:hover,
    body a:active {
        color: #383938;
        text-decoration: none;
    }

    .btn.btn-facebook{ background-color: #3b5998; color: #F4F5F5; }
    .btn.btn-whatsapp{ background-color: #1ebea5; color: #F4F5F5; }

    h1, h2, h3, h4, h5{
        color: #383938;
        font-weight: 300;
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
        position: absolute;
        top: 80px; left: 0; right: 0;
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


    .picture-show { margin-top: -60px; }

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

    .bg-company-photos{
        height: 200px;
    }

    .bg-header-company-name{
        color: #fff;
        font-weight:400;
    }


    .section {
        background-color: #F4F5F5;
    }

    /* Swiper */
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

    /* Interactions */
    .btn.out { display: none !important; }
    .info { display: none; }
    .info.in { display: block; }


 </style>

    <header>
        <div class="bg-picture" style="background-image:url({{$company_fetched->avatar}})"></div>
    </header>

    <section class="section p-t-20">
        <!-- GRID -->
        <div class="container" style="margin-top: 120px;">

            <div class="card">
                <div class="card-header ch-alt text-center">
                    <h1>{{ $company_fetched->name }}</h1>
                </div>
            </div>

            <div class="row m-t-30">

                <!-- LEFT COL -->
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <div class="picture-circle" style="background-image:url({{$company_fetched->avatar}})">
                            </div>
                        </div>
                        <div class="card-body card-padding text-center">
                            <div>
                                <?php $rating_to_loop = $company_fetched->current_rating; ?>
                                @include('components.rating', ['size' => '35'])
                            </div>

                            <!-- Address -->
                            <div class="m-t-10">
                                <i class="ion-ios-location-outline m-r-5"></i>
                                <span class="f-300">{{ $company_fetched->address['full_address'] }}</span>
                            </div>
                            <!-- Address -->

                            <!-- Categories -->
                            <div class="m-t-10">
                                @foreach($company_fetched->categories as $category)
                                    <span class="label label-success">{{ $category->name }}</span>
                                @endforeach
                            </div>
                            <!-- Categories -->

                            <!-- Phone -->
                            @if($company_fetched->phone)
                                <div class="m-t-10">
                                    <button type="button" class="btn btn-xs btn-primary btn-target" data-target="#company-phone">Mostrar telefone</button>
                                    <div class="info" id="company-phone">
                                        <i class="ion-ios-telephone-outline m-r-5"></i>
                                        <span class="f-300">{{ $company_fetched->phone }}</span>
                                    </div>
                                </div>
                            @endif
                            <!-- Phone -->

                            <!-- Website -->
                            @if($company_fetched->website)
                                <div class="m-t-10">
                                    <button type="button" class="btn btn-xs btn-primary btn-target" data-target="#company-website">Mostrar Website</button>
                                    <div class="info" id="company-website">
                                        <i class="ion-ios-world-outline m-r-5"></i>
                                        <span class="f-300">{{ $company_fetched->website }}</span>
                                    </div>
                                </div>
                            @endif
                            <!-- Website -->

                            <button type="button" class="btn btn-xs btn-block btn-facebook m-t-30 p-5 f-15">
                                <i class="ion-social-facebook m-r-5"></i>Compartilhar no facebook
                            </button>
                            <button type="button" class="btn btn-xs btn-block btn-whatsapp m-t-5 p-5 f-15">
                                <i class="ion-social-whatsapp m-r-5"></i>Compartilhar no whatsapp
                            </button>

                        </div>
                    </div>

                    <!-- Description -->
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h2 class="f-300">Descrição</h2>
                        </div>
                        <div class="card-body card-padding">
                            <p class="f-300">{{ $company_fetched->description }}</p>
                        </div>
                    </div>

                    <!-- ADS EXEMPLE -->
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h4>ADS EXEMPLO</h4>
                        </div>
                        <div class="card-body card-padding text-center">
                            <div class="row">
                                <div class="col-sm-12">
                                    <img class="img-responsive" src="http://phandroid.s3.amazonaws.com/wp-content/uploads/2013/05/Flavyr-banner.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / LEFT COL -->

                <!-- CENTER COL -->
                <div class="col-sm-7 text-center">
                    <!-- Ratings -->
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h2 class="f-300">Avaliações</h2>
                            <span class="f-14 f-300">Total de {{$company_fetched->total_rating}}
                                @if($company_fetched->total_rating > 1)
                                    avaliações
                                @else
                                    avaliação
                                @endif
                            </span>
                        </div>

                        <div class="card-body p-t-5 p-b-5">
                            <div class="swiper-container swiper-default-show">
                                <div class="swiper-wrapper">
                                    @foreach($company_fetched->last_ratings as $rating)
                                        <div class="swiper-slide text-center">
                                            <div class="p-10" style="background-color: #f4f5f5; border-radius: 4px;">
                                                <div class="picture-circle picture-circle-p" style="background-image:url({{$rating->client->avatar}})"></div>
                                                <h4 class="m-t-10">{{$rating->client->full_name}}</h4>
                                                <?php $rating_to_loop = $rating->rating; ?>
                                                @include('components.rating', ['size' => '22'])
                                                <p class="f-300 m-t-10">{{$rating->created_at->format('d/m/Y')}}</p>
                                                <p class="f-300 m-t-10">{{$rating->content}}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div style="height: 50px;"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Ratings -->
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h2 class="f-300">Indicações</h2>
                        </div>

                        <div class="card-body p-t-5 p-b-5">
                            <div class="swiper-container swiper-default-show">
                                <div class="swiper-wrapper">
                                    @foreach($company_fetched->last_ratings as $rating)
                                        <div class="swiper-slide text-center">
                                            <div class="p-10" style="background-color: #f4f5f5; border-radius: 4px;">
                                                <div class="picture-circle picture-circle-p" style="background-image:url({{$rating->client->avatar}})"></div>
                                                <h4 class="m-t-10">{{$rating->client->full_name}}</h4>
                                                <p class="f-300 m-t-10">{{$rating->created_at->format('d/m/Y')}}</p>
                                                <p class="f-300 m-t-10">{{$rating->content}}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div style="height: 50px;"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional list -->
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h2 class="f-300">Profissionais</h2>
                        </div>
                        <div class="card-body p-t-5 p-b-5">
                            <div class="swiper-container swiper-default-show">
                                <div class="swiper-wrapper">
                                    @foreach($company_fetched->professionals as $professional)
                                        <div class="swiper-slide text-center">
                                            <div class="p-10" style="background-color: #f4f5f5; border-radius: 4px;">
                                                <div class="picture-circle  picture-circle-p m-t-10" style="background-image:url({{$professional->avatar}})">
                                                </div>
                                                <h3 class="f-300">
                                                    <a href="/new-landing/profissionais/{{$professional->id}}">{{$professional->full_name}}</a>
                                                </h3>
                                                <div class="">
                                                    <?php $rating_to_loop = $professional->current_rating; ?>
                                                    @include('components.rating', ['size' => '22'])
                                                </div>
                                                <div class="p-t-10 m-b-20">
                                                    @foreach($professional->categories as $category)
                                                        <a href="{!! route('landing.search.index', ['category' => $category->name]) !!}"><button class="btn btn-success btn-sm m-b-5">{{ $category->name }}</button></a>
                                                    @endforeach
                                                </div>
                                                <a href="{!! route('landing.professionals.show', $professional->id) !!}" title="{{ $professional->full_name }}">
                                                    <button class="btn btn-block btn-primary f-300 f-16">
                                                        Ver perfil
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div style="height: 50px;"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- / CENTER COL -->

            </div>
        </div>
        <!-- / GRID -->

        <!-- Map -->
        <div class="container">
            @if($company_fetched->address_is_available)
            <!-- Map -->
            <div class="card">
                <div class="card-header ch-alt text-center">
                    <h2 class="f-300">Mapa</h2>
                </div>
                <div class="card-body p-t-10">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d120048.59390324546!2d-44.034090048121215!3d-19.902541183833424!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xa690cacacf2c33%3A0x5b35795e3ad23997!2sBelo+Horizonte%2C+MG!5e0!3m2!1spt-BR!2sbr!4v1503599958415"
                        width="100%"
                        height="450"
                        frameborder="0"
                        style="border:0"
                        allowfullscreen
                    >
                    </iframe>
                </div>
            </div>
            @endif
        </div>
        <!-- / MAP -->

        <!-- PHOTOS COL -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="f-300">Fotos</h2>
                        </div>
                        <div class="card-body card-padding">

                            <div id="gallery" style="display:none;">
                                @foreach($company_fetched->photos as $photo)
                                <img alt="{{$company_fetched->name}}" src="{{$photo->photo_url}}"
                                    data-image="{{$photo->photo_url}}"
                                    data-description="{{$company_fetched->name}}">
                                @endforeach
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / PHOTOS COL -->

    </section>

    @section('scripts')
        @parent

        <script>

            jQuery(document).ready(function(){
                jQuery("#gallery").unitegallery({
                    tiles_type:"justified"
                });
            });

            $('.btn-target').on('click', function(e) {
                var target = e.target.dataset.target
                $(e.target).addClass('out')
                $(target).addClass('in')
            })
            var swiperCertifications = new Swiper('.swiper-certifications', {
                centeredSlides: true,
                spaceBetween: 15,
                loop: false,
                slidesPerView: 3,
                slideToClickedSlide: true,
                paginationClickable: true,
                pagination: '.swiper-pagination',
                breakpoints: {
                    768: {
                        slidesPerView: 1
                    }
                }
            })
            var swiperCertifications = new Swiper('.swiper-default-show', {
                centeredSlides: true,
                spaceBetween: 15,
                loop: false,
                slidesPerView: 1,
                slideToClickedSlide: true,
                paginationClickable: true,
                pagination: '.swiper-pagination',
            })
        </script>


    @stop
@stop
