@extends('oracle.dashboard.layout.index',  ['show_footer' => false])
@section('styles')
    <style>
        .m-t-90{
            margin-top: 90px;
        }
        .btn-group>.btn:first-child:not(:last-child):not(.dropdown-toggle) {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .btn-group>.btn:not(:first-child):not(:last-child):not(.dropdown-toggle) {
            border-radius: 0 !important;
        }
        .btn-group>.btn:last-child:not(:first-child), .btn-group>.dropdown-toggle:not(:first-child) {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .icon-big {
            font-size: 3em;
            min-height: 64px;
        }
        .numbers {
            font-size: 2em;
            text-align: right;
        }

    </style>
@endsection

@section('content')

    <div class="col-sm-12" id="sales-dashboard" :class="{ 'm-t-90': !isFullscreen}" v-cloak>

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2> <img class="logo-1" src="/logos/LOGO-1-01.png" alt="LOGO" width="120px" v-if="isFullscreen">
                        Dashboard de vendas</h2>
                    <h3 class="pull-right">
                        <span v-if="is_loading">Atualizando dados</span>
                        <input type="number" v-model="updateTime" class="form-control" placeholder="Intervalo de atualização" style="display: inline-block; width: 80px;" @blur="handleTimer">
                        <button class="btn btn-default"
                                @click.prevent="reload"
                                title="ion-refresh"
                        >
                            <i class="fa-lg ion-ios-loop-strong"></i>
                        </button>
                        <button class="btn" :class="{ 'btn-default': !isFullscreen, 'btn-success': isFullscreen}"
                                @click.prevent="fullscreen"
                                :title="isFullscreen? 'Sair da tela cheia': 'Tela cheia'"
                        >
                            <i class="fa-lg" :class="{ 'ion-arrow-expand': !isFullscreen, 'ion-arrow-shrink': isFullscreen}"></i>
                        </button>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        Visualização
                        <div class="btn-group">
                            <button class="btn btn-default" :class="{'btn-primary': viewMode === 'today'}" @click.prevent="setViewMode('today')">Hoje</button>
                            <button class="btn btn-default" :class="{'btn-primary': viewMode === 'week'}" @click.prevent="setViewMode('week')">Semana</button>
                            <button class="btn btn-default" :class="{'btn-primary': viewMode === 'month'}" @click.prevent="setViewMode('month')">Mês</button>
                        </div>
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-md-3 col-sm-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big text-center"><i class="ion-ios-people"></i></div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers"><p>Profissionais</p>
                                           @{{ widgets.professionals }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse">Atividades recentes </div>
                            <div class="panel-body collapse in">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-vmiddle">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <td>Assunto</td>
                                            <td>Descrição</td>
                                            <td>Usuário</td>
                                            <th>Criado em:</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="engagement in last_engagements">
                                                <td> @{{engagement.engagement.type}}</td>
                                                <td>
                                                    <span v-if="engagement.metadata.subject">@{{engagement.metadata.subject}}</span>
                                                </td>
                                                <td>
                                                    <span v-if="engagement.metadata.body">@{{engagement.metadata.body}}</span>
                                                </td>
                                                <td>
                                                    @{{engagement.metadata.username}}
                                                </td>

                                                <td>  @{{  engagement.engagement.created_at }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var moment = window.moment

        Vue.config.debug = true;

        var updateTimer = null

        @php
            if(\App::environment('production')){
                echo 'Vue.config.devtools = false;
                  Vue.config.debug = false;
                  Vue.config.silent = true;';
            }
        @endphp

        const newProfessionalAudio = new Audio('/audio/coins.mp3');

        var vm = new Vue({
                el: '#sales-dashboard',
                name: 'sales-dashboard',
                data: () =>{
                    return {
                        isFullscreen: false,
                        is_loading: false,
                        viewMode: 'today',
                        updateTime: 60,
                        widgets:{
                            professionals: 0
                        },
                        last_engagements: []
                    }
                },

                mounted() {
                    let that = this

                    this.getDashboardData()
                    this.$eventBus.$on('new_professional', this.newProfessional)

                },

                beforeDestroy(){
                    this.$eventBus.$off('new_professional')

                    clearInterval(updateTimer);
                },

                methods: {
                    getDashboardData(){
                        let that = this

                        that.is_loading = true

                        this.$http.get('/api/oracle/sales/dashboard/data',
                        ).then(response => {
                            that.last_engagements = response.body.last_engagements;
                            that.widgets.professionals = response.body.professionals_count
                            that.is_loading = false
                        }, error => {
                            // error callback
                            console.log(error)
                            that.is_loading = false
                        });
                    },


                    reload(){
                        // reload data
                        this.getDashboardData()
                    },

                    handleTimer(){
                        let that = this;

                        clearInterval(updateTimer);
                        console.log('update timer')
                        updateTimer = setInterval(function(){ that.getDashboardData() }, that.updateTime * 1000);

                    },

                    fullscreen(){
                        screenfull.toggle();
                        this.isFullscreen = !screenfull.isFullscreen
                        this.$eventBus.$emit('fullscreen',  this.isFullscreen)
                    },

                    setViewMode(selected_mode){

                        this.viewMode = selected_mode

                    },

                    newProfessional(){

                        this.widgets.professionals++

                        this.notification('', 'Novo profissional adicionado')

                        newProfessionalAudio.play()

                    },

                    notification(title, message, position){
                        iziToast.show({
                            position:  position? position : 'topRight',
                            title: title,
                            message: message,
                            color: '#488FEE',
                            titleColor: '#fff',
                            messageColor: '#fff',
                            iconColor: '#fff',
                            progressBarColor: '#fff',
                        });
                    }
                }
            })

    </script>
@endsection
