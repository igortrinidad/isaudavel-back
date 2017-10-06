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
	background-color: #84C567;
}

.table table, .table tr, .table td, .table{
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


<table class="table">
    <tbody>
        <tr>
            <td style="text-align: left;"><img src="https://s3.amazonaws.com/isaudavel-assets/logos/i_saudavel-LOGO-01.png" width="200" alt="Logo" border="0" style="text-align: center;"></td>
            <td style="text-align: right;">
                <div class="picture-circle" style="background-image: url('{{$trainning->from->avatar}}');"></div>
                <p>Adicionado por</p>
                <p>{{$trainning->from->full_name}}</p>
            </td>
        </tr>
    </tbody>
</table>


<h3 style="text-align: center;">Ficha de treinamento</h3>

<table class="table">
    <tbody>
        <tr>
            <td style="text-align: left;">
                <h5>Criado em</h5>
                <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $trainning->created_at)->format('d/m/Y H:i:s')}}</p>
            </td>
            <td style="text-align: right">
                <h5>Frequência cardíaca alvo</h5>
                <p>{{$trainning->heart_rate}}</p>
            </td>
        </tr>
    </tbody>
</table>

<h5>Observações</h5>
<p style="margin-top: 0px;">{{$trainning->observation}}</p>

<h5>Séries e exercícios</h5>

<table>
    <thead>
        <tr>
            <th width="10%" style="font-size: 12px;">Série</th>
            <th width="14%" style="font-size: 12px;">Exercício</th>
            <th width="7%" style="font-size: 12px;">Intervalo</th>
            <th width="10%" style="font-size: 12px;">Quantidade</th>
            <th width="10%" style="font-size: 12px;">Carga</th>
            <th width="10%" style="font-size: 12px;">Ritmo</th>
            <th width="10%" style="font-size: 12px;">Local</th>
            <th width="10%" style="font-size: 12px;">Posição</th>
        </tr>
    </thead>
    <tbody>

        {{$iserie = 0}}
        @foreach($trainning->series as $serie)

            {{$iexerc = 0}}
            @foreach($serie['exercises'] as $exercise)
                
                <tr style="font-size: 10px !important; padding: 3px;">

                    @if($iexerc == 0)
                        <td rowspan="{{count($serie['exercises'])}}">{{ $serie['name'] }}</td>
                    @endif
                    <td style="font-size: 10px !important; padding: 3px;">{{$exercise['name']}}</td>
                    <td style="font-size: 10px !important; padding: 3px;">{{ $serie['interval'] }}</td>
                    <td style="font-size: 10px !important; padding: 3px;">
                        @foreach($exercise['method'] as $method)
                            {{ $method['quantity'] }} {{$method['label']}},
                        @endforeach
                    </td>
                    
                    <td style="font-size: 10px !important; padding: 3px;">
                        @foreach($exercise['method'] as $method)
                            {{ $method['load'] }},
                        @endforeach
                    </td>
                    <td style="font-size: 10px !important; padding: 3px;">
                        @foreach($exercise['method'] as $method)
                            {{ $method['cadency'] }},
                        @endforeach
                    </td>
                    <td style="font-size: 10px !important; padding: 3px;">{{ $exercise['location'] }}</td>
                    <td style="font-size: 10px !important; padding: 3px;">{{ $exercise['position'] }}</td>
                </tr>

            {{$iexerc++}}
            @endforeach

        {{$iserie++}}
        @endforeach

    </tbody>
</table>

