<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>iSaudavel - A sua saúde em boas mãos</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        @include('landing.opengraph')

        
        <!-- Fonts -->
        <!-- Lato -->
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ elixir('build/prelaunch/css/build_vendors_custom.css') }}">

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        @include('landing.hotjar')

    </head>

    <body id="body">

        <div id="app">

        @include('landing.navbar')
        @include('landing.home-partials.header')



            <section id="contact" class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="block">
                                <div class="heading wow fadeInUp">
                                    <h2>Quero ser Saudável</h2>
                                    <p>Você pode ter acesso em primeira mão ao aplicativo, inscreva-se para saber mais:</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-5 wow fadeInUp">
    						<div class="block text-left">
    							<div class="sub-heading">
    								<h4>Sobre</h4>
    								<p><b>iSaudavel</b> é uma rede social criada para conectar você com os melhores profissionais, integrando as principais informações sobre você entre os profissionais que você escolher para te ajudar a atingir seus objetivos.</p>
    							</div>
    						</div>



                            <div class="block text-left">
                                <div class="sub-heading">

                                    <h4>Para você</h4>
                                    <p>Você poderá contratar diretamente da plataforma os seguintes profissionais:</p>

                                    <br>

                                    <ul class="li-prof">
                                        <li>Personal Trainner</li>
                                        <li>Academia</li>
                                        <li>Nutricionista</li>
                                        <li>Crossfit</li>
                                        <li>Fisioterapia</li>
                                        <li>Estúdio de pilates</li>
                                        <li>Clínica de estética</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="block text-left">
                                <div class="sub-heading">
                                
                                    <h4>Para profissionais</h4>
                                
                                    <p>Ofereça seus serviços e auxilie seus clientes a atingir os objetivos de saúde, bem estar e estética.</p>
                                    <br>
                                    <p>Você poderá gerenciar e ter acesso à informações importantes sobre a saúde e rotina de exercícios de seus clientes:</p>
                                    <br>
                                    <ul class="li-prof">
                                        <li>Avaliações</li>
                                        <li>Exames</li>
                                        <li>Treinamentos</li>
                                        <li>Dietas</li>
                                        <li>Fotos</li>
                                        <li>E muito mais</li>
                                    </ul>
                                    <br>
                                    <p>Seu cliente poderá compartilhar todas essas informações com você e você terá acesso à informações inclusive de outras especialidades, uma ferramenta a mais para você auxiliar seus clientes a atingir suas metas de saúde e estética além de facilitar seu controle de fichas, prontuário, avaliações e etc.</p>
                                </div>
                            </div>

                        </div>

                        <style>

                        .li-prof li::before {
                            content: "• ";
                            color: #6EC058; /* or whatever color you prefer */
                            margin-left: 20px;
                        }

                        .li-prof{
                            font-size: 14px;
                            color: #777;
                        }

                        </style>

                        <div class="col-xs-12 col-sm-12 col-md-5 col-md-offset-1 wow fadeInUp" data-wow-delay="0.3s">
                        	<div class="form-group">
                        	    <form action="#" method="post" id="contact-form">
                                    {!! csrf_field() !!}
                        	        <div class="input-field">
                        	            <input type="text" class="form-control" placeholder="Seu nome" name="name">
                        	        </div>
                                    <div class="input-field">
                                        <input type="text" class="form-control" placeholder="Seu telefone" name="phone">
                                    </div>
                        	        <div class="input-field">
                        	            <input type="email" class="form-control" placeholder="Seu email" name="email">
                        	        </div>
                        	        <button class="btn btn-send" type="submit">INSCREVER</button>
                        	    </form>

                        	    <div id="success">
                        	        <p>Obrigado por se inscrever, em breve você receberá mais informações sobre a plataforma.</p>
                        	    </div>
                        	    <div id="error">
                        	        <p>Ocorreu um erro ao realizar sua inscrição :/</p>
                        	    </div>
                        	</div>
                        </div>
                    </div>
                </div>
            </section>

            <section clas="wow fadeInUp">
            	<div class="map-wrapper">
            	</div>
            </section>


        </div>


        <!-- Js -->
        <script src="{{ elixir('build/prelaunch/js/build_vendors_custom.js') }}"></script>

        <!-- GOOGLE ANALYTICS -->
        @include('landing.googleanalytics')


        </script>

        <script type="text/javascript">

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#app',
                data: {
                },
                mounted: function() {

                    console.log('Vue rodando no index');
                },
                methods: {
                }

            })
        </script>



        @section('scripts')
        @show
        
    </body>
</html>
