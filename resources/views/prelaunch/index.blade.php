<!DOCTYPE html>
<html class="no-js" lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>iSaudavel - A sua saúde em boas mãos</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="/icons/icon_p.png" type="image/x-icon"/>
        <link rel="shortcut icon" href="/icons/icon_g.png" type="image/x-icon"/>

        <meta name="google-site-verification" content="Ed4IGd5eqro1sXT8-Cz5eYyKFThT078JpfWLnQP3-VQ" />

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!-- OPENGGRAPH -->
        <meta property="fb:app_id" content="1854829291449231" />
        <meta property="og:locale" content="pt_BR">
        <meta property="og:url" content="https://isaudavel.com">
        <meta property="og:title" content="iSaudavel">
        <meta property="og:site_name" content="iSaudavel">
        <meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
        <meta property="og:image" content="https://isaudavel.com/logos/LOGO-1-02.png">
        <meta property="og:image:type" content="image/png">

        
        <!-- Fonts -->
        <!-- Lato -->
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ elixir('build/prelaunch/css/build_vendors_custom.css') }}">

        <!-- Hotjar Tracking Code for https://isaudavel.com -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:583813,hjsv:5};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

    </head>

    <body id="body">

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


        <div id="app">

    	    <!-- 
    	    Header start
    	    ==================== -->
    	    <div class="navbar-default navbar-fixed-top" id="navigation">
    	        <div class="container">
    	            <!-- Brand and toggle get grouped for better mobile display -->
    	            <div class="navbar-header">
    	                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
    	                    <span class="sr-only">Toggle navigation</span>
    	                    <span class="icon-bar"></span>
    	                    <span class="icon-bar"></span>
    	                    <span class="icon-bar"></span>
    	                </button>
    	                <a class="navbar-brand" href="#">
                            <img class="logo-1" src="logos/LOGO-1-04.png" alt="LOGO" width="120px">
    	                    <img class="logo-2" src="logos/LOGO-1-01.png" alt="LOGO" width="120px">
    	                </a>
    	            </div>

    	            <!-- Collect the nav links, forms, and other content for toggling -->
    	            <nav class="collapse navbar-collapse" id="navbar">
    	                <ul class="nav navbar-nav navbar-right" id="top-nav">
    	                    <li class="current"><a href="#body">Home</a></li>
    	                    <li><a href="#contact">Quero saber mais</a></li>
    	                </ul>
    	            </nav><!-- /.navbar-collapse -->
    	        </div><!-- /.container-fluid -->
    	    </div>

    	    @include('prelaunch.counter')

            <!-- 
            Contact start
            ==================== -->
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

                        <div class="col-xs-12 col-sm-12 col-md-5 col-md-offset-1 wow fadeInUp" data-wow-delay="0.3s">
                        	<div class="form-group">
                        	    <form action="#" method="post" id="contact-form">
                                    {!! csrf_field() !!}
                        	        <div class="input-field">
                        	            <input type="text" class="form-control" placeholder="Seu nome e sobrenome (obrigatório)" name="name" v-model="form.name">
                        	        </div>

                                    <div class="input-field">
                                        <input type="text" class="form-control" placeholder="Seu telefone" name="phone" v-model="form.phone">
                                    </div>

                                    <div class="input-field">
                                        <input type="email" class="form-control" placeholder="Seu email (obrigatório)" name="email" v-model="form.email">
                                    </div>

                                    <div class="input-field">
                                       <select class="form-control" name="is_client" v-model="interactions.is_client" @change="setIsClient()">
                                            <option>Quero cuidar da minha saúde ou estética</option>
                                            <option>Sou profissional da área da saúde</option>
                                        </select>
                                    </div>
                                    <div class="input-field" v-if="!form.is_client">
                                        <h4>Selecione as especialidades em que atua</h4>
                                    </div>

                                    <div class="input-field" v-if="form.is_client">
                                        <h4>Selecione as especialidades que você tem interesse ou já pratica</h4>
                                    </div>

                                    <div v-for="(category, index) in categories">
                                        <div class="checkbox-group" @click.prevent="addCategory(category)">
                                            <label class="checkbox">
                                            <input type="checkbox" class="wp-checkbox-reset wp-checkbox-input" v-model="category.select" >
                                            <div class="wp-checkbox-reset wp-checkbox-inline wp-checkbox">
                                            </div>
                                            <span class="wp-checkbox-text">@{{category.label}}</span></label>
                                        </div>
                                    </div>

                                    <p>Selecione ao menos uma atividade</p>

                        	        <button class="btn btn-send" @click.prevent="sendForm()" :disabled="!form.email || !form.name || !form.categories.length">INSCREVER</button>
                        	    </form>


                        	</div>
                        </div>
                    </div>
                </div>
            </section>

            <section clas="wow fadeInUp">
            	<div class="map-wrapper">
            	</div>
            </section>

            <footer>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="block">
                                <p>Copyright &copy; <a href="http://www.isaudavel.com">iSaudavel</a>| Todos os direitos reservados.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>


        <!-- Js -->
        <script src="{{ elixir('build/prelaunch/js/build_vendors_custom.js') }}"></script>

        <!-- GOOGLE ANALYTICS -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-70761422-7', 'auto');
          ga('send', 'pageview');

        </script>

        <script>

            Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#contact',
                data: {
                    interactions: {
                        is_client: 'Quero cuidar da minha saúde ou estética',
                    },
                    form: {
                        name: '',
                        email: '',
                        phone: '',
                        type: 'prelaunch',
                        is_client: true,
                        categories: [],
                    },
                    categories: [
                            { name: 'personal', label: 'Personal Trainer', select: false },
                            { name: 'phisio', label: 'Fisioterapia', select: false },
                            { name: 'nutrition', label: 'Nutrição', select: false },
                            { name: 'pilates', label: 'Pilates', select: false },
                            { name: 'crossfit', label: 'Crossfit', select: false },
                            { name: 'coaching', label: 'Consultoria e coaching', select: false },
                            { name: 'stetic', label: 'Estética', select: false },
                    ],
                    
                },
                mounted: function() {

                },
                methods: {
                    sendForm: function() {
                        var that = this
                          // GET /someUrl
                          this.$http.post('/leadStoreForm', that.form).then(response => {

                            console.log(response);

                          }, response => {
                            // error callback
                          });
                    },

                    setIsClient: function(){
                        let that = this
                        
                        if( this.interactions.is_client =='Sou profissional da área da saúde'){
                            this.form.is_client = false
                        } else {
                            this.form.is_client = true
                        }

                    },

                    addCategory: function(category){
                        let that = this

                        var index = that.form.categories.indexOf(category.label);

                        if(index === -1){
                            that.form.categories.push(category.label);
                            category.select = true;
                        } else {
                            that.form.categories.splice(index, 1);
                            category.select = false;
                        }
                    },

                }

            })
        </script>

        @section('scripts')
        @show
        
    </body>
</html>
