@extends('landing.events.index')

@section('landing-content')
    <style media="screen">

        @media screen and (max-width: 768px) {
            .btn-buscar {
                margin-top: -20px;
            }

            .header-mobile {
                margin-top: 15px;
            }

            .input-cat {
                margin-top: -20px;
            }

            #search-area {
                padding: 80px 0 10px 0px !important;
            }

            .navbar-default.navbar-fixed-top.animated .navbar-toggle,
            .navbar-default .navbar-toggle:hover,
            .navbar-default .navbar-toggle:focus {
                background-color: #88C657 !important;
            }

            .navbar-default .navbar-toggle {
                border-color: #fff;
            }

            .navbar-default .navbar-toggle .icon-bar {
                background-color: #fff;
            }

            .navbar-default .navbar-collapse {
                border-color: #88C657;
            }

            .navbar-default {
                background-color: #88C657 !important;
            }

            .navbar-default.navbar-fixed-top.animated {
                background: #fff !important;
            }
        }

        @media screen and (min-width: 768px) {
            .btn-buscar {
                margin-top: 17px;
            }
        }

        #search-area.search-page {
            margin-top: 0;
            padding-bottom: 40px;
            background: rgba(0, 0, 0, 0) linear-gradient(180deg, #6EC058 20%, #88C657 100%) repeat scroll 0 0;
        }

        .c-withe {
            color: #fff;
        }

        .category-list .label {
            display: inline-block;
            margin-right: 5px;
            margin-top: 5px;
        }

        .selected-submodality {
            color: #007DB2;
            border: 1px solid #007DB2;
            background-color: #fff
        }

    </style>


    <header id="search-area" class="search-page">

        <div class="container" id="search-events">
            <div class="row header-mobile">
                <h2 class="text-center" style="color: #fff;">Encontre eventos próximos à você</h2>

                <form class="form hidden" id="event-filters" action="{{route('landing.events.list')}}" method="get">
                    <input type="hidden" name="filters" v-model="event_filters" id="event_filters">

                    <div class="col-sm-12 text-center">

                       <div class="row">
                           <div class="col-sm-6 col-xs-12">
                               <div class="form-group search m-t-20">
                                   <label class="c-withe">Nome</label><br>
                                   <span class="search-label d-blocktext-center"></span>
                                   <input type="text" class="form-control" v-model="interactions.search"
                                          placeholder="Procure pelo nome" @blur="setFilters()">
                               </div>
                           </div>

                           <div class="col-sm-6 col-xs-12">
                               <div class="form-group search m-t-20">
                                   <label class="c-withe">Localização</label><br>
                                   <gmap-autocomplete
                                           class="form-control"
                                           :select-first-on-enter="true"
                                           @place_changed="setLocation"
                                           placeholder="Procure pela cidade"
                                           :options="{language: 'pt-BR',types: ['(cities)'], componentRestrictions: { country: 'br' }}"
                                           :value="interactions.city"
                                   >
                                   </gmap-autocomplete>
                               </div>
                           </div>
                       </div>


                        <div class="filters">
                            <div class="category-list m-b-20">
                                <div class="row">
                                    <label class="c-withe">Modalidades</label><br>
                                    <span class="label f-14 label-primary m-t-5 m-r-5 p-5 cursor-pointer"
                                          v-for="(modality, modalityIndex) in modalities"
                                          @click="selectModality(modality)"
                                          :class="{'label-primary':selectedModalities.indexOf(modality) < 0, 'label-info': selectedModalities.indexOf(modality) > -1}">
                                            @{{modality.name}} <i class="ion-close" v-if="selectedModalities.indexOf(modality) > -1"></i></span>
                                </div>
                            </div>

                            <div class="category-list m-b-20">
                                <div class="row">
                                    <label class="c-withe">Sub modalidades</label><br>
                                    <span class="label f-14 label-primary m-t-5 m-r-5 p-5 cursor-pointer"
                                          v-for="(sub_modality, sub_modalityIndex) in subModalities"
                                          @click="selectSubModality(sub_modality)"
                                          :class="{'label-primary':selectedSubModalities.indexOf(sub_modality) < 0, 'selected-submodality': selectedSubModalities.indexOf(sub_modality) > -1}">
                                            @{{sub_modality.name}} <i class="ion-close" v-if="selectedSubModalities.indexOf(sub_modality) > -1"></i></span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="col-sm-12 m-t-20 text-center">
                        <button type="submit" class="btn btn-default btn-block">Pesquisar</button>
                        <button type="submit" class="btn btn-outline btn-xs c-withe m-t-20" v-if="filterHistory"
                                @click="clearFilters">Limpar filtros de pesquisa
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </header>

    @include('landing.events.events', ['has_title' => false])
