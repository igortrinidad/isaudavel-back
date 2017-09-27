@extends('landing.events.index')

@section('landing-content')

    <style media="screen">
    .store-badge { margin: 0 auto; }
    #mapShow {
        height: 100%;
    }

    .event-name {
        height: 78px; width: 100%;
        position: relative;
        display: flex;
        align-items: flex-end;
        flex-flow: row wrap;
    }

    .card-header { position: relative; }
    .comment {
        background-image: url('/images/comment.png');
        background-repeat: no-repeat;
        background-position: center right;
    }
    .comment .icon {
        position: absolute;
        top: 10px; right: 10px;
        max-width: 100%;
    }
    .comment:before {
        content: "";
        position: absolute;
        bottom: -20px; left: 50%;
        margin-left: -20px;
        display: inline-block;
        vertical-align: middle;
        margin-right: 10px;
        width: 0;
        height: 0;

        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-top: 20px solid #F7F7F7;
    }

    .btn.btn-facebook{ background-color: #3b5998; color: #F4F5F5; }
    .btn.btn-whatsapp{ background-color: #1ebea5; color: #F4F5F5; }

    .badge.badge-success { background-color: #00A369; color: #FFF; }

    .event-avatar {
        background-position: top center;
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%; height: 350px;
        margin-top: -100px;
        background-attachment: fixed;
    }
    .fix-event {
        width: 100px;
    }
    </style>
    <section id="events-show" class="section divider">
        <div class="event-avatar" style="background-image: url('{{ $event_fetched->avatar }}')">
        </div>
        <div class="container">

            <div class="row row-event" style="margin-top: -28px;">
                <div class="col-sm-1 col-xs-3 event-col">
                    <div class="event-date text-center">
                        <div class="event-date-header">
                            <span class="f-700 f-12">{{ $event_fetched->date->format('Y') }}</span>
                        </div>
                        <div class="event-date-body">
                            <span class="f-700 f-16">{{ $event_fetched->date->format('d') }}</span>
                            <span class="f-300">{{ $event_fetched->date->format('M') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-11 col-xs-9 event-col">
                    <div class="event-name">
                        <h2 class="f-400 m-0 m-t-25 t-overflow">{{ $event_fetched->name }}</h2>
                        <span>
                            <i class="ion-ios-clock-outline f-20 m-r-5"></i>
                            <span class="f-300">{{ $event_fetched->time }}</span>
                        </span>
                    </div>
                </div>
            </div>



            <div class="row m-t-30">
                <!-- CENTER COL "ABOUT" -->
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="f-400">Sobre o evento</h2>
                        </div>
                        <div class="card-body" style="padding: 6px;">

                            <h4 class="f-300 m-t-15 m-b-10">{{ count($event_fetched->categories) > 1 ? 'Categorias' : 'Categoria' }}:</h4>
                            @foreach($event_fetched->categories as $category)
                                <span class="label label-success">{{ $category->name }}</span>
                            @endforeach

                            <h4 class="f-300 m-t-15 m-b-10">Endereço:</h4>
                            <span class="f-300">{{ $event_fetched->address['full_address'] }}</span>

                            <!-- Event Share -->
                            <h4 class="f-300 m-t-15">Compartilhe:</h4>
                            <div class="m-t-10 m-b-30">
                                <button type="button" class="btn btn-facebook btn-xs p-5 p-l-10 p-r-10" @click="openShareFacebook()">
                                    <i class="ion-social-facebook m-r-5"></i>Facebook
                                </button>
                                <button type="button" class="btn btn-whatsapp btn-xs p-5 p-l-10 p-r-10" @click="openShareWhatsapp()">
                                    <i class="ion-social-whatsapp m-r-5"></i>Whatsapp
                                </button>
                                <button type="button" class="btn btn-primary btn-xs p-5 p-l-10 p-r-10" @click="copyUrl()">
                                    <i class="ion-ios-copy m-r-5"></i>Copiar link
                                </button>
                            </div>
                            <!-- / Event Share -->

                            <hr>

                            <!-- Event Description -->
                            <h4 class="f-300">Descrição do evento:</h4>
                            <div class="f-300 m-t-10 m-b-30">
                                {!! $event_fetched->description !!}
                            </div>
                            <!-- / Event Description -->

                            <hr>

                            <!-- Event Details -->
                            <h4 class="f-300">Detalhes do evento:</h4>
                            <ul class="list-group m-t-10 m-b-30">

                            @if($event_fetched->is_free)
                                <li class="list-group-item f-300">
                                    <strong>Valor</strong> <span class="badge badge-success">Gratuito</span>
                                </li>
                            @else
                                <li class="list-group-item f-300">
                                    <strong>Valor</strong> <span class="badge badge-success">R$ {{ $event_fetched->value }}</span>
                                </li>
                            @endif

                                <li class="list-group-item f-300">
                                    <strong>Data</strong> <span class="badge badge-success">{{ $event_fetched->date->format('d/m/Y') }}</span>
                                </li>
                                <li class="list-group-item f-300">
                                    <strong>Início</strong> <span class="badge badge-success">{{ $event_fetched->time }}</span>
                                </li>
                                <li class="list-group-item f-300">
                                    <strong>Participantes confirmados</strong> <span class="badge badge-success">{{ $event_fetched->total_participants }}</span>
                                </li>
                            </ul>
                            <!-- / Event Details -->

                            <hr>

                            <!-- Event Photos -->
                            @if(count($event_fetched->photos) > 0)
                                <h4 class="f-300">Imagens do evento:</h4>
                                <div class="row row-event m-t-10">
                                    @foreach($event_fetched->photos as $photo)
                                    <div class="col-sm-3 event-col">
                                        <img class="img-responsive" src="{{ $photo->photo_url }}" alt="">
                                    </div>
                                    @endforeach
                                </div>

                            @else
                                <h4 class="f-300">Este evento ainda não possui imagens</h4>
                            @endif
                            <!-- Event Photos -->

                        </div>
                    </div>
                </div>
                <!-- / CENTER COL "ABOUT" -->

                <!-- RIGHT COL "PARTICIPANTS" -->
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header ch-alt p-5 text-center">
                            <h2 class="f-400 m-t-20 m-b-10">Participantes</h2>
                            @if(count($event_fetched->participants) > 0)
                                <span class="f-300">
                                    {{ count($event_fetched->participants) }}
                                    {{ count($event_fetched->participants) > 1 ? 'confirmados' : 'confirmado' }}
                                </span>
                            @endif
                            @if(count($event_fetched->participants) == 0)
                                <span class="f-300">Nenhum participante confirmando ainda.</span>
                            @endif
                        </div>
                        @if(count($event_fetched->participants) > 0)
                            <div class="card-body">

                                <!-- List participants -->
                                <div class="row m-t-30">
                                    @foreach($event_fetched->participants as $indexParticipant => $participant)
                                        @if($indexParticipant < 8)
                                            <div class="col-sm-6 text-center m-t-5">
                                                <div class="picture-circle picture-circle-p" style="background-image:url('{{ $participant->participant->avatar }}')"></div>
                                                <h5 class="m-b-0 m-t-10 f-300 t-overflow">{{ $participant->participant->full_name }}</h5>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- / List participants -->

                                <div class="row m-t-30 m-b-20">
                                    @if(count($event_fetched->participants) > 8)
                                        <div class="col-sm-12 text-center">
                                            <button type="button" data-toggle="modal" data-target="#modal-all-participants" class="btn btn-primary btn-block btn-xs p-5 m-b-10">
                                                Ver todos participantes ({{ count($event_fetched->participants) }})
                                            </button>
                                        </div>
                                    @endif
                                    <!--
                                    <div class="col-sm-12 text-center">
                                        <button type="button" class="btn btn-success btn-block btn-xs p-5">Confirmar participação</button>
                                    </div>
                                    -->
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card">
                        <div class="card-body card-padding">
                            <!-- Call To Download -->
                            <div class="text-center">
                                <h3 class="f-300">Baixe o <strong style="color: #72c157">iSaudavel</strong> e confirme sua presença no evento.</h3>
                                <div class="row">
                                    <div class="col-sm-12 m-t-20">
                                        <a href="https://play.google.com/store/apps/details?id=com.isaudavel" target="_blank" title="Faça o download na PlayStore para Android">
                                            <img class="store-badge img-responsive" src="/images/play_store_btn.png" alt="Faça o download na PlayStore para Android">
                                        </a>
                                    </div>
                                    <div class="col-sm-12 m-t-5">
                                        <a href="https://itunes.apple.com/us/app/isaudavel/id1277115133?mt=8" target="_blank" title="Faça o download na APP Store para IOS">
                                            <img class="istore-badge mg-responsive" src="/images/app_store_btn.png" alt="Faça o download na APP Store para IOS">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- / Call To Download -->
                        </div>
                    </div>

                </div>
                <!-- RIGHT COL "PARTICIPANTS" -->

            </div>
        </div>
    </section>

    <!-- Event Map -->
    <section class="section gray">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="f-400 m-t-20 m-b-10">Mapa</h2>
                            <span class="f-300">Saiba como chegar no local do evento</span>
                        </div>
                        <div class="card-body" height="400px" style="height: 400px;">
                            <div id="mapShow" height="400px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Event Map -->

    <!-- Event Comments -->
    <section class="section divider">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center">
                        <h2 class="m-t-20 m-b-20">Comentários do evento</h2>
                        @if(count($event_fetched->comments) > 0)
                            <span class="f-300">Total de {{ count($event_fetched->comments) }} comentários</span>
                        @endif
                        @if(count($event_fetched->comments) == 0)
                            <span class="f-300">Nenhum comentário foi públicado ainda.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- List comments -->
            @if(count($event_fetched->comments) > 0)
                <div class="row row-event m-t-30">
                    <div class="col-sm-12 event-col">
                        <div class="card">
                            @foreach($event_fetched->comments as $comment)
                            <div class="card-header ch-alt comment">
                                <!-- <img class="icon" src="/images/comment.png" alt=""> -->
                                <p class="f-13 text-center">
                                    {{ $comment->content }}
                                </p>
                            </div>
                            <div class="card-body card-padding">
                                <div class="text-center m-t-20">
                                    <div class="picture-circle picture-circle-p" style="background-image:url('{{ $comment->from->avatar }}')">
                                    </div>
                                    <h5 class="f-300 m-t-15">{{ $comment->from->full_name }} em:
                                        <span class="f-12">
                                            {{ $comment->created_at->format('d/m/Y') }}
                                        </span>
                                    </h5>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <!-- / List comments -->

        </div>
    </section>
    <!-- / Event Comments -->

    <!-- MODAL PARTICIPANTS -->
    <div id="modal-all-participants" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Todos participantes confirmados</h4>
                </div>
                <div class="modal-body">
                    <!-- List participants -->
                    <div class="row row-event m-t-30">
                        @foreach($event_fetched->participants as $participant)
                            <div class="col-sm-4 col-event text-center">
                                <div class="card">
                                    <div class="card-header ch-alt">
                                        <div class="picture-circle picture-circle-p" style="background-image:url('{{ $participant->participant->avatar }}')"></div>
                                        <h5 class="m-b-0 m-t-10 f-300 t-overflow">{{ $participant->participant->full_name }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- / List participants -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- / MODAL PARTICIPANTS -->

    @section('scripts')
        @parent
        <script>
            Vue.config.debug = true;
            var vm = new Vue({
                el: '#events-show',
                data: {
                },
                computed: {

                },
                mounted: function() {

                },
                methods: {
                    // Facebook share
                    openShareFacebook: function() {
                        let that = this
                        var url = 'https://www.facebook.com/dialog/share?app_id=151705885358217&href=https://isaudavel.com/eventos/{{ $event_fetched->slug }}&display=popup&mobile_iframe=true';
                            if(window.cordova){
                                var ref = window.open(url, '_blank', 'location=yes');
                                ref.addEventListener('loadstart', function(event) {
                                    var url = "https://www.facebook.com/dialog/return/close";
                                    if (event.url.indexOf(url) !== -1) {
                                        ref.close();
                                        successNotify('', 'Evento compartilhado com sucesso!')
                                    }
                                });
                            } else {
                                window.open(url, '_blank', 'location=yes');
                            }
                    },
                    // Whatsapp share
                    openShareWhatsapp: function() {
                        var that = this
                        var url = 'https://api.whatsapp.com/send?text=Encontrei o evento {{ $event_fetched->name }} no iSaudavel, veja o abaixo: https://isaudavel.com/eventos/{{ $event_fetched->slug }}';
                        window.open(url, '_system', null);
                    },

                    copyUrl: function() {
                        var that = this
                        var url = 'https://isaudavel.com/eventos/{{ $event_fetched->slug }}';

                        copyToClipboard(url);

                        successNotify('', 'Link copiado para a área de transferência');
                    },
                }

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
                var myLatLng = {lat: {{$event_fetched->lat}}, lng: {{$event_fetched->lng}} };

                var contentString =
                    '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h1 id="firstHeading" class="firstHeading">{{$event_fetched->name}}</h1>'+
                        '<div id="bodyContent" style="font-size: 11px;">'+
                            '<p><b>Endereço:</b> {{$event_fetched->address['full_address']}}</p>'+
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
                  title: '{{$event_fetched->name}}'
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
