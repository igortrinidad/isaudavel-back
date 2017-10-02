<style media="screen">
    .card-picture-header {
        box-sizing: border-box;
        margin: 0 auto;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 150px;
        border: solid 1px #EBECEC;
    }
    .card-header { position: relative ;}
    .card-header:hover .hover{
        display: flex;
    }
    .card-header .hover {
        width: 100%; height: 100%;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        background-color: rgba(56, 57, 56, .6);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #F4F5F5;
        border-radius: 4px;
        font-size: 50px;
        display: none;
    }

    /* Event Cats */
    .list-cats {
        height: 78px; width: 100%;
        position: relative;
        display: flex;
        align-items: flex-end;
    }

    /* Event */
    .row.row-event { margin: -52px -5px 0 -5px; }

    .row.row-event .event-col { padding: 0 5px !important; }

    /* Event Date */
    .event-date {
    height: 78px; width: 100%;
    border: 2px solid #383938;
    border-radius: 4px;
    }

    .event-date-header,
    .event-date-body {
    width: 100%;
    position: relative;
    }

    .event-date-header {
    background-color: #383938;
    display: block;
    color: #F4F5F5;
    padding: 2px 0;
    }

    .event-date-body {
    text-transform: uppercase;
    padding: 4px;
    }
    .event-date-body span { display: block; }
    .event-date-body span:first-child { border-bottom: 1px solid rgba(56, 57, 56, .6); }


    .divider{
        background-image: url("/images/pattern-isaudavel-5-300.png");
    }

    .gray {
        background-color: #f4f5f4;
    }
</style>

<section class="section {{ $has_title ? 'gray' : 'divider' }}">

    <!-- Event Section -->
    <div class="container">

        <!-- User Activities -->
        <div class="row wow fadeInUp" style="margin-top: -160px;">
            <div class="col-sm-12">
                <div class="card card-activities">
                    <h4 class="is-activities-title"><small>atividades de</small>Usuarios</h4>
                    <div class="card-body">
                        <div class="swiper-container swiper-user-activities">
                            <div class="swiper-wrapper p-t-10">
                                @foreach($companies as $index_company => $company)
                                    <div class="swiper-slide text-center p-l-10 p-r-10">
                                        <div class="is-box">
                                            <div class="picture-circle picture-circle-p m-b-10" style="background-image:url({{$company->avatar}})">
                                            </div>
                                            <h5 class="f-300 m-b-5">{{ $company->name }} adicionou <strong>Tapioca Fit</strong></h5>
                                            <span class="label label-success">Receita</span>
                                            <a href="#" class="label label-primary" title="Confira a reiceita de {{ $company->name }}">Ver mais</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-prev is-swiper-button-default arrow-ms arrow-top"><i class="ion-ios-arrow-back"></i></div>
                            <div class="swiper-button-next is-swiper-button-default arrow-ms arrow-top"><i class="ion-ios-arrow-forward"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / User Activities -->

        @if($has_title)
            <h2 class="is-title secondary">
                Eventos esportivos
                <span class="is-icon is-icon-events"></span>
            </h2>
        @endif

        <div class="row m-t-30 wow fadeInUp">

            @unless($events->count())
            <!-- No Events Found -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header ch-alt p-30">
                        <p class="text-center m-0 f-300">Nenhum evento localizado.</p>
                    </div>
                </div>
            </div>
            <!-- No Events Found -->
            @else

                <!-- Events List -->
                <div class="col-sm-9">
                    <div class="swiper-container swiper-featureds-events">
                        <div class="swiper-wrapper p-0">
                            @foreach($events as $event)
                                <div class="swiper-slide">
                                    <a href="/eventos/{{ $event->slug }}" title="Confira mais sobre {{ $event->name }}">
                                        <!-- Card Event -->
                                        <div class="card m-b-10 cursor-pointer">
                                            <!--  Card Event Header-->
                                            <div class="card-header ch-alt card-picture-header" style="background-image:url('{{ $event->avatar }}')">
                                                <div class="hover">
                                                    <i class="ion-ios-plus-empty"></i>
                                                </div>
                                            </div>
                                            <!-- / Card Event Header -->

                                            <!--  Card Event body-->
                                            <div class="card-body card-padding">
                                                <div class="row row-event">
                                                    <!-- Event Date -->
                                                    <div class="col-xs-3 event-col">
                                                        <div class="event-date text-center">
                                                            <div class="event-date-header">
                                                                <span class="f-700 f-12">{{ $event->date->format('Y') }}</span>
                                                            </div>
                                                            <div class="event-date-body">
                                                                <span class="f-700 f-16">{{ $event->date->format('d') }}</span>
                                                                <span class="f-300">{{ $event->date->format('M') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- / Event Date -->
                                                    <div class="col-xs-9 event-col">
                                                        <div class="list-cats">
                                                            @foreach($event->categories as $indexCat => $category)
                                                                @if($indexCat < 1)
                                                                    <span class="label label-success t-overflow f-300 f-14 m-t-5 m-r-5">
                                                                    {{ $category->name }}
                                                                </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <h3 class="f-300 m-t-10 t-overflow">{{ $event->name }}</h3>
                                                <!-- location -->
                                                <div class="m-t-10">
                                                    <span class="d-block f-300 f-14">
                                                        <i class="icon ion-ios-location-outline m-r-10 f-20"></i>{{ $event->city }} - {{ $event->state }}
                                                    </span>
                                                </div>
                                                <!-- / location -->

                                                <div class="text-center">
                                                    <button class="btn btn-primary f-300 f-16 m-t-15">
                                                        Mais informações
                                                    </button>
                                                </div>

                                            </div>
                                            <!-- / Card Event body -->
                                        </div>
                                        <!-- / Card Event -->
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-prev is-swiper-button-default arrow-ls"><i class="ion-ios-arrow-back"></i></div>
                        <div class="swiper-button-next is-swiper-button-default arrow-ls"><i class="ion-ios-arrow-forward"></i></div>
                    </div>
                </div>
                <!-- / Events List -->

                <!-- Call To Download -->
                <div class="col-sm-3">
                    <div class="card card-pattern">
                        <div class="card-body card-padding">
                            <div class="text-center">
                                <h4 class="f-300" style="margin-top: 60px;">
                                    Com o <strong style="color: #72c157">iSaudavel</strong>
                                    você pode encontrar as melhores empresas e profissionais para te
                                    ajudar a manter a forma, mas também pode marcar presença em eventos
                                    próximos a você.
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Call To Download -->

                <div class="col-sm-12 m-t-30 text-center">
                    <a href="/eventos" class="btn btn-success f-300">Veja todos os eventos</a>
                </div>
            @endunless
        </div>
    </div>
    <!-- / Event Section -->
</section>

@section('scripts')
    @parent

    <script>
        var swiperFeaturedsEvents = new Swiper('.swiper-featureds-events', {
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
        var swiperHomeActivities = new Swiper('.swiper-user-activities', {
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
