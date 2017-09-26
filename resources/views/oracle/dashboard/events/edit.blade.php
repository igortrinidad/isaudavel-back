@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="event-edit">

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>{{$event->name}}</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.events.list') }}"> <i
                                    class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <form class="m-b-25" action="{{route('oracle.dashboard.events.update')}}" method="post" role="form">

                    <div class="form-group">
                        <label>Status</label><br>
                        <p class="text">
                            @{{event.is_published ? 'Publicado' : 'Aguardando'}}</p>
                        <label class="switch">
                            <input type="checkbox" v-model="event.is_published" name="is_published" id="is_published">
                            <div class="slider round"></div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Nome" value="{{$event->name}}"
                               required>
                    </div>


                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="id" name="id" value="{{ $event->id }}">

                    <button type="submit" class="btn btn-success btn-lg btn-block m-t-20">Salvar alterações</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        //prevent form submit on enter
        document.getElementById('event-edit').onkeypress = function (e) {
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
                el: '#event-edit',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                data: {
                    event:{}
                },
                mounted: function () {

                    this.event = event
                },
                methods: {}

            })

    </script>
@endsection
