<style>
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

    .picture-circle-p {
        width: 68px;
        height: 68px;
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
    /* wrapper*/
    .wrapper {
        background-position: top center;
        background-size: cover;
        background-attachment: fixed;
        background-repeat: no-repeat;
        position: relative;
        width: 100%;
        padding: 50px 0;
    }

    .wrapper.call-to-search {
        color: #383938;
        background-image: url('https://weplaces.com.br/build/landing/weplaces/mapa.png');
    }

</style>

<hr class="m-0">
 <section id="contact" class="section">
    <div class="container">
        <h2 class="text-center m-t-20 m-b-20">Em destaque</h2>

        <div class="swiper-container swiper-featureds wow fadeInUp">
            <div class="swiper-wrapper">
                @foreach($companies as $index_company => $company)
                    <div class="swiper-slide text-center">
                        <div class="card">
                            <div class="card-header ch-alt text-center">
                                <div class="picture-circle  picture-circle-p m-b-10" style="background-image:url({{$company->avatar}})">
                                </div>
                                <h3 class="m-b-0 t-overflow">
                                    <a href="/new-landing/empresas/{{$company->slug}}" title="{{ $company->name }}">{{ $company->name }}</a>
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
                                <a href="/new-landing/empresas/{{$company->slug}}" title="{{ $company->name }}">
                                    <button class="btn btn-primary f-300 f-16" href="/new-landing/empresas/{{$company->slug}}">
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

    <div class="wrapper call-to-search">
        <div class="container">
            <div class="row m-t-30">
                <div class="col-md-12 col-xs-12 text-center">
                    <h4 class="f-700 m-b-30">Encontre empresas e seus profissionais para te ajudar a cuidar da sua saúde e estética.</h4>
                    <a href="{!! route('landing.search.index') !!}">
                        <button class="btn btn-primary m-t-10">Procurar empresas e profissionais</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</section>

@section('scripts')
    @parent

    <script>
        let swiperFeatureds = new Swiper('.swiper-featureds', {
            centeredSlides: true,
            spaceBetween: 15,
            loop: false,
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
    </script>
@stop
