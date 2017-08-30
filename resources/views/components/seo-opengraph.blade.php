
<!-- OPENGGRAPH -->
<?php 
	$routeName = \Request::route()->getName();
	$category_query = \Request::query('category');

	$current_url = \Request::fullUrl();

	if($category_query){
		$category_query = \App\Models\Category::where('slug', ($category_query))->first(); 
	}
?>
	
	<!-- GLOBAL -->
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta property="fb:app_id" content="1854829291449231" />
	<meta property="og:type" content="website">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="iSaudavel">
	<meta name="robots" content="index, follow">

<!-- LANDING INDEX -->
@if($routeName == 'landing.index' || $routeName == 'landing.clients.about' || $routeName == 'landing.professionals.about')
	
	<title>iSaudavel - A sua saúde em boas mãos</title> 
    <meta name="description" content="No iSaudavel você encontrará profissionais especializados em sua saúde como personal trainer, nutricionista, estúdios de pilates, academia, fisioterapia, crossfit e diversas clínicas de saúde e bem estar - todos unidos em só lugar e você poderá compartilhar as principais informações sobre sua saúde e objetivos com esses profissionais, que juntos irão te ajudar a atingir seus objetivos de saúde, estética e bem estar."> 
 

	<meta property="og:url" content="https://isaudavel.com">
	<meta property="og:title" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="https://isaudavel.com/logos/LOGO-1-02.png">
	<meta property="og:image:type" content="image/png">

@endif

@if($routeName == 'landing.search.index')

	<?php

		$context = \JsonLd\Context::create('search_box', [
		    'url' => 'https://isaudavel.com/',
		    'potentialAction' => [
		        'target' => 'https://isaudavel.com/buscar?category={search_term_string}',
		        'query-input' => 'required name=search_term_string',
		    ],
		]);

		echo $context;

	?>

	@if( !$category_query )

		<title>iSaudavel - Buscar profissionais de saúde</title> 
	    <meta name="description" content="No iSaudavel você encontrará profissionais especializados em sua saúde como personal trainer, nutricionista, estúdios de pilates, academia, fisioterapia, crossfit e diversas clínicas de saúde e bem estar - todos unidos em só lugar e você poderá compartilhar as principais informações sobre sua saúde e objetivos com esses profissionais, que juntos irão te ajudar a atingir seus objetivos de saúde, estética e bem estar.">

		<meta property="og:image" content="https://isaudavel.com/logos/LOGO-1-02.png">
		<meta property="og:image:type" content="image/png">
		<meta property="og:title" content="iSaudavel">
		<meta property="og:url" content="https://isaudavel.com">
		<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">

	@else

		<title>{{$category_query->name}} no iSaudavel - encontre os melhores profissionais</title> 
	    <meta name="description" content="No iSaudavel você encontrará profissionais de {{$category_query->avatar}} e outras especialidades para ajudar você a cuidar de sua saúde, bem estar e estética.">

		<meta property="og:image" content="{{$category_query->avatar}}">
		<meta property="og:image:type" content="image/png">
		<meta property="og:url" content="{{ $current_url }}">
		<meta property="og:title" content="{{$category_query->name}}: encontre os melhores profissionais no iSaudavel.">
		<meta property="og:description" content="Profissionais para {{$category_query->name}} e outras especialidades para ajudar você a cuidar de sua saúde, bem estar e estética.">
	@endif
@endif

<!-- LANDING COMPANIES SHOW -->
@if($routeName == 'landing.companies.show')

	<title>{{$company_fetched->name}} no iSaudavel - {{$company_fetched->city}}</title> 
	<meta name="description" content="Veja o perfil de {{$company_fetched->name}} no iSaudavel, cuide de sua saúde na primeira plataforma fitness do mundo.">

	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="{{$company_fetched->avatar}}">
	<meta property="og:title" content="iSaudavel: {{$company_fetched->name}}">
	<meta property="og:image:type" content="image/png">


		<?php

			$context = \JsonLd\Context::create('local_business', [
			    'name' => $company_fetched->name,
			    'description' => $company_fetched->description,
			    'telephone' => $company_fetched->phone,
			    'geo' => [
			        'latitude' => $company_fetched->lat,
			        'longitude' => $company_fetched->lng,
			    ],
			]);

			echo $context;

		?>

@endif

<!-- LANDING INDEX -->
@if($routeName == 'landing.professionals.show')

	<title>{{$professional_fetched->full_name}} no iSaudavel</title> 
	<meta name="description" content="Veja o perfil de {{$professional_fetched->full_name}} no iSaudavel, cuide de sua saúde na primeira plataforma fitness do mundo.">

	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:title" content="iSaudavel: {{$professional_fetched->full_name}}">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="{{$professional_fetched->avatar}}">
	<meta property="og:image:type" content="image/png">
@endif