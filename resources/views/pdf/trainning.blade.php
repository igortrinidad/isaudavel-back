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

th, td {
    padding: 10px;
    text-align: left;
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

<h5 style=" padding-left: 10px;">Observações</h5>
<p style="padding-left: 10px; margin-top: 0px;">{{$trainning->observation}}</p>

<h5>Séries e exercícios</h5>

<table>
    <thead>
        <tr>
            <th width="12%">Série</th>
            <th width="88%" style="text-align: center;">Exercícios</th>
        </tr>
    </thead>
    <tbody>

        @foreach($trainning->series as $serie)

                    <tr>
                        <td>{{ $serie['name'] }}</td>
                        <td colspan="6">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px;">Exercício</th>
                                        <th style="font-size: 12px;">Intervalo</th>
                                        <th style="font-size: 12px;">Quantidade</th>
                                        <th style="font-size: 12px;">Carga</th>
                                        <th style="font-size: 12px;">Ritmo</th>
                                        <th style="font-size: 12px;">Local</th>
                                        <th style="font-size: 12px;">Posição</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 10px !important; padding: 3px;">
                                    @foreach($serie['exercises'] as $exercise)
                                        
                                        <tr style="font-size: 10px !important; padding: 3px;">
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

                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>


            
        @endforeach

    </tbody>
</table>


