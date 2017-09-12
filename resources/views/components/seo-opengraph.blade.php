
<!-- OPENGGRAPH -->
<?php 
	$routeName = \Request::route()->getName();
	$category_query = \Request::query('category');

	$current_url = \Request::fullUrl();

	$root_url = \Request::root();

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

	<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "WebSite",
		  "url": "https://isaudavel.com/",
		  "potentialAction": {
		    "@type": "SearchAction",
		    "target": "https://isaudavel.com/buscar?q={search_term_string}",
		    "query-input": "required name=search_term_string"
		  }
		}

	</script>

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

			<script type="application/ld+json">
				{
				  	"@context": "http://schema.org",
				  	"@type": "WebSite",
				  	"name": "iSaudavel - sua saúde em boas mãos",
				  	"alternateName": "{{$category_query->name}} no iSaudavel - encontre os melhores profissionais",
				  	"url": "https://isaudavel.com"
				}
			</script>
	@endif
@endif

<!-- LANDING COMPANIES SHOW -->
@if($routeName == 'landing.companies.show')

	<title>{{$company_fetched->name}} no iSaudavel</title> 
	<meta name="description" content="Veja o perfil de {{$company_fetched->name}} no iSaudavel, cuide de sua saúde na primeira plataforma fitness do mundo.">

	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="{{$company_fetched->avatar}}">
	<meta property="og:title" content="iSaudavel: {{$company_fetched->name}}">
	<meta property="og:image:type" content="image/png">


			<script type="application/ld+json">

		<?php

			$review = [];
			foreach($company_fetched->last_ratings as $rating){
				$review[] = [
					"@context" =>  "http://schema.org/",
					"@type" => "Review",
					"itemReviewed" => [
						"@type" => "Person",
						"name" => $company_fetched->name
					],
					"reviewRating"=> [
					    "@type" => "Rating",
					    "ratingValue" => $rating->rating
					],
					"reviewBody" => $rating->description,
					"author" => [
						"@type" => "Person",
						"name" => $rating->client->full_name
					]
				];
			}

			$context = [
				'@context' => 'http://schema.org',
				'@type' => 'Person',
				'name' => $company_fetched->full_name,
			    'image' => $company_fetched->avatar,
			    'url' => $current_url,
			    'aggregateRating' => [
			    	'@type' => 'AggregateRating',
			    	'ratingValue' => ($company_fetched->current_rating > 0) ? $company_fetched->current_rating : 1,
			    	'reviewCount' => ($company_fetched->total_rating > 0) ?  $company_fetched->total_rating : 1,
			    	'bestRating' => 5,
			    	'worstRating' => ($company_fetched->current_rating > 0) ? $company_fetched->current_rating : 1
			    ],
			    'review' => $review,

			];

			echo json_encode($context, JSON_UNESCAPED_SLASHES);

		?>
		</script>

		<script type="application/ld+json">

		<?php

			$context = [
				'@context' => 'http://schema.org',
				'@type' => 'LocalBusiness',
				'name' => $company_fetched->name,
			    'image' => $company_fetched->avatar,
			    'url' => $current_url,
			    'address' => $company_fetched->address['full_address'],
			    'telephone' => $company_fetched->phone,
			    'aggregateRating' => [
			    	'@type' => 'AggregateRating',
			    	'ratingValue' => ($company_fetched->current_rating > 0) ?  $company_fetched->current_rating : 1,
			    	'reviewCount' => ($company_fetched->total_rating > 0) ?  $company_fetched->total_rating : 1,
			    	'bestRating' => 5,
			    	'worstRating' => ($company_fetched->current_rating > 0) ? $company_fetched->current_rating : 1
			    ],

			];

			echo json_encode($context, JSON_UNESCAPED_SLASHES);

		?>
	</script>



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


		<script type="application/ld+json">

		<?php

			$review = [];
			foreach($professional_fetched->last_ratings as $rating){
				$review[] = [
					"@context" =>  "http://schema.org/",
					"@type" => "Review",
					"itemReviewed" => [
						"@type" => "Person",
						"name" => $professional_fetched->full_name
					],
					"reviewRating"=> [
					    "@type" => "Rating",
					    "ratingValue" => $rating->rating
					],
					"reviewBody" => $rating->description,
					"author" => [
						"@type" => "Person",
						"name" => $rating->client->full_name
					]
				];
			}

			$context = [
				'@context' => 'http://schema.org',
				'@type' => 'Person',
				'name' => $professional_fetched->full_name,
			    'image' => $professional_fetched->avatar,
			    'url' => $current_url,
			    'aggregateRating' => [
			    	'@type' => 'AggregateRating',
			    	'ratingValue' => ($professional_fetched->current_rating > 0) ? $professional_fetched->current_rating : 1,
			    	'reviewCount' => ($professional_fetched->total_rating > 0) ?  $professional_fetched->total_rating : 1,
			    	'bestRating' => 5,
			    	'worstRating' => ($professional_fetched->current_rating > 0) ? $professional_fetched->current_rating : 1
			    ],
			    'review' => $review,

			];

			echo json_encode($context, JSON_UNESCAPED_SLASHES);

		?>
		</script>

		<script type="application/ld+json">

		<?php

			$context = [
				'@context' => 'http://schema.org',
				'@type' => 'LocalBusiness',
				'name' => $professional_fetched->full_name,
			    'image' => $professional_fetched->avatar,
			    'url' => $current_url,
			    'address' => '',
			    'phone' => '',
			    'aggregateRating' => [
			    	'@type' => 'AggregateRating',
			    	'ratingValue' => ($professional_fetched->current_rating > 0) ?  $professional_fetched->current_rating : 1,
			    	'reviewCount' => ($professional_fetched->total_rating > 0) ?  $professional_fetched->total_rating : 1,
			    	'bestRating' => 5,
			    	'worstRating' => ($professional_fetched->current_rating > 0) ? $professional_fetched->current_rating : 1
			    ],

			];

			echo json_encode($context, JSON_UNESCAPED_SLASHES);

		?>
	</script>
@endif

<!-- ANALYTICS -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70761422-7', 'auto');
  ga('send', 'pageview');

</script>

<!-- GOOGLE ADSENSE -->
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-3086351824347551",
    enable_page_level_ads: true
  });
</script>