@stop

@section('scripts')



    <script>

        Vue.use(VueGoogleMaps, {
            load: {
                key: 'AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM',
                v: 3,
                libraries: 'places',
            }
        })



        Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

        Vue.config.debug = true;

        @php
            if(\App::environment('production')){
                echo 'Vue.config.devtools = false;
                  Vue.config.debug = false;
                  Vue.config.silent = true;';
            }
        @endphp

        var vm = new Vue({
            el: '#search-events',
            data: {
                interactions: {
                    showFilters: false,
                    search: '',
                    city: 'São Paulo - SP'
                },
                latitude: -23.555877,
                longitude: -46.691593,
                modalities: [],
                subModalities: [],
                selectedModalities: [],
                modalitiesFiltered: [],
                selectedSubModalities: [],
                subModalitiesFiltered: [],
                event_filters: null,
                filterHistory: null
            },
            mounted: function () {
                let that = this
                //Remove class hidden to prevent show brackets when vue is loading
                document.getElementById("event-filters").classList.remove("hidden")

                //prevent form submit on enter
                document.getElementById('event-filters').onkeypress = function(e) {
                    var key = e.charCode || e.keyCode || 0;
                    if (key == 13) {
                        e.preventDefault();
                    }
                }

                that.modalities = modalities

                that.modalities.map(function (modality) {
                    modality.submodalities.map(function (submodality) {
                        that.subModalities.push(submodality)
                    })
                })


                if(filters){
                    that.filterHistory = filters
                    that.handleFilters()
                }
            },
            methods: {

                selectModality(selected) {
                    let that = this

                    let type_index = that.selectedModalities.indexOf(selected)

                    if (type_index < 0) {
                        that.selectedModalities.push(selected)
                    } else {

                        that.selectedModalities.splice(type_index, 1)
                    }

                    that.handleSelectedModalities()
                },

                handleSelectedModalities() {
                    let that = this

                    let selectedModalities = _.reduce(that.selectedModalities, function (result, category, key) {
                        result.push(category.slug);
                        return result;
                    }, []);

                    that.modalitiesFiltered = selectedModalities

                    that.setFilters()
                },

                selectSubModality(selected) {
                    let that = this

                    let type_index = that.selectedSubModalities.indexOf(selected)

                    if (type_index < 0) {
                        that.selectedSubModalities.push(selected)
                    } else {

                        that.selectedSubModalities.splice(type_index, 1)
                    }

                    that.handleSelectedSubModalities()
                },

                handleSelectedSubModalities() {
                    let that = this

                    let selectedModalities = _.reduce(that.selectedSubModalities, function (result, category, key) {
                        result.push(category.slug);
                        return result;
                    }, []);

                    that.subModalitiesFiltered = selectedModalities

                    that.setFilters()
                },

                setFilters() {
                    let that = this

                    let filters = {
                        modalities: that.modalitiesFiltered,
                        submodalities: that.subModalitiesFiltered,
                        city: that.interactions.city,
                        search: that.interactions.search,
                        latitude: that.latitude,
                        longitude: that.longitude
                    }

                    that.event_filters = JSON.stringify(filters)
                },


                setLocation(place) {

                    let that = this
                    if (place.geometry !== undefined) {

                        that.latitude = place.geometry.location.lat()
                        that.longitude = place.geometry.location.lng()
                        that.interactions.city = place.formatted_address

                        that.setFilters()
                    }
                },


                handleFilters(){
                    let that = this

                    that.modalitiesFiltered = that.filterHistory.modalities
                    that.subModalitiesFiltered = that.filterHistory.submodalities
                    that.interactions.search = that.filterHistory.search
                    that.interactions.city = that.filterHistory.city
                    that.latitude = that.filterHistory.latitude
                    that.longitude = that.filterHistory.longitude

                    if(that.modalitiesFiltered.length)
                    {
                        that.modalitiesFiltered.map((selected_modality) => {
                            let modality = _.find(that.modalities, {slug: selected_modality})

                            if(modality){
                                that.selectedModalities.push(modality)
                            }
                        })
                    }

                    if(that.subModalitiesFiltered.length)
                    {
                        that.subModalitiesFiltered.map((selected_sub_modality) => {

                            let sub_modality = _.find(that.subModalities, {slug: selected_sub_modality})

                            if(sub_modality){
                                that.selectedSubModalities.push(sub_modality)
                            }
                        })
                    }

                },

                clearFilters(){
                    let that = this

                    that.recipe_filters = null
                },
            }
        })
    </script>
@endsection
