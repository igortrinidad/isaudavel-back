@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container" id="create-subscription">
        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2><strong>Nova assinatura:</strong> {{$company->name}}</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.companies.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <form class="m-b-25" action="{{route('oracle.dashboard.companies.subscription.store')}}" method="post" role="form">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Início</label>
                                <input type="text" class="form-control" name="start_at" v-model="start_at" placeholder="00/00/0000">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Vencimento</label>
                                <input type="text" class="form-control" name="expire_at" v-model="expire_at" placeholder="00/00/0000">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status da assinatura</label><br>
                                <p class="text">
                                    @{{is_active ? 'Ativa' : 'Inativa'}}</p>
                                <label class="switch">
                                    <input type="checkbox" v-model="is_active" name="is_active" id="is_active">
                                    <div class="slider round"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Especialidades (R$37,90 / especialidade)</label>
                        <multiselect
                                v-model="category"
                                :options="categories"
                                :label="'name'"
                                :multiple="true"
                                placeholder="Selecione ao menos uma categoria"
                                @input="calcValue"
                                track-by="name"
                                :select-label="'Selecionar'"
                                :selected-label="'Selecionado'"
                                :deselect-label="'Remover'"
                        >
                        </multiselect>
                    </div>

                    <div class="form-group">
                        <label>Quantidade de profissionais (R$17,90 / profissional)</label>
                        <input class="form-control" name="professionals" placeholder="" v-model="professionals" required type="number" @blur="calcValue()">
                    </div>

                    <div class="form-group text-center">
                        <label>Valor assinatura</label>
                        <h1 class="text-center">@{{total | formatCurrency}}</h1>
                    </div>

                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="company_id" name="company_id" value="{{ $company->id }}">
                    <input type="hidden" id="categories" name="categories" v-model="categories_parsed">
                    <input type="hidden" id="total" name="total" v-model="total">

                    <button type="submit" class="btn btn-success btn-block btn-lg">Salvar</button>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        accounting.settings = {
            currency: {
                symbol : "R$ ",   // default currency symbol is '$'
                format: "%s%v", // controls output: %s = symbol, %v = value/number (can be object: see below)
                decimal : ",",  // decimal point separator
                thousand: ".",  // thousands separator
                precision : 2   // decimal places
            },
            number: {
                precision: 2,  // default precision on numbers is 0
                thousand: ".",
                decimal : ","
            }
        }

        Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

        Vue.config.debug = true;
        var vm = new Vue({
            el: '#create-subscription',
            components: {
                Multiselect: window.VueMultiselect.default
            },
            filters: {
                'formatCurrency': function(value){
                    return accounting.formatMoney(parseFloat(value))
                }
            },
            data: {
                interactions:{
                    showSubscriptionHistory: false,
                    historiesOpened: []
                },
                city: '',
                category: null,
                categories: [],
                categories_parsed: [],
                professionals:0,
                total: 0,
                start_at: null,
                expire_at: null,
                is_active: false,
            },
            mounted: function() {

                this.getCategories();
                this.company = company
                this.category = company.categories
                this.professionals = company.professionals.length
                this.calcValue()

                var moment = window.moment

                this.start_at = moment().format('DD/MM/YYYY')
                this.expire_at = moment().add(1, 'month').format('DD/MM/YYYY')
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

                calcValue: function(){

                    var cost_categories = this.category.length * 37.90;

                    if(isNaN(this.professionals) || this.professionals == 0){
                        this.professionals = 1;
                    }

                    if(this.professionals == 1){
                        var cost_professional = 0;
                    } else {
                        var cost_professional = 17.90 * (this.professionals - 1);
                    }

                    var total = cost_professional + cost_categories;

                    this.total = total.toFixed(2);

                    this.categories_parsed = []
                    var categories_parsed = []

                    this.category.map((category) => {
                        categories_parsed.push(category.id)
                })

                    this.categories_parsed = JSON.stringify(categories_parsed);

                    this.checked = true

                }
            }

        })

    </script>
@endsection
