
<style>

@media(screen < 768px){
    .form-group {
        margin-top: 0px;
    }
}

@media(screen > 768px){
    .form-group {
        margin-top: 10px;
    }
}

</style>

<section id="hero-area">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 text-center">
                <div class="form-group">
                    <label>Cidade</label>
                    <input class="form-control" id="pac-input" placeholder="Alterar cidade" />
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 text-center">
                <div class="form-group">
                    <label>Nome do profissional</label>
                    <input class="form-control" placeholder="Insira um nome para pesquisar" />
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 text-center">
                <div class="form-group">
                    <button class="btn btn-primary btn-block btn-buscar">Buscar</button>
                </div>
            </div>

        </div>
    </div>
</section>


    @section('scripts')
        @parent

        <script>
            
          function initAutocomplete() {

            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);


            searchBox.addListener('places_changed', function() {
              var places = searchBox.getPlaces();

              if (places.length == 0) {
                return;
              }

              console.log(places);

            });
          }

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
         async defer></script>


    @stop