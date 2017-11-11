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
        transform: scale(1) !important;
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
        color: #383938;
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
        background-image: url('/images/call-to-search.png');
    }

    .featured{
        background-color: rgba(244, 244, 245, 1);
    }
</style>

 <section id="contact" class="section featured p-t-30 p-b-30">
    <div class="container">

        {{-- @include('landing.home.activities-professional') --}}

        <h2 class="is-title secondary">
            Empresas em destaque
            <span class="is-icon is-icon-companies"></span>
        </h2>

        <div class="row m-t-30 wow fadeInUp">

            <!-- Featured Companies -->
            <div class="col-sm-9 m-b-30">
                <div class="swiper-container swiper-companies-featureds">
                    <div class="swiper-wrapper p-t-0">
                        @foreach($companies as $index_company => $company)
                            <div class="swiper-slide text-center">
                                <div class="card m-0">
                                    <div class="card-header ch-alt text-center">
                                        <div class="picture-circle  picture-circle-p m-b-10" style="background-image:url({{$company->avatar}})">
                                        </div>
                                        <h3 class="m-b-0 t-overflow">
                                            <a  href="{!! route('landing.companies.show', $company->slug) !!}" title="{{ $company->name }}">{{ $company->name }}</a>
                                        </h3>
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
                                        <a  href="{!! route('landing.companies.show', $company->slug) !!}" title="{{ $company->name }}">
                                            <button class="btn btn-primary f-16 m-t-20">
                                                Ver perfil
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev is-swiper-button-default arrow-ls"><i class="ion-ios-arrow-back"></i></div>
                    <div class="swiper-button-next is-swiper-button-default arrow-ls"><i class="ion-ios-arrow-forward"></i></div>
                </div>
            </div>
            <!-- / Featured Companies -->

            <!-- Call To Download -->
            <div class="col-sm-3">

                @include('landing.components.card-to-download')
                
            </div>
            <!-- / Call To Download -->

            <div class="col-sm-12 text-center">
                <a href="{!! route('landing.search.index', ['category' => 'pilates']) !!}">
                    <button class="btn btn-primary m-t-10">Procurar empresas e profissionais</button>
                </a>
            </div>

        </div>
    </div>

</section>

@section('scripts')
    @parent

    <script>
        var swiperFeatureds = new Swiper('.swiper-companies-featureds', {
            initialSlide: 0,
            spaceBetween: 25,
            slidesPerView: 3,
            slideToClickedSlide: false,
            prevButton: '.swiper-button-prev',
            nextButton: '.swiper-button-next',
            breakpoints: {
                768: {
                    slidesPerView: 1
                }
            }
        })
        var swiperHomeActivities = new Swiper('.swiper-professional-activities', {
            initialSlide: 0,
            spaceBetween: 0,
            slidesPerView: 4,
            slideToClickedSlide: false,
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
