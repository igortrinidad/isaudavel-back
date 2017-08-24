@extends('landing.companies.index', ['header_with_search' => true])

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

        /* Tabs Default */
        /* Tabs */
        .tabs {

            position: relative;
            top: -1px;
            overflow: visible !important;
            margin-top: -100px;
            margin-bottom: 40px;
            padding-top: 1px;
        }

        .tabs .tab {
            border-top: 1px solid rgba(255, 255, 255, .3);
            border-left: 1px solid rgba(255, 255, 255, .3);
            display: flex;
            height: 50px;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #70c058;
            color: rgba(255, 255, 255, .8);
            padding: 0 10px;
            font-size: 14px;
            position: relative;
            cursor: pointer;
        }
        .tabs .tab:first-child { border-left: 0;}

        .tabs .tab.swiper-slide-active:before{
            content: "";
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            width: 0;
            height: 0;
            bottom: -6px;
            left: 50%;
            margin-left: -6px;
            position: absolute;

            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #70c058;
        }

    </style>

    <section class="section" id="companies-list">

        <!-- Categories Tabs -->
        <div class="swiper-container tabs" ref="tabs">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                <div class="swiper-slide tab" data-url="{{$category->name}}">
                    {{$category->name}}
                </div>
                @endforeach
            </div>
        </div>
        <!-- Categories Tabs -->

        <div class="container">

            <div class="text-center">
                <h2 class="m-t-30">Empresas</h2>
                <p class="f-300">Encontre a empresa ou profissional para ajudar você a cuidar da sua saúde.</p>
            </div>

            <div class="row m-t-20">
                @foreach($companies as $company)
                <div class="col-md-4 col-xs-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <div class="picture-circle  picture-circle-p m-b-10" style="background-image:url({{$company->avatar}})">
                            </div>
                            <h3 class="m-b-0 t-overflow">
                                <a href="/new-landing/empresas/{{$company->slug}}" title="{{ $company->name }}">{{ $company->name }}</a>
                            </h3>
                        </div>
                        <div class="card-body card-padding text-center">
                            <h4>Avaliação</h4>
                            <div class="wp-rating-div">
                                <?php $rating_to_loop = $company->current_rating; ?>
                                @include('components.rating', ['size' => '22'])
                            </div>

                            <div class="m-t-20">
                                @foreach($company->categories as $index_category => $category)
                                    <a href="{!! route('landing.search.index', ['category' => $category->name]) !!}"><button class="btn btn-success btn-sm m-b-5">{{ $category->name }}</button></a>
                                @endforeach
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

        <div class="container m-t-30">
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
                    this.initSwiperTabs()
                    console.log('Vue rodando no companies-list');
                },
                methods: {

                    initSwiperTabs() {
                        setTimeout(() => {
                            // Ver um jeito de redirecionar no onSlideChangeEnd do swiper para a categoria certa

                            // let currentTab
                            // if (window.location.href === 'http://isaudavel-api.dev/new-landing/buscar') currentTab = 0


                            var url = new URL(window.location.href);
                            var category = url.searchParams.get("category");
                            var city = url.searchParams.get("city");

                           var atualindex = $('.swiper-slide[data-url="'+category+'"]').index();

                            this.swiperTabs = new Swiper(this.$refs.tabs, {
                                spaceBetween: 0,
                                slidesPerView: 7,
                                loop: true,
                                centeredSlides: true,
                                initialSlide: atualindex,
                                slideToClickedSlide: true,
                                onSlideChangeEnd: swiper => {

                                    var categorySelected = $('.swiper-slide-active').data('url')

                                    if(!city){
                                        if(categorySelected && categorySelected != category){
                                            window.location.href = '?category=' + categorySelected;
                                        }
                                    } else {
                                        if(categorySelected && categorySelected != category){
                                            window.location.href = '?category=' + categorySelected + '&city=' + city;
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
