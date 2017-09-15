@extends('dashboard.layout.index')

@section('content')
    <div class="container first-container" id="invoice-show">
        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>Fatura de <span v-if="invoice.company">@{{ invoice.company.name }}</span></h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{route('oracle.dashboard.companies.invoices', ['id'=> $invoice->company_id])}}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <form class="m-b-25" action="{{route('oracle.dashboard.companies.invoice.update')}}" method="post" role="form">
                            <div class="row">
                                <div class="col-xs-6">
                                    <address>
                                        <h3>Dados do cliente</h3><br>
                                        <strong>Nome:</strong> <span v-if="invoice.company && invoice.company.owner">@{{ invoice.company.owner.full_name }}</span><br>
                                        <strong>Telefone:</strong> <span
                                                v-if="invoice.company">@{{ invoice.company.phone }}</span><br>
                                        <strong>Endereço:</strong> <span v-if="invoice.company"> @{{ invoice.company.address.full_address }}</span><br>
                                        <strong>Cidade:</strong><span v-if="invoice.company"> @{{ invoice.company.city }} - @{{ invoice.company.state}}</span>
                                    </address>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <h3>
                                    <span class="label label-primary" v-if="!invoice.is_confirmed && !invoice.is_canceled">Aguardando</span>
                                    <span class="label label-success" v-if="invoice.is_confirmed && !invoice.is_canceled">Quitada</span>
                                    <span class="label label-default" v-if="!invoice.is_confirmed && invoice.is_canceled">Cancelada</span>
                                </h3>
                            </div>
                            <div class="form-group">
                                <label>Vencimento</label>
                                <input type="text" class="form-control" name="expire_at" v-model="invoice.expire_at" placeholder="00/00/0000">
                            </div>

                            <div class="form-group">
                                <label>Confirmar recebimento</label><br>
                                <label class="switch">
                                    <input type="checkbox" v-model="invoice.is_confirmed" name="is_confirmed" id="is_confirmed" @change="handleStatus('confirm')">
                                    <div class="slider round"></div>
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Cancelar fatura</label><br>
                                <label class="switch">
                                    <input type="checkbox" v-model="invoice.is_canceled" name="is_canceled" id="is_canceled" @change="handleStatus('cancel')">
                                    <div class="slider round"></div>
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><strong>Itens da fatura</strong></h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-condensed table-vmiddle">
                                                    <thead>
                                                    <tr>
                                                        <td><strong>Descrição</strong></td>
                                                        <td class="text-center"><strong>Quantidade</strong></td>
                                                        <td class="text-center"><strong>Item parcial?</strong></td>
                                                        <td class="text-right"><strong>Preço</strong></td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <tr v-for="item in invoice.items">
                                                        <td>@{{item.description}}<br>
                                                            <small class="text-muted">@{{item.reference}}</small>
                                                        </td>
                                                        <td class="text-center">@{{item.quantity}}</td>
                                                        <td class="text-center">@{{item.is_partial ? 'Sim': 'Não'}}</td>
                                                        <td class="text-right">
                                                            @{{ item.total | formatCurrency}}</td>
                                                    </tr>


                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center"><strong>Total</strong></td>
                                                        <td class="no-line text-right">@{{ invoice.total | formatCurrency }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--Hidden inputs to send vue data on request--}}
                            {{csrf_field()}}
                            <input type="hidden" id="id" name="id" value="{{ $invoice->id }}">

                            <button type="submit" class="btn btn-success btn-block btn-lg">Salvar</button>
                        </form>

                        <button class="btn btn-primary btn-block"  @click.prevent="interactions.showInvoiceHistory = true" v-if="!interactions.showInvoiceHistory">Exibir histórico</button>
                        <button class="btn btn-default btn-block"  @click.prevent="interactions.showInvoiceHistory = false" v-if="interactions.showInvoiceHistory">Esconder histórico</button>

                        <div class="m-t-20 m-b-20 table-responsive">
                            <table class="table table-striped table-hover" v-if="interactions.showInvoiceHistory">
                                <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Descrição</th>
                                    <th>Usuário</th>
                                </tr>
                                </thead>
                                <tbody v-for="history in invoice.history">
                                    <tr>
                                        <td>@{{history.date | formatDate}}</td>
                                        <td>@{{history.label}}</td>
                                        <td >@{{history.full_name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                el: '#invoice-show',
                components: {
                    Multiselect: window.VueMultiselect.default
                },
                filters: {
                    'formatCurrency': function (value) {
                        return accounting.formatMoney(parseFloat(value))
                    },

                    'formatDate': function (date) {

                        return window.moment(date, 'DD-MM-YYYY HH:mm:ss').format('DD/MM/YYYY HH:mm:ss')
                    }
                },
                data: {
                    interactions:{
                      showInvoiceHistory: false,
                    },
                    is_loading: false,
                    invoice: {}
                },
                mounted: function () {
                    this.invoice = invoice

                    console.log(invoice)
                },
                methods: {

                    handleStatus: function(status){
                        if(status == 'confirm'){
                            this.invoice.is_confirmed = true
                            this.invoice.is_canceled = false
                        }

                        if(status == 'cancel'){
                            this.invoice.is_confirmed = false
                            this.invoice.is_canceled = true
                        }
                    }
                }

            })

    </script>
@endsection
