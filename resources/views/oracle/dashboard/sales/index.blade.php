@extends('oracle.dashboard.layout.index',  ['show_footer' => false, 'show_header' => false])
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
    .header-control, .footer{
        z-index: 9;
        background: #FFFFFF;
    }

    .header-control{
        top:0px;
        height: 55px;
        vertical-align: middle;
    }
    .footer{
        position: fixed;
        display: block;
        width: 100%;
        bottom:0px;
    }

    .fp-tableCell{
        vertical-align: inherit;
    }

    .widget-panel{
        background: #F7F7F7;
    }

    .fp-controlArrow.fp-next{
        border-color: transparent transparent transparent #cccccc!important;
        border-width: 28.5px 0 28.5px 24px;
    }

    .fp-controlArrow.fp-prev{
        border-color: transparent #cccccc transparent transparent!important;
        border-width: 28.5px 24px 28.5px 0;
    }
    .section {
        padding: 65px 0;
    }
    </style>
@endsection

@section('content')

    <div class="row" id="sales-dashboard" v-cloak>

        <div class="col-sm-12 header-control" >
            <div class="page-title m-b-10 m-r-10">
                <h2 class="m-l-10 m-t-10"> <img class="logo-1" src="/logos/LOGO-1-01.png" alt="LOGO" width="120px">
                    Vendas e atendimento</h2>
                <h3 class="pull-right m-t-10">
                    <span v-if="is_loading">Atualizando dados</span>
                    <input type="number" v-model="transitionTime" class="form-control" title="Intervalo de transição" style="display: inline-block; width: 80px;" @blur="handleTransitionTimer">
                    <input type="number" v-model="updateTime" class="form-control" title="Intervalo de atualização" style="display: inline-block; width: 80px;" @blur="handleUpdateTimer">
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
        </div>
        <div id="fullpage">
            <div class="section fp-noscroll" id="section1">
                <div class="slide" id="slide1">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <h3 class="text-info "><strong>Estatísticas</strong></h3>
                            <hr>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-ios-people"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Profissionais cadastrados @{{handlePeriod}}</p>
                                                <span v-if="!is_loading">@{{ widgets.professionals }}</span>
                                                <span v-if="is_loading">Carrregando</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-flash"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Tarefas realizadas @{{handlePeriod}}</p>
                                                <span v-if="!is_loading">@{{ widgets.tasks }}</span>
                                                <span v-if="is_loading">Carrregando</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-ios-email"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>E-mails enviados @{{handlePeriod}}</p>
                                                <span v-if="!is_loading"> @{{ widgets.emails }}</span>
                                                <span v-if="is_loading">Carrregando</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-ios-telephone"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Ligações realizadas @{{handlePeriod}}</p>
                                                <span v-if="!is_loading"> @{{ widgets.calls }}</span>
                                                <span v-if="is_loading">Carrregando</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-document"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Notas criadas @{{handlePeriod}}</p>
                                                <span v-if="!is_loading"> @{{ widgets.notes }}</span>
                                                <span v-if="is_loading">Carrregando</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-person-add"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Contatos criados @{{handlePeriod}}</p>
                                               <span v-if="!is_loading">@{{ widgets.contacts_created }}</span>
                                               <span v-if="is_loading">Carrregando</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slide" id="slide2">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <h3 class="text-info "><strong>Atividades de equipe</strong></h3>
                            <hr>
                        </div>
                       <div class="col-sm-12">
                           <div class="table-responsive">
                               <table class="table table-striped table-hover table-vmiddle">
                                   <thead>
                                   <tr>
                                       <th>#</th>
                                       <th>Tipo</th>
                                       <td>Assunto</td>
                                       <td>Descrição</td>
                                       <td>Usuário</td>
                                       <th>Criado em:</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr v-for="(engagement, engagementIndex) in last_engagements">
                                       <td>@{{ engagementIndex + 1 }}</td>
                                       <td>
                                           <span v-if="engagement.engagement.type == 'NOTE'" ><i class="ion-document fa-lg"></i></span>
                                           <span v-if="engagement.engagement.type == 'TASK'"><i class="ion-flash fa-lg"></i></span>
                                           <span v-if="engagement.engagement.type == 'EMAIL'"><i class="ion-ios-email fa-lg"></i></span>
                                           <span v-if="engagement.engagement.type == 'CALL'"><i class="ion-ios-telephone fa-lg"></i></span>
                                       </td>
                                       <td>
                                           <span v-if="engagement.metadata.subject">@{{engagement.metadata.subject}}</span>
                                           <span v-if="!engagement.metadata.subject">---</span>
                                       </td>
                                       <td>
                                           <span v-if="engagement.metadata.body">@{{engagement.metadata.body}}</span>
                                           <span v-if="!engagement.metadata.body">---</span>
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

        <div class="footer">
            <div class="row">

                <div class="col-sm-6 text-left">
                    <a href="/oracle/dashboard/empresas" class="btn btn-default m-b-10 m-l-30">Voltar para dashboard</a>
                </div>
                <div class="col-sm-6 text-right">
                    Visualização
                    <div class="btn-group m-b-10">
                        <button class="btn btn-default" :class="{'btn-primary': viewMode === 'today'}" @click.prevent="setViewMode('today')">Hoje</button>
                        <button class="btn btn-default" :class="{'btn-primary': viewMode === 'week'}" @click.prevent="setViewMode('week')">Semana</button>
                        <button class="btn btn-default" :class="{'btn-primary': viewMode === 'month'}" @click.prevent="setViewMode('month')">Mês</button>
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
        var transitionTimer = null

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
                        transitionTime: 15,
                        widgets:{
                            professionals: 0,
                            tasks: 0,
                            emails: 0,
                            calls: 0,
                            notes: 0,
                            contacts_created: 0,
                        },
                        last_engagements: []
                    }
                },
                computed:{
                    handlePeriod(){
                        if(this.viewMode == 'today'){
                            return 'hoje'
                        }
                        if(this.viewMode == 'week'){
                            return 'esta semana'
                        }
                        if(this.viewMode == 'month'){
                            return 'este mês'
                        }
                    }
                },
                mounted() {
                    let that = this

                    this.initSliders()
                    this.getDashboardData()
                    this.$eventBus.$on('new_professional', this.newProfessional)

                },

                beforeDestroy(){
                    this.$eventBus.$off('new_professional')

                    clearInterval(updateTimer);
                    clearInterval(transitionTimer);
                },

                methods: {
                    getDashboardData(){
                        let that = this

                        that.is_loading = true

                        this.$http.post('/api/oracle/sales/dashboard/data', {period: that.viewMode},
                        ).then(response => {
                            that.last_engagements = response.body.last_engagements;
                            that.widgets = response.body.widgets_data
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

                    handleUpdateTimer(){
                        let that = this;

                        clearInterval(updateTimer);
                        updateTimer = setInterval(function(){ that.getDashboardData() }, that.updateTime * 1000);
                    },

                    handleTransitionTimer(){
                        let that = this;
                        clearInterval(transitionTimer);

                        transitionTimer = setInterval(function () {
                            $.fn.fullpage.moveSlideLeft();
                        }, that.transitionTime * 1000);
                    },

                    fullscreen(){
                        screenfull.toggle();
                        this.isFullscreen = !screenfull.isFullscreen
                        this.$eventBus.$emit('fullscreen',  this.isFullscreen)
                    },

                    setViewMode(selected_mode){

                        this.viewMode = selected_mode

                        this.getDashboardData()

                    },

                    newProfessional(){

                        this.widgets.professionals++

                        this.notification('', 'Novo profissional adicionado')

                        newProfessionalAudio.play()

                    },

                    initSliders(){
                        let that = this
                        $('#fullpage').fullpage({
                            fitToSection: true,
                            continuousVertical: true,
                            css3: true,
                            scrollBar: false,
                            afterRender:() =>{
                                that.handleTransitionTimer()
                            }
                        });
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
