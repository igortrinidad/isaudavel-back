@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="company-edit-form">

        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>


        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>{{$company->name}}</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.companies.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <form class="m-b-25" action="{{route('oracle.dashboard.companies.update')}}" method="post" role="form">

                    <div class="form-group">
                        <label>Status</label><br>
                        <p class="text">
                            @{{company.is_active ? 'Ativa' : 'Inativa'}}</p>
                        <label class="switch">
                            <input type="checkbox" v-model="company.is_active" name="is_active" id="is_active">
                            <div class="slider round"></div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Plano</label><br>
                        <p class="text">
                            @{{company.is_paid ? 'Plus' : 'Free'}}</p>
                        <label class="switch">
                            <input type="checkbox" v-model="company.is_paid" name="is_paid" id="is_paid">
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

                    <fieldset class="m-t-20 m-b-20">
                        <legend>Profissionais</legend>

                       <div class="table-responsive">
                           <table class="table table-striped table-hover table-vmiddle">
                               <thead>
                               <tr>
                                   <th>Nome</th>
                                   <th>Especialidades</th>
                                   <th>Admin?</th>
                                   <th>Ações</th>
                               </tr>
                               </thead>
                               <tbody>
                                   <tr v-for="professional in company.professionals" :class="{'danger': removed_professionals.indexOf(professional.id) > -1}">
                                       <td>@{{ professional.full_name }}</td>
                                       <td>
                                           <span class="label label-success" v-for="category in professional.categories">@{{ category.name }}</span>
                                       </td>
                                       <td>
                                           <span class="label label-primary" v-if="professional.pivot.is_admin">Sim</span>
                                           <span class="label label-default" v-if="!professional.pivot.is_admin">Não</span>
                                       </td>
                                       <td>
                                           <a class="btn btn-info btn-sm" :href="`/profissionais/${professional.id}`" title="Visualizar profissional"><i class="ion-search"></i></a>
                                           <button class="btn btn-success btn-sm" title="Enviar nova senha para o profissional" @click.prevent="sendNewPass(professional)"><i class="ion-unlocked"></i></button>
                                           <button class="btn btn-danger btn-sm" title="Remover profissional" @click.prevent="handleRemoveProfessional(professional)" :disabled="company.owner_id == professional.id || removed_professionals.indexOf(professional.id) > -1"><i class="ion-trash-b"></i></button>
                                       </td>
                                   </tr>
                               </tbody>
                           </table>
                       </div>
                    </fieldset>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="id" name="id" value="{{ $company->id  }}">
                    <input type="hidden" id="categories" name="categories" v-model="categories_parsed">
                    <input type="hidden" id="city" name="city" value="{{ $company->city  }}">
                    <input type="hidden" id="state" name="state" value="{{ $company->state  }}">
                    <input type="hidden" id="lat" name="lat" value="{{ $company->lat }}">
                    <input type="hidden" id="lng" name="lng" value="{{ $company->lng }}">
                    <input type="hidden" id="address" name="address" v-model="address">
                    <input type="hidden" id="has_professionals_to_remove" name="has_professionals_to_remove" v-model="has_professionals_to_remove">
                    <input type="hidden" id="professionals_to_remove" name="professionals_to_remove" v-model="professionals_to_remove">

                    <button type="submit" class="btn btn-primary btn-block">Salvar alterações</button>
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

        @php
            if(\App::environment('production')){
                echo 'Vue.config.devtools = false;
                  Vue.config.debug = false;
                  Vue.config.silent = true;';
            }
        @endphp
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
                is_loading: false,
                city: '',
                category: null,
                categories: [],
                categories_parsed: [],
                address:{},
                company : {},
                has_professionals_to_remove: false,
                professionals_to_remove: [],
                removed_professionals: []
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

                handleRemoveProfessional: function(professional){
                    let that = this
                    swal({
                        title: 'Remover profissional',
                        text: "Tem certeza que deseja remover este profissional da empresa?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Tenho certeza',
                        cancelButtonText: 'Cancelar',
                        confirmButtonClass: 'btn btn-success m-l-10',
                        cancelButtonClass: 'btn btn-danger m-r-10',
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then(function () {

                        that.removed_professionals.push(professional.id)
                        that.professionals_to_remove = JSON.stringify(that.removed_professionals)
                        that.has_professionals_to_remove = true

                    }, function (dismiss) {

                        if( !that.professionals_to_remove.length){
                            that.has_professionals_to_remove = false
                        }

                    })
                },

                sendNewPass: function(professional){
                    let that = this

                    swal({
                        title: 'Enviar nova senha',
                        text: `Enviar uma nova senha para ${professional.full_name}?`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Enviar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonClass: 'btn btn-success m-l-5',
                        cancelButtonClass: 'btn btn-danger m-r-5',
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then(function () {
                        that.is_loading = true

                        that.$http.get(`/api/tools/users/generateNewPass/professional/${professional.email}`).then(response => {

                            that.is_loading = false
                            swal('', `Nova senha enviada com sucesso para ${professional.email}.`, 'success')

                    }, response => {
                            that.is_loading = false
                            swal('', `Não foi possivel enviar a nova senha para ${professional.email}.`, 'error')
                        });

                    }, function (dismiss) {


                    })
                }
            }

        })

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc7FRXAfTUbAG_lUOjKzzFa41JbRCCbbM&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection
