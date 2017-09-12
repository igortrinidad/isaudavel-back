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
    </style>
    <section class="section divider">
        <div class="container">
            <div class="row">
                <!-- CENTER COL "ABOUT" -->
                <div class="col-sm-9">
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
                        <div class="card-body" style="padding: 6px;">

                            @foreach($event_fetched->categories as $category)
                                <span class="label label-success">{{ $category->name }}</span>
                            @endforeach

                            <!-- Event Share -->
                            <h4 class="f-300 m-t-15">Compartilhe:</h4>
                            <div class="m-t-10 m-b-30">
                                <button type="button" class="btn btn-facebook btn-xs p-5 p-l-10 p-r-10">Facebook</button>
                                <button type="button" class="btn btn-whatsapp btn-xs p-5 p-l-10 p-r-10">Whatsapp</button>
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
                                <li class="list-group-item f-300">
                                    <strong>Valor</strong> <span class="badge badge-success">{{ $event_fetched->value }}</span>
                                </li>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="text-center">
                                        <h2 class="m-t-20 m-b-10">Participantes</h2>
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
                                </div>
                            </div>

                            <!-- List participants -->
                            <div class="row m-t-30">
                                @foreach($event_fetched->participants as $indexParticipant => $participant)
                                    @if($indexParticipant < 8)
                                        <div class="col-sm-6 text-center">
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
                                        <button type="button" class="btn btn-primary btn-block btn-xs p-5 m-b-10">Ver todos participantes ({{ count($event_fetched->participants) }})</button>
                                    </div>
                                @endif
                                <div class="col-sm-12 text-center">
                                    <button type="button" class="btn btn-success btn-block btn-xs p-5">Confirmar participação</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- RIGHT COL "PARTICIPANTS" -->

            </div>
        </div>
    </section>

    <!-- Event Comments -->
    <section class="section gray">
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
