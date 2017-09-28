@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container m-b-30" id="recipes-list">
        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3><strong>Receitas</strong></h3>


                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="m-t-20">
                    <form class="m-b-25" action="{{route('oracle.dashboard.recipes.list')}}" method="get" role="form"
                          id="company-edit-form">
                        <label>Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="Digite o que procura" value="{{request()->query('search')}}"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success" id="submit">Buscar</button>
                        </span>
                        </div>
                    </form>
                </div>
                @unless($recipes->count())
                    <div class="alert alert-info">Nenhuma receita cadastrada.</div>
                @else
                    <div class="table-responsive m-t-20">
                        <table class="table table-striped table-hover table-vmiddle">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Cadastrado por</th>
                                <th class="text-center">Rating</th>
                                <th class="text-center">Comentários</th>
                                <th>Status</th>
                                <th>Visualizar</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recipes as $recipe)
                                <tr>
                                    <td>{{$recipe->title}}</td>
                                    <td>{{$recipe->type->name}}</td>
                                    <td>{{$recipe->from->full_name}}</td>
                                    <td class="text-center">{{$recipe->current_rating}}</td>
                                    <td class="text-center">{{$recipe->total_comments}}</td>
                                    <td>
                                        @if($recipe->is_published)
                                            <span class="label label-success">Publicado</span>
                                        @else
                                            <span class="label label-default">Aguardando</span>
                                        @endif
                                    </td>
                                    <td><a href="{{route('landing.recipes.show', $recipe->slug)}}" target="_blank"
                                           class="btn btn-sm btn-primary">Visualizar</a></td>
                                    <td><a href="{{route('oracle.dashboard.recipes.edit', $recipe->id)}}"
                                           class="btn btn-sm btn-info">Editar</a></td>
                                    <td><a class="btn btn-sm btn-danger" @click.prevent="handleRemoveRecipe('{{$recipe->id}}')">Excluir</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $recipes->links() }}
                        </div>
                    </div>
                @endunless
            </div>
        </div>


        {{--modal--}}
        <div class="modal" id="destroy-reason" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Motivo da remoção</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Motivo</label>
                            <textarea class="form-control" name="reason" id="reason" rows="3"
                                      v-model="remove_reason"
                                      placeholder="Informe qual o motivo da remoção para o criador da receita."></textarea>

                            {{csrf_field()}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-danger" :disabled="remove_reason == ''" @click.prevent="destroyEvent">Remover</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        //prevent form submit on enter
        document.getElementById('recipes-list').onkeypress = function (e) {
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
                el: '#recipes-list',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                data: {
                    is_loading: false,
                    meal_recipe_id: null,
                    remove_reason: ''
                },
                mounted: function () {
                },
                methods: {

                    handleRemoveRecipe: function(meal_recipe_id){

                        $('#destroy-reason').modal('show');
                        this.meal_recipe_id = meal_recipe_id
                    },

                    destroyEvent: function(){
                        let that = this
                        $('#destroy-reason').modal('hide');

                        swal({
                            title: 'Remover receita',
                            text: "Tem certeza que deseja remover esta receita?",
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

                            that.$http.post('/oracle/dashboard/receitas/remover', {meal_recipe_id: that.meal_recipe_id, remove_reason: that.remove_reason}).then(response => {

                                that.is_loading = false

                            swal({
                                title: '',
                                text: 'Receita removida com sucesso.',
                                type: "success",
                                confirmButtonText: "OK"
                            }).then(function() {
                                location.reload(true)
                            });

                        }, response => {
                                that.is_loading = false
                                swal('', 'Não foi possivel remover a receita', 'error')
                            });



                        }, function (dismiss) {



                        })
                    }
                }

            })

    </script>
@endsection

