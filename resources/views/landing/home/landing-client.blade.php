<style media="screen">

</style>

<section id="about" class="section">
    <div class="container text-center">
        <h2 class="f-300">Para você</h2>
        <span class="f-300">O Isaudavel é 100% gratuito, talvez algum textinho extra aqui, Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
        <!-- um teste -->
        <div class="row m-t-30">
            <div class="col-xs-12 col-sm-2">
                <div class="card">
                    <div class="card-header ch-alt">
                        <h3>Personal Trainer</h3>
                    </div>
                    <div class="card-body card-padding">

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="card">
                    <div class="card-header ch-alt">
                        <h3>Personal Trainer</h3>
                    </div>
                    <div class="card-body card-padding">

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="card">
                    <div class="card-header ch-alt">
                        <h3>Personal Trainer</h3>
                    </div>
                    <div class="card-body card-padding">

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="card">
                    <div class="card-header ch-alt">
                        <h3>Personal Trainer</h3>
                    </div>
                    <div class="card-body card-padding">

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="card">
                    <div class="card-header ch-alt">
                        <h3>Personal Trainer</h3>
                    </div>
                    <div class="card-body card-padding">

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="card">
                    <div class="card-header ch-alt">
                        <h3>Personal Trainer</h3>
                    </div>
                    <div class="card-body card-padding">

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="container text-center">
        <h2 class="f-300">Conheça</h2>
        <span class="f-300">Confira as telas do aplicativo......</span>

        <div class="swiper-container swiper-screenshots">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen2.png" alt="">
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row m-t-30">
            <div class="col-md-12 col-xs-12 text-center">
                <h4 class="f-300 m-b-30">Saiba mais sobre o Isaudavel para profissionais.</h4>
                <a href="{!! route('landing.search.index') !!}">
                    <button class="btn btn-primary m-t-10 f-300">Para Profissionais</button>
                </a>
            </div>
        </div>
    </div>

</section>

@section('scripts')
    @parent

    <script>
        var swiperScreenshots = new Swiper('.swiper-screenshots', {
            centeredSlides: true,
            spaceBetween: 15,
            loop: false,
            slidesPerView: 4,
            slideToClickedSlide: true,
            paginationClickable: true,
            pagination: '.swiper-pagination',
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
