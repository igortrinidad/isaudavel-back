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

    .card-header { position: relative; }
    .comment { padding: 10px 80px !important; }
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
                    <div class="text-center">
                        <h2 class="m-t-20 m-b-10">Participantes</h2>
                        @if(count($event_fetched->participants) > 0)
                            <span class="f-300">{{ count($event_fetched->participants) }} confirmados</span>
                        @endif
                        @if(count($event_fetched->participants) == 0)
                            <span class="f-300">Nenhum participante confirmando ainda.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- List participants -->
            <div class="row row-event m-t-30">
                @foreach($event_fetched->participants as $participant)
                <div class="col-sm-3 col-xs-4 event-col">
                    <div class="card m-b-10">
                        <div class="card-header ch-alt p-5 text-center">
                            <div class="picture-circle picture-circle-xs" style="background-image:url('{{ $participant->participant->avatar }}')"></div>
                            <h5 class="m-b-0 m-t-10 f-300">{{ $participant->participant->full_name }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- / List participants -->

            <div class="row" style="margin-top: 60px;">
                <div class="col-sm-12 text-center">
                    <button type="button" class="btn btn-success">Confirmar participação</button>
                </div>
            </div>

        </div>
    </section>

    <!-- / Participants Event -->

    <!-- Event Comments -->
    <section class="section gray divider">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center">
                        <h2 class="m-t-20 m-b-20">Comentários</h2>
                        @if(count($event_fetched->comments) > 0)
                            <span class="f-300">{{ count($event_fetched->comments) }} comentários</span>
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
                                <img class="icon" src="/images/comment.png" alt="">
                                <p class="f-13 text-center">{{ $comment->content }}
                                </p>
                            </div>
                            <div class="card-body card-padding">
                                <div class="text-center m-t-10">
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
