@extends('landing.events.index')

@section('landing-content')

    <style media="screen">
    .event-name {
        height: 78px; width: 100%;
        position: relative;
        display: flex;
        align-items: flex-end;
        flex-flow: row wrap;
    }
    </style>

    <!-- About Event -->
    <section class="section gray divider">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header p-5 ch-alt">
                            <div class="row row-event m-t-0">
                                <!-- Event Date -->
                                <div class="col-xs-2 event-col">
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
                                <!-- / Event Date -->
                                <div class="col-xs-10 event-col">
                                    <div class="event-name">
                                        <h3 class="f-300 m-0 t-overflow">{{ $event_fetched->name }}</h3>
                                        <span>
                                            <i class="ion-ios-clock-outline f-20"></i>
                                            <span class="f-300">{{ $event_fetched->time }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /About Event -->

    <!-- Participants Event -->
    <section class="section divider">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="text-center m-t-20 m-b-20">Participantes</h2>
                </div>

                @if(count($event_fetched->participants) == 0)
                    <div class="col-sm-12 text-center">
                        <span class="f-300">Nenhum participante confirmando ainda.</span>
                    </div>
                @endif

            </div>

            <!-- List participants -->
            <div class="row row-event m-t-30">
                @foreach($event_fetched->participants as $participant)
                <div class="col-sm-3 col-xs-4 event-col">
                    <div class="card m-b-10">
                        <div class="card-header ch-alt p-5 text-center">
                            <div class="picture-circle picture-circle-xs" style="background-image:url('{{ $participant->participant_avatar }}')"></div>
                            <h5 class="m-b-0">{{ $participant->participant }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- / List participants -->

        </div>
    </section>

    <!-- / Participants Event -->

    <!-- Event Comments -->
    <section class="section gray divider">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="text-center m-t-20 m-b-20">Comentários</h2>
                </div>

                @if(count($event_fetched->comments) == 0)
                    <div class="col-sm-12 text-center">
                        <span class="f-300">Nenhum comentário foi públicado ainda.</span>
                    </div>
                @endif
            </div>

            <!-- List comments -->
            <div class="row row-event m-t-30">
                <div class="col-sm-12 event-col">
                    <div class="card">
                        @foreach($event_fetched->comments as $comment)
                        <div class="card-body card-padding text-center">
                            <p class="f-13">{{ $comment->content }}</p>
                            <p class="f-12 m-t-0"><i class="ion-ios-clock-outline"></i>
                                {{ $comment->created_at->format('d/m/Y') }}
                            </p>
                            @if(count($event_fetched->comments) > 1)
                                <hr>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- / List comments -->

        </div>
    </section>
    <!-- / Event Comments -->


    @section('scripts')
        @parent
        <script>
            // Vue.config.debug = true;
            // var vm = new Vue({
            //     el: '#list-events',
            //     data: {
            //     },
            //     computed: {
            //
            //     },
            //     mounted: function() {
            //
            //     },
            //     methods: {
            //
            //     }
            //
            // })
        </script>
    @stop
@stop
