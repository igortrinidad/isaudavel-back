<!DOCTYPE html>
<html class="no-js">
<head>

    @include('components.seo-opengraph')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
    <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>
    <meta name="google-site-verification" content="Ed4IGd5eqro1sXT8-Cz5eYyKFThT078JpfWLnQP3-VQ"/>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir('build/landing/css/build_vendors_custom.css') }}">

    <!-- Hotjar Tracking Code for https://isaudavel.com -->
    @include('components.hotjar')

</head>

<body id="body">

@include('landing.home.video')
@include('landing.home.navbar')


<style media="screen">
    /*Plans*/
    .plan-item {
        border-bottom: 1px solid #eee;
        margin: 0 -4px;
        padding: 10px 20px;
        text-align: center;
    }

    /*.plan-item:last-child { border-bottom: 0; }*/

    .plan-item i {
        color: #00A369;
    }

    .plan-item.disable i {
        color: #E14A45;
    }

    .plan-item.disable {
        text-decoration: line-through;
    }

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

    .price .best {
        color: #88C657;
    }

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

    .wrapper.call-to-client {
        background-image: url('/images/gym.jpg');
    }
</style>

<section id="about" class="m-t-30">
    <!-- CLIENT FEATURES -->
    <div class="container p-b-30">
        <div class="text-center">
            <h2 class="f-300 m-b-20">Para empresa e profissionais</h2>
            <h4 class="f-300 m-t-20">Uma ferramenta exclusiva para empresas e profissionais da área da saúde divulgarem seus serviços e atenderem seus clientes com muito mais comodidade tanto para o cliente quanto para você.</h4>
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
                                        <i class="ion-ios-calendar-outline f-30"></i>
                                        <h4 class="f-300">Agenda online</h4>
                                    </div>
                                    <div class="card-body card-padding" style="min-height: 200px;">
                                        <span class="f-300">Cansado de responder mensagens no seu telefone pessoal tarde da noite? Com a agenda online iSaudavel você não precisa mais se preocupar em estar online o tempo inteiro, isso mesmo! Seus clientes poderão remarcar, cancelar ou confirmar as aulas agendadas com você diretamente do aplicativo e o melhor, você recebe as notificações push na hora de qualquer alteração em sua agenda.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-star f-30"></i>
                                        <h4 class="f-300">Apareça</h4>
                                    </div>
                                    <div class="card-body card-padding" style="min-height: 200px;">
                                        <span class="f-300">O iSaudavel é a primeira plataforma fitness onde você poderá mostrar seu perfil profissional completo com cursos, certificados e especialidades de sua carreira, mostrando todo o seu potencial para o mundo.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 wow fadeInUp">
                                <div class="card">
                                    <div class="card-header ch-alt text-center">
                                        <i class="ion-social-usd f-30"></i>
                                        <h4 class="f-300">Seus clientes em dia</h4>
                                    </div>
                                    <div class="card-body card-padding" style="min-height: 200px;">
                                        <span class="f-300">Provavelmente você já precisou fazer a desagradável tarefa de lembrar seus clientes de vez em quando sobre pagamentos em atraso,  não é mesmo? O aplicativo possui um sistema dedicado para gerar e enviar as faturas automaticamente para o email de seus clientes 3 dias antes do vencimento e renovação, assim seus clientes serão informados antes da data de vencimento. E você receberá notificações push toda vez que o sistema gerar faturas automaticamente para seus clientes, tudo para seu controle e acompanhamento.</span>
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
                                        <span class="f-300">Você que se preocupa com seu atendimento e a satisfação de seu cliente, agora você terá uma plataforma para seu cliente demonstrar o reconhecimento por seu trabalho. Seus clientes poderão avaliar seu trabalho ou de sua empresa separadamente, incentivando sempre o melhor de cada profissional que atua no atendimento junto com você.</span>
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
                                        <h4 class="f-300">Dados importantes</h4>
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
        <div class="container text-center m-t-30">
            <h2 class="f-300">Conheça</h2>
            <span class="f-300">Confira algumas telas do aplicativo!</span>
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

    <!-- Actions -->
    <div  style="padding-top: 50px;">
        <div class="container text-center m-t-30 m-b-30">
            <h2 class="f-300">Cadastre-se agora</h2>
            <h2 class="f-300 m-t-10">É grátis!</h2>
            <div class="row m-t-30">
                <div class="col-sm-6 m-b-20">
                    <p>Ainda não tenho uma conta de profissional</p>
                    <a class="btn btn-primary btn-lg btn-block p-10" href="/cadastro">Quero me cadastrar</a>
                </div>

                <div class="col-sm-6">
                    <p>Já sou cadastrado no iSaudavel</p>
                    <a class="btn btn-success btn-lg btn-block p-10 " href="/profissionais/login?redirect=/cadastro/empresa">Fazer login</a>
                </div>
            </div>
        </div>

    </div>
    <!-- / Actions -->

</section>



@include('landing.home.download')
@include('landing.home.footer')

<!-- Js -->
<script src="{{ elixir('build/landing/js/build_vendors_custom.js') }}"></script>

<script>

    setTimeout(function () {
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
</body>
</html>
