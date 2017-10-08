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
            padding-bottom: 20px;
            background: #72c157;
        }

        .search .form-control {
            border-width: 1px;
            border-right: 0;
            transition: none;
            font-weight: 300;
            height: 45.1px;
        }

        .search .form-control:focus {
            border-right: 0;
            transition: none;
        }

        .search .form-control:focus + .btn.btn-light {
            border-color: #383938;
            transition: none;
        }

        .search .btn.btn-light {
            border-color: #dce4ec;
            border-width: 1px;
            border-left: 0 !important;
            transition: none;
            color: #8cc63f !important;
        }

        .c-withe {
            color: #fff;
        }

        .tag-selected {
            color: #007DB2;
            border: 1px solid #007DB2;
            background-color: #fff
        }

    </style>


    <header id="search-area" class="search-page">

        <div class="container" id="search-recipes">
            <div class="row header-mobile">
                <h3 class="text-center" style="color: #fff">Encontre as melhores receitas para você</h3>

                <form class="form hidden" id="recipe-filters" action="{{route('landing.recipes.list')}}" method="get" >
                    <input type="hidden" name="filters" v-model="recipe_filters" id="recipe_filters">

                    <div class="col-sm-12 text-center">
                        <div class="form-group search m-t-20">
                            <span class="search-label d-block m-b-5 text-center"></span>
                            <input type="text" class="form-control" v-model="interactions.search"
                                   placeholder="Procure uma receita" @blur="getMealRecipes()">
                        </div>
                        <div class="filters">
                            <div class="tag-list m-t-20 m-b-20">
                                <div class="row">
                                    <label class="c-withe m-b-20">Tipo de refeição</label><br>
                                    <span class="label f-14 label-primary m-t-5 m-r-5 p-5 cursor-pointer"
                                          v-for="(type, typeIndex) in mealTypes"
                                          @click="selectMealType(type)"
                                          :class="{'label-primary':selectedMealTypes.indexOf(type) < 0,  'label-info': selectedMealTypes.indexOf(type) > -1}">
                            @{{type.name}} <i class="ion-close m-l-5" v-if="selectedMealTypes.indexOf(type) > -1"></i>
                        </span>
                                </div>
                            </div>

                            <div class="tag-list m-t-20">
                                <div class="row">
                                    <label class="c-withe m-b-20">Tags</label><br>
                                    <span class="label f-14 m-t-5 m-r-5 p-5 cursor-pointer"
                                          v-for="(tag, $tagIndex) in tags"
                                          @click="selectTag(tag)"
                                          :class="{'label-primary':selectedTags.indexOf(tag) < 0,  'tag-selected': selectedTags.indexOf(tag) > -1}">
                                            @{{tag.name}} <i class="ion-close" v-if="selectedTags.indexOf(tag) > -1"></i>
                                    </span>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-block m-t-20 m-b-20" @click.prevent="showModalSliders">
                                Filtrar por macro nutrientes
                            </button>
                            <div class="row m-t-20">
                                <span class="label label-default m-t-5 m-r-5 p-5" v-if="macroNutrients.kcal">Calorias: @{{macroNutrients.kcal}} kcal</span>
                                <span class="label label-default m-t-5 m-r-5 p-5" v-if="macroNutrients.protein">Proteina: @{{macroNutrients.protein}} gramas</span>
                                <span class="label label-default m-t-5 m-r-5 p-5" v-if="macroNutrients.carbohydrate">Carboidrato: @{{macroNutrients.carbohydrate}} gramas</span>
                                <span class="label label-default m-t-5 m-r-5 p-5" v-if="macroNutrients.lipids">Lipídios: @{{macroNutrients.lipids}} gramas</span>
                                <span class="label label-default m-t-5 m-r-5 p-5" v-if="macroNutrients.fiber">Fibra: @{{macroNutrients.fiber}} gramas</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 m-t-20 text-center">
                        <button type="submit" class="btn btn-default btn-block">Pesquisar</button>
                        <button type="submit" class="btn btn-outline btn-xs c-withe m-t-20" v-if="filterHistory" @click="clearFilters">Limpar filtros de pesquisa</button>
                    </div>
                </form>
            </div>

            <!--Modal filter macro nutrients-->
            <div class="modal" id="filter-macro-nutrients" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Filtrar receitas por macro nutrientes</h4>
                        </div>
                        <div class="modal-body">

                            <p class="text-center">Informe os valores para os macro nutrientes desejados.</p>
                            <p class="text-center">Os valores zerados não serão considerados na pesquisa.</p>
                            <p class="text-center">Buscaremos as receitas que atendam aos requisitos de macro nutrientes
                                entre 20% para mais ou para menos dos valores informados.</p>
                            <div class="macronutrients m-t-20">
                                <div class="form-group">
                                    <label class="m-l-10">Calorias <strong v-if="macroNutrients.kcal">(@{{macroNutrients.kcal}}
                                            kcal)</strong></label>
                                    <vue-slider ref="kcal"
                                                v-model="macroNutrients.kcal"
                                                :min="0"
                                                :max="1500"
                                                :process-style="sliderConfig.style"
                                                :piecewise-active-style="sliderConfig.style"
                                                :piecewise-style="sliderConfig.style"
                                                :tooltip-style="sliderConfig.style"
                                                tooltip-dir="bottom"
                                                tooltip="hover"
                                    >
                                    </vue-slider>
                                </div>

                                <div class="form-group">
                                    <label class="m-l-10">Proteina <strong v-if="macroNutrients.protein">(@{{macroNutrients.protein}}
                                            gramas)</strong></label>
                                    <vue-slider ref="protein"
                                                v-model="macroNutrients.protein"
                                                :min="0"
                                                :max="250"
                                                :process-style="sliderConfig.style"
                                                :piecewise-active-style="sliderConfig.style"
                                                :piecewise-style="sliderConfig.style"
                                                :tooltip-style="sliderConfig.style"
                                                tooltip-dir="bottom"
                                                tooltip="hover"
                                    >
                                    </vue-slider>
                                </div>

                                <div class="form-group">
                                    <label class="m-l-10">Carboidrato <strong v-if="macroNutrients.carbohydrate">(@{{macroNutrients.carbohydrate}}
                                            gramas)</strong></label>
                                    <vue-slider ref="carbohydrate"
                                                v-model="macroNutrients.carbohydrate"
                                                :min="0"
                                                :max="250"
                                                :process-style="sliderConfig.style"
                                                :piecewise-active-style="sliderConfig.style"
                                                :piecewise-style="sliderConfig.style"
                                                :tooltip-style="sliderConfig.style"
                                                tooltip-dir="bottom"
                                                tooltip="hover"
                                    >
                                    </vue-slider>
                                </div>

                                <div class="form-group">
                                    <label class="m-l-10">Lipídios <strong v-if="macroNutrients.lipids">(@{{macroNutrients.lipids}}
                                            gramas)</strong></label>
                                    <vue-slider ref="lipids"
                                                v-model="macroNutrients.lipids"
                                                :min="0"
                                                :max="250"
                                                :process-style="sliderConfig.style"
                                                :piecewise-active-style="sliderConfig.style"
                                                :piecewise-style="sliderConfig.style"
                                                :tooltip-style="sliderConfig.style"
                                                tooltip-dir="bottom"
                                                tooltip="hover"
                                    >
                                    </vue-slider>
                                </div>
                                <div class="form-group">
                                    <label class="m-l-10">Fibra <strong v-if="macroNutrients.fiber">(@{{macroNutrients.fiber}}
                                            gramas)</strong></label>
                                    <vue-slider ref="fiber"
                                                v-model="macroNutrients.fiber"
                                                :min="0"
                                                :max="250"
                                                :process-style="sliderConfig.style"
                                                :piecewise-active-style="sliderConfig.style"
                                                :piecewise-style="sliderConfig.style"
                                                :tooltip-style="sliderConfig.style"
                                                tooltip-dir="bottom"
                                                tooltip="hover"
                                    >
                                    </vue-slider>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button class="btn btn-info" @click.prevent="getMealRecipes">Adicionar filtros</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Modal filter macro nutrients-->
        </div>

    </header>

    @include('landing.recipes.recipes', ['has_title' => false])

