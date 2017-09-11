@extends('landing.events.index')

@section('landing-content')
    <style media="screen">

    </style>

    <section class="section gray divider">
        <!-- List Events -->
        <div class="container">
            <div class="row">
                @foreach($events as $event)
                    <div class="col-sm-3 col-xs-12">
                        <!-- Card Event -->
                        <div class="card m-b-10 cursor-pointer">
                            <!--  Card Event Header-->
                            <div class="card-header ch-alt card-picture-header" style="background-image:url('{{ $event->avatar }}')">
                                <div class="hover">
                                    <i class="ion-ios-plus-empty"></i>
                                </div>
                            </div>
                            <!-- / Card Event Header -->

                            <!--  Card Event body-->
                            <div class="card-body card-padding">
                                <div class="row row-event">
                                    <!-- Event Date -->
                                    <div class="col-xs-3 event-col">
                                        <div class="event-date text-center">
                                            <div class="event-date-header">
                                                <span class="f-700 f-12">{{ $event->date->format('Y') }}</span>
                                            </div>
                                            <div class="event-date-body">
                                                <span class="f-700 f-16">{{ $event->date->format('d') }}</span>
                                                <span class="f-300">{{ $event->date->format('M') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- / Event Date -->
                                    <div class="col-xs-9 event-col">
                                        <div class="list-cats">
                                            @foreach($event->categories as $category)
                                                <span class="label label-primary f-300 f-14 m-t-5 m-r-5">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <h3 class="f-300 m-t-10">{{ $event->name }}</h3>
                                <!-- location -->
                                <div class="m-t-10">
                                    <span class="d-block f-300 f-14">
                                        <i class="icon ion-ios-location-outline m-r-10 f-20"></i>{{ $event->city }} - {{ $event->state }}
                                    </span>
                                </div>
                                <!-- / location -->

                            </div>
                            <!-- / Card Event body -->

                        </div>
                        <!-- / Card Event -->
                    </div>
                @endforeach
            </div>
        </div>
        <!-- / List Events -->
    </section>

    @section('scripts')
        @parent
        <script>
            // Vue.config.debug = true;
            // var vm = new Vue({
            //     el: '#list-events',
            //     data: {
            //     },
            //     computed: {
            //
            //     },
            //     mounted: function() {
            //
            //     },
            //     methods: {
            //
            //     }
            //
            // })
        </script>
    @stop
@stop
