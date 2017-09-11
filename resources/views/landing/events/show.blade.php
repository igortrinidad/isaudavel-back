@extends('landing.events.index')

@section('landing-content')

    <!-- About Event -->
    <section class="section gray divider">
        {{ $event_fetched->name }}
    </section>
    <!-- /About Event -->

    <!-- Participants Event -->
    <section class="section divider">
        Participants
    </section>

    <!-- / Participants Event -->

    <!-- Event Comments -->
    <section class="section gray divider">
        comments
    </section>
    <!-- / Event Comments -->


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
