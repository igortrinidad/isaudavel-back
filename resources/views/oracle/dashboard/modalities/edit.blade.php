@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="modality-edit">

        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>{{$modality->name}}</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.modalities.list') }}"> <i
                                    class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>

                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <form class="m-b-25" action="{{route('oracle.dashboard.modalities.update')}}" method="post" role="form">

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome" value="{{$modality->name}}"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" class="form-control" name="slug" placeholder="Slug" value="{{$modality->slug}}"
                               required>
                    </div>


                    <div class="row m-t-20 m-b-20">
                        <div class="col-sm-12" v-if="interactions.addSubModality">
                            <legend>Nova sub modalidade</legend>
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="name" placeholder="Nome" v-model="newSubModality.name" required>
                            </div>

                            <div class="form-group" v-if="interactions.editSubModality">
                                <label>Slug</label>
                                <input type="text" class="form-control" name="slug" placeholder="Slug" v-model="newSubModality.slug" required>
                            </div>
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-primary" @click.prevent="cancelAddSubmodality" v-if="interactions.addSubModality">Cancelar</button>
                                <button class="btn btn-success" @click.prevent="saveSubModality"
                                        v-if="interactions.addSubModality && !interactions.editSubModality"
                                        :disabled="newSubModality.name == ''">Salvar
                                </button>
                                <button class="btn btn-success" @click.prevent="updateSubModality" v-if="interactions.editSubModality">Atualizar</button>
                            </div>
                        </div>
                    </div>


                    <fieldset class="m-t-20">

                        <legend>Sub modalidades</legend>
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-success" @click.prevent="addSubmodality" v-if="!interactions.addSubModality">Adicionar sub modalidade</button>
                        </div>

                        <p class="text-danger" v-if="!modality.submodalities.length">Nenhuma sub modalidade adicionada</p>

                        <table class="table table-striped table-hover table-vmiddle"  v-if="modality.submodalities.length">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Slug</th>
                                <th width="10%" class="text-center">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="sub_modality in modality.submodalities">
                                <td>@{{ sub_modality.name }}</td>
                                <td>@{{ sub_modality.slug }}</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm" @click.prevent="handleEditSubModality(sub_modality)"><i class="ion-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" @click.prevent="removeSubModality(sub_modality.id)"><i class="ion-close"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="id" name="id" value="{{ $modality->id }}">

                    <button type="submit" class="btn btn-success btn-lg btn-block m-t-20">Salvar alterações</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        //prevent form submit on enter
        document.getElementById('modality-edit').onkeypress = function (e) {
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
                el: '#modality-edit',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                data: {
                    interactions:{
                        addSubModality: false,
                        editSubModality: false
                    },
                    is_loading: false,
                    modality:{
                        submodalities:[]
                    },
                    newSubModality:{
                        id: '',
                        modality_id: '',
                        name: '',
                        slug: ''
                    }
                },
                mounted: function () {

                    this.modality = modality
                },
                methods: {
                    addSubmodality: function(){
                        this.newSubModality.modality_id = this.modality.id
                        this.interactions.addSubModality = true
                    },

                    cancelAddSubmodality: function(){
                        this.newSubModality.id = ''
                        this.newSubModality.modality_id = ''
                        this.newSubModality.name = ''
                        this.newSubModality.slug = ''
                        this.interactions.addSubModality = false
                        this.interactions.editSubModality = false
                    },

                    saveSubModality: function(){
                        let that = this

                        that.is_loading = true

                        that.$http.post('/oracle/dashboard/submodalidades/criar', that.newSubModality)
                            .then(function (response) {

                                that.modality.submodalities.push(response.body.submodality)
                                that.is_loading = false

                                that.cancelAddSubmodality()

                            }, function (response) {
                                console.log(response)
                                that.is_loading = false
                                swal('', 'Não foi possivel adicionar a modalidade', 'error')
                            });
                    },

                    handleEditSubModality: function(sub_modality){
                        let that = this

                        that.newSubModality = _.cloneDeep(sub_modality)
                        that.interactions.addSubModality = true
                        that.interactions.editSubModality = true
                    },

                    updateSubModality: function(){
                        let that = this

                        that.is_loading = true

                        that.$http.post('/oracle/dashboard/submodalidades/atualizar', that.newSubModality)
                            .then(function (response) {

                                let sub_modality = response.body.submodality

                                let index = _.findIndex(that.modality.submodalities, {id: sub_modality.id})
                                that.modality.submodalities.splice(index, 1)
                                that.modality.submodalities.splice(index, 0, sub_modality)


                                that.cancelAddSubmodality()

                                that.is_loading = false

                            }, function (response) {
                                console.log(response)
                                that.is_loading = false
                                swal('', 'Não foi possivel adicionar a modalidade', 'error')
                            });
                    },

                    removeSubModality: function(sub_modality_id){
                        let that = this

                        swal({
                            title: 'Remover sub modalidade',
                            text: "Tem certeza que deseja remover esta  sub modalidade?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Tenho certeza',
                            cancelButtonText: 'Cancelar',
                            confirmButtonClass: 'btn btn-danger m-l-10',
                            cancelButtonClass: 'btn btn-default m-r-10',
                            buttonsStyling: false,
                            reverseButtons: true
                        }).then(function () {

                            that.is_loading = true

                            that.$http.post('/oracle/dashboard/submodalidades/remover', {id: sub_modality_id})
                                .then(function (response) {

                                    that.modality.submodalities = that.modality.submodalities.filter((submodality) => submodality.id != sub_modality_id)
                                    that.is_loading = false


                                }, function (response) {
                                    console.log(response)
                                    that.is_loading = false
                                    swal('', 'Não foi possivel remover a sub modalidade', 'error')
                                });


                        }, function (dismiss) {


                        })
                    }
                }

            })

    </script>
@endsection
