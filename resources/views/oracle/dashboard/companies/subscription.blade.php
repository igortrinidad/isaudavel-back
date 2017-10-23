@extends('oracle.dashboard.layout.index')

@section('content')
    <div class="container first-container" id="manage-subscription">
        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">

                <div class="page-title m-b-10">
                    <h2>{{$company->name}}</h2>

                    <div class="pull-right btn-group">
                        <a class="btn btn-primary m-r-20" href="{{ route('oracle.dashboard.companies.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                        <button class="btn btn-success m-r-5" v-if="is_disabled" @click.prevent="is_disabled = !is_disabled">Alterar assinatura</button>
                        <button type="submit" class="btn btn-primary m-r-5" v-if="!is_disabled" :disabled="!checked" @click="saveSubscription()">Salvar</button>
                        <button class="btn btn-default m-r-5" v-if="!is_disabled" @click.prevent="cancelUpdate">Cancelar</button>
                        
                    </div>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Validade da assinatura</label>
                            <p>{{$company->subscription->expire_at}}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <p>
                            @if($company->subscription->is_active)
                                <h4><span class="label label-success">Ativa</span></h4>
                            @else
                                <h4><span class="label label-danger">Inativa</span></h4>
                                @endif
                                </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <label>Valor assinatura</label>
                            <h1 class="">@{{total | formatCurrency}}</h1>
                        </div>
                    </div>
                </div>

                    

                <form class="m-b-25" action="{{route('oracle.dashboard.companies.subscription.update')}}" method="post" role="form" id="form-subscription">
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

                    <div class="form-group">
                        <a href="{{route('oracle.dashboard.companies.professional.list', $company->id)}}" class="btn btn-block btn-primary">Ir para lista de profissionais</a>
                    </div>

                    <div class="form-group m-t-25 m-b-25">
                        <label>Alterar data de vencimento</label> <br>

                        <button class="btn m-5" :disabled="is_disabled" @click.prevent="addSubscriptionDays(15)" :class="{'btn-info': selectedExpirationAdd != 15, 'btn-primary': selectedExpirationAdd == 15}">Adicionar 15 dias</button>
                        <button class="btn m-5" :disabled="is_disabled" @click.prevent="addSubscriptionDays(30)" :class="{'btn-info': selectedExpirationAdd != 30, 'btn-primary': selectedExpirationAdd == 30}">Adicionar 30 dias</button>
                        <button class="btn m-5" :disabled="is_disabled" @click.prevent="addSubscriptionDays(45)" :class="{'btn-info': selectedExpirationAdd != 45, 'btn-primary': selectedExpirationAdd == 45}">Adicionar 45 dias</button>
                        <button class="btn m-5" :disabled="is_disabled" @click.prevent="addSubscriptionDays(60)" :class="{'btn-info': selectedExpirationAdd != 60, 'btn-primary': selectedExpirationAdd == 60}">Adicionar 60 dias</button>
                        <button class="btn m-5" :disabled="is_disabled" @click.prevent="addSubscriptionDays(75)" :class="{'btn-info': selectedExpirationAdd != 75, 'btn-primary': selectedExpirationAdd == 75}">Adicionar 75 dias</button>
                        <button class="btn m-5" :disabled="is_disabled" @click.prevent="addSubscriptionDays(90)" :class="{'btn-info': selectedExpirationAdd != 90, 'btn-primary': selectedExpirationAdd == 90}">Adicionar 90 dias</button>
                        <button class="btn m-5" :disabled="is_disabled" @click.prevent="addSubscriptionDays(120)" :class="{'btn-info': selectedExpirationAdd != 120, 'btn-primary': selectedExpirationAdd == 120}">Adicionar 120 dias</button>
                    </div>

                    <div class="form-group">
                        <label v-if="update_expiration">Nova data de vencimento</label>
                        <p>@{{expire_at}}</p>
                    </div>

                    <div class="form-group">
                        <label>Status</label><br>
                        <p class="text">
                            @{{is_active ? 'Ativa' : 'Inativa'}}</p>
                        <label class="switch">
                            <input type="checkbox" v-model="is_active" name="is_active" id="is_active" :disabled="is_disabled">
                            <div class="slider round"></div>
                        </label>
                    </div>



                    {{--Hidden inputs to send vue data on request--}}
                    {{csrf_field()}}
                    <input type="hidden" id="id" name="id" value="{{ $company->id }}">
                    <input type="hidden" id="categories" name="categories" v-model="categories_parsed">
                    <input type="hidden" id="categories" name="company_professionals" v-model="company_professionals">
                    <input type="hidden" id="expire_at" name="expire_at" v-model="expire_at">
                    <input type="hidden" id="update_expiration" name="update_expiration" v-model="update_expiration">


                </form>

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

        @php
            if(\App::environment('production')){
                echo 'Vue.config.devtools = false;
                  Vue.config.debug = false;
                  Vue.config.silent = true;';
            }
        @endphp
        var vm = new Vue({
            el: '#manage-subscription',
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
                checked: false,
                expire_at: null,
                update_expiration: false,
                set_manual_expiration: false,
                selectedExpirationAdd: null,
                is_active: false,
            },
            mounted: function() {

                this.getCategories();
                this.company = company
                this.category = company.categories
                this.professionals = company.subscription.professionals
                this.is_active = company.subscription.is_active
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

                saveSubscription: function(){
                    let that = this
                
                    $('#form-subscription').submit();
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
                    this.expire_at = null,
                    this.update_expiration =  false
                    this.selectedExpirationAdd = null
                },

                handleDetail: function(id){
                    let index = this.interactions.historiesOpened.indexOf(id)

                    if(index > -1){
                        this.interactions.historiesOpened.splice(index,1)
                    } else {
                        this.interactions.historiesOpened.push(id);
                    }
                },

                addSubscriptionDays: function (days) {
                    this.expire_at = null,
                    this.update_expiration = false
                    this.selectedExpirationAdd = null

                    let expire_at = window.moment(this.company.subscription.expire_at, 'DD/MM/YYYY').startOf('day')
                    this.selectedExpirationAdd = days
                    this.expire_at = expire_at.add(days, 'days').format('DD/MM/YYYY')
                    this.update_expiration = true
                }
            }

        })

    </script>
@endsection
