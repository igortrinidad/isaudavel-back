@extends('landing.events.index')

@section('landing-content')
    <style media="screen">

    </style>

    <section class="section gray divider">
        <!-- List Events -->
        <div class="container">
            <div class="row">
                {{ $event_fetched->name }}
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
