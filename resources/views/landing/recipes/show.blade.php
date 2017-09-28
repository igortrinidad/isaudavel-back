@extends('landing.recipes.index')

@section('landing-content')

    <style media="screen">
    /* Recipe Title */
    .section-main-title {
        border-left: 4px solid #6ec058;
        padding: 10px 20px 10px 20px;
        background: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        width: auto;
        display: inline-block;
    }

    .store-badge { margin: 0 auto; }
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
    .img-recipe { border-radius: 4px; }

    .ingredients { list-style: none; padding-left: 10px; }
    .ingredients .item {
        margin: 0;
        margin-bottom: 1em;
        padding-left: 1.5em;
        position: relative;
    }
    .ingredients .item:after {
        content: '';
        height: 5px;
        width: 5px;
        background: #7ac358;
        display: block;
        position: absolute;
        transform: rotate(45deg);
        top: 50%;
        left: 0;
        margin-top: -2px;
    }
    .recipe-icon {
        color: #7ac358;
        font-size: 28px;
    }
    .recipe-box {
        background: #fff;
        border-radius: 4px;
        padding: 10px;
        margin-top: 30px;
    }
    .badge.badge-info { background-color: #337ab7; color: #FFFFFF; }
    </style>

    <div id="show-recipe">
        <section id="events-show" class="section divider">
            <div class="event-avatar" style="background-image: url('{{ $recipe_fetched->avatar }}')">
            </div>
            <div class="container">

                <h2 class="section-main-title f-400 m-0 m-t-25">
                    {{ $recipe_fetched->title }}
                    <small class="f-300 f-14">publicado em: {{ $recipe_fetched->created_at->format('d/m/Y') }}</small>
                </h2>

                <div class="row m-t-30">
                    <!-- CENTER COL "ABOUT" -->
                    <div class="col-sm-9">

                        <!-- Recipe Informations -->
                        <div class="row">
                            <!-- Recipe Prep Time -->
                            <div class="col-xs-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="recipe-box">
                                            <i class="ion-android-alarm-clock recipe-icon"></i>

                                            <h5 class="f-300 m-0 m-b-10">Preparo</h5>
                                            <small class="f-300">{{ $recipe_fetched->prep_time }} min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Recipe Prep Time -->

                            <!-- Recipe Portions -->
                            <div class="col-xs-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="recipe-box">
                                            <i class="ion-android-restaurant recipe-icon"></i>

                                            <h5 class="f-300 m-0 m-b-10">Rendimento</h5>
                                            <small class="f-300">{{ $recipe_fetched->portions }} porções ({{ $recipe_fetched->portion_size }} cada)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Recipe Portions -->

                            <!-- Recipe Difficulty -->
                            <div class="col-xs-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="recipe-box">
                                             @for($i = 0; $i < $recipe_fetched->difficulty; $i++)
                                                 <i class="ion-spoon recipe-icon m-r-5" v-for="n in $recipe_fetched->difficulty"></i>
                                             @endfor
                                             <h5 class="f-300 m-0 m-b-10">Dificuldade</h5>
                                             @if($recipe_fetched->difficulty < 3)
                                                 <small class="f-300">Fácil</small>
                                             @endif
                                             @if($recipe_fetched->difficulty == 3)
                                                 <small class="f-300">Normal</small>
                                             @endif
                                             @if($recipe_fetched->difficulty > 3)
                                                 <small class="f-300">Difícil</small>
                                             @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- / Recipe Difficulty -->

                            <!-- Recipe Rating -->
                            <div class="col-xs-6 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="recipe-box">
                                            <i class="ion-ios-star recipe-icon"></i>

                                            <h5 class="f-300 m-0 m-b-10">Avaliação</h5>
                                            <small class="f-300">{{ $recipe_fetched->current_rating }} {{ $recipe_fetched->current_rating > 1 ? 'Estrelas' : 'Estrela' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Recipe Rating -->

                        </div>
                        <!-- / Recipe Informations -->

                        <div class="card">
                            <div class="card-header ch-alt text-center">
                                <h4 class="f-300 m-0">Macro nutrientes</h4>
                                <small class="f-300">Por {{ $recipe_fetched->portion_size }}</small>
                            </div>
                            <div class="card-body">
                                <ul class="list-group m-t-10 m-b-0">
                                    <li class="list-group-item f-300">
                                        Calorias <span class="badge badge-info">{{ $recipe_fetched->kcal }}</span>
                                    </li>
                                    <li class="list-group-item f-300">
                                        Proteínas <span class="badge badge-info">{{ $recipe_fetched->protein }}</span>
                                    </li>
                                    <li class="list-group-item f-300">
                                        Carboidrato <span class="badge badge-info">{{ $recipe_fetched->carbohydrate }}</span>
                                    </li>
                                    <li class="list-group-item f-300">
                                        Lipídios <span class="badge badge-info">{{ $recipe_fetched->lipids }}</span>
                                    </li>
                                    <li class="list-group-item f-300">
                                        Fibra <span class="badge badge-info">{{ $recipe_fetched->fiber }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card Recipe Content -->
                        <div class="card">
                            <div class="card-body" style="padding: 6px;">

                                <!-- Recipe Ingredients -->
                                <h4 class="f-300 m-t-15">Ingredientes:</h4>
                                <ul class="ingredients m-t-10">
                                    @foreach($recipe_fetched->ingredients as $ingredient)
                                    <li class="item f-300">{{ $ingredient['description'] }}</li>
                                    @endforeach
                                </ul>
                                <!-- / Recipe Ingredients -->

                                <hr>

                                <!-- Recipe Prep Description -->
                                <h4 class="f-300 m-t-15">Modo de preparo:</h4>
                                <div class="m-t-10">
                                    {!!  $recipe_fetched->prep_description !!}
                                </div>
                                <!-- / Recipe Prep Description -->


                                <!-- Recipe Share -->
                                <h4 class="f-300 m-t-15">Compartilhe:</h4>
                                <div class="m-t-10 m-b-30">
                                    <button type="button" class="btn btn-facebook btn-xs p-5 p-l-10 p-r-10 open-share-facebook">
                                        <i class="ion-social-facebook m-r-5"></i>Facebook
                                    </button>
                                    <button type="button" class="btn btn-whatsapp btn-xs p-5 p-l-10 p-r-10 open-share-whatsapp">
                                        <i class="ion-social-whatsapp m-r-5"></i>Whatsapp
                                    </button>
                                    <a href="{{ route('landing.recipes.pdf', $recipe_fetched->id) }}" class="btn btn-default btn-xs p-5 p-l-10 p-r-10" target="_blank"> <i class="ion-ios-printer m-r-5"></i>Imprimir</a>
                                </div>
                                <!-- / Recipe Share -->

                                <hr>

                                <!-- Recipe Photos -->
                                @if(count($recipe_fetched->photos) > 0)
                                    <h4 class="f-300 m-t-15 m-b-10">Fotos da receita:</h4>
                                    <div id="gallery" style="display:none;">
                                        @foreach($recipe_fetched->photos as $photo)
                                        <img class="img-recipe" alt="{{$recipe_fetched->name}}" src="{{$photo->photo_url}}"
                                            data-image="{{$photo->photo_url}}"
                                            data-description="{{$recipe_fetched->name}}">
                                        @endforeach
                                    </div>
                                @else
                                    <h4 class="f-300">Este receita ainda não possui imagens</h4>
                                @endif
                                <!-- Recipe Photos -->

                            </div>
                        </div>
                        <!-- / Card Recipe Content -->

                        <!-- Card Recipe From -->
                        <div class="card">
                            <div class="card-body text-center">
                                <h4 class="f-300 m-t-15">Esta receita foi enviada por:</h4>
                                <div class="text-center">
                                    <div class="picture-circle picture-circle-l m-t-10 m-b-10" style="background-image:url('{{ $recipe_fetched->from->avatar }}')">
                                    </div>
                                    <span class="f-300">{{ $recipe_fetched->from->full_name }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- / Card Recipe From -->

                    </div>
                    <!-- / CENTER COL "ABOUT" -->

                    <!-- RIGHT COL "RATINGS" -->
                    <div class="col-sm-3">
                        <!-- Card Rarings -->
                        <div class="card">
                            <div class="card-header ch-alt text-center">
                                <h4 class="m-0 f-300">Últimas avaliações</h4>
                                <small class="f-300">{{ count($recipe_fetched->ratings) }} {{ count($recipe_fetched->ratings) > 1 ? 'Avaliações' : 'Avaliação' }}</small>
                            </div>
                            @if(count($recipe_fetched->ratings) > 0)
                                <div class="card-body card-padding">
                                    @foreach($recipe_fetched->ratings as $ratingIndex => $rating)
                                        @if($ratingIndex < 5)
                                            <div class="text-center m-t-10">
                                                <div class="picture-circle picture-circle-p" style="background-image:url('{{ $rating->from->avatar }}')">
                                                </div>
                                                <h5 class="f-300 m-t-10 m-b-10">{{ $rating->from->full_name }}</h5>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->rating)
                                                        <i class="ion-ios-star fa-lg" style="color: #FFCC5F;"></i>
                                                    @else
                                                        <i class="ion-ios-star-outline fa-lg" style="color: #FFCC5F;"></i>
                                                    @endif
                                                @endfor

                                                @if($ratingIndex + 1 < count($recipe_fetched->ratings))
                                                    <hr class="m-t-30">
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                        </div>
                        <!-- /Card Ratings -->
                        <div class="card">
                            <div class="card-body card-padding">
                                <!-- Call To Download -->
                                <div class="text-center">
                                    <h3 class="f-300">Baixe o <strong style="color: #72c157">iSaudavel</strong> e deixe sua avaliação.</h3>
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
                    <!-- / RIGHT COL "RATINGS" -->

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
    </div>
@stop
