@extends('landing.companies.index', ['header_with_search' => true])

 @section('landing-content')

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

        .picture-circle-p{
            width: 68px;
            height: 68px;
        }

        .picture-circle-m{
            width: 86px;
            height: 86px;
        }

        .section {
            background-color: #F4F5F5;
        }
        .wp-rating-div {
            color: #FFCC5F;
            font-size: 25px;
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
        /*.wrapper.call-to-recomendation {
            background-image: url('/images/gym.jpg');
        }*/

        #companies-list{
            background-image: url("/images/pattern-isaudavel-5-300.png");
            /*background-color: #fff;*/
        }

    </style>

    <section class="section p-b-0" id="companies-list" :class="categoryFromParams">
        <!-- Categories Tabs -->
        <div class="swiper-container tabs" ref="tabs">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                <div class="swiper-slide tab" data-url="{{$category->slug}}">
                    {{$category->name}}
                </div>
                @endforeach
            </div>
            <div class="swiper-button-prev">
                <i class="ion-ios-arrow-left"></i>
            </div>
            <div class="swiper-button-next">
                <i class="ion-ios-arrow-right"></i>
            </div>
        </div>
        <!-- Categories Tabs -->

        <div class="container">

            <div class="row">
                {{-- Companies List --}}
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body card-padding">
                                    <h2 class="f-300">
                                        Você está pesquisando por <b>{{ $category_fetched->name }}</b>
                                        @if(isset($city_fetched))
                                            <span> em <b>{{$city_fetched }}</b></span>
                                        @endif
                                    </h2>

                                    @if(count($companies) == 0)
                                        <div class="m-t-30">
                                            <span class="f-300 f-18">Porém, nenhuma empresa ou profissional foi encontrado <i class="ion-sad-outline"></i></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        @foreach($companies as $company)
                        <div class="col-md-4 col-xs-12">
                            <div class="card">
                                <div class="card-header ch-alt text-center">
                                    <div class="picture-circle  picture-circle-p m-b-10" style="background-image:url({{$company->avatar}})">
                                    </div>
                                    <h3 class="m-b-0 t-overflow">
                                        <a  href="{!! route('landing.companies.show', $company->slug) !!}" title="{{ $company->name }}">{{ $company->name }}</a>
                                    </h3>
                                </div>
                                <div class="card-body card-padding text-center">
                                    <h4>Avaliação</h4>
                                    <div class="wp-rating-div">
                                        <?php $rating_to_loop = $company->current_rating; ?>
                                        <h3>{{$company->current_rating}}</h3>
                                        @include('components.rating', ['size' => '22'])
                                    </div>

                                    <div class="m-t-20">
                                        @foreach($company->categories as $index_category => $category)
                                            <a href="{!! route('landing.search.index', ['category' => $category->slug]) !!}"><button class="btn btn-success btn-sm m-b-5">{{ $category->name }}</button></a>
                                        @endforeach
                                    </div>

                                    <div class="m-t-20">
                                        <span class="f-300 f-18 t-overflow">
                                            <i class="ion-ios-location-outline m-r-5"></i>
                                            {{ $company->address['full_address'] }}
                                        </span>
                                    </div>
                                    @if(isset($company->distance_km) )
                                    <div class="m-t-20">
                                        <span class="f-300 f-18 ">
                                            <i class="ion-ios-location-outline m-r-5"></i>
                                            {{ $company->distance_km }}km
                                        </span>
                                    </div>
                                    @endif
                                    <a  href="{!! route('landing.companies.show', $company->slug) !!}" title="{{ $company->name }}">
                                        <button class="btn btn-block btn-primary m-t-20 f-16">
                                            Ver perfil
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Navigation --}}
                        <div class="col-sm-12">
                            {!! $companies->render() !!}
                        </div>
                        {{-- End Navigation --}}

                    </div>
                </div>
                {{-- End Companies List --}}

                {{-- Sidebar --}}
                <div class="col-sm-3">
                    @include('landing.home.sidebar', ['current_view' => 'companies'])
                </div>
                {{-- End Side Bar --}}
            </div>
        </div>
        <hr class="divider-colorful">
        <div class="wrapper call-to-recomendation">
            <div class="container text-center">
                <h4 class="f-700 m-b-30">Não achou a empresa ou profissional que você procura?</h4>
                <button class="btn btn-secondary">Indique uma empresa ou profissional</button>
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
                    city: '',
                    categoryFromParams: null,
                },
                mounted: function() {
                    this.initSwiperTabs()

                    var url = new URL(window.location.href);
                    var categoryFromParams = url.searchParams.get("category");
                    var city = url.searchParams.get("city");

                    this.categoryFromParams = categoryFromParams;
                    this.city = city
                    console.log('Vue rodando no companies-list');
                },
                methods: {

                    initSwiperTabs() {
                        setTimeout(() => {

                            var url = new URL(window.location.href);
                            var category = url.searchParams.get("category");
                            var city = url.searchParams.get("city");

                            var lat = url.searchParams.get("lat");
                            var lng = url.searchParams.get("lng");

                           var atualindex = $('.swiper-slide[data-url="'+category+'"]').index();

                            this.swiperTabs = new Swiper(this.$refs.tabs, {
                                spaceBetween: 0,
                                slidesPerView: 7,
                                loop: true,
                                centeredSlides: true,
                                initialSlide: atualindex,
                                slideToClickedSlide: true,
                                nextButton: '.swiper-button-next',
                                prevButton: '.swiper-button-prev',
                                onSlideChangeEnd: swiper => {

                                    var categorySelected = $('.swiper-slide-active').data('url')

                                    if(!lat){
                                        if(categorySelected && categorySelected != category){
                                            window.location.href = '?category=' + categorySelected;
                                        }
                                    } else if(lat) {
                                        if(categorySelected != category){
                                            window.location.href = '?category=' + categorySelected + '&city=' + city + '&lat=' + lat + '&lng=' + lng;
                                        }
                                    }

                                },
                                breakpoints: {
                                    768: {
                                        slidesPerView: 3,
                                    },
                                }
                            })
                        }, 50)
                    },

                }

            })

        </script>


    @stop
@stop
