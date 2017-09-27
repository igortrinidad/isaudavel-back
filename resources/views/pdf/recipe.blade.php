<div class="" style="text-align: center;">
	<img src="https://s3.amazonaws.com/isaudavel-assets/logos/i_saudavel-LOGO-01.png" width="200" alt="Logo" border="0" style="text-align: center;">
</div>
<h3 style="text-align: center;">{{ $meal->title}}</h3>
<h4>Ingredientes</h4>
	<p>
		@foreach($meal->ingredients as $ingredient)
			{{$ingredient['description']}}<br>
		@endforeach
	</p>

<h4>Modo de preparo</h4>
{{ strip_tags($meal->prep_description) }}



