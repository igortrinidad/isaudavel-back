@extends('landing.home.index')

    @section('content')

    <style media="screen">
        /*Plans*/
        .plan-item {
            border-bottom: 1px solid #eee;
            margin: 0 -4px;
            padding: 10px 20px;
            text-align: center;
        }

        /*.plan-item:last-child { border-bottom: 0; }*/

        .plan-item i { color: #00A369; }
        .plan-item.disable i { color: #E14A45; }

        .plan-item.disable { text-decoration: line-through; }

        /* Plans Price */
        .price {
            font-weight: 900;
            font-size: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 60px;
            opacity: .8;
            margin-top: 20px;
        }
        .price .best { color: #88C657; }
        .price .currency-symbol {
            opacity: .8;
            font-size: 2rem;
            align-self: flex-start;
        }
        .price .duration {
            opacity: .8;
            font-size: 2rem;
            align-self: flex-end;
        }

        /*Wrapper*/
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

    <section id="about" class="section p-b-0 m-b-0">
        <!-- CLIENT FEATURES -->
        <div class="container p-b-30">
            <div class="text-center">
                <h2 class="f-300">Para empresa e profissionais</h2>
                <span class="f-300">Um canal exclusivo para profissionais da área da saúde, fitness e estética</span>
            </div>
            <div class="row" style="margin-top: 60px">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 wow fadeInUp">
                            <img class="img-responsive" src="/images/screenshots/professional/mc1.png" alt="">
                        </div>
                        <div class="col-xs-12 col-sm-9">
                            <div class="row">
                                <div class="col-sm-6 wow fadeInUp">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <i class="ion-star f-30"></i>
                                            <h4 class="f-300">Apareça</h4>
                                        </div>
                                        <div class="card-body card-padding" style="min-height: 200px;">
                                            <span class="f-300">O iSaudavel é a primeira plataforma fitness onde você poderá mostrar seu perfil profissional completo com cursos, certificados e especialidades de sua carreira. </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 wow fadeInUp">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <i class="ion-reply-all f-30"></i>
                                            <h4 class="f-300">Sigam me os bons</h4>
                                        </div>
                                        <div class="card-body card-padding" style="min-height: 200px;">
                                            <span class="f-300">Você que se preocupa com seu atendimento e a satisfação de seu cliente, agora você terá uma plataforma para seu cliente demonstrar o reconhecimento por seu trabalho. Seus clientes poderão avaliar seu trabalho ou de sua empresa separadamente, incentivando sempre o melhor de cada profissional.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 wow fadeInUp">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <i class="ion-android-person-add f-30"></i>
                                            <h4 class="f-300">A união faz a força</h4>
                                        </div>
                                        <div class="card-body card-padding" style="min-height: 200px;">
                                            <span class="f-300">Você provavelmente já possui diversas parceirias em seu segmento, por exemplo um nutricionista indica determinados personais ou academia ou vice e versa. Agora você terá um canal exclusivo para fortalecer as parcerias e indicar os serviços de profissionais que você confia. Uma maneira formalizar as indicações e parcerias entre empresas e profissionais.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 wow fadeInUp">
                                    <div class="card">
                                        <div class="card-header ch-alt text-center">
                                            <i class="ion-clipboard f-30"></i>
                                            <h4 class="f-300">Dados pra quem quer dados</h4>
                                        </div>
                                        <div class="card-body card-padding" style="min-height: 200px;">
                                            <span class="f-300">Acesse e gerencie informações sobre seu cliente que antes era difíceis de se ter como o histórico de avaliações, histórico de lesões, restrições alimentares, fichas de treinamentos, dietas alimentares, histórico de indicadores de saúde e muitas outras. Informações que você poderá usar para ajudar seu cliente à atingir os objetivos com muito mais eficácia.</span>
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


        <div style="background-color: #fff;">
            <!-- Screen Shots -->
            <div class="container text-center" style="padding: 100px 0;">
                <h2 class="f-300">Conheça</h2>
                <span class="f-300">Confira as telas do aplicativo!</span>
                <div class="swiper-container swiper-screenshots">
                    <div class="swiper-wrapper" style="padding-top: 60px;">
                        <div class="swiper-slide">
                            <img class="img-responsive" src="/images/screenshots/professional/mc1.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img class="img-responsive" src="/images/screenshots/professional/mc2.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img class="img-responsive" src="/images/screenshots/professional/mc3.png" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img class="img-responsive" src="/images/screenshots/professional/mc4.png" alt="">
                        </div>
                    </div>
                    <div class="swiper-button-prev"><i class="ion-ios-arrow-back"></i></div>
                    <div class="swiper-button-next"><i class="ion-ios-arrow-forward"></i></div>
                    <div style="height: 50px;"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <!-- / Screen Shots -->
        </div>


        <!-- Plans -->
        <div class="container" style="padding-top: 100px; padding-bottom: 100px;">
            <div class="text-center">
                <h2 class="f-300">Preço</h2>
                <span class="f-300">É realmente barato!</span>
            </div>
            <div class="row m-t-30">
                <div class="col-sm-4 col-sm-offset-4 col-xs-12">
                    <div class="card wow fadeInUp">
                        <div class="card-header ch-alt text-center">
                            <h4 class="f-300">Assinatura</h4>
                            <span class="price"><span class="currency-symbol">R$</span><span class="best">37,90</span> <span class="duration">/mês</span></span>
                            <br>
                            a partir
                        </div>
                        <div class="card-body text-center">
                            <ul>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        1 especialidade
                                        <br>
                                        <span class="f-12">R$37,90 / especialidade adicional / mês</span>
                                </li>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        1 profissional
                                        <br>
                                        <span class="f-12">R$17,90 / profissional adicional / mês</span>
                                </li>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        Acesso aos Dashboard's dos clientes
                                        <br>
                                        <span class="f-12">*Sujeito à permissões do cliente</span>
                                </li>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        Perfil da empresa na plataforma
                                </li>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        Agenda online
                                </li>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        Planos e preços online
                                </li>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        Controle de planos e aulas de clientes
                                </li>
                                <li class="plan-item f-300">
                                    <i class="ion-ios-checkmark-empty m-r-5 f-18"></i>
                                        Galeria de fotos
                                </li>

                            </ul>
                            <a href="{!! route('landing.professionals.signup') !!}" class="btn btn-success m-t-15 m-b-10 f-22 p-10">Quero Cadastrar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Plans -->

        <!-- CLIENTS BANNER -->
        <div class="wrapper call-to-client">
            <div class="container">
                <div class="row m-t-30">
                    <div class="col-md-12 col-xs-12 text-center">
                        <h4 class="f-700 m-b-30">Saiba mais sobre o iSaudavel para sua saúde</h4>
                        <a href="{!! route('landing.clients.about') !!}">
                            <button class="btn btn-success btn-xl m-t-10">Para Você</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </section>

    @section('scripts')
        @parent

        <script>

            setTimeout(function(){
                $('html, body').animate({
                    scrollTop: $("#about").offset().top
                }, 1000);
            }, 300);

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

@stop
