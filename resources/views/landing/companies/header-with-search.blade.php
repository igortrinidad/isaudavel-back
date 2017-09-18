
<style>
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

    .swiper-categories .swiper-pagination .swiper-pagination-bullet {
        background-color: transparent !important;
        opacity: 1 !important;
        border: 1px solid #88C657;
    }

    .swiper-categories .swiper-pagination .swiper-pagination-bullet-active{ background-color: #88C657 !important; }

    .swiper-categories .swiper-button-prev,
    .swiper-categories .swiper-button-next {
        background-image: none !important;
        font-size: 20px;
        background-color: #fff;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 30px !important;
        margin-top: -30px !important;
    }
    .swiper-categories .swiper-button-prev { left: 0; }
    .swiper-categories .swiper-button-next {}

    #search-area {
        padding: 80px 0 80px 0px;
        background: #fff;
        position: relative;
        display: block;
        margin-top: -80px;
    }
    #search-area.search-page {
        margin-top: 0;
        padding-bottom: 20px;
        background: rgba(0, 0, 0, 0) linear-gradient(180deg, #88C657 20%, #6EC058 100%) repeat scroll 0 0;
    }
    .card.category .picture-bg { border-radius: 4px; height: 120px; }

    .picture-bg {
        box-sizing: border-box;
        margin: 0 auto;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>

<header id="search-area" :class="{ 'search-page' : pathSearch }">

    <div class="container p-t-30">
       <div class="row header-mobile">
           <h3 class="text-center m-b-30">Encontre empresas e profissionais de saúde próximos à você</h3>
           <div class="col-xs-12 col-sm-4 col-md-4">
               <div class="form-group">
                   <input class="form-control" id="autocomplete" placeholder="Informe a cidade" />
               </div>
           </div>
           <div class="col-xs-12 col-sm-4 col-md-4 input-cat">
               <div class="form-group">
                   <select v-model="category" class="form-control" @change="setCategory($event)">
                      <option :value="null" disabled>Selecione uma categoria (obrigatório)</option>
                      <option :value="category.slug" v-for="(category, indexCat) in categories">@{{category.name}}</option>
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
           <div class="col-xs-12 col-md-12 text-left" v-if="category">
               <p class="f-13">Você está pesquisando por <b>@{{ category }}</b><span v-if="city"> em <b>@{{ city }}</b></span></p>
           </div>
       </div>

        <div class="row m-t-10">
            <div class="col-sm-12">
                <div class="swiper-container swiper-categories wow fadeInUp">
                    <div class="swiper-wrapper">
                        @foreach($categories as $category)
                            <div class="swiper-slide">
                                <div class="card category">
                                    <a href="{{route('landing.search.index', ['category' => $category->slug])}}">
                                        <div
                                            class="card-header ch-alt picture-bg"
                                            style="background-image:url({{ $category->avatar }})"
                                        >
                                        </div>
                                        <div class="card-body card-padding text-center">
                                            <h5 class="f-300">{{ $category->name }}</h5>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev">
                        <i class="ion-ios-arrow-left"></i>
                    </div>
                    <div class="swiper-button-next">
                        <i class="ion-ios-arrow-right"></i>
                    </div>
                    <div style="height: 30px;"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
   </div>
</header>

    @section('scripts')
        @parent

        <script>

          function initAutocomplete() {


            var autocomplete = new google.maps.places.Autocomplete(
            (
                document.getElementById('autocomplete')), {
              types: ['(cities)'],
              language: 'pt-BR',
              componentRestrictions: {'country': 'br'}
            });


            autocomplete.addListener('place_changed', function() {
              var place = autocomplete.getPlace();
                if (place.geometry) {

                    var city = document.getElementById('city');
                    city.setAttribute('value', place.name)

                    var lat = document.getElementById('lat');
                    lat.setAttribute('value', place.geometry.location.lat())

                    var lng = document.getElementById('lng');
                    lng.setAttribute('value', place.geometry.location.lng())

                } else {
                    document.getElementById('autocomplete').placeholder = 'Enter a city';
                }

            });
          }

            Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#search-area',
                data: {
                    city: '',
                    category: null,
                    categories: [],
                    pathSearch: false
                },
                mounted: function() {

                    var url = new URL(window.location.href);
                    var category = url.searchParams.get("category");
                    var city = url.searchParams.get("city");
                    this.category = category
                    this.city = city
                    if (window.location.pathname === '/buscar') {
                        this.pathSearch = true
                    }

                    this.getCategories();

                },
                methods: {
                  getCategories: function(){
                      let that = this

                        this.$http.get('/api/company/category/list').then(response => {

                            that.categories = response.body;

                        }, response => {
                            // error callback
                        });
                  },

                  setCategory: function(ev){

                    var city = document.getElementById('category');
                    city.setAttribute('value', ev.target.value)

                  },
                }

            })

            var swiperFeatureds = new Swiper('.swiper-categories', {
                centeredSlides: true,
                spaceBetween: 15,
                loop: true,
                slidesPerView: 5,
                slideToClickedSlide: false,
                paginationClickable: true,
                pagination: '.swiper-pagination',
                prevButton: '.swiper-button-prev',
                nextButton: '.swiper-button-next',
                breakpoints: {
                    768: {
                        slidesPerView: 1
                    }
                }
            })

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
             async defer></script>


    @stop
