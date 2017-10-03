<style>
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

    .picture-circle-p {
        width: 68px;
        height: 68px;
    }

    /* Swiper */
    .swiper-wrapper { padding-top: 30px; }
    .swiper-slide .card {
        transform: scale(.9);
        z-index: 99999;
        transition: ease .3s;
    }

    .swiper-slide-active .card {
        transform: scale(1);
        transition: ease .3s;
    }

    /* List & Cards With Swiper */
    .swiper-pagination-bullet,
    .swiper-pagination-bullet { border-color: #88C657 !important }

    .swiper-pagination-bullet.swiper-pagination-bullet-active,
    .swiper-pagination-bullet.swiper-pagination-bullet-active { background-color: #88C657 !important }

    /* Swiper Buttons Prev & Next */
    .swiper-button-prev { right: auto; left: 20px; }
    .swiper-button-next { right: 20px; left: auto; }

    .swiper-button-prev,
    .swiper-button-next {
        color: #383938;
    }

    /* wrapper*/
    .wrapper {
        background-position: top center;
        background-size: cover;
        background-attachment: fixed;
        background-repeat: no-repeat;
        position: relative;
        width: 100%;
        padding: 50px 0;
    }

    .wrapper.call-to-search {
        color: #383938;
        background-image: url('/images/call-to-search.png');
    }

    .featured{
        background-color: rgba(244, 244, 245, 1);
    }

    .bg-pattern{
        background-image: url("/images/pattern-isaudavel-5-300.png");
    }
</style>
 <section id="contact" class="section featured p-t-30 p-b-30">

    <div class="container">

        <h2 class="is-title secondary">
            Artigos
            <span class="is-icon is-icon-blog"></span>
        </h2>

        <div class="row m-t-30 wow fadeInUp">
            @unless($recipes->count())
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ch-alt p-30">
                            <p class="text-center m-0 f-300">Nenhum artigo localizado.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-sm-12">
                    <div class="swiper-container swiper-article-featureds">
                        <div class="swiper-wrapper">
                            @foreach($articles as $article)
                                <div class="swiper-slide text-center">
                                    <div class="card">
                                        <div class="card-header ch-alt card-picture-header" style="background-image:url('{{ $article->avatar }}')">
                                            <a href="{!! route('landing.recipes.show', $article->slug) !!}" title="{{ $article->title }}">
                                                <div class="hover">
                                                    <i class="ion-ios-plus-empty"></i>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="card-body card-padding text-center">
                                            <h3 class="f-300" style="min-height: 70px;">{{$article->title}}</h3>

                                            <a href="{!! route('landing.articles.show', $article->slug) !!}" title="{{ $article->title }}">
                                                <button class="btn btn-primary f-300 f-16 m-t-20">
                                                    Continue lendo
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-prev is-swiper-button-default arrow-ls"><i class="ion-ios-arrow-back"></i></div>
                        <div class="swiper-button-next is-swiper-button-default arrow-ls"><i class="ion-ios-arrow-forward"></i></div>
                    </div>
                </div>
            @endunless

            <div class="col-sm-12 m-t-30 text-center">
                <a href="{{route('landing.articles.list')}}" class="btn btn-info f-300">Veja todos os artigos</a>
            </div>
        </div>
    </div>
</section>

@section('scripts')
    @parent

    <script>
        var swiperFeatureds = new Swiper('.swiper-article-featureds', {
            initialSlide: 0,
            spaceBetween: 25,
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
