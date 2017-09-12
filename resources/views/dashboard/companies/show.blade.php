@extends('dashboard.layout.index')

@section('content')
    <div class="container first-container" id="company-edit-form">
        <div class="row m-t-20">
            <h2>{{$company->name}}</h2>
            <h4>Gerenciar assinatura e faturas</h4>

            <div class="m-t-20">
                @include('flash::message')
            </div>

            <form class="m-b-25" action="{{route('professional.dashboard.subscription.update')}}" method="post" role="form">
                <div class="alert alert-info" v-if="!is_disabled">
                    <strong>Atenção</strong>: remoções de especialidades e quantidade de profissionais serão refletidas na próxima fatura, para as adições será criada uma fatura com a diferença parcial.
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
                            :disabled="is_disabled"
                            track-by="name"
                            :select-label="'Selecionar'"
                            :selected-label="'Selecionado'"
                            :deselect-label="'Remover'"
                    >
                    </multiselect>
                </div>

                <div class="form-group">
                    <label>Quantidade de profissionais (R$17,90 / profissional)</label>
                    <input class="form-control" name="professionals" placeholder="" v-model="professionals" required type="number" @blur="calcValue()" :disabled="is_disabled">
                </div>
                <hr class="m-t-30">
                <div class="form-group" v-if="company.professionals && professionals < company.professionals.length">
                    <label>Profissionais</label>

                    <div class="alert alert-warning">
                        <strong>Atenção</strong>: A quantidade de profissionais da empresa foi alterada, você deve selecionar quais profissionais devem ser removidos.
                    </div>

                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Especialidades</th>
                            <th>Remover</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="professional in company.professionals">
                            <td>@{{professional.full_name}}</td>
                            <td>
                                <span  class="label label-success m-r-10" v-for="category in professional.categories">
                                    @{{category.name}}
                                </span>
                            </td>
                            <td>
                                <div class="checkbox-group">
                                    <label class="checkbox">
                                        <input type="checkbox" class=" wp-checkbox-input" name="professional_remove" @change="handleProfessionalRemove(professional.id)" :disabled="professional.pivot.is_admin ? true: false">
                                        <div class=" wp-checkbox-inline wp-checkbox"></div>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <div class="form-group text-center">
                    <label>Valor assinatura</label>
                    <h1 class="text-center">@{{total | formatCurrency}}</h1>
                </div>

                {{--Hidden inputs to send vue data on request--}}
                {{csrf_field()}}
                <input type="hidden" id="id" name="id" value="{{ $company->id }}">
                <input type="hidden" id="categories" name="categories" v-model="categories_parsed">
                <input type="hidden" id="categories" name="company_professionals" v-model="company_professionals">

                <button class="btn btn-success btn-block" v-if="is_disabled" @click.prevent="is_disabled = !is_disabled">Alterar assinatura</button>

                <button type="submit" class="btn btn-primary btn-block" v-if="!is_disabled" :disabled="!checked">Salvar</button>
                <button class="btn btn-default btn-block" v-if="!is_disabled" @click.prevent="cancelUpdate">Cancelar</button>

            </form>

            <a class="btn btn-primary btn-block m-b-25" href="{{route('professional.dashboard.invoices.list', ['id'=> $company->id])}}">Minhas faturas</a>

            <button class="btn btn-primary btn-block"  @click.prevent="interactions.showSubscriptionHistory = true" v-if="!interactions.showSubscriptionHistory">Exibir histórico da assinatura</button>
            <button class="btn btn-default btn-block"  @click.prevent="interactions.showSubscriptionHistory = false" v-if="interactions.showSubscriptionHistory">Esconder histórico da assinatura</button>

            <div class="m-t-20 m-b-20 table-responsive">
                <table class="table table-striped table-hover" v-if="interactions.showSubscriptionHistory">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Usuário</th>
                        <th>Detalhe</th>
                    </tr>
                    </thead>
                    <tbody v-for="history in company.subscription.histories">
                            <tr>
                                <td>@{{history.created_at}}</td>
                                <td>@{{history.description}}</td>
                                <td v-if="history.user">@{{history.user.full_name}}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" @click.prevent="handleDetail(history.id)">
                                        <i class="ion-plus" v-if="interactions.historiesOpened.indexOf(history.id) < 0"></i>
                                        <i class="ion-minus" v-if="interactions.historiesOpened.indexOf(history.id) > -1"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="interactions.historiesOpened.indexOf(history.id) > -1">
                                <td width="auto">
                                    <div>
                                        <p><strong>Profissionais</strong></p>
                                        <span><strong>Valor atual: </strong> @{{history.professionals_new_value}} </span><br>
                                        <span><strong>Valor antigo: </strong>@{{history.professionals_old_value}} </span>
                                    </div>
                                </td>
                                <td  width="auto">
                                    <div>
                                        <p><strong>Categorias</strong></p>
                                        <span><strong>Valor atual: </strong> @{{history.categories_new_value}} </span><br>
                                        <span><strong>Valor antigo: </strong>@{{history.categories_old_value}} </span>
                                    </div>
                                </td>
                                <td  width="auto">
                                    <div>
                                        <p><strong>Total</strong></p>
                                        <span><strong>Valor atual: </strong> @{{history.total_new_value | formatCurrency}} </span><br>
                                        <span><strong>Valor antigo: </strong>@{{history.total_old_value  | formatCurrency}} </span>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                    </tbody>
                </table>
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
            el: '#company-edit-form',
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
                address:{},
                company : {},
                professionals:0,
                total: 0,
                is_disabled: true,
                company_professionals: [],
                professionals_for_remove: [],
                checked: false
            },
            mounted: function() {

                this.getCategories();
                this.company = company
                this.category = company.categories
                this.professionals = company.subscription.professionals
                this.calcValue()
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

                },

                handleProfessionalRemove: function (professional_id){

                    let professional_index = this.professionals_for_remove.indexOf(professional_id)

                    let company_professionals = []

                    if(professional_index == -1){
                        this.professionals_for_remove.push(professional_id);

                        this.company.professionals.map((professional) => {
                            if(professional.id != professional_id){
                                company_professionals.push(professional.id)
                            }
                        })

                    }else{
                        this.professionals_for_remove.splice(professional_index, 1);

                        let index = company_professionals.indexOf(professional_id)

                        company_professionals.splice(index, 1)
                    }

                    this.company_professionals = JSON.stringify(company_professionals);

                    if(company_professionals.length == (company.professionals.length - this.professionals_for_remove.length)){
                        this.checked = true
                    }else{
                        this.checked = false
                    }

                },

                cancelUpdate: function(){
                    this.is_disabled = true
                    this.professionals_for_remove = []
                },

                handleDetail: function(id){
                    let index = this.interactions.historiesOpened.indexOf(id)

                    if(index > -1){
                        this.interactions.historiesOpened.splice(index,1)
                    } else {
                        this.interactions.historiesOpened.push(id);
                    }
                }
            }

        })

    </script>
@endsection

