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
                    <img class="logo-1" src="/logos/LOGO-1-04.png" alt="LOGO" width="120px">
                    <img class="logo-2" src="/logos/LOGO-1-01.png" alt="LOGO" width="120px">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <nav class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav navbar-right" id="top-nav">
                    <li class="{{ getActiveRoute('landing.index') }}"><a href="{!! route('landing.index') !!}">Home</a></li>
                    <li class="{{ getActiveRoute('landing.clients.about') }}"><a href="{!! route('landing.clients.about') !!}">Para você</a></li>
                    <li class="{{ getActiveRoute('landing.professionals.about') }}"><a href="{!! route('landing.professionals.about') !!}">Para profissionais</a></li>
                    <li class="{{ getActiveRoute('landing.search.index') }}"><a href="{!! route('landing.search.index') !!}">Buscar empresas</a></li>
                    <li class="{{ getActiveRoute('landing.index') }}"><a href="{!! route('landing.index') !!}#contato">Contato</a></li>
                </ul>
            </nav><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </div>
