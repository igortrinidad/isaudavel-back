<style type="text/css">

.picture-circle{
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 50%;
    width: 68px;
    height: 68px;
}

.picture-all-bg{
	background-position: center center;
	background-repeat: no-repeat;
    background-size: cover; height: 300px;
    text-align: center;
}

.badge{
    background-color: #CBC3C6;
}

table {
    border-collapse: collapse;
    width: 100%;
}

table, th, td {
    border: 1px solid black;
}

th{
    font-size: 12px;
	background-color: #84C567;
}

td{
    font-size: 10px;
    padding: 3px;
}

.table-no-border table, .table-no-border tr, .table-no-border td, .table-no-border{
    border: 0px;
}

p{
    margin-top: 5px;
}

h1, h2{
    margin-bottom: 10px;
}
h3, h4, h5{
    margin-bottom: 5px;
}
</style>


<table class="table-no-border">
    <tbody>
        <tr>
            <td style="text-align: left;"><img src="https://s3.amazonaws.com/isaudavel-assets/logos/i_saudavel-LOGO-01.png" width="200" alt="Logo" border="0" style="text-align: center;"></td>
            <td style="text-align: right;">
                <div class="picture-circle" style="background-image: url('{{$evaluation->from->avatar}}');"></div>
                <p>Por</p>
                <h4>{{$evaluation->from->full_name}}</h4>
            </td>
        </tr>
    </tbody>
</table>


<h3 style="text-align: center;">Avaliação</h3>

<table class="table-no-border">
    <tbody>
        <tr>

            <td style="text-align: left; font-size: 12px;">
                <h5>Usuário</h5>
                {{$evaluation->client->full_name}}
            </td>
            <td style="text-align: right; font-size: 12px;">
                <h5>Data</h5>
                <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $evaluation->created_at)->format('d/m/Y')}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left; font-size: 12px;">
                <h5>Observações</h5>
                <p>{{$evaluation->observation}}</p>
            </td>
        </tr>
    </tbody>
</table>


<h4 style="text-align: center;">Índices</h4>

<table>
    <thead>
        <tr>
            <th width="40%">Índice</th>
            <th width="30%">Valor atual</th>
            <th width="30%">Objetivo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($evaluation['items'] as $item)
        <tr>
            <td>{{$item['label']}}</td>
            <td style="text-align: center;">{{$item['value']}}</td>
            <td style="text-align: center;">{{$item['target']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>




