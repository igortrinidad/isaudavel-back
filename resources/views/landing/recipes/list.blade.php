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

    @include('landing.events.events', ['has_title' => false])

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
