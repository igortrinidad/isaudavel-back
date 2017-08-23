
<style>

@media screen and (max-width: 992px) {
    .btn-buscar {
        margin-top: -20px;
    }
}

@media screen and (min-width: 992px) {
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
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
                
                <div class="form-group">
                    <input class="form-control" id="pac-input" placeholder="Informe a cidade" />
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

        </div>
    </div>
</header>


    @section('scripts')
        @parent

        <script>
            
          function initAutocomplete() {

            var input = document.getElementById('pac-input');

            var options = {
              types: ['(cities)'],
              language: 'pt-BR',
              componentRestrictions: {country: "br"}
             };

            var searchBox = new google.maps.places.SearchBox(input, options);


            searchBox.addListener('places_changed', function() {
              var places = searchBox.getPlaces();

              if (places.length == 0) {
                return;
              }

              var city = document.getElementById('city');

              city.setAttribute('value', places[0].name)

            });
          }

            Vue.config.debug = true;
            var vm = new Vue({
                el: '#search-area',
                data: {
                },
                mounted: function() {

                    console.log('Vue rodando no index');
                },
                methods: {
                }

            })

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
             async defer></script>


    @stop