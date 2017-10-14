@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container m-b-30" id="modalities-list">

        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3><strong>Modalidades</strong></h3>

                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <a href="{{route('oracle.dashboard.modalities.create')}}" class="btn btn-primary">Adicionar nova modalidade</a>
                        </div>
                    </div>
                </div>

                <div class="m-t-20">
                    <form class="m-b-25" action="{{route('oracle.dashboard.modalities.list')}}" method="get" role="form">
                        <label>Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Digite o que procura" value="{{request()->query('search')}}"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary" id="submit">Buscar</button>
                        </span>
                        </div>
                    </form>
                </div>


                @unless($modalities->count())
                    <div class="alert alert-info">Nenhuma modalidade cadastrada.</div>
                @else

                    <div class="table-responsive m-t-20">

                        <table class="table table-striped table-hover table-vmiddle">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th class="text-center">Sub modalidades</th>
                                <th class="text-center">Eventos</th>
                                <th>Criado em:</th>
                                <th width="10%">Editar</th>
                                <th width="10%">Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($modalities as $modality)
                                <tr>
                                    <td>{{$modality->name}}</td>
                                    <td class="text-center">{{$modality->submodalities_count}}</td>
                                    <td class="text-center">{{$modality->events_count}}</td>
                                    <td>{{$modality->created_at->format('d/m/Y H:i:s')}}</td>
                                    <td><a href="{{route('oracle.dashboard.modalities.edit', $modality->id)}}"
                                           class="btn btn-sm btn-info">Editar</a></td>
                                    <td><a class="btn btn-sm btn-danger"
                                           @click.prevent="removeModality('{{$modality->id}}')">Excluir</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $modalities->links() }}
                        </div>
                    </div>
                @endunless
            </div>
        </div>

    </div>
@endsection

@section('scripts')

    <script>
        //prevent form submit on enter
        document.getElementById('modalities-list').onkeypress = function (e) {
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
                el: '#modalities-list',
                data: {
                    is_loading: false,
                    modalities: [],
                    modality_id: null,
                },
                mounted: function () {

                    this.modalities = modalities
                },
                methods: {
                    removeModality: function(modality_id){
                        let that = this

                        swal({
                            title: 'Remover modalidade',
                            text: "Tem certeza que deseja remover esta modalidade?",
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

                            that.$http.post('/oracle/dashboard/modalidades/remover', {modality_id: modality_id,}).then(response => {

                                that.is_loading = false

                            swal({
                                title: '',
                                text: 'Modalidade removida com sucesso.',
                                type: "success",
                                confirmButtonText: "OK"
                            }).then(function() {
                                location.reload(true)
                            });

                        }, response => {
                                that.is_loading = false
                                swal('', 'Não foi possivel remover a modalidade', 'error')
                            });



                        }, function (dismiss) {



                        })
                    }
                }

            })

    </script>
@endsection

