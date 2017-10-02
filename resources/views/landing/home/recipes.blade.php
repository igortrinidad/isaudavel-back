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

 <section id="contact" class="section featured bg-pattern" style="background-color: #fff;">
    <div class="container">
        <h2 class="is-title secondary">
            Receitas fitness
            <span class="is-icon is-icon-recipes"></span>
        </h2>

        <div class="swiper-container swiper-featureds">
            @unless($recipes->count())
                <p class="text-center m-t-20">Nenhuma receita localizada.</p>
            @else
                <div class="swiper-wrapper">
                    @foreach($recipes as $recipe)
                        <div class="swiper-slide text-center">
                            <div class="card">
                                <div class="card-header ch-alt card-picture-header" style="background-image:url('{{ $recipe->avatar }}')">
                                    <a href="{!! route('landing.recipes.show', $recipe->slug) !!}" title="{{ $recipe->title }}">
                                        <div class="hover">
                                            <i class="ion-ios-plus-empty"></i>
                                        </div>
                                    </a>
                                </div>
                                <div class="card-body card-padding text-center">
                                    <h3>{{$recipe->title}}</h3>

                                    <h4 class="f-300 m-t-20">Avaliação dos usuários</h4>
                                    <div class="wp-rating-div">
                                        <?php $rating_to_loop = $recipe->current_rating; ?>
                                        @include('components.rating', ['size' => '22'])
                                    </div>
                                    <hr class="m-t-20">
                                    <a href="{!! route('landing.recipes.show', $recipe->slug) !!}" title="{{ $recipe->title }}">
                                        <button class="btn btn-primary f-300 f-16">
                                            Ver receita completa
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev swiper-button-black"></div>
                <div class="swiper-button-next swiper-button-black"></div>
                <div style="height: 50px;"></div>
                <div class="swiper-pagination"></div>
            @endunless

        </div>
    </div>

</section>

@section('scripts')
    @parent

    <script>
        var swiperFeatureds = new Swiper('.swiper-featureds', {
            initialSlide: 0,
            spaceBetween: 10,
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
    </script>
@stop
