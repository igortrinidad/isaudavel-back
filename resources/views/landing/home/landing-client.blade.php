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

.wrapper.call-to-professional { background-image: url('/images/gym.jpg'); }
</style>

<section id="about" class="section p-b-0">

    <!-- CLIENT FEATURES -->
    <div class="container">
        <div class="text-center">
            <h2 class="f-300">Para você</h2>
            <span class="f-300">Sua saúde em boas mãos.</span>
        </div>
        <div class="row" style="margin-top: 60px">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 wow fadeInUp">
                        <img class="img-responsive" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen1.png" alt="Splash Screenshot">
                    </div>
                    <div class="col-xs-12 col-sm-9">
                        <div class="row">
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-ios-navigate-outline f-30"></i>
                                        <h4 class="f-300">Encontre o profissional ideal</h4>
                                    </div>
                                    <div class="card-body card-padding" style="min-height: 200px;">
                                        <span class="f-300">
                                        Procure por profissionais para te ajudarem a atingir seus objetivos de saúde, bem estar e estética próximos à você. Você pode procurar por especialidade como Personal Trainer, Crossfit, Nutrição, Fisioterapia e muitas outras.
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-clipboard f-30"></i>
                                        <h4 class="f-300">Dashboard</h4>
                                    </div>
                                    <div class="card-body card-padding" style="min-height: 200px;">
                                        <span class="f-300">
                                            Um painel de controle de sua saúde em suas mãos, cadastre seus treinamentos ou fichas de exercícios, suas dietas, suas avaliações físicas e de saúde, exames e restrições para que a próxima vez que você for a um profisisonal ele tenha acesso às informações que precisa para te ajudar à atingir seus objetivos e você tenha ótimos resultados.
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-lock-combination f-30"></i>
                                        <h4 class="f-300">Privacidade</h4>
                                    </div>
                                    <div class="card-body card-padding" style="min-height: 200px;">
                                        <span class="f-300">Selecione os módulos que cada empresa e seus profissionais poderão acessar de seu Dashboard, assim você poderá compartilhar por exemplo somente suas dietas e avaliações com seu nutricionista ou somente seus treinamentos com seu personal ou fisioterapeuta.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-flag f-30"></i>
                                        <h4 class="f-300">Resultados</h4>
                                    </div>
                                    <div class="card-body card-padding" style="min-height: 200px;">
                                        <span class="f-300">Acompanhe o resultado de suas avaliações a qualquer momento com um gráfico cronológico dos indicadores que você fez avaliações para ajudar você e seus profissionais a medirem os resultados e ajustar as medidas necessárias para que você atinja seus objetivos</span>
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

    <div class="wrapper call-to-professional">
        <div class="container">
            <div class="row m-t-30">
                <div class="col-md-12 col-xs-12 text-center">
                    <h4 class="f-700 m-b-30">Saiba mais sobre o Isaudavel para profissionais.</h4>
                    <a href="{!! route('landing.professionals.about')!!}">
                        <button class="btn btn-success m-t-10">Para Profissionais</button>
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
