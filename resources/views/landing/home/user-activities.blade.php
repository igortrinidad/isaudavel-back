<style media="screen">
    .wp-wow.animate-leave,
    .wp-wow.animate-enter {
        position: relative;
        animation-timing-function: ease;
    }

    .wp-wow.animate-leave { animation: wp-fadeOut 1s; }
    .wp-wow.animate-enter { animation: wp-fadeIn 1s; }

    .wp-wow.animate-move { animation: wp-move 1s; }

    @keyframes wp-fadeIn {
        0%   { opacity: 0; left: -100px; }
        100% { opacity: 1; left: 0; }
    }

    @keyframes wp-move {
        0%   { left: -100px; }
        100% { left: 0; }
    }

    @keyframes wp-fadeOut {
        0%   { opacity: 1; right: 0; }
        100% { opacity: 0; right: -100px; }
    }

</style>

<section id="user-activities" class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div
                                class="col-sm-3"
                                :class="{
                                    'wp-wow animate-leave' : interactions.fadeOutLast && index == activities.length-1,
                                    'wp-wow animate-enter' : interactions.fadeInFirst && index == 0
                                }"
                                v-for="(activity, index) in activities"
                            >
                                <span class="f-300">@{{ activity.from.full_name }}, adicionou <strong>@{{ activity.title }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@section('scripts')
        @parent
        <script type="text/javascript">
            Vue.config.debug = true;
            var vm = new Vue({
                el: '#user-activities',
                data: {
                    interactions: {
                        fadeOutLast: false,
                        fadeInFirst: false
                    },
                    activities: [
                        {
                            title: 'Bolo de fubá',
                            activity_type: 'Receita',
                            from: { full_name: 'Rodolfo Abrantes', avatar: 'http://www.m3logotipos.com.br/wp-content/uploads/2016/03/logo-6-giv-e-copy.jpg' }
                        },
                        {
                            title: 'Corrida pela saúde',
                            activity_type: 'Evento',
                            from: { full_name: 'Rodolfo Abrantes', avatar: 'http://www.m3logotipos.com.br/wp-content/uploads/2016/03/logo-6-giv-e-copy.jpg' }
                        },
                        {
                            title: '15º Maratona de Presidente Prudente',
                            activity_type: 'Evento',
                            from: { full_name: 'Rodolfo Abrantes', avatar: 'http://www.m3logotipos.com.br/wp-content/uploads/2016/03/logo-6-giv-e-copy.jpg' }
                        },
                        {
                            title: 'Abdominal do guerreiro!',
                            activity_type: 'Exercício',
                            from: { full_name: 'Rodolfo Abrantes', avatar: 'http://www.m3logotipos.com.br/wp-content/uploads/2016/03/logo-6-giv-e-copy.jpg' }
                        }
                    ],
                    newActivity: {
                        title: 'Bolinho de arroz integral',
                        activity_type: 'Receita',
                        from: { full_name: 'Rodolfo Abrantes', avatar: 'http://www.m3logotipos.com.br/wp-content/uploads/2016/03/logo-6-giv-e-copy.jpg' }
                    }
                },
                computed: {

                },
                mounted: function() {
                    this.updateActivity()
                },
                methods: {
                    updateActivity: function() {
                        let that = this
                        // remove
                        setInterval(function() {
                            that.interactions.fadeOutLast = !that.interactions.fadeOutLast
                            that.interactions.fadeInFirst = !that.interactions.fadeInFirst
                            that.activities.unshift(that.newActivity)
                            that.newActivity = that.activities.pop()
                        },5000)

                    }
                }
            })
        </script>

    @stop
