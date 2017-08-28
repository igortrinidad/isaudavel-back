<style media="screen">
.wrapper {
    background-position: top center;
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    position: relative;
    width: 100%;
    padding: 50px 0;
    color: #fff;
}

.wrapper.call-to-client { background-image: url('/images/gym.jpg'); }
</style>

<section id="about" class="section p-b-0">
    <!-- CLIENT FEATURES -->
    <div class="container p-b-30">
        <div class="text-center">
            <h2 class="f-300">Para profissionais</h2>
            <span class="f-300">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
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

    <!-- Screen Shots -->
    <div class="container text-center p-t-10">
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
    <!-- / Screen Shots -->


    <div class="wrapper call-to-client">
        <div class="container">
            <div class="row m-t-30">
                <div class="col-md-12 col-xs-12 text-center">
                    <h4 class="f-700 m-b-30">Saiba mais sobre o Isaudavel para clientes.</h4>
                    <a href="/new-landing/para-voce">
                        <button class="btn btn-success m-t-10">Para Você</button>
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
