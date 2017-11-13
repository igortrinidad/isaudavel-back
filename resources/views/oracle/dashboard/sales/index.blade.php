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
    .shadow {
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .4);
    }
    </style>
@endsection

@section('content')

    <div class="row" id="sales-dashboard" v-cloak>

        {{-- Header --}}
        <div class="col-sm-12 header-control" >
            <div class="page-title m-b-10 m-r-10">
                <h2 class="m-l-10 m-t-10"> <img class="logo-1" src="/logos/LOGO-1-01.png" alt="LOGO" width="120px">
                    Vendas e atendimento</h2>
                <h3 class="pull-right m-t-10">
                    <i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true" v-if="is_loading"></i>
                    <input type="number" v-model="transitionTime" class="form-control" title="Intervalo de transição" style="display: inline-block; width: 80px;" @blur="handleTransitionTimer">
                    <input type="number" v-model="updateTime" class="form-control" title="Intervalo de atualização" style="display: inline-block; width: 80px;" @blur="handleUpdateTimer">
                    <button class="btn" :class="{ 'btn-default': !enableTransition, 'btn-info': enableTransition}"
                            @click.prevent="handleTransition"
                            :title="enableTransition? 'Transição automática ativada': 'Ativar transição automática'"
                    >
                        <i class="fa-lg ion-arrow-swap"></i>
                    </button>
                    <button class="btn btn-default"
                            @click.prevent="reload"
                            title="Atualizar dados"
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
        {{-- Header --}}

       {{-- Content --}}
        <div id="fullpage">
            <div class="section fp-noscroll" id="section1">
                {{-- Statistics --}}
                <div class="slide" id="slide1">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <h3 class="text-info "><strong>Estatísticas</strong></h3>
                            <hr>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel shadow shadow">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-ios-people"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Profissionais cadastrados @{{handlePeriod}}</p>
                                                <span v-if="!is_loading">@{{ widgets.professionals }}</span>
                                                <span v-if="is_loading"><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel shadow">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-flash"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Tarefas realizadas @{{handlePeriod}}</p>
                                                <span v-if="!is_loading">@{{ widgets.tasks }}</span>
                                                <span v-if="is_loading"><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel shadow">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-ios-email"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>E-mails enviados @{{handlePeriod}}</p>
                                                <span v-if="!is_loading"> @{{ widgets.emails }}</span>
                                                <span v-if="is_loading"><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel shadow">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-ios-telephone"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Ligações realizadas @{{handlePeriod}}</p>
                                                <span v-if="!is_loading"> @{{ widgets.calls }}</span>
                                                <span v-if="is_loading"><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel shadow">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-flag"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Notas criadas @{{handlePeriod}}</p>
                                                <span v-if="!is_loading"> @{{ widgets.notes }}</span>
                                                <span v-if="is_loading"><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel shadow">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-chatboxes"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Reuniões agendadas @{{handlePeriod}}</p>
                                                <span v-if="!is_loading">@{{ widgets.meetings }}</span>
                                                <span v-if="is_loading"><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="panel widget-panel shadow">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="icon-big text-center"><i class="ion-person-add"></i></div>
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="numbers"><p>Contatos criados @{{handlePeriod}}</p>
                                                <span v-if="!is_loading">@{{ widgets.contacts_created }}</span>
                                                <span v-if="is_loading"><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Statistics --}}

                {{-- Engagements --}}
                <div class="slide" id="slide2">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <h3 class="text-info "><strong>Atividades de equipe</strong></h3>
                            <hr>
                        </div>
                        <div class="col-sm-12">
                            <h4 v-if="!last_engagements.length && !is_loading">Nenhuma atividade registrada no período.</h4>

                            <h4 v-if="is_loading"><span><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span> Carregando dados.</h4>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-vmiddle" v-if="last_engagements.length && !is_loading">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo</th>
                                        <th>Título</th>
                                        <th>Descrição</th>
                                        <th>Usuário</th>
                                        <th>Criado em:</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(engagement, engagementIndex) in last_engagements">
                                        <td>@{{ engagementIndex + 1 }}</td>
                                        <td>
                                            <span v-if="engagement.engagement.type == 'NOTE'" ><i class="ion-flag fa-lg"></i></span>
                                            <span v-if="engagement.engagement.type == 'TASK'"><i class="ion-flash fa-lg"></i></span>
                                            <span v-if="engagement.engagement.type == 'EMAIL'"><i class="ion-ios-email fa-lg"></i></span>
                                            <span v-if="engagement.engagement.type == 'CALL'"><i class="ion-ios-telephone fa-lg"></i></span>
                                            <span v-if="engagement.engagement.type == 'MEETING'"><i class="ion-chatboxes fa-lg"></i></span>
                                        </td>
                                        <td>
                                            <span v-if="engagement.metadata.subject">@{{engagement.metadata.subject}}</span>
                                            <span v-if="engagement.metadata.title">@{{engagement.metadata.title}}</span>
                                            <span v-if="!engagement.metadata.title && !engagement.metadata.subject">---</span>
                                        </td>
                                        <td>
                                            <span v-if="engagement.metadata.body">@{{engagement.metadata.body}}</span>
                                            <span v-if="engagement.metadata.text">@{{engagement.metadata.text | truncate}}</span>
                                            <span v-if="!engagement.metadata.body && !engagement.metadata.text">---</span>
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
                {{-- Engagements --}}

                {{--Classification--}}
                <div class="slide" id="slide3">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <h3 class="text-info "><strong>Classificação</strong></h3>
                            <hr>
                        </div>
                        <div class="col-sm-12">
                            <h4 v-if="is_loading"><span><i class="fa ion-load-c fa-spin fa-lg fa-fw text-primary" aria-hidden="true"></i></span> Carregando dados.</h4>
                           {{-- Classification chart --}}

                            <div class="chart-container" style="position: relative; height:40vh; width:80vw;  margin: auto;">
                                <canvas id="classification-chart"></canvas>
                            </div>
                           {{-- Classification chart --}}
                        </div>
                    </div>
                </div>
                {{--Classification--}}
            </div>
        </div>
       {{-- Content --}}

        {{-- Footer --}}
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
        {{-- Footer --}}




    </div>
