
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
	<link rel="canonical" href="{{$current_url}}" />

@if($routeName == 'landing.index' || $routeName == 'landing.clients.about' || $routeName == 'landing.professionals.about')
<!-- LANDING INDEX -->
	
	<title>iSaudavel - A sua saúde em boas mãos</title> 
    <meta name="description" content="No iSaudavel você encontrará profissionais especializados em sua saúde como personal trainer, nutricionista, estúdios de pilates, academia, fisioterapia, crossfit e diversas clínicas de saúde e bem estar - todos unidos em só lugar e você poderá compartilhar as principais informações sobre sua saúde e objetivos com esses profissionais, que juntos irão te ajudar a atingir seus objetivos de saúde, estética e bem estar."> 
 

	<meta property="og:url" content="https://isaudavel.com">
	<meta property="og:title" content="iSaudavel">
	<meta property="og:description" content="iSaudavel é uma ferramenta para conectar você e os melhores profissionais para cuidar da sua saúde.">
	<meta property="og:image" content="https://s3.amazonaws.com/isaudavel-assets/preview_post2.png">
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

		<meta property="og:image" content="https://s3.amazonaws.com/isaudavel-assets/preview_post2.png">
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

@if($routeName == 'landing.companies.show')
<!-- COMPANIES SHOW -->

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

@if($routeName == 'landing.professionals.show')
<!-- PROFESSIONAL SHOW -->

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

@if($routeName == 'landing.events.show')
<!-- EVENT INDEX -->

	<title>{{$event_fetched->name}} no iSaudavel</title> 
	<meta name="description" content="Participe do evento {{$event_fetched->name}}! iSaudavel - Sua saúde em boas mãos">

	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:title" content="iSaudavel: {{$event_fetched->name}}">
	<meta property="og:description" content="{!! strip_tags($event_fetched->description) !!}">
	<meta property="og:image" content="{{$event_fetched->avatar}}">
	<meta property="og:image:type" content="image/png">


		<script type="application/ld+json">
		<?php

			$context = [
				'@context' => 'http://schema.org',
				'@type' => 'Event',
				'name' => $event_fetched->name,
			    'image' => $event_fetched->avatar,
			    'startDate' => $event_fetched->date . 'T' . $event_fetched->time,
			    'url' => $current_url,
			    'description' => strip_tags($event_fetched->description),
			    'offers' => [
			    	'@type' => "Offer",
				    'url' => $current_url,
				    'price' => $event_fetched->value,
				    'priceCurrency' => "REAL",
				    'availability' => "http://schema.org/InStock",
			    ],
			    'performer' => [
			    	'@type' => 'Person',
			    	'name' => $event_fetched->from->full_name
			    ]
			];

			echo json_encode($context, JSON_UNESCAPED_SLASHES);

		?>
		</script>

@endif

@if($routeName == 'landing.recipes.show')
<!-- RECIPE INDEX -->

	<title>{{$recipe_fetched->title}} - iSaudavel</title> 
	<meta name="description" content="{!! $recipe_fetched->title !!}">

	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:title" content="iSaudavel: {{ $recipe_fetched->title }}">
	<meta property="og:description" content="{!! strip_tags( $recipe_fetched->description ) !!}">
	<meta property="og:image" content="{{$recipe_fetched->avatar}}">
	<meta property="og:image:type" content="image/png">


	<script type="application/ld+json">
    {
      	"@context": "http://schema.org/",
      	"@type": "Recipe",
      	"name": "{{$recipe_fetched->title}}",
      	"image": [
        	"{{$recipe_fetched->avatar}}"
        ],
      	"author": {
	      	"@type": "Person",
	      	"name": "{{$recipe_fetched->from->full_name}}"
      	},
      "datePublished": "{{$recipe_fetched->created_at}}",
      "description": "{{$recipe_fetched->prep_description}}",
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{$recipe_fetched->current_rating}}",
        "reviewCount": "{{$recipe_fetched->total_rating}}"
      },
     "prepTime": "PT{{$recipe_fetched->prep_time}}M",
     "totalTime": "PT{{$recipe_fetched->prep_time}}M",
     "recipeYield": "{{$recipe_fetched->portions}}",
     "nutrition": {
       "@type": "NutritionInformation",
       "servingSize": "100 g",
       "calories": "{{$recipe_fetched->kcal}}",
       "fatContent": "{{$recipe_fetched->lipids}} g",
       "proteinContent": "{{$recipe_fetched->protein}} g",
       "fiberContent": "{{$recipe_fetched->fiber}} g",
       "carbohydrateContent": "{{$recipe_fetched->carbohydrate}} g"
     },
      "recipeIngredient": [
      	@foreach($recipe_fetched->ingredients as $ingredient)
      		"{{$ingredient['description']}}",
        @endforeach
       ],
     "recipeInstructions": "{!! $recipe_fetched->prep_description !!}"
     }
   </script>

@endif

@if($routeName == 'landing.articles.show')
<!-- ARTICLE INDEX -->

	<title>{{$article_fetched->title}} - iSaudavel</title> 
	<meta name="description" content="{!! $article_fetched->title !!}">

	<meta property="og:url" content="{{ $current_url }}">
	<meta property="og:title" content="iSaudavel: {{ $article_fetched->title }}">
	<meta property="og:description" content="{!! strip_tags( $article_fetched->description ) !!}">
	<meta property="og:image" content="{{$article_fetched->avatar}}">
	<meta property="og:image:type" content="image/png">


	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "NewsArticle",
	  "mainEntityOfPage": {
	    "@type": "WebPage",
	    "@id": "https://isaudavel.com/artigos"
	  },
	  "headline": "{{$article_fetched->title}}",
	  "image": [
	    "{{$article_fetched->avatar}}"
	   ],
	  "datePublished": "{{$article_fetched->created_at->toIso8601String()}}",
	  "dateModified": "{{$article_fetched->updated_at->toIso8601String()}}",
	  "author": {
	    "@type": "Person",
	    "name": "Igor Trindade"
	  },
	   "publisher": {
	    "@type": "Organization",
	    "name": "iSaudavel",
	    "logo": {
	      "@type": "ImageObject",
	      "url": "https://s3.amazonaws.com/isaudavel-assets/img/isaudavel_holder550.png"
	    }
	  },
	  "description": "{{$article_fetched->content}}"
	}
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
