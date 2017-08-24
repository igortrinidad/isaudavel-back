<section id="hero-area">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="block">
                    <h1 class="wow fadeInDown">Cuide da sua saúde como nunca</h1>
                    <p class="wow fadeInDown" data-wow-delay="0.3s">Uma rede social para promover a sua saúde com auxílio dos melhores profissionais.</p>
                    <div class="wow fadeInDown" data-wow-delay="0.3s">
                    	<a class="btn btn-default btn-home" href="#contact" role="button">Quero saber mais</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 wow zoomIn">
                <div class="block">
                    <div class="counter text-center">
                        <ul id="countdown_dashboard">
                            <li>
                                <div class="dash days_dash">
                                    <div class="digit">@{{remain.days}}</div>
                                    <span class="dash_title">Days</span>
                                </div>
                            </li>
                            <li>
                                <div class="dash hours_dash">
                                    <div class="digit">@{{remain.hours}}</div>
                                    <span class="dash_title">Hours</span>
                                </div>
                            </li>
                            <li>
                                <div class="dash minutes_dash">
                                    <div class="digit">@{{remain.minutes}}</div>
                                    <span class="dash_title">Minutes</span>
                                </div>
                            </li>
                            <li>
                                <div class="dash seconds_dash">
                                    <div class="digit">@{{remain.seconds}}</div>
                                    <span class="dash_title">Seconds</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- .row close -->
    </div><!-- .container close -->
</section><!-- header close -->

@section('scripts')
    @parent

    <script>
        
        Vue.config.debug = true;
        var vm = new Vue({
            el: '#hero-area',
            data: {
                remain: {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                }
            },
            mounted: function() {
                this.checkRemainTime()
            },
            methods: {
                checkRemainTime: function(){
                    let that = this
                
                    setInterval( function(){

                        var then = "31/08/2017 14:00:00";

                        var ms = moment(then,"DD/MM/YYYY HH:mm:ss").diff(moment());
                        var d = moment.duration(ms);

                        that.remain.days = d.days(); 
                        that.remain.hours = d.hours(); 
                        that.remain.minutes = d.minutes(); 
                        that.remain.seconds = d.seconds(); 

                    }, 1000)
                    
                    
                },
            }

        })

    </script>


@stop