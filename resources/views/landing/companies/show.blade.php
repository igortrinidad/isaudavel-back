

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
        <div class="bg-picture" style="background-image:url({{$company_fetched->avatar}})">
        </div>
    </header>

    <section class="section p-t-20">
        <!-- TEST -->
        <div class="container m-t-30">

            <div class="card">
                <div class="card-header ch-alt">
                    <h1 class="text-center">{{ $company_fetched->name }}</h1>
                </div>
            </div>

            <div class="row m-t-30">
                <!-- LEFT COL -->
                <div class="col-sm-4">
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
                                <i class="ion-ios-location m-r-5"></i>
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
                                        <i class="ion-ios-location m-r-5"></i>
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
                                        <i class="ion-ios-location m-r-5"></i>
                                        <span class="f-300">{{ $company_fetched->website }}</span>
                                    </div>
                                </div>
                            @endif
                            <!-- Website -->
                        </div>
                    </div>
                </div>
                <!-- LEFT COL -->

                <!-- RIGHT COL -->
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body card-padding text-center">
                            <!-- Description -->
                            <h2 class="m-b-20">Descrição</h2>
                            <p class="f-300">{{ $company_fetched->description }}</p>

                            <!-- Ratings -->
                            <h2 class="m-t-20 m-b-20">Avaliações</h2>

                            <!-- Photos -->
                            <h2 class="m-t-20 m-b-20">Fotos</h2>
                            <div class="row">
                                @foreach($company_fetched->photos as $photo)
                                <div class="col-md-3 col-xs-12">
                                    <div class="card" style="background-color: #f4f5f5;">
                                        <img class="img-responsive" src="{{$photo->photo_url}}" alt="{{$company_fetched->name}}" />
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
                <!-- RIGHT COL -->
            </div>

        </div>
        <!-- TEST -->

        <!-- OLD -->
        <!-- <div class="container">
            <div class="row m-t-20">
                <div class="col-md-12 col-xs-12 text-center">
                    <hr>
                    <h2>Avaliações</h2>
                    <p class="f-14 m-t-10">{{$company_fetched->current_rating}} de {{$company_fetched->total_rating}} avaliações</p>
                    <?php $rating_to_loop = $company_fetched->current_rating; ?>
                    @include('components.rating', ['size' => '35'])
                </div>
            </div>

            <div class="row m-t-20">
                @foreach($company_fetched->last_ratings as $rating)
                    <div class="col-md-4  col-sm-6 col-xs-12 text-center">

                        <div class="card">
                            <div class="card-header ch-alt">
                                 <div class="picture-circle picture-circle-p" style="background-image:url({{$rating->client->avatar}})"></div>
                                <h4>{{$rating->client->full_name}}</h4>
                                <?php $rating_to_loop = $rating->rating; ?>
                                @include('components.rating', ['size' => '22'])
                                <p>{{$rating->created_at->format('d/m/Y')}}</p>
                            </div>
                            <div class="card-body p-10">
                                <p>{{$rating->content}}</p>
                            </div>
                        </div>

                    </div>

                @endforeach
            </div>
        </div> -->

        <!-- Professional List -->
        <div class="container m-t-30">
            <h2 class="text-center m-t-20 m-b-20">Profissionais</h2>

            <div class="swiper-container swiper-certifications">
                <div class="swiper-wrapper">
                    @foreach($company_fetched->professionals as $professional)
                        <div class="swiper-slide text-center">
                            <div class="card">
                                <div class="card-header ch-alt text-center">
                                    <div class="picture-circle  picture-circle-p m-t-10" style="background-image:url({{$professional->avatar}})">
                                    </div>
                                    <h3 class="f-300">
                                        <a href="/new-landing/profissionais/{{$professional->id}}">{{$professional->full_name}}</a>
                                    </h3>
                                </div>
                                <div class="card-body card-padding text-center">
                                    <div class="">
                                        <?php $rating_to_loop = $professional->current_rating; ?>
                                        @include('components.rating', ['size' => '22'])
                                    </div>
                                    <div class="p-t-10 m-b-30">
                                        @foreach($professional->categories as $category)
                                            <a href="{!! route('landing.search.index', ['category' => $category->name]) !!}"><button class="btn btn-success btn-sm m-b-5">{{ $category->name }}</button></a>
                                        @endforeach
                                    </div>
                                    <hr class="m-t-20">
                                    <a href="{!! route('landing.professionals.show', $professional->id) !!}" title="{{ $professional->full_name }}">
                                        <button class="btn btn-primary f-300 f-16">
                                            <i class="ion-ios-plus-outline m-r-5 f-20"></i>Ver perfil
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="height: 10px;"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!-- /Professional List -->

    </section>

    @section('scripts')
        @parent

        <script>
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
        </script>


    @stop
@stop
