
<!-- OPENGGRAPH -->
<?php 
	$routeName = \Request::route()->getName();
	$category_query = \Request::query('category');

	$current_url = \Request::fullUrl();

	if($category_query){
		$category_query = \App\Models\Category::where('slug', ($category_query))->first(); 
	}
?>

<!-- LANDING INDEX -->
@if($routeName == 'landing.index')
	<meta property="fb:app_id" content="1854829291449231" />
	<meta property="og:locale" content="pt_BR">
	<meta property="og:url" content="https://isaudavel.com">
	<meta property="og:title" content="iSaudavel">
	<meta property="og:site_name" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="https://isaudavel.com/logos/LOGO-1-02.png">
	<meta property="og:image:type" content="image/png">
@endif

@if($routeName == 'landing.search.index')
	<meta property="fb:app_id" content="1854829291449231" />
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="iSaudavel">
	

	@if( !$category_query )
		<meta property="og:image" content="https://isaudavel.com/logos/LOGO-1-02.png">
		<meta property="og:image:type" content="image/png">
		<meta property="og:title" content="iSaudavel">
		<meta property="og:url" content="https://isaudavel.com">
		<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	@else
		<meta property="og:image" content="{{$category_query->avatar}}">
		<meta property="og:image:type" content="image/png">
		<meta property="og:url" content="{{ $current_url }}">
		<meta property="og:title" content="{{$category_query->name}}: encontre os melhores profissionais no iSaudavel.">
		<meta property="og:description" content="Profissionais para {{$category_query->name}} e outras especialidades para ajudar você a cuidar de sua saúde, bem estar e estética.">
	@endif
@endif

<!-- LANDING COMPANIES SHOW -->
@if($routeName == 'landing.companies.show')
	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:site_name" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="{{$company_fetched->avatar}}">
	<meta property="og:title" content="iSaudavel: {{$company_fetched->name}}">
	<meta property="og:image:type" content="image/png">
@endif

<!-- LANDING INDEX -->
@if($routeName == 'landing.professionals.show')
	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:title" content="iSaudavel: {{$professional_fetched->full_name}}">
	<meta property="og:site_name" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="{{$professional_fetched->avatar}}">
	<meta property="og:image:type" content="image/png">
@endif