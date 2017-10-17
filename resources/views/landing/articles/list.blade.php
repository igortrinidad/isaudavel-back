@extends('landing.articles.index')

@section('landing-content')

    <style media="screen">
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

    .article-avatar {
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

    <div>
        <section id="articles-list" class="section gray divider">

            <div class="container">

                <div class="row">

                </div>

                <div class="row">
                    <div class="col-sm-9">
                        <div class="row">

                            <div class="col-md-12 col-xs-12">
                                <div class="card">
                                    <div class="card-body card-padding">
                                        <form method="GET" action="{{route('landing.articles.list')}}">
                                        <div class="form-group">
                                            <label class="f-300 f-24 m-b-10" for="input-search-articles">Pesquisar artigos</label>
                                            <input class="form-control" id="input-search-articles" name="search" placeholder="Pesquise por palavra chave ou parte do conteúdo">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Pesquisar</button>
                                        </div>
                                        @if ($articles->count() == 0)
                                            <div class="m-t-20">
                                                <span class="f-300 f-16">Nenhum artigo encontrado <i class="m-l-5 ion-sad-outline"></i></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @foreach($articles as $article)
                                <div class="col-md-4 col-xs-12">

                                    <div class="card">
                                        <div class="card-header ch-alt card-picture-header" style="background-image:url('{{ $article->avatar }}')">
                                            <a href="{!! route('landing.articles.show', $article->slug) !!}" title="{{ $article->title }}">
                                                <div class="hover">
                                                    <i class="ion-ios-plus-empty"></i>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="card-body card-padding text-center">
                                            <h3 class="f-300" style="min-height: 70px;">{{$article->title}}</h3>

                                            <a href="{!! route('landing.articles.show', $article->slug) !!}" class="btn btn-primary f-300 f-16 m-t-20" title="{{ $article->title }}">
                                                Continue lendo
                                            </a>
                                        </div>
                                    </div>
            	                </div>
        	                @endforeach
                        </div>
                    </div>
                    <div class="col-sm-3">
                        @include('landing.home.sidebar', ['current_view' => 'articles'])
                    </div>
                </div>
            </div>
        </section>

    </div>

    @section('scripts')
    	@parent


    @stop
@stop