@endsection

@section('scripts')
    <script>
        var moment = window.moment

        Vue.config.debug = true;

        var updateTimer = null;
        var transitionTimer = null;

        Vue.filter('truncate', (value)=>{

            return _.truncate(value, {'length': 100, 'omission': '...'});
        });

        @php
            if(\App::environment('production')){
                echo '';
                echo 'Vue.config.devtools = false;
                  Vue.config.debug = false;
                  Vue.config.silent = true;';
            }
        @endphp

        const newProfessionalAudio = new Audio('/audio/coins.mp3');
        const default_notificationAudio = new Audio('/audio/default_notification.mp3');

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
                        enableTransition: true,
                        widgets:{
                            professionals: 0,
                            tasks: 0,
                            emails: 0,
                            calls: 0,
                            notes: 0,
                            contacts_created: 0,
                        },
                        last_engagements: [],
                        charts_data: []
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
                    this.$eventBus.$on('sales_dashboard_notification', this.handleNotifications)

                },

                beforeDestroy(){
                    this.$eventBus.$off('sales_dashboard_notification')

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
                            that.charts_data = response.body.charts_data
                            that.classificationChart()
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

                        that.enableTransition = true
                    },

                    handleTransition(){
                        let that = this
                        that.enableTransition = !that.enableTransition

                        if(!that.enableTransition){
                            clearInterval(transitionTimer);
                        }

                        if(that.enableTransition){
                            transitionTimer = setInterval(function () {
                                $.fn.fullpage.moveSlideLeft();
                            }, that.transitionTime * 1000);
                        }
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

                    initSliders(){
                        let that = this
                        $('#fullpage').fullpage({
                            fitToSection: true,
                            continuousVertical: true,
                            css3: true,
                            scrollBar: false,
                            afterRender:() =>{
                                that.handleTransitionTimer()
                            },
                            normalScrollElements: '.table-responsive',
                        });
                    },

                    handleNotifications(payload){
                        let that = this

                        if(payload.type == 'new_professional'){
                            that.widgets.professionals++
                            that.notification('', 'Novo profissional adicionado')
                            newProfessionalAudio.play()
                            return true
                        }

                        if(payload.type == 'hubspot_contact_creation'){
                            that.widgets.contacts_created++
                            that.notification(payload.title, payload.content)
                            default_notificationAudio.play()
                            return true
                        }

                        if(payload.type == 'hubspot_contact_deletion'){
                            that.notification(payload.title, payload.content)
                            default_notificationAudio.play()
                            return true
                        }
                    },

                    notification(title, message, position){
                        iziToast.show({
                            position:  position ? position : 'topRight',
                            title: title,
                            message: message,
                            color: '#488FEE',
                            titleColor: '#fff',
                            messageColor: '#fff',
                            iconColor: '#fff',
                            progressBarColor: '#fff',
                        });
                    },

                    classificationChart(){

                        let that = this;
                        var barOptions_stacked = {
                            tooltips: {
                                enabled: true
                            },
                            hover :{
                                animationDuration:0
                            },
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        beginAtZero:true,
                                    },
                                    scaleLabel:{
                                        display:false
                                    },
                                    gridLines: {
                                    },
                                    stacked: true
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display:false,
                                        color: "#fff",
                                        zeroLineColor: "#fff",
                                        zeroLineWidth: 0
                                    },
                                    stacked: true
                                }]
                            },
                            legend:{
                                display:true
                            },
                        };

                        var ctx = document.getElementById("classification-chart");
                        var myChart = new Chart(ctx, {
                            type: 'horizontalBar',
                            data: {
                                labels: that.handleClassificationChartData().data_labels,

                                datasets: that.handleClassificationChartData().chart_data
                            },

                            options: barOptions_stacked,
                        });
                    },

                    handleClassificationChartData(){
                        let that = this

                        let data_labels = []
                        let chart_data = []

                        that.charts_data.map((data) => {
                            Object.keys(data).map(function(key, index) {
                                if(key == 'label'){
                                    data_labels.push(data[key])
                                }

                                if(key == 'TASK'){
                                    let keyIndex = _.findIndex(chart_data, {label:'Tarefas'})

                                    if(keyIndex < 0){
                                        chart_data.push({label: 'Tarefas', data:[data[key]], fill: false, backgroundColor: "rgba(255, 99, 132, 0.2)", borderColor: "rgb(255, 99, 132)", borderWidth: 1})
                                    }else{
                                        chart_data[keyIndex].data.push(data[key])
                                    }
                                }

                                if(key == 'CALL'){
                                    let keyIndex = _.findIndex(chart_data, {label:'Ligações'})

                                    if(keyIndex < 0){
                                        chart_data.push({label: 'Ligações', data:[data[key]], fill: false, backgroundColor: "rgba(153, 102, 255, 0.2)", borderColor: "rgb(153, 102, 255)", borderWidth: 1})
                                    }else{
                                        chart_data[keyIndex].data.push(data[key])
                                    }
                                }

                                if(key == 'EMAIL'){
                                    let keyIndex = _.findIndex(chart_data, {label:'E-mails'})

                                    if(keyIndex < 0){
                                        chart_data.push({label: 'E-mails', data:[data[key]], fill: false, backgroundColor: "rgba(255, 159, 64, 0.2)", borderColor: "rgb(255, 159, 64)", borderWidth: 1})
                                    }else{
                                        chart_data[keyIndex].data.push(data[key])
                                    }
                                }

                                if(key == 'MEETING'){
                                    let keyIndex = _.findIndex(chart_data, {label:'Reuniões'})

                                    if(keyIndex < 0){
                                        chart_data.push({label: 'Reuniões', data:[data[key]], fill: false, backgroundColor: "rgba(75, 192, 192, 0.2)", borderColor: "rgb(75, 192, 192)", borderWidth: 1})
                                    }else{
                                        chart_data[keyIndex].data.push(data[key])
                                    }
                                }

                                if(key == 'NOTE'){
                                    let keyIndex = _.findIndex(chart_data, {label:'Notas'})

                                    if(keyIndex < 0){
                                        chart_data.push({label: 'Notas', data:[data[key]], fill: false, backgroundColor: "rgba(201, 203, 207, 0.2)", borderColor: "rgb(201, 203, 207)", borderWidth: 1})
                                    }else{
                                        chart_data[keyIndex].data.push(data[key])
                                    }
                                }
                            });
                        })

                        return {chart_data, data_labels }

                    }
                }

            })

    </script>
@endsection
