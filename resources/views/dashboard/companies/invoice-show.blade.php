@extends('dashboard.layout.index')

@section('content')
    <div class="container first-container" onload="window.print();">
        <div class="row m-t-20">
            <div class="row">
                <div class="col-xs-12">
                    <div class="invoice-title">
                        <h2>Fatura</h2>
                        <h3 class="pull-right">
                            @if(!$invoice->is_confirmed && !$invoice->is_canceled)
                            <span class="label label-primary">Aguardando</span>
                            @endif

                            @if($invoice->is_confirmed && !$invoice->is_canceled)
                                <span class="label label-primary">Quitada</span>
                            @endif

                            @if(!$invoice->is_confirmed && $invoice->is_canceled)
                                <span class="label label-default">Cancelada</span>
                            @endif
                        </h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <address>
                                <h3>Dados do cliente</h3><br>
                                <strong>Nome:</strong> {{$invoice->company->owner->full_name}}<br>
                                <strong>Telefone:</strong> {{$invoice->company->phone}}<br>
                                <strong>Endereço:</strong> {{$invoice->company->address['full_address']}}<br>
                                <strong>Cidade:</strong> {{$invoice->company->city}} - {{$invoice->company->state}}
                            </address>
                        </div>
                    </div>
                </div>
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
                                    @foreach($invoice->items as $item)
                                        <tr >
                                            <td>{{$item['description']}}<br>
                                                <small class="text-muted">{{$item['reference']}}</small>
                                            </td>
                                            <td class="text-center">{{$item['quantity']}}</td>
                                            <td class="text-center">{{$item['is_partial'] ? 'Sim': 'Não'}}</td>
                                            <td class="text-right">R${{number_format($item['total'], 2, ',', '.')}}</td>
                                        </tr>

                                    @endforeach
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Total</strong></td>
                                        <td class="no-line text-right">R${{number_format($invoice->total, 2, ',', '.')}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-t-25, m-b-25">
                <button class="btn btn-success btn-block">Pagar fatura</button>
                <button class="btn btn-primary btn-block">Imprimir</button>
            </div>

        </div>
    </div>
@endsection
