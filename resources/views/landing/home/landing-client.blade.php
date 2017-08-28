<style media="screen">

</style>

<section id="about" class="section">

    <!-- CLIENT FEATURES -->
    <div class="container">
        <div class="text-center">
            <h2 class="f-300">Para você</h2>
            <span class="f-300">O Isaudavel é 100% gratuito, talvez algum textinho extra aqui, Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
        </div>
        <div class="row" style="margin-top: 60px">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 wow fadeInUp">
                        <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen1.png" alt="Splash Screenshot">
                    </div>
                    <div class="col-xs-12 col-sm-9">
                        <div class="row m-t-30">
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-ios-navigate-outline f-30"></i>
                                        <h4 class="f-300">Local próximo de você</h4>
                                    </div>
                                    <div class="card-body card-padding">
                                        <span class="f-300">Some text here...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-ios-calendar-outline f-30"></i>
                                        <h4 class="f-300">Agenda</h4>
                                    </div>
                                    <div class="card-body card-padding">
                                        <span class="f-300">Some text here...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-ios-star-outline f-30"></i>
                                        <h4 class="f-300">Avaliações</h4>
                                    </div>
                                    <div class="card-body card-padding">
                                        <span class="f-300">Some text here...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-ios-star-outline f-30"></i>
                                        <h4 class="f-300">Feature</h4>
                                    </div>
                                    <div class="card-body card-padding">
                                        <span class="f-300">Some text here...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / CLIENT FEATURES -->

    <hr>

    <div class="container text-center" style="padding-top: 100px">
        <h2 class="f-300">Conheça</h2>
        <span class="f-300">Confira as telas do aplicativo!</span>
        <div class="swiper-container swiper-screenshots">
            <div class="swiper-wrapper" style="padding-top: 60px;">
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
            <div class="swiper-button-prev"><i class="ion-ios-arrow-back"></i></div>
            <div class="swiper-button-next"><i class="ion-ios-arrow-forward"></i></div>
            <div style="height: 50px;"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="wrapper paralax">
        <div class="container">
            <div class="row m-t-30">
                <div class="col-md-12 col-xs-12 text-center">
                    <h4 class="f-300 m-b-30">Saiba mais sobre o Isaudavel para profissionais.</h4>
                    <a href="/new-landing/para-profissionais">
                        <button class="btn btn-primary m-t-10 f-300">Para Profissionais</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</section>

@section('scripts')
    @parent

    <script>
        var swiperScreenshots = new Swiper('.swiper-screenshots', {
            spaceBetween: 0,
            loop: false,
            slidesPerView: 4,
            slidesPerGroup: 4,
            slideToClickedSlide: false,
            paginationClickable: true,
            pagination: '.swiper-pagination',
            prevButton: '.swiper-button-prev',
            nextButton: '.swiper-button-next',
            breakpoints: {
                768: {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                }
            }
        })
    </script>
@stop
