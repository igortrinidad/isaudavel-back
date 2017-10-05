<style media="screen">
    .card-picture-header {
        box-sizing: border-box;
        margin: 0 auto;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 150px;
        border: solid 1px #EBECEC;
    }
    .card-header { position: relative ;}
    .card-header:hover .hover{
        display: flex;
    }
    .card-header .hover {
        width: 100%; height: 100%;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        background-color: rgba(56, 57, 56, .6);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #F4F5F5;
        border-radius: 4px;
        font-size: 50px;
        display: none;
    }

    /* Event Cats */
    .list-cats {
        height: 78px; width: 100%;
        position: relative;
        display: flex;
        align-items: flex-end;
    }

    /* Event */
    .row.row-event { margin: -52px -5px 0 -5px; }

    .row.row-event .event-col { padding: 0 5px !important; }

    /* Event Date */
    .event-date {
    height: 78px; width: 100%;
    border: 2px solid #383938;
    border-radius: 4px;
    }

    .event-date-header,
    .event-date-body {
    width: 100%;
    position: relative;
    }

    .event-date-header {
    background-color: #383938;
    display: block;
    color: #F4F5F5;
    padding: 2px 0;
    }

    .event-date-body {
    text-transform: uppercase;
    padding: 4px;
    }
    .event-date-body span { display: block; }
    .event-date-body span:first-child { border-bottom: 1px solid rgba(56, 57, 56, .6); }


    .divider{
        background-image: url("/images/pattern-isaudavel-5-300.png");
    }

    .gray {
        background-color: #f4f5f4;
    }

    .recipe-info{
        width: 50%;
        box-sizing: border-box;
        float: left;
        text-transform: uppercase;
        display: flex;
        flex-direction: column;
    }

    .time-info{
        border-right: 1px solid #d8d8d8;
    }

    .f-primary {
        color: #88C657 !important;
    }

    /* Pagination */
    .pagination {
        font-size: 16px !important;
    }
    .pagination > li > a,
    .pagination > li > span {
        background-color: #fff;
        color: #73C158 !important;
    }

    .pagination > li.active > a,
    .pagination > li.active > span {
        background-color: #73C158 !important;
        border-color: #73C158 !important;
        color: #fff !important;
    }

    .pagination > li.disabled > a,
    .pagination > li.disabled > span {

        color: #ccc !important;
    }

    .pagination > li > a > i,
    .pagination > li > span > i{
        padding: 6px;
    }

    .tag-list .label {
        display: inline-block;
        margin-right: 5px;
        margin-top: 5px;
    }
</style>

<section class="section {{ $has_title ? 'gray' : 'divider' }}">
    <!-- List Events -->
    <div class="container">
        @if($has_title)
            <div class="text-center">
                <h2>Receitas</h2>
                <span class="f-300">Encontre as melhores receitas para você.</span>
            </div>
        @endif
        <div class="row">
            {{-- Recipes --}}
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body card-padding">
                                <h2 class="f-300">Exibindo <strong>{{$recipes->count()}}</strong> {{$recipes->count() > 1? 'receitas': 'receita'}} de <strong>{{$recipes->total()}}</strong> {{$recipes->total() > 1 ? 'receitas': 'receita'}} localizadas
                                </h2>
                                {{-- @if($recipes->count() == 0)
                                    <div class="m-t-30">
                                        <span class="f-300 f-18">Porém, nenhuma receita foi encontrada <i class="ion-sad-outline"></i></span>
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                    @foreach($recipes as $recipe)
                        <div class="col-md-4 col-xs-12 wow fadeInUp m-t-30">
                            <a href="{{route('landing.recipes.show', $recipe->slug)}}" title="Confira mais sobre {{ $recipe->title }}">
                                <!-- Card recipe -->
                                <div class="card m-b-10 cursor-pointer">
                                    <!--  Card recipe Header-->
                                    <div class="card-header ch-alt card-picture-header" style="background-image:url('{{ $recipe->avatar }}')">
                                        <div class="hover">
                                            <i class="ion-ios-plus-empty"></i>
                                        </div>
                                    </div>
                                    <!-- / Card recipe Header -->

                                    <!--  Card recipe body-->
                                    <div class="card-body card-padding text-center">

                                        <h3 class="f-300 m-t-5" style="height: 60px;">{{ $recipe->title }}</h3>

                                        @if($recipe->current_rating > 0)
                                            <div class="wp-rating-div">
                                                <h5>Avaliação</h5>
                                                @php
                                                    $rating_to_loop = $recipe->current_rating;
                                                @endphp
                                                @include('components.rating', ['size' => '28', 'icon' => 'ion-star', 'color' => '#DEB62F'])
                                            </div>
                                        @endif

                                        <div class="row m-t-10">
                                            <div class="recipe-info time-info">
                                                <i class="ion-android-alarm-clock fa-lg f-primary"></i> {{$recipe->prep_time}} MIN
                                            </div>
                                            <div class="recipe-info">
                                                <i class="ion-android-restaurant fa-lg f-primary"></i> {{$recipe->portions}} {{$recipe->portions > 1 ? 'Porções' : 'Porção'}}
                                            </div>
                                        </div>

                                        <div  class="row m-t-20" style="height: 100px;">
                                            <div class=" tag-list text-center m-b-10">
                                                @foreach($recipe->types as $type)
                                                    <span class="label label-info f-14 p-5">{{$type->name}}</span>
                                                @endforeach
                                            </div>
                                            <div class=" tag-list text-center m-b-10">
                                                @foreach($recipe->tags as $tag)
                                                    <span class="label label-success f-14 p-5">{{$tag->name}}</span>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                    <!-- / Card recipe body -->
                                </div>
                                <!-- / Card recipe -->
                            </a>
                        </div>
                    @endforeach
                    <div class="col-sm-12">
                        <div class="text-center">
                            {{ $recipes->links() }}
                        </div>
                    </div>
                </div>
            </div>
            {{-- Recipes --}}

            {{-- sidebar --}}
            <div class="col-sm-3">
                @include('landing.home.sidebar')
            </div>
            {{-- sidebar --}}
        </div>

    </div>
    <!-- / List recipes -->
</section>
