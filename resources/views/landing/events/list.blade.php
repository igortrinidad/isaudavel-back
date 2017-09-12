@extends('landing.events.index')

@section('landing-content')
    <style media="screen">

        @media screen and (max-width: 768px) {
            .btn-buscar {
                margin-top: -20px;
            }

            .header-mobile{
              margin-top: 15px;
            }

            .input-cat{
              margin-top: -20px;
            }

            #search-area { padding: 80px 0 10px 0px !important; }

            .navbar-default.navbar-fixed-top.animated .navbar-toggle,
            .navbar-default .navbar-toggle:hover,
            .navbar-default .navbar-toggle:focus { background-color: #88C657 !important; }

            .navbar-default .navbar-toggle { border-color: #fff; }
            .navbar-default .navbar-toggle .icon-bar { background-color: #fff; }
            .navbar-default .navbar-collapse { border-color: #88C657; }
            .navbar-default { background-color: #88C657 !important; }
            .navbar-default.navbar-fixed-top.animated { background: #fff !important; }
        }

        @media screen and (min-width: 768px) {
            .btn-buscar {
                margin-top: 17px;
            }
        }

        #search-area.search-page {
            margin-top: 0;
            padding-bottom: 20px;
            background: rgba(0, 0, 0, 0) linear-gradient(180deg, #6EC058 20%, #88C657 100%) repeat scroll 0 0;
        }

    </style>


    <header id="search-area" class="search-page">

        <div class="container">
           <div class="row header-mobile">
               <h3 class="text-center">Encontre eventos próximos à você</h3>
               <div class="col-xs-12 col-sm-4 col-md-4">
                   <div class="form-group">
                       <input class="form-control" id="autocomplete" placeholder="Informe a cidade" />
                   </div>
               </div>
               <div class="col-xs-12 col-sm-4 col-md-4 input-cat">
                   <div class="form-group">
                       <select v-model="category" class="form-control">
                          <option :value="null" disabled>Selecione uma categoria (obrigatório)</option>
                       </select>
                   </div>
               </div>
               <div class="col-xs-12 col-sm-4 col-md-4 text-center">
                   <form method="GET" action="/buscar">
                       <input type="hidden" name="city" id="city" value="">
                       <input type="hidden" name="lat" id="lat" value="">
                       <input type="hidden" name="lng" id="lng" value="">
                       <input type="hidden" name="category" id="category" value="">
                       <div class="form-group">
                           <button type="submit" class="btn btn-primary btn-block btn-buscar" :disabled="!category">Buscar</button>
                       </div>
                   </form>
               </div>
               <!-- <div class="col-xs-12 col-md-12 text-left" v-if="category">
                   <p class="f-13">Você está pesquisando por <b>@{{category}}</b><span v-if="city"> em <b>@{{city}}</b></span></p>
               </div> -->
           </div>
       </div>
    </header>

    <section class="section gray divider">
        <!-- List Events -->
        <div class="container">
            <div class="row">
                @foreach($events as $event)
                    <div class="col-sm-3 col-xs-12">
                        <a href="/eventos/{{ $event->slug }}" title="Confira mais sobre {{ $event->name }}">
                            <!-- Card Event -->
                            <div class="card m-b-10 cursor-pointer">
                                <!--  Card Event Header-->
                                <div class="card-header ch-alt card-picture-header" style="background-image:url('{{ $event->avatar }}')">
                                    <div class="hover">
                                        <i class="ion-ios-plus-empty"></i>
                                    </div>
                                </div>
                                <!-- / Card Event Header -->

                                <!--  Card Event body-->
                                <div class="card-body card-padding">
                                    <div class="row row-event">
                                        <!-- Event Date -->
                                        <div class="col-xs-3 event-col">
                                            <div class="event-date text-center">
                                                <div class="event-date-header">
                                                    <span class="f-700 f-12">{{ $event->date->format('Y') }}</span>
                                                </div>
                                                <div class="event-date-body">
                                                    <span class="f-700 f-16">{{ $event->date->format('d') }}</span>
                                                    <span class="f-300">{{ $event->date->format('M') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- / Event Date -->
                                        <div class="col-xs-9 event-col">
                                            <div class="list-cats">
                                                @foreach($event->categories as $category)
                                                    <span class="label label-primary f-300 f-14 m-t-5 m-r-5">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="f-300 m-t-10">{{ $event->name }}</h3>
                                    <!-- location -->
                                    <div class="m-t-10">
                                        <span class="d-block f-300 f-14">
                                            <i class="icon ion-ios-location-outline m-r-10 f-20"></i>{{ $event->city }} - {{ $event->state }}
                                        </span>
                                    </div>
                                    <!-- / location -->

                                </div>
                                <!-- / Card Event body -->
                            </div>
                            <!-- / Card Event -->
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- / List Events -->
    </section>

    @section('scripts')
        @parent
        <script>
            // Vue.config.debug = true;
            // var vm = new Vue({
            //     el: '#list-events',
            //     data: {
            //     },
            //     computed: {
            //
            //     },
            //     mounted: function() {
            //
            //     },
            //     methods: {
            //
            //     }
            //
            // })
        </script>
    @stop
@stop
