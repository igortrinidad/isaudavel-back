@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container">
        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>{{$company->name}}</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ url()->previous() }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <form class="m-b-25" action="{{route('oracle.dashboard.companies.update')}}" method="post" role="form" id="company-edit-form">

                    <div class="form-group">
                        <label>Status</label><br>
                        <p class="text">
                            @{{company.address_is_available ? 'Ativa' : 'Inativa'}}</p>
                        <label class="switch">
                            <input type="checkbox" v-model="company.is_active" name="is_active" id="is_active">
                            <div class="slider round"></div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome" value="{{$company->name}}" required>
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" class="form-control" name="website" placeholder="Website" value="{{$company->website}}">
                    </div>
                    <div class="form-group">
                        <label>Url</label>
                        <input type="text" class="form-control" name="slug" placeholder="Url" value="{{$company->slug}}" required>
                    </div>
                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea type="text" class="form-control" name="description" placeholder="Descrição" required>{{$company->description}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Telefone com DDD" value="{{$company->phone}}" required>
                    </div>


                    <div class="form-group">
                        <label>Atende os clientes em casa ou outros locais?</label><br>
                        <p class="text">
                            @{{company.is_delivery ? 'Sim' : 'Não'}}</p>
                        <label class="switch">
                            <input type="checkbox" v-model="company.is_delivery" name="is_delivery" id="is_delivery">
                            <div class="slider round"></div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Possui endereço fixo de atendimento?</label><br>
                        <p class="text">
                            @{{company.address_is_available ? 'Sim' : 'Não'}}</p>
                        <label class="switch">
                            <input type="checkbox" v-model="company.address_is_available" name="address_is_available" id="address_is_available">
                            <div class="slider round"></div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Endereço</label>
                        <input class="form-control" id="autocomplete" name="address" placeholder="Informe o endereço da empresa" value="{{$company->address['full_address']}}" />
                    </div>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="id" name="id" value="{{ $company->id  }}">
                    <input type="hidden" id="categories" name="categories" v-model="categories_parsed">
                    <input type="hidden" id="city" name="city" value="{{ $company->city  }}">
                    <input type="hidden" id="state" name="state" value="{{ $company->state  }}">
                    <input type="hidden" id="lat" name="lat" value="{{ $company->lat }}">
                    <input type="hidden" id="lng" name="lng" value="{{ $company->lng }}">
                    <input type="hidden" id="address" name="address" v-model="address">

                    <button type="submit" class="btn btn-primary btn-block">Salvar</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        function initAutocomplete() {

            //prevent form submit on enter
            document.getElementById('company-edit-form').onkeypress = function(e) {
                var key = e.charCode || e.keyCode || 0;
                if (key == 13) {
                    e.preventDefault();
                }
            }

            var autocomplete = new google.maps.places.Autocomplete(
                (
                    document.getElementById('autocomplete')), {
                    language: 'pt-BR',
                    componentRestrictions: {'country': 'br'}
                });


            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (place.geometry) {

                    var city = document.getElementById('city');
                    var state = document.getElementById('state');

                    place.address_components.map((current) =>{
                        current.types.map((type) => {
                        if(type == 'administrative_area_level_1'){
                        state.setAttribute('value', current.short_name)
                    }

                    if(type == 'administrative_area_level_2'){
                        city.setAttribute('value', current.short_name)
                    }
                })
                })

                    var lat = document.getElementById('lat');
                    lat.setAttribute('value', place.geometry.location.lat())

                    var lng = document.getElementById('lng');
                    lng.setAttribute('value', place.geometry.location.lng())


                    var address = document.getElementById('address');

                    var address_data = {
                        name:  company.name,
                        url:  place.url,
                        full_address: place.formatted_address,
                    }

                    address.setAttribute('value', JSON.stringify(address_data))

                } else {
                    document.getElementById('autocomplete').placeholder = 'Enter a city';
                }

            });
        }

        Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

        Vue.config.debug = true;
        var vm = new Vue({
            el: '#company-edit-form',
            components: {
                Multiselect: window.VueMultiselect.default
            },
            filters: {
                'formatCurrency': function(value){
                    return accounting.formatMoney(parseFloat(value))
                }
            },
            data: {
                city: '',
                category: null,
                categories: [],
                categories_parsed: [],
                address:{},
                company : {}
            },
            mounted: function() {

                this.getCategories();

                this.company = company
                this.category = company.categories
                this.handleCategories()
                this.address = JSON.stringify(company.address)
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

                handleCategories: function(){

                    this.categories_parsed = []
                    var categories_parsed = []

                    this.category.map((category) => {
                        categories_parsed.push(category.id)
                })

                    this.categories_parsed = JSON.stringify(categories_parsed);

                },
            }

        })

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection
