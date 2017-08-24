
<style>

@media screen and (max-width: 768px) {
    .btn-buscar {
        margin-top: -20px;
    }
}

@media screen and (min-width: 768px) {
    .btn-buscar {
        margin-top: 17px;
    }
}

#search-area {
    padding: 60px 0 10px 0px;
    background: rgba(0, 0, 0, 0) linear-gradient(180deg, #88C657 20%, #6EC058 100%) repeat scroll 0 0;
}

</style>

<header id="search-area">
    <div class="container">

        
        <div class="row">

            <br>
            <h3 class="text-center">Encontre empresas e profissionais de saúde próximos à você</h3>
            <div class="col-xs-12 col-sm-8 col-md-8">
                
                <div class="form-group">
                    <input class="form-control" id="autocomplete" placeholder="Informe a cidade" />
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 text-center">
                <form method="GET" action="/new-landing/buscar">
                    <input type="hidden" name="city" id="city" value="">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-buscar">Buscar</button>
                    </div>
                </form>
            </div>

            <div class="col-xs-12 col-md-12 text-left" v-if="category">

                <p class="f-13">Você está pesquisando por <b>@{{category}}</b><span v-if="city"> em <b>@{{city}}</b></span></p>
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
                } else {
                    document.getElementById('autocomplete').placeholder = 'Enter a city';
                }

            });
          }

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#search-area',
                data: {
                    city: '',
                    category: '',
                },
                mounted: function() {

                    var url = new URL(window.location.href);
                    var category = url.searchParams.get("category");
                    var city = url.searchParams.get("city");
                    this.category = category
                    this.city = city
                },
                methods: {
                }

            })

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
             async defer></script>


    @stop