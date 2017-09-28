<style media="screen">

</style>

<section id="user-activities" class="section">
    <div class="container">
    </div>
</section>


@section('scripts')
        @parent
        <script type="text/javascript">
            Vue.config.debug = true;
            var vm = new Vue({
                el: '#user-activities',
                data: {
                },
                computed: {

                },
                mounted: function() {
                },
                methods: {

                }
            })
        </script>

    @stop
