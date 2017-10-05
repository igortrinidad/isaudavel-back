<div class="card">
    <div class="card-body">
        <div class="card-body card-padding text-center">
            <div class="sidebar">
                @if (isset($current_view) && $current_view != 'companies')
                    <div class="sidebar-item">
                        <hr class="m-t-30 m-b-30">
                        <h3 class="f-300 m-t-0">Encontre <strong>empresas e profissionais</strong> certos para você com as avaliações de outros usuários.</h3>
                        <a href="{!! route('landing.search.index', ['category' => 'pilates']) !!}" class="btn btn-secondary btn-xs f-300 f-16 m-t-20" title="Conferir eventos">empresas e profissionais</a>
                    </div>
                @endif

                @if (isset($current_view) && $current_view != 'recipes')
                    <div class="sidebar-item">
                        <hr class="m-t-30 m-b-30">
                        <h3 class="f-300 m-t-30">Que tal encontrar aquela <strong>receita</strong> saborosa sem sair de forma?</h3>
                        <a href="{!! route('landing.recipes.list') !!}" class="btn btn-success btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir Receitas</a>
                        <a href="{!! route('landing.recipes.list') !!}" class="btn btn-secondary btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir Receitas</a>
                    </div>
                @endif

                @if (isset($current_view) && $current_view != 'events')
                    <div class="sidebar-item">
                        <hr class="m-t-30 m-b-30">
                        <h3 class="f-300 m-t-0">Encontre <strong>eventos</strong> próximos à você e garanta sua presença.</h3>
                        <a href="{!! route('landing.events.list') !!}" class="btn btn-success btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir eventos</a>
                        <a href="{!! route('landing.events.list') !!}" class="btn btn-secondary btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir eventos</a>
                    </div>
                @endif

                @if (isset($current_view) && $current_view != 'articles')
                    <div class="sidebar-item">
                        <hr class="m-t-30 m-b-30">
                        <h3 class="f-300 m-t-30">Acompanhe nossos <strong>artigos</strong> e fique por dentro do universo saudável</h3>
                        <a href="{!! route('landing.articles.list') !!}" class="btn btn-success btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir Artigos</a>
                        <a href="{!! route('landing.articles.list') !!}" class="btn btn-secondary btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir Artigos</a>
                    </div>
                @endif

                <div class="sidebar-item">
                    <hr class="m-t-30 m-b-30">
                    <h3 class="f-300 m-t-30">Baixe o <strong>iSaudavel</strong> e faça parte da rede social que cuida da sua saúde.</h3>
                    <div class="row">
                        <div class="col-sm-12 m-t-30">
                            <a href="https://play.google.com/store/apps/details?id=com.isaudavel" target="_blank" title="Faça o download na PlayStore para Android">
                                <img class="store-badge img-responsive" src="/images/play_store_btn.png" alt="Faça o download na PlayStore para Android">
                            </a>
                        </div>
                        <div class="col-sm-12 m-t-30">
                            <a href="https://itunes.apple.com/us/app/isaudavel/id1277115133?mt=8" target="_blank" title="Faça o download na APP Store para IOS">
                                <img class="store-badge img-responsive" src="/images/app_store_btn.png" alt="Faça o download na APP Store para IOS">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
