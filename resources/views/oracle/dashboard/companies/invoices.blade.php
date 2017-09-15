@extends('oracle.dashboard.layout.index')

@section('content')

    <div class="container first-container" id="company-invoices">

        <div class="loading-wrapper" v-if="is_loading">
            <div class="loading-spinner">
                <i class="ion-load-c fa-spin fa-3x"></i>
                <h3 class="f-300" style="color: #5D5D5D">Carregando</h3>
            </div>
        </div>


        <div class="row m-t-20 m-b-20">
            <div class="col-md-12">
                <div class="page-title m-b-10">
                    <h2>Faturas</h2>
                    <h3 class="pull-right">
                        <a class="btn btn-primary" href="{{ route('oracle.dashboard.companies.list') }}"> <i class="ion-arrow-left-c"></i> Voltar</a>
                    </h3>
                </div>
                <hr>
                <div class="m-t-20">
                    @include('flash::message')
                </div>

                @unless($invoices->count())
                    <div class="alert alert-info">
                        Nenhuma fatura localizada.
                    </div>
                @else
                    <div class="table-responsive m-t-20">
                        <table class="table table-striped table-hover table-vmiddle">
                            <thead>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Criado em:</th>
                            <th>Ações</th>
                            </thead>
                            <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{$invoice->expire_at}}</td>
                                    <td>R${{number_format($invoice->total, 2, ',','.')}}</td>
                                    <td>
                                        @if(!$invoice->is_confirmed && !$invoice->is_canceled)
                                            <span class="label label-primary">Aguardando</span>
                                        @endif

                                        @if($invoice->is_confirmed && !$invoice->is_canceled)
                                            <span class="label label-success">Quitada</span>
                                        @endif

                                        @if(!$invoice->is_confirmed && $invoice->is_canceled)
                                            <span class="label label-default">Cancelada</span>
                                        @endif
                                    </td>
                                    <td>{{$invoice->created_at->format('d/m/Y H:i:s')}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{route('oracle.dashboard.companies.invoice.show', ['id'=> $invoice->company_id, 'invoice_id' => $invoice->id])}}"><i class="ion-search"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $invoices->links() }}
                        </div>
                    </div>
                @endunless

            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = $('input[name=_token]').val();

        Vue.config.debug = true;
        var vm = new Vue({
            el: '#company-invoices',
            components: {
                Multiselect: window.VueMultiselect.default
            },
            filters: {
                'formatCurrency': function(value){
                    return accounting.formatMoney(parseFloat(value))
                }
            },
            data: {
                is_loading: false,
            },
            mounted: function() {
            },
            methods: {

            }

        })

    </script>
@endsection
