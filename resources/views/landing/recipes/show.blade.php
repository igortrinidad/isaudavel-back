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

    .event-avatar {
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%; height: 350px;
        margin-top: -100px;
    }
    .fix-event {
        width: 100px;
    }
    </style>
    <section id="events-show" class="section divider">
        <div class="event-avatar" style="background-image: url('{{ $recipe_fetched->avatar }}')">
        </div>
        <div class="container">

            <div class="row row-event" style="margin-top: -28px;">
                <div class="col-sm-11 col-xs-9 event-col">
                    <div class="event-name">
                        <h2 class="f-400 m-0 m-t-25 t-overflow">{{ $recipe_fetched->title }}</h2>
                    </div>
                </div>
            </div>



            <div class="row m-t-30">
                <!-- CENTER COL "ABOUT" -->
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-header ch-alt text-center">
                            <h2 class="f-400">Sobre o receita</h2>
                        </div>
                        <div class="card-body" style="padding: 6px;">

                            <h4 class="f-300 m-t-15 m-b-10">Endereço:</h4>
                            <span class="f-300">{{ $recipe_fetched->address['full_address'] }}</span>

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
                            <h4 class="f-300">Descrição do receita:</h4>
                            <div class="f-300 m-t-10 m-b-30">
                                {!! $recipe_fetched->description !!}
                            </div>
                            <!-- / Event Description -->

                            <hr>

                            <!-- Event Photos -->
                            @if(count($recipe_fetched->photos) > 0)
                                <h4 class="f-300">Imagens do receita:</h4>
                                <div class="row row-event m-t-10">
                                    @foreach($recipe_fetched->photos as $photo)
                                    <div class="col-sm-3 event-col">
                                        <img class="img-responsive" src="{{ $photo->photo_url }}" alt="">
                                    </div>
                                    @endforeach
                                </div>

                            @else
                                <h4 class="f-300">Este receita ainda não possui imagens</h4>
                            @endif
                            <!-- Event Photos -->

                        </div>
                    </div>
                </div>
                <!-- / CENTER COL "ABOUT" -->

            </div>
        </div>
    </section>

    <!-- Recipe Comments -->
    <section class="section divider">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center">
                        <h2 class="m-t-20 m-b-20">Comentários sobre a receita</h2>
                        @if(count($recipe_fetched->comments) > 0)
                            <span class="f-300">Total de {{ count($recipe_fetched->comments) }} comentários</span>
                        @endif
                        @if(count($recipe_fetched->comments) == 0)
                            <span class="f-300">Nenhum comentário foi públicado ainda.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- List comments -->
            @if(count($recipe_fetched->comments) > 0)
                <div class="row row-event m-t-30">
                    <div class="col-sm-12 event-col">
                        <div class="card">
                            @foreach($recipe_fetched->comments as $comment)
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
    <!-- / Recipe Comments -->


@stop
