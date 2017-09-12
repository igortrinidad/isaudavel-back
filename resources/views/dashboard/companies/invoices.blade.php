@extends('dashboard.layout.index')

@section('content')
    <div class="container first-container">
        <div class="row m-t-20">
            <h2>Minhas faturas</h2>
            <div class="table-responsive m-t-20">
                <table class="table table-striped table-hover table-vmiddle">
                    <thead>
                    <tr>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Criado em:</th>
                        <th>Ações</th>
                    </tr>
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
                                    <span class="label label-primary">Quitada</span>
                                @endif

                                @if(!$invoice->is_confirmed && $invoice->is_canceled)
                                    <span class="label label-default">Cancelada</span>
                                @endif
                            </td>
                            <td>{{$invoice->created_at->format('d/m/Y H:i:s')}}</td>
                            <td>
                                <a class="btn btn-info btn-sm" href="{{route('professional.dashboard.invoice.show', ['id'=> $invoice->company_id, 'invoice_id' => $invoice->id])}}"><i class="ion-search"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