@stop

@section('scripts')
    <script>

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
                el: '#search-recipes',
                components: {
                    'vueSlider': window['vue-slider-component'],
                },
                data: {
                    is_loading: false,
                    mealTypes: [],
                    tags: [],
                    interactions: {
                        showFilters: false,
                        search: ''
                    },
                    currentMealType: '',
                    located_count: 0,
                    mealTypes: [],
                    tags: [],
                    meal_recipes: [],
                    pagination: {},
                    selectedTags: [],
                    sliderConfig: {
                        min: 0,
                        max: 1000,
                        style: {
                            "backgroundColor": "#59BF63",
                            "borderColor": "#59BF63"
                        }
                    },
                    macroNutrients: {
                        kcal: 0,
                        protein: 0,
                        carbohydrate: 0,
                        lipids: 0,
                        fiber: 0,
                    },
                    selectedMealTypes: [],
                    mealTypesFiltered: [],
                    tagsFiltered: [],
                    current_page: null,
                    recipe_filters: null,
                    filterHistory: null,

                },
                mounted: function () {

                    //Remove class hidden to prevent show brackets on page loading
                    document.getElementById("recipe-filters").classList.remove("hidden")

                    this.mealTypes = meal_types
                    this.tags = tags

                    if(filters){
                        this.filterHistory = filters
                        this.handleFilters()
                    }


                },
                methods: {
                    selectTag(selected) {
                        let that = this

                        let tag_index = that.selectedTags.indexOf(selected)

                        if (tag_index < 0) {
                            that.selectedTags.push(selected)
                        } else {
                            that.selectedTags.splice(tag_index, 1)
                        }

                        that.handleSelectedTags()
                    },

                    handleSelectedTags() {
                        let that = this

                        let selectedTags = _.reduce(that.selectedTags, function (result, tag, key) {
                            result.push(tag.slug);
                            return result;
                        }, []);

                        that.tagsFiltered = selectedTags

                        that.getMealRecipes()
                    },

                    showModalSliders() {

                        $('#filter-macro-nutrients').modal('show')
                        this.$nextTick(() => {
                            this.$refs.kcal.refresh()
                            this.$refs.protein.refresh()
                            this.$refs.carbohydrate.refresh()
                            this.$refs.lipids.refresh()
                            this.$refs.fiber.refresh()
                        })
                    },

                    selectMealType(selected) {
                        let that = this

                        let type_index = that.selectedMealTypes.indexOf(selected)

                        if (type_index < 0) {
                            that.selectedMealTypes.push(selected)
                        } else {

                            that.selectedMealTypes.splice(type_index, 1)
                        }

                        that.handleSelectedMealTypes()
                    },

                    handleSelectedMealTypes() {
                        let that = this

                        let selectedMealTypes = _.reduce(that.selectedMealTypes, function (result, type, key) {
                            result.push(type.slug);
                            return result;
                        }, []);

                        that.mealTypesFiltered = selectedMealTypes

                        that.getMealRecipes()
                    },

                    handleFilterHistory() {

                        let that = this

                        let filters = JSON.parse(localStorage.getItem('recipe-filters'))

                        if (filters) {
                            that.mealTypesFiltered = filters.types
                            that.tagsFiltered = filters.tags
                            that.macroNutrients = filters.nutrients
                            that.interactions.search = filters.search
                            that.current_page = filters.current_page
                        }

                        if (that.current_page) {
                            that.navigate(that.current_page)
                        }

                        if (!that.current_page) {
                            that.getMealRecipes()
                        }

                    },
                    getMealRecipes() {
                        let that = this

                        let filters = {
                            types: that.mealTypesFiltered,
                            tags: that.tagsFiltered,
                            nutrients: that.macroNutrients,
                            search: that.interactions.search
                        }

                        $('#filter-macro-nutrients').modal('hide')

                        that.recipe_filters = JSON.stringify(filters)

                    },

                    handleFilters(){
                        let that = this

                        that.mealTypesFiltered = that.filterHistory.types
                        that.tagsFiltered = that.filterHistory.tags
                        that.macroNutrients =  _.mapValues(that.filterHistory.nutrients,(value, key) => {return parseInt(value)});
                        that.interactions.search = that.filterHistory.search

                        if(that.mealTypesFiltered.length)
                        {
                            that.mealTypesFiltered.map((type) => {
                                let meal_type = _.find(that.mealTypes, {slug: type})

                                if(meal_type){
                                    that.selectedMealTypes.push(meal_type)
                                }
                            })
                        }

                        if(that.tagsFiltered.length)
                        {
                            that.tagsFiltered.map((tag) => {
                                let filter_tag = _.find(that.tags, {slug: tag})

                                if(filter_tag){
                                    that.selectedTags.push(filter_tag)
                                }
                            })
                        }

                    },

                    clearFilters(){
                        let that = this

                        that.recipe_filters = null
                    }
                }

            })

    </script>
@endsection
