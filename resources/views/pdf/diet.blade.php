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
                <div class="picture-circle" style="background-image: url('{{$diet->from->avatar}}');"></div>
                <p>Por</p>
                <h4>{{$diet->from->full_name}}</h4>
            </td>
        </tr>
    </tbody>
</table>


<h3 style="text-align: center;">Dieta</h3>

<table class="table-no-border">
    <tbody>
        <tr>

            <td style="text-align: left; font-size: 12px;">
                <h5>Usuário</h5>
                {{$diet->client->full_name}}
            </td>
            <td style="text-align: right; font-size: 12px;">
                <h5>Data</h5>
                <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $diet->created_at)->format('d/m/Y')}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left; font-size: 12px;">
                <h5>Observações</h5>
                <p>{{$diet->observation}}</p>
            </td>
        </tr>
    </tbody>
</table>

<h4 style="text-align: center;">Macronutrientes por dia</h4>

<table>
    <thead>
        <tr>
            <th width="20%">Calorias</th>
            <th width="20%">Proteínas</th>
            <th width="20%">Carboidratos</th>
            <th width="20%">Lipídios</th>
            <th width="20%">Fibras</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">{{$diet->daily_total_kcal}}</td>
            <td style="text-align: center;">{{$diet->daily_total_protein}} g</td>
            <td style="text-align: center;">{{$diet->daily_total_carb}} g</td>
            <td style="text-align: center;">{{$diet->daily_total_fat}} g</td>
            <td style="text-align: center;">{{$diet->daily_total_fiber}} g</td>
        </tr>
    </tbody>
</table>

<h4 style="text-align: center;">Refeições</h4>

<hr style="margin-top: 0px;">

@foreach($diet->meals as $meal)
    
    @php
    $ioption = 1;
    @endphp

    <h5 style="text-align: center;">{{$meal['type']}} ( {{$meal['hour']}} )</h5>
    <h5 style="text-align: center;">Macronutrientes</h5>
    <table>
        <thead>
            <tr>
                <th width="20%">Calorias</th>
                <th width="20%">Proteínas</th>
                <th width="20%">Carboidratos</th>
                <th width="20%">Lipídios</th>
                <th width="20%">Fibras</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">{{ $meal['kcal'] }}</td>
                <td style="text-align: center;">{{ $meal['protein'] }} g</td>
                <td style="text-align: center;">{{ $meal['carb'] }} g</td>
                <td style="text-align: center;">{{ $meal['fat'] }} g</td>
                <td style="text-align: center;">{{ $meal['fiber'] }} g</td>
            </tr>
        </tbody>
    </table>

    <br>

    @foreach($meal['options'] as $option)

        @php
        $iitem = 1;
        @endphp
        @foreach($option['items'] as $item)

            <span class="badge">
                {{$item}}
            </span>

            @if($iitem < count($option['items']))
                <br>
            @endif

            @php $iitem++; @endphp
        @endforeach

        @if($ioption < count($meal['options']))
            <br>
            ou
            <br>
        @endif


        @php $ioption++; @endphp

    @endforeach



@endforeach




