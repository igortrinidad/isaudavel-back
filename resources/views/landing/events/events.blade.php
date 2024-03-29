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

    /* Pagination */
    .pagination {
        font-size: 16px !important;
    }
    .pagination > li > a,
    .pagination > li > span {
        background-color: #fff;
        color: #73C158 !important;
    }

    .pagination > li.active > a,
    .pagination > li.active > span {
        background-color: #73C158 !important;
        border-color: #73C158 !important;
        color: #fff !important;
    }

    .pagination > li.disabled > a,
    .pagination > li.disabled > span {

        color: #ccc !important;
    }

    .pagination > li > a > i,
    .pagination > li > span > i{
        padding: 6px;
    }


</style>

<section class="section gray divider">

    <!-- Event Section -->
    <div class="container">

        <div class="row">
            <div class="col-sm-9">
                <div class="row">
                    @if($events->count() == 0)
                        <!-- No Events Found -->
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body card-padding">
                                    <span class="f-300 f-18">Nenhum evento foi encontrado <i class="ion-sad-outline"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- No Events Found -->
                    @else
                        <!-- Events List -->
                        @foreach($events as $event)
                            <div class="col-md-4 col-xs-12">
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
                                                         <span class="label label-success t-overflow f-300 f-14 m-t-5 m-r-5">
                                                                {{ $event->modality->name }}
                                                           </span>
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
                                                <button class="btn btn-primary f-16 m-t-15">
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
                        <!-- / Events List -->

                        <div class="col-sm-12">
                            <div class="text-center">
                                {{ $events->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-sm-3">
                @include('landing.home.sidebar', ['current_view' => 'events'])
            </div>
            {{-- End Side Bar --}}

        </div>

    </div>
    <!-- / Event Section -->
</section>

@section('scripts')
    @parent

@stop
