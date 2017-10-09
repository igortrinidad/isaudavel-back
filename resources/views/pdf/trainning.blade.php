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
                <div class="picture-circle" style="background-image: url('{{$trainning->from->avatar}}');"></div>
                <p>Adicionado por</p>
                <h4>{{$trainning->from->full_name}}</h4>
            </td>
        </tr>
    </tbody>
</table>


<h3 style="text-align: center;">Treinamento</h3>

<table class="table-no-border">
    <tbody>
        <tr>

            <td style="text-align: left; font-size: 12px;">
                <h5>Usuário</h5>
                {{$trainning->client->full_name}}
            </td>
            <td style="text-align: right; font-size: 12px;">
                <h5>Data</h5>
                <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $trainning->created_at)->format('d/m/Y')}}</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; font-size: 12px;">
                <h5>Frequência cardíaca alvo</h5>
                <p>{{$trainning->heart_rate}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left; font-size: 12px;">
                <h5>Observações</h5>
                <p>{{$trainning->observation}}</p>
            </td>
        </tr>
    </tbody>
</table>



<h5>Séries e exercícios</h5>

<table>
    <thead>
        <tr>
            <th width="10%">Série</th>
            <th width="14%">Exercício</th>
            <th width="7%">Intervalo</th>
            <th width="10%">Quantidade</th>
            <th width="10%">Carga</th>
            <th width="10%">Ritmo</th>
            <th width="10%">Local</th>
            <th width="10%">Posição</th>
        </tr>
    </thead>
    <tbody>

        {{$iserie = 0}}
        @foreach($trainning->series as $serie)

            {{$iexerc = 0}}
            @foreach($serie['exercises'] as $exercise)
                
                <tr style="padding: 3px;">

                    @if($iexerc == 0)
                        <td rowspan="{{count($serie['exercises'])}}">{{ $serie['name'] }}</td>
                    @endif
                    <td style="padding: 3px;">{{$exercise['name']}}</td>
                    <td style="padding: 3px;">{{ $serie['interval'] }}</td>
                    <td style="padding: 3px;">
                        @foreach($exercise['method'] as $method)
                            {{ $method['quantity'] }} {{$method['label']}},
                        @endforeach
                    </td>
                    
                    <td style="padding: 3px;">
                        @foreach($exercise['method'] as $method)
                            {{ $method['load'] }},
                        @endforeach
                    </td>
                    <td style="padding: 3px;">
                        @foreach($exercise['method'] as $method)
                            {{ $method['cadency'] }},
                        @endforeach
                    </td>
                    <td style="padding: 3px;">{{ $exercise['location'] }}</td>
                    <td style="padding: 3px;">{{ $exercise['position'] }}</td>
                </tr>

            {{$iexerc++}}
            @endforeach

        {{$iserie++}}
        @endforeach

    </tbody>
</table>


