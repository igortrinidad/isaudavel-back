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
                @if (Auth::guard('professional_web')->guest())
                <li><a href="{{ route('landing.professionals.login') }}">Login</a></li>
                @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::guard('professional_web')->user()->full_name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('professional.logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Sair
                            </a>

                            <form id="logout-form" action="{{ route('professional.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right" id="top-nav">
                <li class="{{ getActiveRoute('landing.index') }}"><a href="{!! route('landing.index') !!}">Home</a></li>
                <li class="{{ getActiveRoute('professional.dashboard.companies.list') }}"><a href="{!! route('professional.dashboard.companies.list') !!}">Minhas empresas</a></li>
            </ul>
        </nav><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</div>
