@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="oracle-edit">

        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>


        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>{{$oracle->full_name}}</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.oracles.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <form class="m-b-25" action="{{route('oracle.dashboard.oracles.update')}}" method="post" role="form">

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome" value="{{$oracle->name}}" required>
                    </div>

                    <div class="form-group">
                        <label>Sobrenome</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Sobrenome" value="{{$oracle->last_name}}" required>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="text" class="form-control" name="email" placeholder="E-mail" value="{{$oracle->email}}" required>
                    </div>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}

                    <input type="hidden" id="id" name="id" value="{{ $oracle->id  }}">

                    <button class="btn btn-info btn-block m-t-20" title="Enviar nova senha" @click.prevent="sendNewPass(oracle)">Gerar nova senha</button>

                    <button type="submit" class="btn btn-success btn-lg btn-block m-t-20">Salvar alterações</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        //prevent form submit on enter
        document.getElementById('oracle-edit').onkeypress = function(e) {
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
                el: '#oracle-edit',
                data: {
                    is_loading: false,
                    oracle: {}
                },
                mounted: function() {
                    this.oracle = oracle
                },
                methods: {

                    sendNewPass: function(oracle){
                        let that = this

                        swal({
                            title: 'Enviar nova senha',
                            text: `Enviar uma nova senha para ${oracle.full_name}?`,
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

                            that.$http.get(`/api/tools/users/generateNewPass/oracle/${oracle.email}`).then(response => {

                                that.is_loading = false
                            swal('', `Nova senha enviada com sucesso para ${oracle.email}.`, 'success')

                        }, response => {
                                that.is_loading = false
                                swal('', `Não foi possivel enviar a nova senha para ${oracle.email}.`, 'error')
                            });

                        }, function (dismiss) {


                        })
                    }
                }

            })

    </script>
@endsection
