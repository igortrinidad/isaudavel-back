@extends('landing.events.index')

@section('landing-content')
    <style media="screen">
        .form-control:focus {
            box-shadow: 0 0 4px rgba(110, 192, 88, .4);
            border-color: #6ec058;
        }

        .card-body.card-custom-padding{
            padding: 43px 14px;
        }
    </style>

    <section id="signup" class="section gray p-t-30 p-b-0">

        <div class="container" style="min-height: 700px">

            <h2 class="text-center m-t-30">Quem bom ver você novamente, estamos quase lá!</h2>

            {{-- Form Container --}}
            <div class="row m-t-30">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-custom-padding">

                        </div>
                    </div>
                </div>
            </div>
            {{-- End Form Container --}}

        </div>

    </section>

@stop

@section('scripts')
    <script>

        Vue.config.debug = true;

        var vm = new Vue({
                el: '#signup',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                data: {
                    categoryFromParams: '',
                    categories: [],
                    category: null
                },
                mounted: function () {

                    this.getCategories();
                    console.log('Vue rodando no signup');
                },
                methods: {
                    getCategories: function(){
                        let that = this

                        this.$http.get('/api/company/category/list').then(response => {

                            that.categories = response.body;

                        }, response => {
                            // error callback
                        });
                    },
                }
            })

    </script>
@endsection
