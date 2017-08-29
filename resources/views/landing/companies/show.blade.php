

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

    .swiper-button-prev,
    .swiper-button-next {
        background-image: none;
        font-size: 40px;
        height: 40px;
        margin-top: -35px;
        top: 50%;
        padding: 0;
        color: #88C657;
    }

    .plan-limit{ display: block;}

    /* List & Cards With Swiper */
    .swiper-pagination-bullet,
    .swiper-pagination-bullet { border-color: #88C657 !important }

    .swiper-pagination-bullet.swiper-pagination-bullet-active,
    .swiper-pagination-bullet.swiper-pagination-bullet-active { background-color: #88C657 !important }


    /* Interactions */
    .btn.out { display: none !important; }
    .info { display: none; }
    .info.in { display: block; }

    #mapShow {
        height: 100%;
      }

 </style>
    <header>
        <div class="bg-picture" style="background-image:url({{$company_fetched->avatar}})"></div>
    </header>
    <section class="section p-t-20" id="show-company">
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
                                <div class="m-t-15">
                                    <button type="button" class="btn btn-sm btn-primary btn-target" data-target="#company-phone">Mostrar telefone</button>
                                    <div class="info" id="company-phone">
                                        <i class="ion-ios-telephone-outline m-r-5"></i>
                                        <span class="f-300 f-16"><a href="tel:{{ $company_fetched->phone }}">{{ $company_fetched->phone }}</a></span>
                                    </div>
                                </div>
                            @endif
                            <!-- Phone -->

                            <!-- Website -->
                            @if($company_fetched->website)
                                <div class="m-t-10">
                                    <button type="button" class="btn btn-sm btn-primary btn-target" data-target="#company-website">Mostrar Website</button>
                                    <div class="info" id="company-website">
                                        <i class="ion-ios-world-outline m-r-5"></i>
                                        <span class="f-300 f-16"><a href="{{ $company_fetched->website }}" target="_blank">{{ $company_fetched->website }}</a></span>
                                    </div>
                                </div>
                            @endif
                            <!-- Website -->

                            <button type="button" class="btn btn-xs btn-block btn-facebook m-t-20 p-5 f-15" @click="openShareFacebook()">
                                <i class="ion-social-facebook m-r-5" ></i>Compartilhar no facebook
                            </button>
                            <button type="button" class="btn btn-xs btn-block btn-whatsapp m-t-5 p-5 f-15" @click="openShareWhatsapp()">
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

                    <!-- Plan -->
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h4>Planos</h4>
                        </div>
                        <div class="card-body card-padding p-b-10">
                            @foreach($company_fetched->plans as $plan)
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <h4 class="m-b-10">{{ $plan->name }}</h4>
                                        <small class="label label-success p-10">R$ {{ $plan->value }}</small>
                                        <span class="plan-limit m-t-10 f-300">
                                            Esse plano conta com
                                            {{ $plan->limit_quantity ? $plan->quantity : "não possui limite de ".$plan->label."s" }}
                                            {{ $plan->quantity > 1 ? $plan->label."s" : $plan->label }}
                                            <p class="plan-description f-300 m-t-10">{{ $plan->description }}</p>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
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
                        @if(count($company_fetched->last_ratings) == 0)
                            <span class="f-300 m-t-30 m-b-30 d-block">Esta empresa ainda não possui avaliações</span>
                        @endif
                        @if(count($company_fetched->last_ratings) > 0)
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
                                    <div class="swiper-button-prev"><i class="ion-ios-arrow-back"></i></div>
                                    <div class="swiper-button-next"><i class="ion-ios-arrow-forward"></i></div>
                                    <div style="height: 50px;"></div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Ratings -->
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h2 class="f-300">Indicações</h2>
                        </div>
                        <div class="card-body p-t-5 p-b-5">
                            @if(count($company_fetched->last_ratings) == 0)
                                <span class="f-300 m-t-30 m-b-30 d-block">Esta empresa ainda não possui indicações</span>
                            @endif
                            @if(count($company_fetched->last_ratings) > 0)
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
                                    <div class="swiper-button-prev"><i class="ion-ios-arrow-back"></i></div>
                                    <div class="swiper-button-next"><i class="ion-ios-arrow-forward"></i></div>
                                    <div style="height: 50px;"></div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Professional list -->

                    <div class="card">
                        <div class="card-header ch-alt">
                            <h2 class="f-300">Profissionais</h2>
                        </div>
                        <div class="card-body p-t-5 p-b-5">
                            @if(count($company_fetched->professionals) == 0)
                            <span class="f-300 m-t-30 m-b-30 d-block">Esta empresa ainda não possui profissionais cadastrados</span>
                            @endif
                            @if(count($company_fetched->professionals) > 0)
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
                                    <div class="swiper-button-prev"><i class="ion-ios-arrow-back"></i></div>
                                    <div class="swiper-button-next"><i class="ion-ios-arrow-forward"></i></div>
                                    <div style="height: 50px;"></div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            @endif
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
                <div class="card-body" height="400px" style="height: 400px;">
                    <div id="mapShow" height="400px"></div>
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


            Vue.config.debug = true;
            var vm = new Vue({
                el: '#show-company',
                data: {
                },
                mounted: function() {

                },
                methods: {
                    openShareFacebook: function () {
                        let that = this
                        var url = `https://www.facebook.com/dialog/share?app_id=1854829291449231&href={{\Request::fullUrl()}}&display=popup&mobile_iframe=true`;

                        window.open(url, '_blank', 'location=yes');

                        // this.updateTrackingInfo('share_facebook')
                    },

                    openShareWhatsapp: function () {
                        var that = this

                        var url = `https://api.whatsapp.com/send?&text=Olá, encontrei o perfil de {{$company_fetched->name}}, confira também: {{\Request::fullUrl()}} .`;

                        window.open(url, '_system', null);
                        // this.updateTrackingInfo('contact_whatsapp')
                    },


                    handleSlug(text) {
                        return slug(text).toLowerCase()
                    },
                }

            })

            $(document).ready(function(){
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
                prevButton: '.swiper-button-prev',
                nextButton: '.swiper-button-next',
                breakpoints: {
                    768: {
                        slidesPerView: 1
                    }
                }
            })
            var swiperCertifications = new Swiper('.swiper-default-show', {
                centeredSlides: true,
                spaceBetween: 4,
                loop: false,
                slidesPerView: 1,
                slideToClickedSlide: true,
                paginationClickable: true,
                prevButton: '.swiper-button-prev',
                nextButton: '.swiper-button-next',
                pagination: '.swiper-pagination',
            })

            var mapStyle = [
              {
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#ebe3cd"
                  }
                ]
              },
              {
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#523735"
                  }
                ]
              },
              {
                "elementType": "labels.text.stroke",
                "stylers": [
                  {
                    "color": "#f5f1e6"
                  }
                ]
              },
              {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [
                  {
                    "color": "#c9b2a6"
                  }
                ]
              },
              {
                "featureType": "administrative.land_parcel",
                "elementType": "geometry.stroke",
                "stylers": [
                  {
                    "color": "#dcd2be"
                  }
                ]
              },
              {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#ae9e90"
                  }
                ]
              },
              {
                    "featureType": "landscape.natural",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#dfd2ae"
                        }
                    ]
                },
                {
                    featureType: 'poi',
                    stylers: [{visibility: 'off'}]
                },
                {
                    featureType: 'transit',
                    elementType: 'labels.icon',
                    stylers: [{visibility: 'off'}]
                },
              {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#f5f1e6"
                  }
                ]
              },
              {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#fdfcf8"
                  }
                ]
              },
              {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#f8c967"
                  }
                ]
              },
              {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [
                  {
                    "color": "#e9bc62"
                  }
                ]
              },
              {
                "featureType": "road.highway.controlled_access",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#e98d58"
                  }
                ]
              },
              {
                "featureType": "road.highway.controlled_access",
                "elementType": "geometry.stroke",
                "stylers": [
                  {
                    "color": "#db8555"
                  }
                ]
              },
              {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#806b63"
                  }
                ]
              },
              {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#dfd2ae"
                  }
                ]
              },
              {
                "featureType": "transit.line",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#8f7d77"
                  }
                ]
              },
              {
                "featureType": "transit.line",
                "elementType": "labels.text.stroke",
                "stylers": [
                  {
                    "color": "#ebe3cd"
                  }
                ]
              },
              {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [
                  {
                    "color": "#dfd2ae"
                  }
                ]
              },
              {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [
                  {
                    "color": "#b9d3c2"
                  }
                ]
              },
              {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                  {
                    "color": "#92998d"
                  }
                ]
              }
            ];

            function initMap() {
                var myLatLng = {lat: {{$company_fetched->lat}}, lng: {{$company_fetched->lng}} };

                var contentString =
                    '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h1 id="firstHeading" class="firstHeading">{{$company_fetched->name}}</h1>'+
                        '<div id="bodyContent" style="font-size: 11px;">'+
                            '<p><b>Descrição:</b> {{$company_fetched->description}}</p>'+
                            '<p><b>Endereço:</b> {{$company_fetched->address['full_address']}}</p>'+
                        '</div>'+
                    '</div>';

                var infowindow = new google.maps.InfoWindow({
                  content: contentString,
                  maxWidth: 200
                });


                var map = new google.maps.Map(document.getElementById('mapShow'), {
                  zoom: 16,
                  center: myLatLng,
                  styles: mapStyle
                });

                var marker = new google.maps.Marker({
                  position: myLatLng,
                  map: map,
                  icon: 'https://s3.amazonaws.com/isaudavel-assets/img/MAP+ICON-02.png',
                  title: '{{$company_fetched->name}}'
                });

                marker.addListener('click', function() {
                  infowindow.open(map, marker);
                });
              }


        </script>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&callback=initMap">
            </script>


    @stop
@stop
