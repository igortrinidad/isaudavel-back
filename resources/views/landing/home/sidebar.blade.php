<div class="card">
    <div class="card-body">
        <div class="card-body card-padding text-center">

            @if (isset($current_view) && $current_view != 'companies')
                <h3 class="f-300 m-t-0">Encontre empresas e profissionais <strong style="color: #f1562b">certos</strong> para você com as avaliações de outros usuários.</h3>
                <a href="{!! route('landing.search.index', ['category' => 'pilates']) !!}" class="btn btn-secondary btn-xs f-300 f-16 m-t-20" title="Conferir eventos">empresas e profissionais</a>
            @endif

            @if (isset($current_view) && $current_view != 'recipes')
                <hr class="m-t-30 m-b-30">
                <h3 class="f-300 m-t-30">Que tal encontrar aquela <strong style="color: #72c157">receita</strong> saborosa sem sair de forma?</h3>
                <a href="{!! route('landing.recipes.list') !!}" class="btn btn-success btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir Receitas</a>
            @endif

            @if (isset($current_view) && $current_view != 'events')
                <hr class="m-t-30 m-b-30">
                <h3 class="f-300 m-t-0">Encontre eventos <strong style="color: #f1562b">próximos à você</strong> e garanta sua presença.</h3>
                <a href="{!! route('landing.events.list') !!}" class="btn btn-secondary btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir eventos</a>
            @endif

            @if (isset($current_view) && $current_view != 'articles')
                <hr class="m-t-30 m-b-30">
                <h3 class="f-300 m-t-30">Fique atento a <strong style="color: #f1562b">noticias</strong> na nossa sessão de artigos</h3>
                <a href="{!! route('landing.articles.list') !!}" class="btn btn-secondary btn-xs f-300 f-16 m-t-20" title="Conferir eventos">Conferir Artigos</a>
            @endif

            <hr class="m-t-30 m-b-30">
            <h3 class="f-300 m-t-30">Baixe o <strong style="color: #72c157">iSaudavel</strong> e faça parte da rede social que cuida da sua saúde.</h3>
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
