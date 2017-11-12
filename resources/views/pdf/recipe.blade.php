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
    background-size: cover; height: 200px;
    text-align: center;
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

</style>


<table class="table-no-border">
    <tbody>
        <tr>
            <td style="text-align: left;"><img src="https://s3.amazonaws.com/isaudavel-assets/logos/i_saudavel-LOGO-01.png" width="200" alt="Logo" border="0" style="text-align: center;"></td>
            <td style="text-align: right;">
                <div class="picture-circle" style="background-image: url('{{$meal->from->avatar}}');"></div>
                <p>Enviado por</p>
                <h4>{{$meal->from->full_name}}</h4>
            </td>
        </tr>
    </tbody>
</table>

<h4 style="text-align: center;">{{$meal->title}}</h4>

<h5 style="text-align: center;">Macronutrientes por porção</h5>

<table>
    <thead>
        <tr>
            <th width="20%" style="text-align: center;">Calorias</th>
            <th width="20%" style="text-align: center;">Proteínas</th>
            <th width="20%" style="text-align: center;">Carboidratos</th>
            <th width="20%" style="text-align: center;">Lipídios</th>
            <th width="20%" style="text-align: center;">Fibras</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">{{$meal->kcal}}</td>
            <td style="text-align: center;">{{$meal->protein}} g</td>
            <td style="text-align: center;">{{$meal->carbohydrate}} g</td>
            <td style="text-align: center;">{{$meal->lipids}} g</td>
            <td style="text-align: center;">{{$meal->fiber}} g</td>
        </tr>
    </tbody>
</table>

<h4>Ingredientes</h4>
	<p>
		@foreach($meal->ingredients as $ingredient)
			{{$ingredient['description']}}<br>
		@endforeach
	</p>

<h4>Modo de preparo</h4>
{{ strip_tags($meal->prep_description) }}





