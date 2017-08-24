
<!-- OPENGGRAPH -->

<meta property="fb:app_id" content="1854829291449231" />
<meta property="og:locale" content="pt_BR">

@if(!isset($company_fetched) && !isset($professional_fetched))
	<meta property="og:url" content="https://isaudavel.com">
	<meta property="og:title" content="iSaudavel">
	<meta property="og:site_name" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="https://isaudavel.com/logos/LOGO-1-02.png">
<meta property="og:image:type" content="image/png">
@endif

@if(isset($company_fetched))
	<meta property="og:url" content="{{url()->current()}}">
	<meta property="og:title" content="iSaudavel: {{$company_fetched->name}}">
	<meta property="og:site_name" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="{{$company_fetched->avatar}}">
	<meta property="og:image:type" content="image/png">

@endif

@if(isset($professional_fetched))
	<meta property="og:url" content="{{url()->current()}}">
	<meta property="og:title" content="iSaudavel: {{$professional_fetched->full_name}}">
	<meta property="og:site_name" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="{{$professional_fetched->avatar}}">
	<meta property="og:image:type" content="image/png">

@endif