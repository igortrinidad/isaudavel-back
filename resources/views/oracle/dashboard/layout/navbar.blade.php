<div class="navbar-default navbar-fixed-top animated" id="navigation" v-if="!isFullscreen">
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
                <img class="logo-1" src="/logos/LOGO-1-04.png" alt="LOGO" width="120px">
                <img class="logo-2" src="/logos/LOGO-1-01.png" alt="LOGO" width="120px">
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <nav class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav navbar-right">
                <li class="{{ getActiveRoute('landing.index') }}"><a href="{!! route('landing.index') !!}">Home</a></li>

                <li class="{{ getActiveRoute('oracle.dashboard.sales.dashboard') }}"><a href="{!! route('oracle.dashboard.sales.dashboard') !!}"><strong>Vendas</strong></a></li>

                <li class="{{ getActiveRoute('oracle.dashboard.companies.list') }}"><a href="{!! route('oracle.dashboard.companies.list') !!}">Empresas</a></li>

                <li class="{{ getActiveRoute('oracle.dashboard.follow-up') }}"><a href="{!! route('oracle.dashboard.follow-up') !!}">Follow-up</a></li>

                <li class="{{ getActiveRoute('oracle.dashboard.events.list') }}"><a href="{!! route('oracle.dashboard.events.list') !!}">Eventos</a></li>

                <li class="{{ getActiveRoute('oracle.dashboard.recipes.list') }}"><a href="{!! route('oracle.dashboard.recipes.list') !!}">Receitas</a></li>

                <li class="{{ getActiveRoute('oracle.dashboard.articles.list') }}"><a href="{!! route('oracle.dashboard.articles.list') !!}">Artigos</a></li>

                <li class="{{ getActiveRoute('oracle.dashboard.modalities.list') }}"><a href="{!! route('oracle.dashboard.modalities.list') !!}">Modalidades</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        Usuários <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="{{ getActiveRoute('oracle.dashboard.clients.list') }}" ><a href="{!! route('oracle.dashboard.clients.list') !!}">Clientes</a></li>
                        <li class="{{ getActiveRoute('oracle.dashboard.professionals.list') }}" ><a href="{!! route('oracle.dashboard.professionals.list') !!}">Profissionais</a></li>
                        <li class="{{ getActiveRoute('oracle.dashboard.oracles.list') }}" ><a href="{!! route('oracle.dashboard.oracles.list') !!}">Administradores</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        Sistema <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="{{ getActiveRoute('oracle.dashboard.system.edit-version') }}" ><a href="{!! route('oracle.dashboard.system.edit-version') !!}">Editar versão</a></li>
                        <li class="{{ getActiveRoute('oracle.dashboard.eval-index.list') }}" ><a href="{!! route('oracle.dashboard.eval-index.list') !!}">Índices de avaliações</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::guard('oracle_web')->user()->full_name }}
                            <small class="label label-success" v-if="unreaded_notifications" v-cloak>@{{unreaded_notifications}}</small>
                        <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li class="{{ getActiveRoute('oracle.dashboard.notifications.show') }}" ><a href="{!! route('oracle.dashboard.notifications.show') !!}">Notificações</a></li>
                        <li class="{{ getActiveRoute('oracle.dashboard.profile.show') }}" ><a href="{!! route('oracle.dashboard.profile.show') !!}">Meu perfil</a></li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('oracle.dashboard.logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Sair
                            </a>

                            <form id="logout-form" action="{{ route('oracle.dashboard.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</div>

@section('navbar-scripts')

    <script>

        Vue.config.debug = true;

        @php
            if(\App::environment('production')){
                echo 'Vue.config.devtools = false;
                  Vue.config.debug = false;
                  Vue.config.silent = true;';
            }
        @endphp

        var vm = new Vue({
                el: '#navigation',
                name: 'navbar',
                data: () =>{
                    return {
                        unreaded_notifications: 0,
                        isFullscreen: false
                    }
                },

                mounted: function () {
                    let that = this
                    this.getUserStatus()

                    this.$eventBus.$on('update-counter', function (readed_notifications) {
                        that.unreaded_notifications = that.unreaded_notifications - readed_notifications
                    })

                    this.$eventBus.$on('increment-counter', function (unreaded_notifications) {
                        that.unreaded_notifications = that.unreaded_notifications + unreaded_notifications
                    })

                    this.$eventBus.$on('fullscreen', function (state) {
                        that.isFullscreen = state
                    })
                },
                beforeDestroy() {
                    this.$eventBus.$off('update-counter')
                    this.$eventBus.$off('increment-counter')
                    this.$eventBus.$off('fullscreen')
                },
                methods: {
                    getUserStatus: function(){
                        let that = this

                        this.$http.post('/api/oracle/status', {oracle_user_id: '{{\Auth::user()->id}}'}).then(response => {
                            that.unreaded_notifications = response.body.unreaded_notifications
                        }, response => {
                            // error callback
                        });
                    },
                }

            })

    </script>
@endsection

