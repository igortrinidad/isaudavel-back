@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="notifications">

        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>Notificações</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.companies.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>

                @unless($notifications->count())
                    <div class="alert alert-info">
                        Nenhum notificação.
                    </div>
                @else

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right m-b-10">
                                <button class="btn btn-success pull-right" :disabled="!hasUnreadNotifications"
                                        @click.prevent="markAllNotificationAsReaded">Marcar tudo como lido
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive m-t-20">
                        <table class="table table-striped table-hover table-vmiddle" v-cloak>
                            <thead>
                            <tr>
                                <th>Título</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Ações:</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(notification, index) in notifications">
                                <td>@{{notification.title}}</td>
                                <td>@{{formatDate(notification.created_at)}}</td>
                                <td>
                                    <span class="label label-default" v-if="!notification.is_readed">Não lido</span>
                                    <span class="label label-info" v-if="notification.is_readed">Lido</span>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm"
                                       @click.prevent="showNofication(notification.id)"><i class="ion-search fa-lg"></i></a>
                                    <a class="btn btn-success btn-sm"
                                       @click.prevent="markAsReaded(notification.id, index)"
                                       v-if="!notification.is_readed"><i class="ion-checkmark fa-lg"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                @endunless

            </div>
        </div>

        {{--modal--}}
        <div class="modal" id="show-notification" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">@{{ selectedNotification.title ? selectedNotification.title : 'Notificação' }}</h4>
                    </div>
                    <div class="modal-body text-center">
                        <p>@{{ selectedNotification.content }}</p>

                        <div class="m-t-20 m-b-20" v-if="selectedNotification.button_label && selectedNotification.button_action">
                            <a :href="selectedNotification.button_action" class="btn btn-success"> @{{ selectedNotification.button_label }}</a>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Fechar</button>
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

        @php
            if(\App::environment('production')){
                echo 'Vue.config.devtools = false;
                  Vue.config.debug = false;
                  Vue.config.silent = true;';
            }
        @endphp

        var vm = new Vue({
                el: '#notifications',
                name: 'notifications',
                data: () =>{
                    return {
                        notifications: [],
                        selectedNotification: {}
                    }
                },
                computed:{
                    hasUnreadNotifications(){
                        return this.notifications.filter((notification) => !notification.is_readed).length ? true : false
                    }
                },
                mounted: function () {
                    this.notifications = notifications.data
                },
                methods: {

                    formatDate(date){
                        return moment(date, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY HH:mm:ss')
                    },

                    showNofication(id){
                        console.log(id)

                        this.selectedNotification = _.find(this.notifications, {id: id})

                        $('#show-notification').modal('show')

                    },

                    markAsReaded(id, index){
                        let that = this
                        this.$http.get(`/api/oracle/notification/mark_readed/${id}`,
                        ).then(response => {
                            that.notifications.splice(index, 1)
                            that.notifications.splice(index, 0, response.body.notification)

                            that.$eventBus.$emit('update-counter', 1)
                        }, response => {
                            // error callback
                        });
                    },

                    markAllNotificationAsReaded() {
                        let that = this

                        swal({
                            title: '',
                            text: 'Deseja marcar todas as mensagens como lidas?',
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sim',
                            cancelButtonText: 'Cancelar',
                            reverseButtons: true
                        }).then(function () {

                            that.$http.post('/api/oracle/notification/mark_all_readed', {id: '{{\Auth::user()->id}}'})
                                .then(function (response) {

                                    that.notifications.map((notification) => {
                                        if(!notification.is_readed){
                                            notification.is_readed = true
                                            notification.readed_at = moment().format('YYYY-MM-DD HH:mm:ss')
                                        }
                                    })

                                    that.$eventBus.$emit('update-counter', response.body.readed_notifications)

                                })
                                .catch(function (error) {
                                    console.log(error)
                                });

                        }).catch(function () {

                        })
                    }
                }

            })

    </script>
@endsection
