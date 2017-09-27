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
</style>

<div class="" style="text-align: center;">
	<img src="https://s3.amazonaws.com/isaudavel-assets/logos/i_saudavel-LOGO-01.png" width="200" alt="Logo" border="0" style="text-align: center;">
</div>
<h3 style="text-align: center;">{{ $meal->title}}</h3>

<div class="picture-all-bg" style="background-image: url('{{$meal->avatar}}');"></div>
<br>

<h4>Enviado por</h4>
<div class="picture-circle" style="background-image: url('{{$meal->from->avatar}}');"></div>
<p>{{$meal->from->full_name}}</p>

<h4>Macro nutrientes</h4>
<p>
	<b>Kcal:</b> {{$meal->kcal}} | <b>Proteína: </b>{{$meal->protein}}g | <b>Carbs: </b>{{$meal->carbohydrate}}g | <b>Lípidios: </b>{{$meal->lipids}}g | <b>Fibra: </b>{{$meal->fiber}}g
</b>

<h4>Ingredientes</h4>
	<p>
		@foreach($meal->ingredients as $ingredient)
			{{$ingredient['description']}}<br>
		@endforeach
	</p>

<h4>Modo de preparo</h4>
{{ strip_tags($meal->prep_description) }}





