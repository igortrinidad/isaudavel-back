@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="modality-create">

        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>Adicionar modalidade</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.modalities.list') }}"> <i
                                    class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>

                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <form class="m-b-25" action="{{route('oracle.dashboard.modalities.store')}}" method="post" role="form">

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome" required>
                    </div>

                    <div class="row m-t-20 m-b-20">
                        <div class="col-sm-12" v-if="interactions.addSubModality">
                            <legend>Nova sub modalidade</legend>

                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="name" placeholder="Nome" v-model="newSubModality.name" required @keyup.enter="saveSubModality">
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button class="btn btn-default" @click.prevent="cancelAddSubmodality" v-if="interactions.addSubModality">Cancelar</button>
                                    <button class="btn btn-primary" @click.prevent="saveSubModality"
                                            v-if="interactions.addSubModality && !interactions.editSubModality"
                                            :disabled="newSubModality.name == ''">Adicionar
                                    </button>
                                    <button class="btn btn-primary" @click.prevent="updateSubModality" v-if="interactions.editSubModality" :disabled="newSubModality.name == ''">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <fieldset class="m-t-20">

                        <legend>Sub modalidades</legend>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-success" @click.prevent="addSubmodality" v-if="!interactions.addSubModality">Nova sub modalidade</button>
                            </div>
                        </div>

                        <p class="text-danger" v-if="!modality.submodalities.length">Nenhuma sub modalidade adicionada</p>

                        <table class="table table-striped table-hover table-vmiddle" v-if="modality.submodalities.length">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th width="10%" class="text-center">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(sub_modality, index) in modality.submodalities">
                                <td>@{{ sub_modality.name }}</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm" @click.prevent="handleEditSubModality(sub_modality, index)"><i class="ion-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" @click.prevent="removeSubModality(index)"><i class="ion-close"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="submodalities" name="submodalities" v-model="submodalities_parsed">

                    <button type="submit" class="btn btn-success btn-lg btn-block m-t-20">Salvar</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        //prevent form submit on enter
        document.getElementById('modality-create').onkeypress = function (e) {

            var key = e.charCode || e.keyCode || 0;
            if (key == 13) {
                console.log('enter')
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
                el: '#modality-create',
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
                        name: ''
                    },
                    subModalityIndex: null,
                    submodalities_parsed: null
                },
                mounted: function () {

                },
                methods: {
                    addSubmodality: function(){
                        this.interactions.addSubModality = true
                    },

                    cancelAddSubmodality: function(){
                        this.newSubModality.name = ''
                        this.subModalityIndex = null
                        this.interactions.addSubModality = false
                        this.interactions.editSubModality = false
                    },

                    saveSubModality: function(){
                        let that = this


                        that.modality.submodalities.push(_.cloneDeep(this.newSubModality))

                        that.submodalities_parsed = JSON.stringify(that.modality.submodalities)

                        that.cancelAddSubmodality()
                    },

                    handleEditSubModality: function(sub_modality, index){
                        let that = this

                        that.newSubModality = _.cloneDeep(sub_modality)
                        that.subModalityIndex = index
                        that.interactions.addSubModality = true
                        that.interactions.editSubModality = true
                    },

                    updateSubModality: function(){
                        let that = this

                        that.modality.submodalities.splice(that.subModalityIndex, 1)
                        that.modality.submodalities.splice(that.subModalityIndex, 0, _.cloneDeep(that.newSubModality))
                        that.cancelAddSubmodality()

                    },

                    removeSubModality: function(index){
                        let that = this

                        that.modality.submodalities.splice(index, 1)
                    }
                }

            })

    </script>
@endsection
