<div class="navbar-default navbar-fixed-top animated" id="navigation">
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
                <li class="{{ getActiveRoute('oracle.dashboard.companies.list') }}"><a href="{!! route('oracle.dashboard.companies.list') !!}">Empresas</a></li>
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
                @if (Auth::guard('oracle_web')->check())
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::guard('oracle_web')->user()->full_name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li class="{{ getActiveRoute('oracle.dashboard.profile.show') }}" ><a href="{!! route('oracle.dashboard.profile.show') !!}">Meu perfil</a></li>
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
                @endif
            </ul>
        </nav><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</div>
