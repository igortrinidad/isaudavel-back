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

    <div id="">
        <section id="article-show" class="section divider">
            <div class="article-avatar" style="background-image: url('{{ $article_fetched->avatar }}')">
            </div>
            <div class="container">

                <h2 class="section-main-title f-400 m-0 m-t-25">
                    {{ $article_fetched->title }}
                    <small class="f-300 f-14">publicado em: {{ $article_fetched->created_at->format('d/m/Y') }}</small>
                </h2>

                <div class="row m-t-30">
                    <!-- CENTER COL "ABOUT" -->
                    <div class="col-sm-9">

                        <!-- Card Recipe Content -->
                        <div class="card">
                            <div class="card-body" style="padding: 6px;">

                                {!! $article_fetched->content!!}


                                <!-- Recipe Share -->
                                <h4 class="f-300 m-t-15">Compartilhe:</h4>
                                <div class="m-t-10 m-b-30">
                                    <button type="button" class="btn btn-facebook btn-xs p-5 p-l-10 p-r-10 open-share-facebook">
                                        <i class="ion-social-facebook m-r-5"></i>Facebook
                                    </button>
                                    <button type="button" class="btn btn-whatsapp btn-xs p-5 p-l-10 p-r-10 open-share-whatsapp">
                                        <i class="ion-social-whatsapp m-r-5"></i>Whatsapp
                                    </button>
                                </div>
                                <!-- / Recipe Share -->

                                <hr>


                            </div>
                        </div>
                        <!-- / Card Recipe Content -->


                </div>
            </div>
        </section>

    </div>

    @section('scripts')
    	@parent

    	        <script type="text/javascript">
            $(document).ready(function(){

                $('.open-share-facebook').on('click', function() {
                    var url = `https://www.facebook.com/dialog/share?app_id=151705885358217&href=https://isaudavel.com/receitas/{{ $article_fetched->slug }}&display=popup&mobile_iframe=true`;
                    window.open(url, '_blank', 'location=yes');
                })

                $('.open-share-whatsapp').on('click', function() {
                    // Whatsapp share
                    var url = `https://api.whatsapp.com/send?text=Encontrei a receita {{ $article_fetched->title }} no iSaudavel, veja o abaixo: https://isaudavel.com/receitas/{{ $article_fetched->slug }}`;
                    window.open(url, '_system', null);
                })

                $('.open-print').on('click', function() {
                    // Printer
                    var url = `https://isaudavel.com/receitas/imprimir/{{ $article_fetched->id }}`;
                    window.open(url, '_system', null);
                })
            });
        </script>

    @stop
@stop
