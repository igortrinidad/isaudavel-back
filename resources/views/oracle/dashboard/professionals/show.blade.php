@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="client-edit">

        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>


        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>{{$professional->full_name}}</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.professionals.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <form class="m-b-25" action="{{route('oracle.dashboard.professionals.update')}}" method="post" role="form">


                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome" value="{{$professional->name}}" required>
                    </div>

                    <div class="form-group">
                        <label>Sobrenome</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Sobrenome" value="{{$professional->last_name}}" required>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="text" class="form-control" name="email" placeholder="E-mail" value="{{$professional->email}}" required>
                    </div>

                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Telefone" value="{{$professional->phone}}">
                    </div>

                    <div class="form-group">
                        <label>Especialidades</label>
                        <multiselect
                                v-model="category"
                                :options="categories"
                                :label="'name'"
                                :multiple="true"
                                placeholder="Selecione ao menos uma categoria"
                                @input="handleCategories"
                                track-by="name"
                                :select-label="'Selecionar'"
                                :selected-label="'Selecionado'"
                                :deselect-label="'Remover'"
                        >
                        </multiselect>
                    </div>


                    <button class="btn btn-primary btn-block m-t-20"  @click.prevent="interactions.showCompanies = true" v-if="!interactions.showCompanies">Exibir empresas (@{{ professional.companies.length }})</button>
                    <button class="btn btn-default btn-block m-t-20"  @click.prevent="interactions.showCompanies = false" v-if="interactions.showCompanies">Esconder empresas</button>

                    <div class="m-t-20 table-responsive">
                        <table class="table table-striped table-hover table-vmiddle" v-if="interactions.showCompanies">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Especialidades</th>
                                <th>Avaliação</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($professional->companies as $company)
                                    <tr>
                                        <td>{{ $company->name }}</td>
                                        <td>
                                            @foreach($company->categories as $category)
                                                <span class="label label-success m-5">{{ $category->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($company->current_rating > 0)
                                                <div class="wp-rating-div">
                                                    @php
                                                        $rating_to_loop = $company->current_rating
                                                    @endphp
                                                    @include('components.rating', ['size' => '16'])
                                                </div>
                                            @endif
                                        </td>
                                        <td><a class="btn btn-info btn-sm" href="{!! route('landing.companies.show', $company->slug) !!}"><i class="ion-search"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="id" name="id" value="{{ $professional->id  }}">
                    <input type="hidden" id="categories" name="categories" v-model="categories_parsed">

                    <button class="btn btn-info btn-block m-t-20" title="Enviar nova senha para o profissional" @click.prevent="sendNewPass(professional)">Gerar nova senha</button>

                    <button type="submit" class="btn btn-success btn-lg btn-block m-t-20">Salvar alterações</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        //prevent form submit on enter
        document.getElementById('client-edit').onkeypress = function(e) {
            var key = e.charCode || e.keyCode || 0;
            if (key == 13) {
                e.preventDefault();
            }
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
                el: '#client-edit',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                data: {
                    is_loading: false,
                    interactions:{
                        showCompanies: false
                    },
                    categories:[],
                    category:[],
                    categories_parsed:[],
                    professional: {
                        categories:[],
                        companies:[]
                    }
                },
                mounted: function() {
                    this.getCategories()

                    this.professional = professional
                    this.category = professional.categories
                    this.handleCategories()
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
@endsection
